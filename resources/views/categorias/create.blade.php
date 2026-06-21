@extends('layouts.app')

@section('title', 'Nova categoria - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Cadastro</p>
            <h1>Nova categoria</h1>
        </div>
        <a class="button ghost" href="{{ route('categorias.index') }}">Voltar</a>
    </div>

    <form class="form-panel" action="{{ route('categorias.store') }}" method="post">
        @csrf
        @include('categorias._form')
    </form>
@endsection
