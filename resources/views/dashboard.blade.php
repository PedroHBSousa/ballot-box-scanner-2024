<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico da Apuração</title>
    <link rel="icon" href="{{ asset('graphicon.svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('graphicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/dashboard.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
    </script>
</head>

<body>
    <header>
        <div class="text-container">
            <h1>APURAÇÃO ELEITORAL<span>2024</span></h1>
        </div>

        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>

    <div class="container">
        <div class="custom-select">
            <select id="filter-select">
                <option value="geral">Filtro</option>
                <option value="prefeitos" hidden>Prefeito</option>
                <option value="vereadores" hidden>Vereadores</option>
                <option value="localidades">Escolas</option>
                <option value="bairros">Bairros</option>
                <option value="partidos">Partidos</option>
                <option value="regioes">Regiões</option>
            </select>
        </div>

        <div id="subfilter-container" style="display: none;">
            <select id="bairro-subfilter-select">
                <option value="">Selecione o bairro</option>
            </select>
        </div>
        <div id="school-filter-container" style="display: none;">
            <select class="escola-subfilter-select" id="escola-subfilter-select">
                <option value="">Selecione a escola</option>
            </select>
        </div>
        <div id="regiao-subfilter-container" style="display: none;">
            <select id="regiao-subfilter-select">
                <option value="">Selecione a região</option>
            </select>
        </div>

        <div class="charts-container">
            <div class="chart">
                <div class="pref">
                    <h2>Prefeito</h2>
                </div>
                <canvas id="piechart-prefeitos" width="400" height="400"></canvas>
            </div>
            <section class="accountant">
                <div class="accountant-container">
                    <div class="accountant-header">
                        <h1>SITUAÇÃO ATUAL DOS VOTOS</h1>
                        <h2 class="last-update-title"> Última entrada de dados: </h2>
                        <h2 class="last-update-time">{{ $ultimaAtualizacao->format('H:i:s d/m/Y') }}</h2>
                    </div>
                    <h1 class="accountant-numbers-title">Prefeito</h1>
                    <div class="accountant-numbers">
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Nominal:</h2>
                            <h2 class="accountant-number" id="accountant-number">
                                {{ $nominais }} ({{ number_format($porcentagemNominais, 2) }}%)
                            </h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Branco:</h2>
                            <h2 class="accountant-number" id="accountant-number">
                                {{ $brancos }} ({{ number_format($porcentagemBrancos, 2) }}%)
                            </h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Nulo:</h2>
                            <h2 class="accountant-number" id="accountant-number">
                                {{ $nulos }} ({{ number_format($porcentagemNulos, 2) }}%)
                            </h2>
                        </div>
                    </div>
                    <h1 class="accountant-numbers-title">Vereadores</h1>
                    <div class="accountant-numbers">
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Nominal:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Branco:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Nulo:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>

                    </div>
                    <h1 class="accountant-numbers-title">Geral</h1>
                    <div class="accountant-numbers">
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Total:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Abstenção:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>
                        <div class="accountant-items">
                            <h2 class="accountant-type-voto">Restante:</h2>
                            <h2 class="accountant-number" id="accountant-number">8.967 (20%)</h2>
                        </div>
                    </div>
                </div>
            </section>
            <div class="chart">
                <div class="verea">
                    <h2>Vereadores</h2>
                </div>
                <canvas id="barchart-vereadores" width="400" height="400"></canvas>
            </div>
            <div class="chart">
                <div class="bairros">
                    <h2>Bairros</h2>
                </div>
                <canvas id="barchart-bairros" width="400" height="400"></canvas>
            </div>
            <div class="chart">
                <div class="escolas">
                    <h2>Escolas</h2>
                </div>
                <canvas id="barchart-escolas" width="400" height="400"></canvas>

            </div>
            <div class="chart">
                <div class="partidos">
                    <h2>Partidos</h2>
                </div>
                <canvas id="barchart-partidos" width="400" height="400"></canvas>
            </div>
            <div class="chart">
                <div class="regioes">
                    <h2>Regioes</h2>
                </div>
                <canvas id="barchart-regioes" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="search-container">
        <div class="search">
            <h2>Busque o vereador</h2>
            <form class="form" action="{{ route('buscar.vereador') }}" method="GET">
                @csrf
                <input class="input-search-vereador" type="number" id="search" name="search"
                    placeholder="Digite o número do vereador">
                <button class="button-submit-vereador" type="submit">Buscar</button>
            </form>

            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('vereador'))
                @php
                    $vereador = session('vereador');
                @endphp
                <div class="items-buscar-vereador">
                    <h4><span>Número: </span>{{ $vereador['id'] }}</h4>
                    <h4><span>Nome: </span>{{ $vereador['nome'] }}</h4>
                    <h4><span>Partido: </span>{{ $vereador['partido'] }}</h4>
                    <h4><span>Total de votos: </span>{{ $vereador['quantidade_votos'] }}</h4>
                </div>
                @if (session('secoes'))
                    <h4>Seções em que foi votado:</h4>
                    <ul>
                        @foreach (session('secoes') as $secao)
                            <h4>{{ $secao->id }}</h4>
                        @endforeach
                    </ul>
                @endif

                @php
                    session()->forget('vereador');
                    session()->forget('totalVotes');
                    session()->forget('secoes');
                @endphp
            @endif

        </div>
    </div>

    <footer>
        <h1>Juntos é possível!</h1>
    </footer>
</body>

</html>
