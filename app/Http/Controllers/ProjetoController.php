<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjetoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $busca = $request->string('busca')->toString();
        $categoriaId = $request->integer('categoria_id');
        $status = $request->string('status')->toString();
        $anoLetivo = $this->filtroAnoLetivo($request);
        $periodoLetivo = $this->filtroPeriodoLetivo($request, $user);

        $projetos = Projeto::with('categoria')
            ->where('user_id', $user->id)
            ->when($busca, function ($query) use ($busca) {
                $query->where(function ($query) use ($busca) {
                    $query->where('titulo', 'like', "%{$busca}%")
                        ->orWhere('responsavel', 'like', "%{$busca}%")
                        ->orWhere('resumo', 'like', "%{$busca}%");
                });
            })
            ->when($anoLetivo, fn ($query) => $query->where('ano_letivo', $anoLetivo))
            ->when($periodoLetivo, fn ($query) => $query->where('periodo_letivo', $periodoLetivo))
            ->when($categoriaId, fn ($query) => $query->where('categoria_id', $categoriaId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('projetos.index', [
            'projetos' => $projetos,
            'categorias' => Categoria::where('user_id', $user->id)->orderBy('nome')->get(),
            'statusList' => Projeto::STATUS,
            'periodosLetivos' => $user->periodosLetivos(),
            'anosLetivos' => $this->anosLetivos($user),
            'filtros' => compact('busca', 'categoriaId', 'status', 'anoLetivo', 'periodoLetivo'),
        ]);
    }

    public function create()
    {
        $user = auth()->user();

        return view('projetos.create', [
            'projeto' => new Projeto([
                'status' => 'Planejamento',
                'ano_letivo' => now()->year,
                'periodo_letivo' => $user->periodoAtual(),
            ]),
            'categorias' => Categoria::where('user_id', $user->id)->where('ativo', true)->orderBy('nome')->get(),
            'statusList' => Projeto::STATUS,
            'periodosLetivos' => $user->periodosLetivos(),
        ]);
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        $dados['imagem'] = $this->salvarImagem($request);

        $request->user()->projetos()->create($dados);

        return redirect()
            ->route('projetos.index')
            ->with('success', 'Projeto cadastrado com sucesso.');
    }

    public function show(Projeto $projeto)
    {
        $this->garantirDono($projeto);

        $projeto->load(['categoria', 'etapas']);

        return view('projetos.show', [
            'projeto' => $projeto,
            'periodosLetivos' => auth()->user()->periodosLetivos(),
        ]);
    }

    public function edit(Projeto $projeto)
    {
        $this->garantirDono($projeto);
        $user = auth()->user();

        return view('projetos.edit', [
            'projeto' => $projeto,
            'categorias' => Categoria::where('user_id', $user->id)->where('ativo', true)->orderBy('nome')->get(),
            'statusList' => Projeto::STATUS,
            'periodosLetivos' => $user->periodosLetivos(),
        ]);
    }

    public function update(Request $request, Projeto $projeto)
    {
        $this->garantirDono($projeto);

        $dados = $this->validar($request);
        $novaImagem = $this->salvarImagem($request);

        if ($novaImagem) {
            $this->removerImagem($projeto);
            $dados['imagem'] = $novaImagem;
        }

        $projeto->update($dados);

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Projeto $projeto)
    {
        $this->garantirDono($projeto);

        $this->removerImagem($projeto);
        $projeto->delete();

        return redirect()
            ->route('projetos.index')
            ->with('success', 'Projeto excluído com sucesso.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id')->where('user_id', $request->user()->id),
            ],
            'titulo' => ['required', 'string', 'max:160'],
            'responsavel' => ['required', 'string', 'max:120'],
            'resumo' => ['required', 'string', 'max:1500'],
            'status' => ['required', Rule::in(Projeto::STATUS)],
            'ano_letivo' => ['required', 'integer', 'min:2000', 'max:2100'],
            'periodo_letivo' => ['required', 'integer', 'min:1', 'max:'.$request->user()->quantidadePeriodos()],
            'data_inicio' => ['nullable', 'date'],
            'data_entrega' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'imagem' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function salvarImagem(Request $request): ?string
    {
        if (! $request->hasFile('imagem')) {
            return null;
        }

        return $request->file('imagem')->store('projetos', 'public');
    }

    private function removerImagem(Projeto $projeto): void
    {
        if ($projeto->imagem) {
            Storage::disk('public')->delete($projeto->imagem);
        }
    }

    private function garantirDono(Projeto $projeto): void
    {
        abort_unless($projeto->user_id === auth()->id(), 404);
    }

    private function filtroAnoLetivo(Request $request): ?int
    {
        if (! $request->has('ano_letivo')) {
            return now()->year;
        }

        return $request->filled('ano_letivo') ? $request->integer('ano_letivo') : null;
    }

    private function filtroPeriodoLetivo(Request $request, User $user): ?int
    {
        if (! $request->has('periodo_letivo')) {
            return $user->periodoAtual();
        }

        return $request->filled('periodo_letivo') ? $request->integer('periodo_letivo') : null;
    }

    private function anosLetivos(User $user): array
    {
        return $user->projetos()
            ->select('ano_letivo')
            ->distinct()
            ->orderByDesc('ano_letivo')
            ->pluck('ano_letivo')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }
}
