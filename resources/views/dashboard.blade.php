@extends('layouts.app')

@section('title', 'Início - Projetos PW2')

@section('content')
    <section class="hero">
        <div>
            <p class="eyebrow">Programação Web 2</p>
            <h1>Sistema de acompanhamento de projetos acadêmicos</h1>
            <p class="hero-text">Organize projetos por categoria, acompanhe status, responsáveis e prazos, e apresente uma aplicação Laravel com MVC, banco de dados e interface reutilizável.</p>
            <div class="hero-actions">
                <a class="button primary" href="{{ route('projetos.create') }}">Novo projeto</a>
                <a class="button light" href="{{ route('categorias.create') }}">Nova categoria</a>
            </div>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat">
            <span>{{ $totalProjetos }}</span>
            <strong>Projetos cadastrados</strong>
        </article>
        <article class="stat">
            <span>{{ $totalCategorias }}</span>
            <strong>Categorias ativas</strong>
        </article>
        <article class="stat">
            <span>{{ now()->format('Y') }}</span>
            <strong>Ano letivo</strong>
        </article>
    </section>

    <section class="split">
        <div>
            <div class="section-title">
                <div>
                    <p class="eyebrow">Últimas entregas</p>
                    <h2>Projetos recentes</h2>
                </div>
                <a href="{{ route('projetos.index') }}">Ver todos</a>
            </div>

            <div class="stack">
                @forelse ($projetosRecentes as $projeto)
                    <article class="list-card">
                        <span class="status">{{ $projeto->status }}</span>
                        <h3>{{ $projeto->titulo }}</h3>
                        <p>{{ Str::limit($projeto->resumo, 120) }}</p>
                        <small>{{ $projeto->categoria->nome }} por {{ $projeto->responsavel }}</small>
                    </article>
                @empty
                    <div class="empty">Nenhum projeto cadastrado ainda.</div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="section-title">
                <div>
                    <p class="eyebrow">Organização</p>
                    <h2>Categorias</h2>
                </div>
                <a href="{{ route('categorias.index') }}">Gerenciar</a>
            </div>

            <div class="category-list">
                @forelse ($categorias as $categoria)
                    <a class="category-chip" href="{{ route('categorias.show', $categoria) }}">
                        <span style="background: {{ $categoria->cor }}"></span>
                        {{ $categoria->nome }}
                        <small>{{ $categoria->projetos_count }} projetos</small>
                    </a>
                @empty
                    <div class="empty">Crie categorias para classificar os projetos.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
