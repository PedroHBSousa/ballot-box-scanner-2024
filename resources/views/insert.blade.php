<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inserir</title>
    <link rel="icon" href="{{ asset('inserticon.svg') }}" type="image/x-icon" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/insert.css')
</head>

<body>
    <header>
        <h1>SÃO SEBASTIÃO</h1>
        <div class="container-image">

            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>
    <div class="container">
        <div class="busca">
            <h1>Insira os dados abaixo</h1>

            <div id="secao">
                <form action="{{ route('insert') }}" method="GET">
                    <label for="search">Buscar Seção:</label>
                    <input type="number" id="search" name="search" placeholder="Digite o ID da seção">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <div>
                @if(request('search') && !$secoes->isEmpty())
                    <h2>Dados da Seção Selecionada</h2>
                    @foreach($secoes as $secao)
                        <div>
                            <h3>Seção ID: {{ $secao->id }} | {{ $secao->localidade->nome }}</h3> <br>

                            <form action="{{ route('insert.data')}}" method="POST">
                                @csrf
                                <input type="hidden" name="secao_id" value="{{ $secao->id }}">

                                <h3>Digite os votos para cada candidato:</h3>
                                @foreach ($candidatos as $candidato)
                                    <div>
                                        <label for="candidato_{{ $candidato->id }}">{{ $candidato->nome }}</label>
                                        <input type="number" name="votos[{{ $candidato->id }}]" id="candidato_{{ $candidato->id }}" min="0" placeholder="Digite os votos" required>
                                    </div>
                                @endforeach
                                
                                <button type="submit">Enviar</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p>Nenhuma seção encontrada.</p>
                @endif
            </div>
        </div>

        <div>
                <!-- 

            <h2>Boletim:</h2>

            <form action="{{ route('insert.data') }}" method="POST">
                @csrf
        
                <label>Seção: </label>
                <input type="number" name="secao_id" id="secao_id" placeholder="Seção do boletim" required><br><br>
                <label>Aptos: </label>
                <input type="number" name="apto" id="apto" placeholder="Aptos presentes" required><br><br>
                <label>Assinatura digital: </label>
                <input type="text" name="assinatura_digital" id="assinatura_digital" placeholder="Assinatura do boletim" required><br><br>
                <label>N° de pessoas que compareceram: </label>
                <input type="number" name="comp" id="comp" required><br><br>
                <label>N° de pessoas que faltaram: </label>
                <input type="number" name="falt" id="falt" required><br><br>
                
                
                <button type="submit">Enviar</button>

            </form> -->
        </div>

        @if (session('success'))
            <span style="color: #082;">
                {{ session('success') }}
            </span>
        @endif

        @if (session('error'))
            <span style="color: #f00;">
                {{ session('error') }}
            </span>
        @endif

    </div>
</body>
<footer>
    <h1>Juntos é possível!</h1>

</footer>

</html>