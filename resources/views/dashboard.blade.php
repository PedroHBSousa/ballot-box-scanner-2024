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
                    <div class="teste">
                        <div class="accountant-numbers">
                            <h1 class="accountant-numbers-title">Prefeito</h1>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Nominal:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($nominais, 0) }} ({{ number_format($porcentagemNominais, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Branco:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($brancos, 0) }} ({{ number_format($porcentagemBrancos, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Nulo:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($nulos, 0) }} ({{ number_format($porcentagemNulos, 2) }}%)
                                </h2>
                            </div>
                        </div>
                        <div class="accountant-numbers">
                            <h1 class="accountant-numbers-title">Vereadores</h1>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Nominal:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($nominaisVereador, 0) }}
                                    ({{ number_format($porcentagemNominaisVereador, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Branco:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($brancosVereador, 0) }}
                                    ({{ number_format($porcentagemBrancosVereador, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="outros">
                                <h2 class="accountant-type-voto">Nulo:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($nulosVereador, 0) }}
                                    ({{ number_format($porcentagemNulosVereador, 2) }}%)
                                </h2>
                            </div>
                        </div>
                        <div class="accountant-numbers">
                            <h1 class="accountant-numbers-title">Geral</h1>
                            <div class="accountant-items" id="total">
                                <h2 class="accountant-type-voto">Total:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($totalApurados, 0) }} ({{ number_format($percentApurados, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="abstencao">
                                <h2 class="accountant-type-voto">Abstenção:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($totalFaltantes, 0) }}
                                    ({{ number_format($percentFaltantes, 2) }}%)
                                </h2>
                            </div>
                            <div class="accountant-items" id="restante">
                                <h2 class="accountant-type-voto">Restante:</h2>
                                <h2 class="accountant-number" id="accountant-number">
                                    {{ number_format($restanteApurar, 0) }}
                                    ({{ number_format($percentRestante, 2) }}%)
                                </h2>
                            </div>
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
            <form class="form" id="form-buscar-vereador">
                <input class="input-search-vereador" type="number" id="search" name="search"
                    placeholder="Digite o número do vereador">
                <button class="button-submit-vereador" type="submit">Buscar</button>
            </form>

            <div id="error-message" class="error-message" style="display:none;"></div>
            <div id="result-container" class="items-buscar-vereador" style="display:none;"></div>
        </div>
    </div>

    <script>
        document.getElementById('form-buscar-vereador').addEventListener('submit', function(e) {
            e.preventDefault(); // Evita o reload da página

            let search = document.getElementById('search').value;
            let errorMessage = document.getElementById('error-message');
            let resultContainer = document.getElementById('result-container');

            errorMessage.style.display = 'none';
            resultContainer.style.display = 'none';

            fetch(`/buscar-vereador?search=${search}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Adiciona o token CSRF para segurança
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        errorMessage.innerText = data.error;
                        errorMessage.style.display = 'block';
                    } else {
                        let secoesList = data.secoes.map(secao => `
                            <tr>
                                <th scope="row">${secao.id}</th>
                                <td>${secao.localidade.nome}</td>
                                <td>${secao.votos_na_secao}</td>
                            </tr>
            `).join('');
                        resultContainer.innerHTML = `
                <div class="councilor-information">
                    <div class="councilor-information-items">
                         <h4 class="councilor-information-items-title">Número</h4>
                         <h5 class="councilor-information-items-subtitle">${data.vereador.id}</h5>
                    </div>
                    <div class="councilor-information-items">
                        <h4 class="councilor-information-items-title">Nome</h4>
                        <h5 class="councilor-information-items-subtitle">${data.vereador.nome}</h5>
                    </div>
                    <div class="councilor-information-items">
                        <h4 class="councilor-information-items-title">Partido</h4>
                        <h5 class="councilor-information-items-subtitle">${data.vereador.partido}</h5>
                    </div>
                    <div class="councilor-information-items">
                        <h4 class="councilor-information-items-title">Total de votos</h4>
                        <h5 class="councilor-information-items-subtitle">${data.vereador.quantidade_votos}</h5>
                    </div>
                </div>
                <div class="section-table">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Secão</th>
                                <th scope="col">Escola</th>
                                <th scope="col">Votos</th>
                            </tr>
                        </thead>
                        ${secoesList}
                    </table>
                </div>
            `;
                        resultContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
        });
    </script>
    <footer>
        <h1>Juntos é possível!</h1>
    </footer>
</body>

</html>
