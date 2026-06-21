@extends('layouts.app')

@section('title', 'Entrar - Projetos PW2')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Acesso individual</p>
            <h1>Entre para acompanhar seus projetos</h1>
            <p>Cada usuário visualiza apenas as próprias categorias, projetos e etapas de desenvolvimento.</p>
        </div>

        <form class="form-panel auth-card" action="{{ route('login.store') }}" method="post">
            @csrf

            <label>
                E-mail
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>

            <label>
                Senha
                <input type="password" name="password" required>
            </label>

            <label class="check-row">
                <input type="checkbox" name="remember" value="1">
                Manter conectado
            </label>

            <div class="form-actions">
                <button class="button primary" type="submit">Entrar</button>
                <a class="button ghost" href="{{ route('register') }}">Criar conta</a>
            </div>
        </form>
    </section>
@endsection
