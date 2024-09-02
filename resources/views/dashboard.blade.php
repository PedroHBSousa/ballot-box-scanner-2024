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
    @vite('resources/css/app.css')

</head>

<body>
    <header>
        <h1>APURAÇÃO ELEITORAL</h1>
        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>

    <div class="container">
        <div class="custom-select">
            <select id="filter-select">
                <option value="">Filtro</option>
                <option value="prefeitos" hidden>Prefeito</option>
                <option value="vereadores">Vereadores</option>
                <option value="escolas">Escolas</option>
                <option value="bairros">Bairros</option>
                <option value="regioes">Regiões</option>
            </select>
        </div>
        <div id="subfilter-container" style="display: none;">
            <select id="subfilter-select">
                <option value="">Selecione um bairro</option>
            </select>
            <select id="school-filter-select" style="display: none;">
                <option value="">Selecione uma escola</option>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Função para atualizar o gráfico fornecido com os dados recebidos.
        function updateChartInstance(chartInstance, data) {
            if (!Array.isArray(data) || data.length === 0) {
                console.log('Nenhum dado encontrado para este filtro.');
                chartInstance.data.labels = [];
                chartInstance.data.datasets[0].data = [];
            } else {
                chartInstance.data.labels = data.map(item => item.nome);
                chartInstance.data.datasets[0].data = data.map(item => item.total || 0);
            }
            chartInstance.update();
        }

        // Função para atualizar o gráfico com base no filtro selecionado
        function updateChart(filter, data) {
            // Função auxiliar para mostrar ou esconder gráficos
            function toggleChartVisibility(chartId, shouldShow) {
                const chartElement = document.getElementById(chartId);
                if (chartElement && chartElement.parentElement) {
                    chartElement.parentElement.style.display = shouldShow ? 'block' : 'none';
                } else {
                    console.error(`Elemento com ID ${chartId} não encontrado.`);
                }
            }

            if (filter === 'prefeitos') {
                updateChartInstance(chartInstancePrefeitos, data);
                // Esconde os outros gráficos
                toggleChartVisibility('barchart-vereadores', false);
                toggleChartVisibility('barchart-bairros', false);
                toggleChartVisibility('barchart-escolas', false);
            } else if (filter === 'vereadores') {
                updateChartInstance(chartInstanceVereadores, data);
                toggleChartVisibility('barchart-vereadores', true);
                toggleChartVisibility('barchart-bairros', false);
                toggleChartVisibility('barchart-escolas', false);
            } else if (filter === 'bairros') {
                updateChartInstance(chartInstanceBairros, data);
                toggleChartVisibility('barchart-vereadores', false);
                toggleChartVisibility('barchart-bairros', true);
                toggleChartVisibility('barchart-escolas', false);
            } else if (filter === 'escolas') {
                updateChartInstance(chartInstanceEscolas, data);
                toggleChartVisibility('barchart-vereadores', false);
                toggleChartVisibility('barchart-bairros', false);
                toggleChartVisibility('barchart-escolas', true);
            } else {
                console.error('Filtro desconhecido:', filter);
            }
        }

        // Carregar o gráfico de prefeitos automaticamente ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {

            axios.get('/data/prefeitos')
                .then(response => {
                    const data = response.data;
                    updateChartInstance(chartInstancePrefeitos, data);
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do gráfico de prefeitos:', error);
                });

            // Esconder o gráfico de bairros inicialmente
            document.getElementById('barchart-bairros').parentElement.style.display = 'none';
            document.getElementById('barchart-escolas').parentElement.style.display = 'none';
        });

        document.getElementById('filter-select').addEventListener('change', function() {
            const selectedFilter = this.value;

            if (selectedFilter) {
                axios.get(`/data/${selectedFilter}`)
                    .then(response => {
                        console.log('Dados recebidos:', response.data);
                        updateChart(selectedFilter, response.data);

                        if (selectedFilter === 'bairros') {
                            axios.get('/get-bairros')
                                .then(response => {
                                    const subfilterSelect = document.getElementById('subfilter-select');
                                    subfilterSelect.innerHTML = '<option value="">Selecione um bairro</option>';

                                    response.data.forEach(bairro => {
                                        const option = document.createElement('option');
                                        option.value = bairro.id;
                                        option.textContent = bairro.nome;
                                        subfilterSelect.appendChild(option);
                                    });

                                    document.getElementById('subfilter-container').style.display = 'block';

                                    subfilterSelect.addEventListener('change', function() {
                                        const selectedBairroId = subfilterSelect.value;

                                        if (selectedBairroId) {
                                            axios.get(`/data/bairros/${selectedBairroId}`)
                                                .then(response => {
                                                    updateChartInstance(chartInstanceBairros, response.data);
                                                })
                                                .catch(error => {
                                                    console.error('Erro ao buscar votos para o bairro:', error);
                                                });
                                        }
                                    });
                                })
                                .catch(error => {
                                    console.error('Erro ao buscar bairros:', error);
                                });
                        } else {
                            document.getElementById('subfilter-container').style.display = 'none';
                        }

                        if (selectedFilter === 'escolas') {
                            axios.get('/get-localidades')
                                .then(response => {
                                    const schoolSelect = document.getElementById('school-filter-select');
                                    schoolSelect.innerHTML = '<option>Selecione uma escola</option>';

                                    response.data.forEach(localidade => {
                                        const option = document.createElement('option');
                                        option.value = localidade.id;
                                        option.textContent = localidade.nome;
                                        schoolSelect.appendChild(option);
                                    });

                                    document.getElementById('school-filter-container').style.display = 'block';
                                    schoolSelect.style.display = 'block';

                                    schoolSelect.addEventListener('change', function() {
                                        const selectedSchoolId = schoolSelect.value; // Captura o localidade_id do dropdown

                                        if (selectedSchoolId) {
                                            axios.get(`/data/escolas/${selectedSchoolId}`) // Envia o ID como parte da URL
                                                .then(response => {
                                                    // Atualiza o gráfico com os votos das escolas
                                                    updateChartInstance(chartInstanceEscolas, response.data);
                                                })
                                                .catch(error => {
                                                    console.error('Erro ao buscar votos para a escola:', error);
                                                });
                                        } else {
                                            console.error('Nenhuma escola selecionada.');
                                        }
                                    });
                                })
                                .catch(error => {
                                    console.error('Erro ao carregar localidades:', error);
                                });
                        } else {
                            document.getElementById('school-filter-container').style.display = 'none';
                        }
                    })
            }
        });

        // Configurações dos gráficos
        const ctxPrefeitos = document.getElementById('piechart-prefeitos');
        const chartInstancePrefeitos = new Chart(ctxPrefeitos, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        'rgba(7, 217, 0)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(30,144,255)',
                        'rgba(255,69,0)'
                    ],
                    borderColor: ['#FFF', '#FFF', '#FFF', '#FFF', '#FFF'],
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        const ctxVereadores = document.getElementById('barchart-vereadores');
        const chartInstanceVereadores = new Chart(ctxVereadores, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(30,144,255)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(7, 217, 0)',
                        'rgba(255,69,0)'
                    ],
                    borderColor: [
                        'rgba(30,144,255)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(7, 217, 0)',
                        'rgba(255,69,0)'
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0
                    }
                }
            }
        });

        const ctxBairros = document.getElementById('barchart-bairros');
        const chartInstanceBairros = new Chart(ctxBairros, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(7, 217, 0)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(30,144,255)',
                        'rgba(255,69,0)'
                    ],
                    borderColor: [
                        'rgba(7, 217, 0)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(30,144,255)',
                        'rgba(255,69,0)'
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0
                    }
                }
            }
        });

        const ctxEscolas = document.getElementById('barchart-escolas');
        const chartInstanceEscolas = new Chart(ctxEscolas, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(7, 217, 0)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(30,144,255)',
                        'rgba(255,69,0)'
                    ],
                    borderColor: [
                        'rgba(7, 217, 0)',
                        'rgba(255,0,0)',
                        'rgba(252, 186, 3)',
                        'rgba(30,144,255)',
                        'rgba(255,69,0)'
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0
                    }
                }
            }
        });

        window.onload = function() {
            document.getElementById('barchart-vereadores').parentElement.style.display = 'block';
            document.getElementById('barchart-bairros').parentElement.style.display = 'none';
            document.getElementById('barchart-escolas').parentElement.style.display = 'none';
        };
    </script>

</body>
<footer>
    <h1>Juntos é possível!</h1>

</footer>

</html>