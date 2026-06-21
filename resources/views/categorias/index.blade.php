@extends('layouts.app')

@section('title', 'Categorias - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">CRUD de tabela 1</p>
            <h1>Categorias</h1>
        </div>
        <a class="button primary" href="{{ route('categorias.create') }}">Nova categoria</a>
    </div>

    <form class="filter-bar" method="get">
        <input type="search" name="busca" value="{{ $busca }}" placeholder="Buscar por nome ou descrição">
        <button class="button dark" type="submit">Filtrar</button>
        <a class="button ghost" href="{{ route('categorias.index') }}">Limpar</a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Projetos</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td>
                            <span class="color-dot" style="background: {{ $categoria->cor }}"></span>
                            <strong>{{ $categoria->nome }}</strong>
                            <small>{{ Str::limit($categoria->descricao, 80) }}</small>
                        </td>
                        <td>{{ $categoria->projetos_count }}</td>
                        <td>{{ $categoria->ativo ? 'Ativa' : 'Inativa' }}</td>
                        <td class="actions">
                            <a href="{{ route('categorias.show', $categoria) }}">Ver</a>
                            <a href="{{ route('categorias.edit', $categoria) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">Nenhuma categoria encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categorias->links() }}
@endsection
