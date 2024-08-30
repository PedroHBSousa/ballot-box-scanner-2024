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
                <form method="GET" action="{{ route('getSecao') }}">
                    @csrf
                    <label for="search">Buscar Seção:</label>
<<<<<<< HEAD
                    <input type="number" id="search" name="search" placeholder="Digite o ID da seção: ">
                    <button type="submit" name="action" value="buscar_secao">Buscar</button>
=======
                    <input type="number" id="search" name="search" placeholder="Digite o ID da seção">
                    <button type="submit">Buscar</button>
>>>>>>> e6699a0944d07007b27e3298c0e153fdd21ddd9c
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
                                        <input type="number" name="votos[{{ $candidato->id }}][quantidade]"
                                            id="candidato_{{ $candidato->id }}_quantidade" min="0"
                                            placeholder="Votos">
                                        <input type="hidden" name="votos[{{ $candidato->id }}][candidato_id]"
                                            value="{{ $candidato->id }}">
                                    </div>
                                @endforeach

                                <!-- Campos para votos em branco e nulos -->
                                <br>
                                <div>
                                    <label for="votos_branco">Total de votos em branco:</label>
                                    <input type="number" id="votos_branco" name="votos_branco" min="0"
                                        placeholder="Votos em branco"><br>
                                    <label for="votos_nulo">Total de votos nulos:</label>
                                    <input type="number" id="votos_nulo" name="votos_nulo" min="0"
                                        placeholder="Votos nulos"><br><br>
                                </div>

                                <!-- Seção para buscar e adicionar candidato para vereador -->
                                <h3>Adicionar votos para Vereador:</h3>
                                <div id="vereadors">
                                    <div>
                                        <label for="vereador_search">Buscar Candidato:</label>
                                        <input type="number" id="vereador_search" name="vereador_search"
                                            placeholder="Digite o ID do candidato: ">
                                        <button type="submit" name="action" value="buscar_vereador">Adicionar</button>
                                    </div>
                                    <div id="vereador_resultado">
                                        @if (isset($vereador))
                                            <div>
                                                <label>{{ $vereador->nome }}</label>
                                                <input type="number" name="votos[{{ $vereador->id }}][quantidade]"
                                                    min="0" placeholder="Votos">
                                                <input type="hidden" name="votos[{{ $vereador->id }}][candidato_id]"
                                                    value="{{ $vereador->id }}">
                                            </div>
                                            @elseif(request('action') === 'buscar_vereador')
                                            <p>Candidato não encontrado!</p>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" name="action" value="inserir_votos" >Enviar</button>
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
