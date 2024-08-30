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
                <option value="prefeitos">Prefeito</option>
                <option value="vereadores">Vereadores</option>
                <option value="escolas">Escolas</option>
                <option value="bairros">Bairros</option>
                <option value="regioes">Regiões</option>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            // Gráfico de pizza para prefeitos
            const ctxPrefeitos = document.getElementById('piechart-prefeitos');
            let chartInstancePrefeitos = new Chart(ctxPrefeitos, {
                type: 'pie',
                data: {
                    labels: [], // Labels dinâmicos
                    datasets: [{
                        data: [], // Dados dinâmicos
                        backgroundColor: [
                            'rgba(7, 217, 0)',
                            'rgba(255,0,0)',
                            'rgba(252, 186, 3)',
                            'rgba(30,144,255)',
                            'rgba(255,69,0)'
                        ],
                        borderColor: [
                            '#FFF',
                            '#FFF',
                            '#FFF',
                            '#FFF',
                            '#FFF',
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true // Exibe a legenda para o gráfico de pizza
                        }
                    }
                }
            });

            // Gráfico de barras para vereadores
            const ctxVereadores = document.getElementById('barchart-vereadores');
            let chartInstanceVereadores = new Chart(ctxVereadores, {
                type: 'bar',
                data: {
                    labels: [], // Labels dinâmicos
                    datasets: [{
                        data: [], // Dados dinâmicos
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

        document.getElementById('filter-select').addEventListener('change', function () {
            const selectedFilter = this.value;

                if (selectedFilter) {
                    axios.get(`/data/${selectedFilter}`)
                        .then(response => {
                            const data = response.data;
                            updateChart(selectedFilter, data);
                        })
                        .catch(error => {
                            console.error('Erro ao buscar dados do gráfico:', error);
                        });
                }
            });

        function updateChart(filter, data) {
            if (filter === 'prefeitos') {
                updateChartInstance(chartInstancePrefeitos, data);
            } else if (filter === 'vereadores') {
                updateChartInstance(chartInstanceVereadores, data);
            }
            // Adicionar lógica para outros filtros se necessário
        }

        function updateChartInstance(chartInstance, data) {
            if (data.length === 0) {
                console.log('Nenhum dado encontrado para este filtro.');
                chartInstance.data.labels = [];
                chartInstance.data.datasets[0].data = [];
            } else {
                chartInstance.data.labels = data.map(item => item.nome);
                chartInstance.data.datasets[0].data = data.map(item => item.total);
            }
            chartInstance.update();
        }
    </script>
</body>
<footer>
    <h1>Juntos é possível!</h1>

</footer>

</html>
