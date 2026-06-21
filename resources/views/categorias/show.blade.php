@extends('layouts.app')

@section('title', $categoria->nome . ' - Projetos PW2')

@section('content')
    <div class="detail-hero">
        <span class="color-dot large" style="background: {{ $categoria->cor }}"></span>
        <div>
            <p class="eyebrow">Categoria</p>
            <h1>{{ $categoria->nome }}</h1>
            <p>{{ $categoria->descricao ?: 'Sem descrição cadastrada.' }}</p>
        </div>
        <div class="detail-actions">
            <a class="button light" href="{{ route('categorias.edit', $categoria) }}">Editar</a>
            <form action="{{ route('categorias.destroy', $categoria) }}" method="post" onsubmit="return confirm('Excluir esta categoria?')">
                @csrf
                @method('DELETE')
                <button class="button danger" type="submit">Excluir</button>
            </form>
        </div>
    </div>

    <section>
        <div class="section-title">
            <div>
                <p class="eyebrow">Relacionamento</p>
                <h2>Projetos desta categoria</h2>
            </div>
            <a href="{{ route('projetos.create') }}">Cadastrar projeto</a>
        </div>

        <div class="cards-grid">
            @forelse ($categoria->projetos as $projeto)
                <article class="project-card">
                    <span class="status">{{ $projeto->status }}</span>
                    <h3>{{ $projeto->titulo }}</h3>
                    <p>{{ Str::limit($projeto->resumo, 120) }}</p>
                    <a href="{{ route('projetos.show', $projeto) }}">Abrir projeto</a>
                </article>
            @empty
                <div class="empty">Nenhum projeto vinculado.</div>
            @endforelse
        </div>
    </section>
@endsection
