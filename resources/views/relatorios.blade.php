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
        @foreach($localidades as $localidadeId => $dados)
            <h2 class="title">{{ $dados['nome'] }} ({{ $dados['regiao'] }})</h2>

            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Seção</th>
                        <th>Reinaldinho</th>
                        <th>Gleivison</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dados['secoes'] as $secao)
                        @php
                            // Pegando o primeiro boletim, se existir
                            $boletim = $secao->boletins->first();
                            $totalVotosSecao = $boletim ? $boletim->comp : 0;

                            // Votos dos candidatos na seção
                            $votosReinaldinhoSecao = $secao->votos->where('candidato_id', 10)->count();
                            $votosGleivisonSecao = $secao->votos->where('candidato_id', 11)->count();

                            // Cálculo dos percentuais na seção
                            $percentualReinaldinhoSecao = $totalVotosSecao > 0 ? ($votosReinaldinhoSecao / $totalVotosSecao) * 100 : 0;
                            $percentualGleivisonSecao = $totalVotosSecao > 0 ? ($votosGleivisonSecao / $totalVotosSecao) * 100 : 0;
                        @endphp
                        <tr>
                            <td>Seção {{ $secao->id }}</td>
                            <td>
                                {{ $votosReinaldinhoSecao }} <span class="percentagem">({{ number_format($percentualReinaldinhoSecao, 2) }}%)</span>
                            </td>
                            <td>
                                {{ $votosGleivisonSecao }} <span class="percentagem">({{ number_format($percentualGleivisonSecao, 2) }}%)</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tabela dos totais por localidade -->
            <table border="1" cellpadding="10" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Informação</th>
                        <th>Total</th>
                        <th>Percentual</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalVotosLocalidade = $dados['totalVotos'];
                        $votosReinaldinhoLocalidade = $dados['votosReinaldinho'];
                        $votosGleivisonLocalidade = $dados['votosGleivison'];
                        $totalEleitores = $dados['secoes']->first()->localidade->eleitores; // Total de eleitores

                        // Cálculo dos percentuais por localidade
                        $percentualReinaldinhoLocalidade = $totalVotosLocalidade > 0 ? ($votosReinaldinhoLocalidade / $totalVotosLocalidade) * 100 : 0;
                        $percentualGleivisonLocalidade = $totalVotosLocalidade > 0 ? ($votosGleivisonLocalidade / $totalVotosLocalidade) * 100 : 0;
                    @endphp
                    <tr>
                        <td>Reinaldinho</td>
                        <td>{{ $votosReinaldinhoLocalidade }}</td>
                        <td><span class="percentagem"> {{ number_format($percentualReinaldinhoLocalidade, 2) }}%</span></td>
                    </tr>
                    <tr>
                        <td>Gleivison</td>
                        <td>{{ $votosGleivisonLocalidade }}</td>
                        <td><span class="percentagem">{{ number_format($percentualGleivisonLocalidade, 2) }}%</span></td>
                    </tr>
                    <tr>
                        <td>Total de Aptos</td>
                        <td>{{ $totalEleitores }}</td>
                        <td><span class="percentagem">100%</span></td>
                    </tr>
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
