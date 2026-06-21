@extends('layouts.app')

@section('title', 'Editar categoria - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Edição</p>
            <h1>{{ $categoria->nome }}</h1>
        </div>
        <a class="button ghost" href="{{ route('categorias.show', $categoria) }}">Voltar</a>
    </div>

    <form class="form-panel" action="{{ route('categorias.update', $categoria) }}" method="post">
        @csrf
        @method('PUT')
        @include('categorias._form')
    </form>
@endsection
