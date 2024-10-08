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
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    <div class="alert">
        <p>
            <strong>Atenção:</strong> todos os dados apresentados nesta página são atualizados automaticamente a cada 1
            minuto.
        </p>
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
                <option value="partidos-vereador">Vereadores por partido</option>
                <option value="regioes">Regiões</option>
            </select>
        </div>

        <div class="custom-select" id="subfilter-container" style="display: none;">
            <select id="bairro-subfilter-select">
                <option value="">Selecione o bairro</option>
            </select>
        </div>
        <div class="custom-select" id="school-filter-container" style="display: none;">
            <select id="escola-subfilter-select">
                <option value="">Selecione a escola</option>
            </select>
        </div>
        <div class="custom-select" id="regiao-subfilter-container" style="display: none;">
            <select id="regiao-subfilter-select">
                <option value="">Selecione a região</option>
            </select>
        </div>
        <div class="custom-select" id="partido-subfilter-container" style="display: none;">
            <select id="partido-subfilter-select">
                <option value="">Selecione o partido</option>
            </select>
        </div>

        <div class="charts-container row-container">
            <div class="chart">
                <div class="pref">
                    <h2>Prefeito - Votação Geral</h2>
                </div>
                <canvas id="piechart-prefeitos-geral" width="400" height="400"></canvas>
                <div class="legend">
                    <p><span class="square" style="background-color: rgba(30,144,255);"></span> Reinadinho</p>
                    <p><span class="square" style="background-color: rgba(245, 69, 242);"></span> Prof. Gleivison</p>
                    <p><span class="square" style="background-color: rgba(255, 136, 0);"></span> Dr Juan Garcia</p>
                    <p><span class="square" style="background-color: rgba(1, 110, 17);"></span> Dr NilL</p>
                    <p><span class="square" style="background-color: rgba(252, 3, 3);"></span> Vinicius PCB</p>
                    <p><span class="square" style="background-color: rgba(143, 62, 0);"></span> Abstenção</p>
                    <p><span class="square" style="background-color: rgba(220, 220, 220);"></span> Votos Brancos</p>
                    <p><span class="square" style="background-color: rgba(128, 128, 128);"></span> Votos Nulos</p>
                </div>

            </div>
            <div class="chart">
                <div class="pref">
                    <h2>Prefeito - Votação Nominal</h2>
                </div>
                <canvas id="piechart-prefeitos" width="400" height="400"></canvas>
                <div class="legend-2">
                    <p><span class="square" style="background-color:rgba(30,144,255);"></span> Reinadinho</p>
                    <p><span class="square" style="background-color: rgba(204,0,255);"></span> Prof. Gleivison</p>
                    <p><span class="square" style="background-color: rgba(255, 136, 0);"></span> Dr Juan Garcia</p>
                    <p><span class="square" style="background-color: rgba(1, 110, 17);"></span> Dr NilL</p>
                    <p><span class="square" style="background-color: rgba(252,3,3);"></span> Vinicius PCB</p>
                </div>
            </div>

            {{-- -------------------------------------------------- inicio do painel ---------------------------------------------- --}}
            <div class="painel">
                <div class="painel-container">
                    <div class="painel-header">
                        <h1 class="painel-header-title">SITUAÇÃO ATUAL DOS VOTOS</h1>
                        <h2 class="last-update-time">Última entrada de
                            dados:{{ $ultimaAtualizacao->format('H:i:s d/m/Y') }}</h2>
                    </div>
                    <div class="painel-body">
                        <div class="group-candidato">
                            <h1 class="group-title">PREFEITO</h1>
                            <div class="votos-container">
                                <div class="voto">
                                    <h1 class="voto-information" id="voto-information-nominal">
                                        <span>Nominal</span>{{ number_format($nominais, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNominais, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information" id="voto-information-branco">
                                        <span>Branco</span>{{ number_format($brancos, 0, '.', '.') }}
                                        ({{ number_format($porcentagemBrancos, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information" id="voto-information-nulo">
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
                                    <h1 class="voto-information" id="voto-information-nominal-vereador">
                                        <span>Nominal</span>{{ number_format($nominaisVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNominaisVereador, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information" id="voto-information-branco-vereador">
                                        <span>Branco</span>{{ number_format($brancosVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemBrancosVereador, 2) }}%)
                                    </h1>
                                </div>
                                <div class="voto">
                                    <h1 class="voto-information" id="voto-information-nulo-vereador">
                                        <span>Nulo</span> {{ number_format($nulosVereador, 0, '.', '.') }}
                                        ({{ number_format($porcentagemNulosVereador, 2) }}%)
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="group-geral">
                            <h1 class="group-title">ELEITORES</h1>
                            <div class="eleitores-container">
                                <div class="eleitores" id="voto-legenda">
                                    <h1 class="eleitor-information">
                                        <span>Votos de legenda</span>
                                        {{ number_format($totalLegc, 0, '.', '.') }}
                                        ({{ number_format($percentLegc, 2) }}%)
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
                                        <span>Seções apuradas</span> {{ number_format($secoesApuradas, 0) }}/210
                                        ({{ number_format($percentSecoesApuradas, 2) }}%)
                                    </h1>
                                </div>
                                <div class="eleitores" id="total-votos-apurados">
                                    <h1 class="eleitor-information">
                                        <span>Votos apurados</span>
                                        {{ number_format($totalApurados, 0, '.', '.') }}/67.081
                                        ({{ number_format($percentApurados, 2) }}%)
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- -------------------------------------------------- fim do painel
            ---------------------------------------------- --}}

            <div class="chart" id="vereadores-chart">
                <div class="verea">
                    <h2>Vereadores (Votação em ordem decrescente)</h2>
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
        <button id="button-download-chart-pdf" class="button-download-pdf" onclick="downloadPDFChart()"
            style="display:none;">Download
            PDF</button>
    </div>
    {{-- -------------------------------------------------- inicio do search
    ---------------------------------------------- --}}
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
            <div id="result-container" class="items-buscar-vereador" style="display:none;">
            </div>
            <button id="button-download-pdf" class="button-download-pdf" onclick="downloadPDF()"
                style="display:none;">Download PDF</button>
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

        function downloadPDFChart() {
            let chartContainer = document.getElementById('vereadores-chart');
            let buttonDownloadPDF = document.getElementById('button-download-chart-pdf');

            // Verificar se o gráfico tem conteúdo
            if (!chartContainer.innerHTML.trim()) {
                alert("Não há gráfico para gerar o PDF.");
                return;
            }

            // Temporariamente mostrar o conteúdo do chartContainer
            chartContainer.style.display = 'block';

            // Forçar renderização antes de gerar o PDF
            window.scrollTo(0, 0);

            // Configurações para html2pdf
            let opt = {
                margin: [0.5, 0.5, 0.5, 0.5], // Margens para o PDF
                filename: 'grafico-vereadores.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2, // Aumentar a escala para melhorar a qualidade
                    useCORS: true,
                    logging: true, // Ativar logging para depuração
                    onclone: (documentClone) => {
                        // Garantir que o conteúdo clonado mantenha os estilos corretos
                        let clonedChartContainer = documentClone.getElementById('vereadores-chart');
                        clonedChartContainer.style.display = 'block';
                        clonedChartContainer.style.backgroundColor = '#000'; // Adicionar cor de fundo

                    }
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy'] // Evitar quebras estranhas no meio de elementos
                }
            };
            // Usar setTimeout para garantir a renderização do conteúdo antes de gerar o PDF
            setTimeout(function() {
                html2pdf().set(opt).from(chartContainer).save();
            }, 300); // Atraso de 300ms para garantir a renderização
        }

        function downloadPDF() {
            let resultContainer = document.getElementById('result-container');
            let buttonDownloadPDF = document.getElementById('button-download-pdf');

            // Verificar se o resultContainer tem conteúdo
            if (!resultContainer.innerHTML.trim()) {
                alert("Não há conteúdo para gerar o PDF.");
                return;
            }

            // Temporariamente mostrar o conteúdo do resultContainer
            resultContainer.style.display = 'block';

            // Forçar renderização antes de gerar o PDF
            window.scrollTo(0, 0);

            // Função para gerar sufixo aleatório
            function generateRandomSuffix(length) {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return result;
            }

            // Definir o nome do arquivo com prefixo e sufixo aleatório
            const prefix = 'votos-de-candidato-';
            const suffix = generateRandomSuffix(8); // 8 caracteres aleatórios
            const filename = `${prefix}${suffix}.pdf`;

            // Configurações para html2pdf
            let opt = {
                margin: [0.5, 0.5, 0.5, 0.5], // Diminuir a margem para aproveitar mais espaço na página
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2, // Aumentar a escala para melhorar a qualidade
                    useCORS: true,
                    logging: true, // Ativar logging para depuração
                    onclone: (documentClone) => {
                        // Garantir que o conteúdo clonado mantenha os estilos corretos
                        let clonedResultContainer = documentClone.getElementById('result-container');
                        clonedResultContainer.style.display = 'block';

                        // Definir largura fixa para manter o layout de desktop
                        clonedResultContainer.style.width = '800px'; // Defina a largura apropriada ao seu layout
                    }
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy'] // Evitar quebras estranhas no meio de elementos
                }
            };

            // Usar setTimeout para garantir a renderização do conteúdo antes de gerar o PDF
            setTimeout(function() {
                html2pdf().set(opt).from(resultContainer).save().then(() => {
                    // Após gerar o PDF, você pode ocultar o conteúdo novamente, se necessário
                    resultContainer.style.display = 'none';
                    buttonDownloadPDF.style.display = 'none'; // Exibe o botão de download do PDF
                });
            }, 500); // Atraso de 500ms para garantir a renderização
        }


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
            let buttonDownloadPDF = document.querySelector('button'); // Botão de download do PDF

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
            let buttonDownloadPDF = document.getElementById('button-download-pdf');

            fetch(`/buscar-vereador?search=${vereadorId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.vereador) {
                        // Ordena as seções pelo campo 'votos_na_secao' em ordem decrescente
                        let secoesOrdenadas = data.secoes.sort((a, b) => b.votos_na_secao - a.votos_na_secao);

                        // Gera a lista de seções onde o vereador recebeu votos
                        let secoesList = secoesOrdenadas.map(secao => `
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
                        buttonDownloadPDF.style.display = 'block'; // Exibe o botão de download do PDF
                    } else {
                        resultContainer.innerHTML = `<div>${data.error}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar detalhes do vereador:', error);
                });
        }

        function atualizarPainel() {
            fetch('/atualizar-dados')
                .then(response => response.json()) // Converte a resposta para JSON
                .then(data => {
                    // Função para formatar números com casas decimais e separadores
                    function formatNumber(value, decimalPlaces = 0) {
                        return Number(value).toFixed(decimalPlaces).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }

                    // Atualizar o tempo da última atualização
                    let updateTimeElement = document.querySelector('.last-update-time');
                    if (updateTimeElement) {
                        updateTimeElement.textContent = 'Última entrada de dados: ' + data.ultimaAtualizacao;
                    }

                    // Atualizar dados de Prefeito
                    let nominalElement = document.querySelector('#voto-information-nominal span');
                    if (nominalElement) {
                        nominalElement.nextSibling.textContent = formatNumber(data.nominais, 0) + ' (' + formatNumber(
                            data.porcentagemNominais, 2) + '%)';
                    }

                    let brancoElement = document.querySelector('#voto-information-branco span');
                    if (brancoElement) {
                        brancoElement.nextSibling.textContent = formatNumber(data.brancos, 0) + ' (' + formatNumber(data
                            .porcentagemBrancos, 2) + '%)';
                    }

                    let nuloElement = document.querySelector('#voto-information-nulo span');
                    if (nuloElement) {
                        nuloElement.nextSibling.textContent = formatNumber(data.nulos, 0) + ' (' + formatNumber(data
                            .porcentagemNulos, 2) + '%)';
                    }

                    // Atualizar dados de Vereadores
                    let nominalVereadorElement = document.querySelector('#voto-information-nominal-vereador span');
                    if (nominalVereadorElement) {
                        nominalVereadorElement.nextSibling.textContent = formatNumber(data.nominaisVereador, 0) + ' (' +
                            formatNumber(data.porcentagemNominaisVereador, 2) + '%)';
                    }

                    let brancoVereadorElement = document.querySelector('#voto-information-branco-vereador span');
                    if (brancoVereadorElement) {
                        brancoVereadorElement.nextSibling.textContent = formatNumber(data.brancosVereador, 0) + ' (' +
                            formatNumber(data.porcentagemBrancosVereador, 2) + '%)';
                    }

                    let nuloVereadorElement = document.querySelector('#voto-information-nulo-vereador span');
                    if (nuloVereadorElement) {
                        nuloVereadorElement.nextSibling.textContent = formatNumber(data.nulosVereador, 0) + ' (' +
                            formatNumber(data.porcentagemNulosVereador, 2) + '%)';
                    }

                    // Atualizar dados gerais
                    let votoLegendaElement = document.querySelector('#voto-legenda span');
                    if (votoLegendaElement) {
                        votoLegendaElement.nextSibling.textContent = formatNumber(data.totalLegc, 0) + ' (' +
                            formatNumber(data.percentLegc, 2) + '%)';
                    }

                    let eleitorFaltanteElement = document.querySelector('#eleitor-faltante span');
                    if (eleitorFaltanteElement) {
                        eleitorFaltanteElement.nextSibling.textContent = formatNumber(data.totalFaltantes, 0) + ' (' +
                            formatNumber(data.percentFaltantes, 2) + '%)';
                    }

                    let naoApuradoElement = document.querySelector('#nao-apurado span');
                    if (naoApuradoElement) {
                        naoApuradoElement.nextSibling.textContent = formatNumber(data.restanteApurar, 0) + ' (' +
                            formatNumber(data.percentRestante, 2) + '%)';
                    }

                    let secoesApuradasElement = document.querySelector('#secoes-apuradas span');
                    if (secoesApuradasElement) {
                        secoesApuradasElement.nextSibling.textContent = formatNumber(data.secoesApuradas, 0) +
                            '/210 (' + formatNumber(data.percentSecoesApuradas, 2) + '%)';
                    }

                    let totalVotosApuradosElement = document.querySelector('#total-votos-apurados span');
                    if (totalVotosApuradosElement) {
                        totalVotosApuradosElement.nextSibling.textContent = formatNumber(data.totalApurados, 0) +
                            '/67.081 (' + formatNumber(data.percentApurados, 2) + '%)';
                    }
                })
                .catch(error => console.error('Erro ao atualizar o painel:', error));
        }
        // Limpa qualquer intervalo anterior se já houver um ativo
        let painelRefreshInterval;
        if (painelRefreshInterval) {
            clearInterval(painelRefreshInterval);
        }

        // Define o intervalo para atualizar o painel de dados a cada 2 minutos (120000 ms)
        painelRefreshInterval = setInterval(atualizarPainel, 60000);

        // Chama a função atualizarPainel imediatamente ao carregar a página
        atualizarPainel();
    </script>
    {{-- -------------------------------------------------- fim do search ----------------------------------------------
    --}}
    <footer>
        <h1 class="footer-title">Vai ser ainda melhor.</h1>
    </footer>
</body>

</html>
