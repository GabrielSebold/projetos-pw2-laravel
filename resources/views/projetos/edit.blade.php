@extends('layouts.app')

@section('title', 'Editar projeto - Projetos PW2')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Edição</p>
            <h1>{{ $projeto->titulo }}</h1>
        </div>
        <a class="button ghost" href="{{ route('projetos.show', $projeto) }}">Voltar</a>
    </div>

    <form class="form-panel" action="{{ route('projetos.update', $projeto) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('projetos._form')
    </form>
@endsection
