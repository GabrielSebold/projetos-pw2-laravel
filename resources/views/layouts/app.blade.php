<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Projetos PW2')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-mark">PW2</span>
            <span>
                <strong>Projetos PW2</strong>
                <small>Gestão acadêmica</small>
            </span>
        </a>

        <nav class="nav">
            @auth
                <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>Início</a>
                <a href="{{ route('projetos.index') }}" @class(['active' => request()->routeIs('projetos.*')])>Projetos</a>
                <a href="{{ route('categorias.index') }}" @class(['active' => request()->routeIs('categorias.*')])>Categorias</a>
            @else
                <a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Entrar</a>
                <a href="{{ route('register') }}" @class(['active' => request()->routeIs('register')])>Cadastro</a>
            @endauth
        </nav>

        @auth
            <form class="user-menu" action="{{ route('logout') }}" method="post">
                @csrf
                <span>{{ auth()->user()->name }}</span>
                <button type="submit">Sair</button>
            </form>
        @endauth
    </header>

    <main class="page">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
