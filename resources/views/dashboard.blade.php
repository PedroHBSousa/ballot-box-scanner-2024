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
                <option value="prefeitos">Prefeitos</option>
                <option value="vereadores">Vereadores</option>
                <option value="partidos">Partidos</option>
                <option value="bairros">Bairros</option>
                <option value="regioes">Regiões</option>
            </select>
        </div>
        <div class="charts-container">
            <div class="chart">
                <canvas id="barchart" width="400" height="400"></canvas>
            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    
<script>    

        const ctx2 = document.getElementById('barchart');

        new Chart(ctx2, {
            type: 'bar',
                data: {
                    labels: ['Reinaldinho', 'Prof. Gleivison', 'Dr. Juan', 'Vinícius', 'Dr.Nil'],
                    datasets: [{
                        data: [2504, 1550, 1812, 522, 1],
                        borderWidth: 1,
                        backgroundColor: [
                            'rgba(30,144,255)', // Cor para Reinaldinho
                            'rgba(0,100,0)', // Cor para Professor Gleivison
                            'rgba(255,0,0)',    // Cor para Dr. Juan
                            'rgba(128,0,128)',  // Cor para Vinícius
                            'rgba(255,69,0)'  // Cor para Dr. Nil
                        ],
                        borderColor: [
                            'rgba(30,144,255)', 
                            'rgba(0,100,0)',
                            'rgba(255,0,0)',
                            'rgba(128,0,128)',
                            'rgba(255,69,0)'
                        ]
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Desativa a legenda
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMin: 0   // Sugere que o valor mínimo no eixo Y seja 0
                        }
                    }
                }
            });
</script>
</body>

</html>
