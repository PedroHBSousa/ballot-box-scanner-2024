<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatórios</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/relatorios.css')
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

    {{-- <h1 class="body-title">Resultados de Votos por Localidade</h1> --}}

    <div class="button-container">
        <button class="no-print"  onclick="window.print()">Imprimir Tabela</button>
    </div>
      <!-- Botão de imprimir -->


      <div class="table-container">
        @foreach($resultados as $localidadeId => $secoes)
            <h2 class="title">{{ $secoes->first()->localidade->nome }} ({{ $secoes->first()->localidade->regiao }})</h2>

            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Seção</th>
                        <th>Reinaldinho</th>
                        <th>Gleivison</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($secoes as $secao)
                        @php
                            // Pegando o primeiro boletim, se existir
                            $boletim = $secao->boletins->first(); // Assume que cada secao tem pelo menos um boletim
                            $totalVotos = $boletim ? $boletim->comp : 0; // Verifica se o boletim existe

                            // Votos dos candidatos
                            $votosReinaldinho = $secao->votos->where('candidato_id', 10)->count();
                            $votosGleivison = $secao->votos->where('candidato_id', 11)->count();

                            // Cálculo dos percentuais
                            $percentualReinaldinho = $totalVotos > 0 ? ($votosReinaldinho / $totalVotos) * 100 : 0;
                            $percentualGleivison = $totalVotos > 0 ? ($votosGleivison / $totalVotos) * 100 : 0;
                        @endphp
                        <tr>
                            <td>Seção {{ $secao->id }}</td>
                            <td>
                                {{ $votosReinaldinho }} <span class="percentagem">({{ number_format($percentualReinaldinho, 2) }}%)</span>
                            </td>
                            <td>
                                {{ $votosGleivison }} <span class="percentagem">({{ number_format($percentualGleivison, 2) }}%)</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        @endforeach
    </div>

    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>
</html>
