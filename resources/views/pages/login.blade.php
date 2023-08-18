@extends('pages.layout.base')
@section('content')
    <h1>Login</h1>
    <form submit="/" method="POST">
        @csrf
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <label class="input">
            E-Mail
            <input type="email" name="email" required value="{{old('email')}}" size="60" autocomplete="off">
        </label>
        <label class="input">
            Senha
            <input type="password" name="password" required size="60" autocomplete="off">
        </label>
        <label class="box">
            Manter conectado
            <input type="checkbox" name="remember" autocomplete="off">
        </label>
        <button type="submit">Entrar</button>
    </form>
    <a href="/cadastrar">Fazer cadastro</a>
@endsection