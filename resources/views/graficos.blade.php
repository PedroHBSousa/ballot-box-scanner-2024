<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico da Apuração</title>
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        body {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 200px;
        }

        .chart{
            padding: 2rem;
            border: 1px solid #000;
            border-radius: 1rem;
            background: #000;
            box-shadow: 0 0 16px rgba(0,0,0,0.);
        }
    </style>
    <div class="container">

        <div class="chart">
            <canvas id="barchart" width="500" height="500"></canvas>
        </div>

        <div class="chart">
            <canvas id="doughnut" width="500" height="500"></canvas>
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
            type: 'barchart',
            data: {
                labels: ['Reinaldinho','Professor Gleivison', 'Dr. Juan'],
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