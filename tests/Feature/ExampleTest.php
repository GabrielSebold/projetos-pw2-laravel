<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('Sistema de acompanhamento de projetos acadêmicos');
    }

    public function test_project_stage_can_be_registered(): void
    {
        $user = User::factory()->create();

        $categoria = Categoria::create([
            'user_id' => $user->id,
            'nome' => 'Teste',
            'descricao' => 'Categoria usada no teste.',
            'cor' => '#0f766e',
            'ativo' => true,
        ]);

        $projeto = Projeto::create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id,
            'titulo' => 'Projeto teste',
            'responsavel' => 'Aluno',
            'resumo' => 'Resumo do projeto usado no teste.',
            'status' => 'Planejamento',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $user->periodoAtual(),
        ]);

        $response = $this->actingAs($user)->post(route('projetos.etapas.store', $projeto), [
            'titulo' => 'Modelagem',
            'data_registro' => now()->format('Y-m-d'),
            'status' => 'Concluída',
            'descricao' => 'Criação da modelagem inicial.',
            'problematica' => 'Definir o relacionamento entre tabelas.',
            'metodo_gestao' => 'Uso de migrations e validação incremental.',
        ]);

        $response->assertRedirect(route('projetos.show', $projeto));

        $this->assertDatabaseHas('etapa_projetos', [
            'projeto_id' => $projeto->id,
            'titulo' => 'Modelagem',
            'status' => 'Concluída',
        ]);
    }

    public function test_user_only_sees_own_categories(): void
    {
        $gabriel = User::factory()->create();
        $outroUsuario = User::factory()->create();

        Categoria::create([
            'user_id' => $gabriel->id,
            'nome' => 'Categoria do Gabriel',
            'descricao' => 'Visível para o usuário correto.',
            'cor' => '#0f766e',
            'ativo' => true,
        ]);

        Categoria::create([
            'user_id' => $outroUsuario->id,
            'nome' => 'Categoria de outro usuário',
            'descricao' => 'Não deve aparecer para Gabriel.',
            'cor' => '#2563eb',
            'ativo' => true,
        ]);

        $response = $this->actingAs($gabriel)->get(route('categorias.index'));

        $response
            ->assertOk()
            ->assertSee('Categoria do Gabriel')
            ->assertDontSee('Categoria de outro usuário');
    }

    public function test_projects_use_initial_school_period_filter(): void
    {
        $user = User::factory()->create(['tipo_periodo' => 'bimestre']);

        $categoria = Categoria::create([
            'user_id' => $user->id,
            'nome' => 'Aplicações Web',
            'descricao' => 'Categoria usada no teste.',
            'cor' => '#0f766e',
            'ativo' => true,
        ]);

        Projeto::create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id,
            'titulo' => 'Projeto do período atual',
            'responsavel' => 'Aluno',
            'resumo' => 'Projeto que deve aparecer no filtro inicial.',
            'status' => 'Planejamento',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $user->periodoAtual(),
        ]);

        Projeto::create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id,
            'titulo' => 'Projeto de outro período',
            'responsavel' => 'Aluno',
            'resumo' => 'Projeto que nao deve aparecer no filtro inicial.',
            'status' => 'Planejamento',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $user->periodoAtual() === 1 ? 2 : 1,
        ]);

        $response = $this->actingAs($user)->get(route('projetos.index'));

        $response
            ->assertOk()
            ->assertSee('Projeto do período atual')
            ->assertDontSee('Projeto de outro período');
    }
}
