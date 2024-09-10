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
            <h1>APURAÇÃO ELEITORAL</h1>
            <p>2024</p>
        </div>

        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>

    <div class="accountant">
        <div class="accountant-container">
            <div class="accountant-text">
                <h1>TOTAL DE VOTOS APURADOS</h1>
            </div>
            <div class="accountant-numbers">
                <p>Prefeito</p>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">20%</h1>
                    <h1 class="accountant-voto">Branco</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">10%</h1>
                    <h1 class="accountant-voto">Nulo</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">8%</h1>
                    <h1 class="accountant-voto">Abstenção</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">2%</h1>
                    <h1 class="accountant-voto">Restante</h1>
                </div>
            </div>
            <div class="accountant-numbers">
                <p>Vereadores</p>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">20%</h1>
                    <h1 class="accountant-voto">Branco</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">10%</h1>
                    <h1 class="accountant-voto">Nulo</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">8%</h1>
                    <h1 class="accountant-voto">Abstenção</h1>
                </div>
                <div class="accountant-items">
                    <h1 class="accountant-number" id="accountant-number">2%</h1>
                    <h1 class="accountant-voto">Restante</h1>
                </div>
            </div>
        </div>
    </div>

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
                <option value="">Selecione um bairro</option>
            </select>
        </div>
        <div id="school-filter-container" style="display: none;">
            <select class="escola-subfilter-select" id="escola-subfilter-select">
                <option value="">Selecione uma escola</option>
            </select>
        </div>
        <div id="regiao-subfilter-container" style="display: none;">
            <select id="regiao-subfilter-select">
                <option value="">Selecione uma região</option>
            </select>
        </div>

        <div class="charts-container">
            <div class="chart">
                <div class="pref">
                    <h2>Prefeito</h2>
                </div>
                <canvas id="piechart-prefeitos" width="400" height="400"></canvas>
            </div>
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

    <footer>
        <h1>Juntos é possível!</h1>
    </footer>
</body>

</html>
