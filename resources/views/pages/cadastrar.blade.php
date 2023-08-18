@extends('pages.layout.base')
@section('content')
    <h1>Cadastrar</h1>
    <form submit="/cadastrar" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <label class="input">
            Nome
            <input type="name" name="name" required value="{{old('name')}}" size="60" autocomplete="off">
        </label>
        <label class="input">
            E-Mail
            <input type="email" name="email" required value="{{old('email')}}" size="60" autocomplete="off">
        </label>
        <label class="input">
            Senha
            <input type="password" name="password" required size="60" autocomplete="off">
        </label>
        <label class="input">
            Confirmação da senha
            <input type="password" name="password_confirmation" required size="60" autocomplete="off">
        </label>
        <label class="input">
            Currículo
            <input type="file" name="curriculum" required autocomplete="off">
        </label>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="/">Voltar para o login</a>
@endsection