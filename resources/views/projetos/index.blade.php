@extends('layouts.app')

@section('title', 'Projetos - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">CRUD de tabela 2</p>
            <h1>Projetos</h1>
            <p class="heading-note">Filtros iniciais pelo ano letivo e pelo período configurado no cadastro.</p>
        </div>
        <a class="button primary" href="{{ route('projetos.create') }}">Novo projeto</a>
    </div>

    <form class="filter-bar" method="get">
        <select name="ano_letivo" aria-label="Ano letivo">
            <option value="">Todos os anos</option>
            @foreach ($anosLetivos as $ano)
                <option value="{{ $ano }}" @selected($filtros['anoLetivo'] === $ano)>{{ $ano }}</option>
            @endforeach
        </select>

        <select name="periodo_letivo" aria-label="Período letivo">
            <option value="">Todos os períodos</option>
            @foreach ($periodosLetivos as $numero => $rotulo)
                <option value="{{ $numero }}" @selected($filtros['periodoLetivo'] === $numero)>{{ $rotulo }}</option>
            @endforeach
        </select>

        <input type="search" name="busca" value="{{ $filtros['busca'] }}" placeholder="Buscar por título, responsável ou resumo">

        <select name="categoria_id">
            <option value="">Todas as categorias</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected($filtros['categoriaId'] === $categoria->id)>{{ $categoria->nome }}</option>
            @endforeach
        </select>

        <select name="status">
            <option value="">Todos os status</option>
            @foreach ($statusList as $status)
                <option value="{{ $status }}" @selected($filtros['status'] === $status)>{{ $status }}</option>
            @endforeach
        </select>

        <button class="button dark" type="submit">Filtrar</button>
        <a class="button ghost" href="{{ route('projetos.index', ['ano_letivo' => '', 'periodo_letivo' => '']) }}">Ver todos</a>
    </form>

    <div class="cards-grid">
        @forelse ($projetos as $projeto)
            <article class="project-card">
                <div class="cover">
                    @if ($projeto->imagem)
                        <img src="{{ asset('storage/' . $projeto->imagem) }}" alt="Imagem do projeto {{ $projeto->titulo }}">
                    @else
                        <span>{{ Str::substr($projeto->titulo, 0, 2) }}</span>
                    @endif
                </div>
                <div class="project-body">
                    <span class="status">{{ $projeto->status }}</span>
                    <h2>{{ $projeto->titulo }}</h2>
                    <p>{{ Str::limit($projeto->resumo, 130) }}</p>
                    <small>{{ $projeto->categoria->nome }} por {{ $projeto->responsavel }}</small>
                    <small>{{ $projeto->ano_letivo }} - {{ $periodosLetivos[$projeto->periodo_letivo] ?? $projeto->periodo_letivo.'º período' }}</small>
                </div>
                <div class="card-actions">
                    <a href="{{ route('projetos.show', $projeto) }}">Ver detalhes</a>
                    <a href="{{ route('projetos.edit', $projeto) }}">Editar</a>
                </div>
            </article>
        @empty
            <div class="empty">Nenhum projeto encontrado para os filtros selecionados.</div>
        @endforelse
    </div>

    {{ $projetos->links() }}
@endsection
