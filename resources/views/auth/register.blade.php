@extends('layouts.app')

@section('title', 'Cadastro - Projetos PW2')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Novo usuário</p>
            <h1>Crie sua conta no sistema</h1>
            <p>Escolha como seu ano letivo será organizado. Esse padrão define os filtros iniciais dos projetos.</p>
        </div>

        <form class="form-panel auth-card" action="{{ route('register.store') }}" method="post">
            @csrf

            <label>
                Nome
                <input name="name" value="{{ old('name') }}" required autofocus maxlength="120">
            </label>

            <label>
                E-mail
                <input type="email" name="email" value="{{ old('email') }}" required maxlength="160">
            </label>

            <label>
                Organização do ano letivo
                <select name="tipo_periodo" required>
                    @foreach ($tiposPeriodo as $valor => $rotulo)
                        <option value="{{ $valor }}" @selected(old('tipo_periodo', 'semestre') === $valor)>{{ $rotulo }}</option>
                    @endforeach
                </select>
            </label>

            <div class="form-grid">
                <label>
                    Senha
                    <input type="password" name="password" required minlength="6">
                </label>

                <label>
                    Confirmar senha
                    <input type="password" name="password_confirmation" required minlength="6">
                </label>
            </div>

            <div class="form-actions">
                <button class="button primary" type="submit">Cadastrar</button>
                <a class="button ghost" href="{{ route('login') }}">Já tenho conta</a>
            </div>
        </form>
    </section>
@endsection
