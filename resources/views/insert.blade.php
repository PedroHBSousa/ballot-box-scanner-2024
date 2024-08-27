<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir</title>
</head>
<body>
    <h1>Insira os dados abaixo.</h1>
    
    <div id="secao">
        <form action="{{ route('insert') }}" method="GET">
            <label for="search">Buscar Seção:</label>
            <input type="number" id="search" name="search" placeholder="Digite o ID da seção">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div>
        @if(request('search'))
            @if($secoes->isEmpty())
                <p>Nenhuma seção encontrada.</p>
            @else
                <ul>
                    @foreach($secoes as $secao)
                        <li>ID da seção: {{ $secao->id }} - Localidade: {{ $secao->localidade->nome }}</li>
                    @endforeach
                </ul>
            @endif
        @endif
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
</body>
</html>