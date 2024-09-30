<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/login.css')
</head>

<body>
    <!-- resources/views/auth/login.blade.php -->
    <div class="container-image">
        <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
        <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
        <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
    </div>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label for="password">Chave de Acesso</label>
            <input type="password" name="password" id="password">
        </div>
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror
        <button type="submit">Entrar</button>
    </form>

</body>

</html>
