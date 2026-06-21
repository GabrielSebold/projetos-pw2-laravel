<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->string('busca')->toString();

        $categorias = Categoria::withCount('projetos')
            ->where('user_id', $request->user()->id)
            ->when($busca, function ($query) use ($busca) {
                $query->where(function ($query) use ($busca) {
                    $query->where('nome', 'like', "%{$busca}%")
                        ->orWhere('descricao', 'like', "%{$busca}%");
                });
            })
            ->orderBy('nome')
            ->paginate(8)
            ->withQueryString();

        return view('categorias.index', compact('categorias', 'busca'));
    }

    public function create()
    {
        return view('categorias.create', [
            'categoria' => new Categoria(['cor' => '#0f766e', 'ativo' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $request->user()->categorias()->create($this->validar($request));

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function show(Categoria $categoria)
    {
        $this->garantirDono($categoria);

        $categoria->load(['projetos' => fn ($query) => $query->latest()]);

        return view('categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        $this->garantirDono($categoria);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $this->garantirDono($categoria);

        $categoria->update($this->validar($request));

        return redirect()
            ->route('categorias.show', $categoria)
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Categoria $categoria)
    {
        $this->garantirDono($categoria);

        if ($categoria->projetos()->exists()) {
            return back()->with('error', 'Não é possível excluir uma categoria que possui projetos vinculados.');
        }

        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'cor' => ['required', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ativo' => ['nullable', 'boolean'],
        ]) + ['ativo' => false];
    }

    private function garantirDono(Categoria $categoria): void
    {
        abort_unless($categoria->user_id === auth()->id(), 404);
    }
}
