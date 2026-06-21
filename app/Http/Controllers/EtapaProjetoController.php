<?php

namespace App\Http\Controllers;

use App\Models\EtapaProjeto;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EtapaProjetoController extends Controller
{
    public function store(Request $request, Projeto $projeto)
    {
        $this->garantirDonoProjeto($projeto);

        $dados = $this->validar($request);
        $dados['ordem'] = $dados['ordem'] ?? $projeto->etapas()->count() + 1;

        $projeto->etapas()->create($dados);

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Etapa registrada no acompanhamento do projeto.');
    }

    public function update(Request $request, Projeto $projeto, EtapaProjeto $etapaProjeto)
    {
        $this->garantirDonoProjeto($projeto);
        $this->garantirProjetoCorreto($projeto, $etapaProjeto);

        $etapaProjeto->update($this->validar($request));

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Etapa atualizada com sucesso.');
    }

    public function destroy(Projeto $projeto, EtapaProjeto $etapaProjeto)
    {
        $this->garantirDonoProjeto($projeto);
        $this->garantirProjetoCorreto($projeto, $etapaProjeto);

        $etapaProjeto->delete();

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Etapa removida do acompanhamento.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:140'],
            'data_registro' => ['required', 'date'],
            'status' => ['required', Rule::in(EtapaProjeto::STATUS)],
            'descricao' => ['required', 'string', 'max:1500'],
            'problematica' => ['nullable', 'string', 'max:1500'],
            'metodo_gestao' => ['nullable', 'string', 'max:1500'],
            'ordem' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);
    }

    private function garantirProjetoCorreto(Projeto $projeto, EtapaProjeto $etapaProjeto): void
    {
        abort_unless($etapaProjeto->projeto_id === $projeto->id, 404);
    }

    private function garantirDonoProjeto(Projeto $projeto): void
    {
        abort_unless($projeto->user_id === auth()->id(), 404);
    }
}
