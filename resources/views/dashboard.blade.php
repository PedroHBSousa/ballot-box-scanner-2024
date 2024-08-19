<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico da Apuração</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')

</head>
<header>
    <h1>APURAÇÃO ELEITORAL</h1>
    <div class="container-image">
        <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
        <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
        <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
    </div>
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
                    data: [2504, 500, 412],
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
