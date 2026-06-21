@extends('layouts.app')

@section('title', 'Novo projeto - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Cadastro</p>
            <h1>Novo projeto</h1>
        </div>
        <a class="button ghost" href="{{ route('projetos.index') }}">Voltar</a>
    </div>

    <form class="form-panel" action="{{ route('projetos.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('projetos._form')
    </form>
@endsection
