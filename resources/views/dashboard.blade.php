<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico da Apuração</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite('resources/css/app.css')
    
</head>
<header>
    <img src="{{ Vite::asset('resources/img/Reinaldinho.png') }}" >
    <h1>Apuração Eleitoral</h1>
    <img src="{{ Vite::asset('resources/img/Reis.png') }}">
    
</header>

<body>
    <div class="container">
    

        <div class="custom-select">
            <select>
                <option value="">Selecione o filtro</option>
                <option value="">Prefeitos</option>
                <option value="">Vereadores</option>
                <option value="">Partidos</option>
                <option value="">Bairros</option>
                <option value="">Regiões</option>
            </select>
        </div>
        <div class="charts-container">
            <div class="chart">
                <canvas id="barchart" width="400" height="400"></canvas>
            </div>

            <div class="chart">
                <canvas id="doughnut" width="400" height="400"></canvas>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('doughnut');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Reinaldinho', 'Professor Gleivison', 'Dr. Juan'],
                datasets: [{
                    label: 'Total de Votos',
                    data: [20, 3, 8],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctx2 = document.getElementById('barchart');

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Reinaldinho', 'Professor Gleivison', 'Dr. Juan'],
                datasets: [{
                    label: 'Total de Votos',
                    data: [1, 3, 8],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>