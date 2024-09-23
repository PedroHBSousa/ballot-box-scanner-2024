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
            <h1>APURAÇÃO ELEITORAL<span>SÃO SEBASTIÃO</span></h1>
            <h2 class="text-container-title">2024</h2>
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
                    <p>Prefeito</p>
                </div>
                <canvas id="piechart-prefeitos" width="400" height="400"></canvas>
            </div>
            {{-- -------------------------------------------------- inicio do painel ---------------------------------------------- --}}
            <div class="painel">
                <div class="painel-container">
                    <div class="painel-header">
                        <h1 class="painel-header-title">SITUAÇÃO ATUAL DOS VOTOS</h1>
                        <h2 class="last-update-time">Última entrada de dados:
                            {{ $ultimaAtualizacao->format('H:i:s d/m/Y') }}
                        </h2>
                    </div>
                    <div class="painel-body">
                        <div class="group-candidato">
                            <h1 class="group-title">PREFEITO</h1>
                            <div class="votos-container">
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Nominal</span>{{ number_format($nominais, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNominais, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Branco</span>{{ number_format($brancos, 0, '.', '.') }}
                                        ({{ number_format($porcentagemBrancos, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Nulo</span> {{ number_format($nulos, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNulos, 2) }}%)
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="group-candidato">
                            <h1 class="group-title">VEREADORES</h1>
                            <div class="votos-container">
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Nominal</span>{{ number_format($nominaisVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNominaisVereador, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Branco</span>{{ number_format($brancosVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemBrancosVereador, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information">
                                        <span>Nulo</span> {{ number_format($nulosVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNulosVereador, 2) }}%)
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="group-geral">
                            <h1 class="group-title">ELEITORES</h1>
                            <div class="eleitores-container">
                                <div class="eleitores" id="total-votos-apurados">
                                    <h1 class="eleitor-information">
                                        <span>Votos apurados</span>
                                        {{ number_format($totalApurados, 0, '.', '.') }}/64.437
                                        ({{ number_format($percentApurados, 2) }}%)
                                    </h1>
                                </div>
                                <div class="eleitores" id="eleitor-faltante">
                                    <h1 class="eleitor-information">
                                        <span>Abstenção</span>{{ number_format($totalFaltantes, 0, '.', '.') }}
                                        ({{ number_format($percentFaltantes, 2) }}%)
                                    </h1>
                                </div>
                                <div class="eleitores" id="nao-apurado">
                                    <h1 class="eleitor-information">
                                        <span>Não apurados</span>{{ number_format($restanteApurar, 0, '.', '.') }}
                                        ({{ number_format($percentRestante, 2) }}%)
                                    </h1>
                                </div>
                                <div class="eleitores" id="secoes-apuradas">
                                    <h1 class="eleitor-information">
                                        <span>Seções apuradas</span> {{ number_format($secoesApuradas, 0) }}/206
                                        ({{ number_format($percentSecoesApuradas, 2) }}%)
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- -------------------------------------------------- fim do painel ---------------------------------------------- --}}
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
    {{-- -------------------------------------------------- inicio do search ---------------------------------------------- --}}
    <div class="search-container">
        <div class="search">
            <h2>Buscar votos de candidato</h2>
            <form class="form" id="form-buscar-vereador">
                <input class="input-search-vereador" type="text" id="search" name="search"
                    placeholder="Digite o nome ou número do candidato">
                <button class="button-submit-vereador" type="submit">Buscar</button>
                <div id="autocomplete-list" class="autocomplete-items"></div> <!-- Lista de sugestões -->
            </form>
            <div id="error-message" class="error-message" style="display:none;"></div>
            <div id="result-container" class="items-buscar-vereador" style="display:none;"></div>
        </div>
    </div>

    <script>
        // Evento de input para o campo de busca
        document.getElementById('search').addEventListener('input', function() {
            let search = this.value.trim(); // Remove espaços desnecessários
            let autocompleteList = document.getElementById('autocomplete-list');

            autocompleteList.innerHTML = ''; // Limpa as sugestões anteriores

            // Evita buscar se o campo de pesquisa tiver menos de 2 caracteres
            if (search.length < 2) {
                return;
            }

            // Se a busca for por nome (ou partido)
            if (isNaN(search)) { // Se for um texto
                searchPorNomeOuPartido(search);
            }
        });

        // Adiciona o evento de submit para o formulário
        document.getElementById('form-buscar-vereador').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            let search = document.getElementById('search').value.trim(); // Captura o valor do campo de busca

            // Verifica se o campo de busca está vazio
            if (search.length < 2) {
                document.getElementById('error-message').innerText = 'Por favor, digite pelo menos 2 caracteres.';
                document.getElementById('error-message').style.display = 'block'; // Exibe a mensagem de erro
                return;
            } else {
                document.getElementById('error-message').style.display = 'none'; // Esconde a mensagem de erro
            }

            // Se a busca for por número (ID do candidato)
            if (!isNaN(search)) { // Se for um número
                searchVereadorPorNumero(search);
            }
        });

        // Função para buscar por nome do vereador ou partido
        function searchPorNomeOuPartido(search) {
            let autocompleteList = document.getElementById('autocomplete-list');

            fetch(`/buscar-vereador?search=${search}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    autocompleteList.innerHTML = ''; // Limpa as sugestões anteriores
                    if (data.error) {
                        autocompleteList.innerHTML = `<div>${data.error}</div>`;
                    } else {
                        if (data.vereadores && data.vereadores.length > 0) {
                            // Se houver vereadores correspondentes, exibe os resultados
                            data.vereadores.forEach(vereador => {
                                let item = document.createElement('div');
                                item.innerHTML = `<strong>${vereador.nome}</strong> (${vereador.partido})`;

                                // Quando o item da lista for clicado, busca os detalhes do vereador
                                item.addEventListener('click', function() {
                                    fetchVereadorDetails(vereador.id);
                                    autocompleteList.innerHTML = ''; // Limpa as sugestões ao selecionar
                                });

                                autocompleteList.appendChild(item);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar vereadores:', error);
                });
        }

        // Função para buscar vereador pelo número (ID)
        function searchVereadorPorNumero(search) {
            let resultContainer = document.getElementById('result-container'); // Para mostrar os detalhes do vereador

            fetch(`/buscar-vereador?search=${search}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        resultContainer.innerHTML = `<div>${data.error}</div>`;
                        resultContainer.style.display = 'block'; // Exibe a mensagem de erro
                    } else if (data.vereador) {
                        // Exibe os detalhes do vereador diretamente
                        fetchVereadorDetails(data.vereador.id); // Chama a função que exibe os detalhes do vereador
                    } else {
                        resultContainer.innerHTML = '<div>Nenhum vereador encontrado.</div>';
                        resultContainer.style.display = 'block'; // Exibe a mensagem se não encontrar
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar vereador por número:', error);
                });
        }

        // Função para buscar e exibir os detalhes do vereador selecionado
        function fetchVereadorDetails(vereadorId) {
            let resultContainer = document.getElementById('result-container');

            fetch(`/buscar-vereador?search=${vereadorId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.vereador) {
                        // Gera a lista de seções onde o vereador recebeu votos
                        let secoesList = data.secoes.map(secao => `
                    <tr>
                        <th scope="row">${secao.id}</th>
                        <td>${secao.localidade.nome}</td>
                        <td>${secao.votos_na_secao}</td>
                    </tr>
                `).join('');

                        // Exibe os detalhes do vereador selecionado
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Seção</th>
                                    <th scope="col">Escola</th>
                                    <th scope="col">Votos</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${secoesList}
                            </tbody>
                        </table>
                    </div>
                `;

                        resultContainer.style.display = 'block'; // Exibe o container de resultados
                    } else {
                        resultContainer.innerHTML = `<div>${data.error}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar detalhes do vereador:', error);
                });
        }
    </script>

    <footer>
        <h1 class="footer-title">Vai ser ainda melhor.</h1>
    </footer>
</body>

</html>