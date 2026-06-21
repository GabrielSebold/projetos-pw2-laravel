<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\EtapaProjeto;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@pw2.local',
            'tipo_periodo' => 'semestre',
            'password' => Hash::make('password'),
        ]);

        $categorias = collect([
            [
                'user_id' => $admin->id,
                'nome' => 'Aplicações Web',
                'descricao' => 'Projetos com foco em sistemas Laravel, rotas, controllers, views e banco de dados.',
                'cor' => '#0f766e',
            ],
            [
                'user_id' => $admin->id,
                'nome' => 'Dados e Relatórios',
                'descricao' => 'Projetos que organizam informações, filtros, consultas e indicadores.',
                'cor' => '#2563eb',
            ],
            [
                'user_id' => $admin->id,
                'nome' => 'Experiência do Usuário',
                'descricao' => 'Projetos com foco em interface, navegação e apresentação visual.',
                'cor' => '#d97706',
            ],
        ])->map(fn ($dados) => Categoria::create($dados));

        $portal = Projeto::create([
            'user_id' => $admin->id,
            'categoria_id' => $categorias[0]->id,
            'titulo' => 'Portal de Projetos Acadêmicos',
            'responsavel' => 'Gabriel',
            'resumo' => 'Sistema web para cadastrar, filtrar e acompanhar projetos desenvolvidos na disciplina de Programação Web 2.',
            'status' => 'Em andamento',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $admin->periodoAtual(),
            'data_inicio' => now()->subDays(15),
            'data_entrega' => now()->addDays(2),
        ]);

        $painel = Projeto::create([
            'user_id' => $admin->id,
            'categoria_id' => $categorias[1]->id,
            'titulo' => 'Painel de Entregas',
            'responsavel' => 'Equipe Acadêmica',
            'resumo' => 'Painel com indicadores de quantidade de projetos, categorias e status para apoiar a apresentação da atividade.',
            'status' => 'Planejamento',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $admin->periodoAtual(),
            'data_inicio' => now()->subDays(8),
            'data_entrega' => now()->addDays(10),
        ]);

        $interface = Projeto::create([
            'user_id' => $admin->id,
            'categoria_id' => $categorias[2]->id,
            'titulo' => 'Melhoria de Interface',
            'responsavel' => 'Gabriel',
            'resumo' => 'Aplicação de layout responsivo, menu de navegação, componentes reutilizáveis e tela visualmente atrativa.',
            'status' => 'Concluído',
            'ano_letivo' => now()->year,
            'periodo_letivo' => $admin->periodoAtual(),
            'data_inicio' => now()->subDays(20),
            'data_entrega' => now()->subDays(1),
        ]);

        EtapaProjeto::create([
            'projeto_id' => $portal->id,
            'titulo' => 'Definição da ideia',
            'data_registro' => now()->subDays(14),
            'status' => 'Concluída',
            'descricao' => 'Foi definido que o sistema acompanharia projetos acadêmicos com categorias, responsáveis, prazos e status.',
            'problematica' => 'A atividade precisava cobrir CRUD, relacionamento e busca sem deixar a apresentação confusa.',
            'metodo_gestao' => 'O escopo foi dividido em entidades principais e funcionalidades extras para manter a entrega objetiva.',
            'ordem' => 1,
        ]);

        EtapaProjeto::create([
            'projeto_id' => $portal->id,
            'titulo' => 'Modelagem do banco',
            'data_registro' => now()->subDays(10),
            'status' => 'Concluída',
            'descricao' => 'Foram criadas as tabelas de categorias, projetos e etapas do desenvolvimento com suas chaves e relacionamentos.',
            'problematica' => 'Era necessário representar o relacionamento entre tabelas e também registrar o histórico do andamento.',
            'metodo_gestao' => 'Foram usadas migrations do Laravel e chaves estrangeiras para manter integridade entre os dados.',
            'ordem' => 2,
        ]);

        EtapaProjeto::create([
            'projeto_id' => $portal->id,
            'titulo' => 'Implementação das telas',
            'data_registro' => now()->subDays(4),
            'status' => 'Em execução',
            'descricao' => 'Foram desenvolvidas telas de listagem, cadastro, edição, detalhe e filtros usando Blade e CSS próprio.',
            'problematica' => 'As informações precisavam ficar claras em telas responsivas e sem repetir código desnecessário.',
            'metodo_gestao' => 'O layout base e formulários parciais foram reutilizados para padronizar a interface.',
            'ordem' => 3,
        ]);

        EtapaProjeto::create([
            'projeto_id' => $painel->id,
            'titulo' => 'Indicadores iniciais',
            'data_registro' => now()->subDays(6),
            'status' => 'Planejada',
            'descricao' => 'A tela inicial apresenta totais, projetos recentes e categorias para facilitar a leitura do sistema.',
            'problematica' => 'O dashboard precisava resumir dados sem substituir as telas de CRUD.',
            'metodo_gestao' => 'Foram escolhidos poucos indicadores diretos e links de navegação para as ações principais.',
            'ordem' => 1,
        ]);

        EtapaProjeto::create([
            'projeto_id' => $interface->id,
            'titulo' => 'Revisao visual',
            'data_registro' => now()->subDays(2),
            'status' => 'Concluída',
            'descricao' => 'A interface recebeu menu, cards, filtros, estados vazios e componentes reaproveitados.',
            'problematica' => 'A aplicação não deveria parecer apenas um formulário simples.',
            'metodo_gestao' => 'Foi criado um CSS centralizado com padrão visual único para todas as telas.',
            'ordem' => 1,
        ]);
    }
}
