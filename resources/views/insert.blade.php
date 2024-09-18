<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inserir</title>
    <link rel="icon" href="{{ asset('inserticon.svg') }}" type="image/x-icon" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite('resources/css/insert.css')
</head>

<body>
    <header>
        <h1>SÃO SEBASTIÃO</h1>
        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>
    <div class="sections-panel-container">
        <div class="sections-panel">
            <button class="sections-panel-button">Seções restantes <span class="material-symbols-outlined">
                    arrow_drop_down
                </span></button>
            <div class="sections-info" style="display: none;">
                <ul id="secoesRestantes"></ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <form class="form" method="GET" action="{{ route('getSecao') }}">
                @csrf
                <div class="form-group">
                    <label for="search">Buscar seção</label>
                    <input type="number" id="search" name="search" placeholder="Digite o número da seção">
                    <button class="form-submit-btn" type="submit" name="action" value="buscar_secao">Buscar</button>
                </div>
            </form>

            @isset($secao)
                <div style="display:flex; flex-direction:column; justify-content:center; align-items: center">
                    <h2 style="text-align:center; font-size:21px;">Localidade da Seção {{ $secao->id }}:</h2>
                    <h3 style="text-align:center;font-size:16px;">{{ $secao->localidade->nome }}</h3> <br>
                </div>
                <form class="form" action="{{ route('insert.data') }}" method="POST">
                    @csrf
                    <input type="hidden" name="secao_id" value="{{ $secao->id }}">
                    <div class="form-group-container">
                        <div class="form-group">
                            <label class="eleitores-title">Aptos</label>
                            <h1 class="qtd-eleitores">{{ $secao->aptos }}</h1>
                            <input type="hidden" name="apto" id="apto" placeholder="Número de pessoas da seção"
                                value="{{ $secao->aptos }}" required>
                        </div>
                        <div class="form-group">
                            <label class="eleitores-title">Eleitores que compareceram</label>
                            <h1 class="qtd-eleitores" id="comp-display">0</h1>
                            <input type="hidden" name="comp" id="comp" placeholder="Número de pessoas que votaram"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="eleitores-title">Eleitores que faltaram</label>
                            <h1 class="qtd-eleitores" id="falt-display">0</h1>
                            <input type="hidden" name="falt" id="falt" placeholder="Número de pessoas que faltaram"
                                required>
                        </div>
                    </div>

                    <h2>Digite os votos para prefeito</h2>
                    <div class="form-group-container">
                        @foreach ($candidatos as $candidato)
                            <div class="form-group">
                                <label class="names" for="candidato_{{ $candidato->id }}">{{ $candidato->nome }}</label>
                                <input type="number" name="votos[{{ $candidato->id }}][quantidade]"
                                    id="candidato_{{ $candidato->id }}_quantidade" min="0" placeholder="Votos"
                                    required>
                                <input type="hidden" name="votos[{{ $candidato->id }}][candidato_id]"
                                    value="{{ $candidato->id }}">
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group-container">
                        <!-- Campos para votos em branco e nulos -->
                        <div class="form-group">
                            <label for="votos_branco_prefeito">Total de votos branco</label>
                            <input type="number" id="votos_branco_prefeito" name="votos_branco_prefeito" min="0"
                                placeholder="Votos branco para prefeito" required>
                        </div>
                        <div class="form-group">
                            <label for="votos_nulo_prefeito">Total de votos nulo</label>
                            <input type="number" id="votos_nulo_prefeito" name="votos_nulo_prefeito" min="0"
                                placeholder="Votos nulos para prefeito" required>
                        </div>
                    </div>
                    <!-- Seção para buscar e adicionar candidato para vereador -->
                    <h2>Adicionar votos para vereador</h2>
                    <div class="form-group" id="vereadors">
                        <div>
                            <label for="vereador_search">Buscar vereador</label>
                            <input type="number" id="vereador_search" name="vereador_search"
                                placeholder="Digite o número do vereador">
                            <button class="form-submit-btn" type="button" onclick="buscarVereador()">Adicionar</button>
                        </div>
                        <!-- Mensagem de erro, se existir -->
                        <div id="erro_vereador" style="color: red;"></div>
                        <!-- Lista de candidatos adicionados -->
                        <div id="vereador_resultado"></div>
                    </div>

                    <div class="form-group-container">
                        <!-- Campos para votos em branco e nulos -->
                        <div class="form-group">
                            <label for="votos_branco_vereador">Total de votos branco:</label>
                            <input type="number" id="votos_branco_vereador" name="votos_branco_vereador" min="0"
                                placeholder="Votos branco para vereador">
                        </div>
                        <div class="form-group">
                            <label for="votos_nulo_vereador">Total de votos nulo:</label>
                            <input type="number" id="votos_nulo_vereador" name="votos_nulo_vereador" min="0"
                                placeholder="Votos nulos para vereador">
                        </div>
                    </div>
                    <button class="form-submit-btn" type="submit" name="action" value="inserir_votos">Enviar</button>
                </form>
            @endisset
            {{-- @else
                <p>Nenhuma seção encontrada.</p>
            @endif --}}
            @if (session('success'))
                <span style="color: #082;">
                    {{ session('success') }}
                </span>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <span style="color: rgb(136, 0, 0);">
                        {{ $error }}
                    </span>
                @endforeach
            @endif
        </div>

    </div>
    <script>
        function buscarVereador() {
            const vereadorId = document.getElementById('vereador_search').value;

            fetch(`/enter-manually/${vereadorId}`)
                .then(response => response.json())
                .then(data => {
                    const vereadorResultado = document.getElementById('vereador_resultado');
                    const erroVereador = document.getElementById('erro_vereador');

                    erroVereador.innerHTML = '';

                    // Verifica se o candidato já foi adicionado
                    if (document.querySelector(`input[name='votos[${vereadorId}][candidato_id]']`)) {
                        erroVereador.innerHTML = '<p>Este candidato já foi adicionado!</p>';
                        return; // Impede a adição do candidato duplicado
                    }
                    if (data.success) {
                        const candidato = data.candidato;
                        const novoVereador = document.createElement('div');
                        novoVereador.classList.add('form-group-vereador');
                        novoVereador.innerHTML = `
                        <div style="width:100%">
                            <label style="text-transform: capitalize;">${candidato.id} - ${candidato.nome}</label>
                            <input type="number" name="votos[${candidato.id}][quantidade]" min="0" placeholder="Votos" required>
                            <input type="hidden" name="votos[${candidato.id}][candidato_id]" value="${candidato.id}">
                        </div>
                            <button class="button-delete" onclick="removerVereador(this)">X</button>
                `;
                        // Adiciona o novo vereador no início
                        vereadorResultado.insertAdjacentElement('afterbegin', novoVereador);
                    } else {
                        erroVereador.innerHTML = '<p>Candidato não encontrado!</p>';
                    }
                })
                .catch(error => console.error('Erro ao buscar candidato:', error));
        }

        function removerVereador(button) {
            // Remove o campo do vereador ao clicar no botão "X"
            button.parentElement.remove();
        }

        // Seleciona os campos de aptos, comparecimento e faltantes
        const aptoInput = document.getElementById('apto');
        const compInput = document.getElementById('comp');
        const faltInput = document.getElementById('falt');
        const compDisplay = document.getElementById('comp-display');
        const faltDisplay = document.getElementById('falt-display');

        function atualizarDisplayComparecimento() {
            compDisplay.textContent = compInput.value || 0; // Mostra o valor no h1
            faltDisplay.textContent = faltInput.value || 0; // Mostra o valor no h1
        }

        // Seleciona os campos de votos nulos, brancos e todos os candidatos
        const votosBrancoPrefeitoInput = document.getElementById('votos_branco_prefeito');
        const votosNuloPrefeitoInput = document.getElementById('votos_nulo_prefeito');
        const candidatosInputs = document.querySelectorAll('input[id^="candidato_"][id$="_quantidade"]');

        // Função para calcular o total de votos
        function calcularTotalVotos() {
            let totalVotos = 0;

            // Soma os votos dos candidatos
            candidatosInputs.forEach(input => {
                totalVotos += parseInt(input.value) || 0;
            });

            // Adiciona os votos nulos e brancos
            totalVotos += parseInt(votosBrancoPrefeitoInput.value) || 0;
            totalVotos += parseInt(votosNuloPrefeitoInput.value) || 0;

            // Preenche o campo de comparecimento com o total
            compInput.value = totalVotos;

            // Atualiza o cálculo de faltantes após preencher o campo de comparecimento
            calcularFaltantes();
            atualizarDisplayComparecimento();
        }

        // Adiciona eventos para calcular quando os valores mudarem
        if (compInput && aptoInput && faltInput) {
            compInput.addEventListener('input', calcularFaltantes);
        }

        if (votosBrancoPrefeitoInput && votosNuloPrefeitoInput && candidatosInputs.length > 0) {
            votosBrancoPrefeitoInput.addEventListener('input', calcularTotalVotos);
            votosNuloPrefeitoInput.addEventListener('input', calcularTotalVotos);

            candidatosInputs.forEach(input => {
                input.addEventListener('input', calcularTotalVotos);
            });
        }

        // Função para calcular e preencher automaticamente o campo de faltantes
        function calcularFaltantes() {
            const aptos = parseInt(aptoInput.value) || 0; // Obtém o valor de aptos
            const compareceram = parseInt(compInput.value) || 0; // Obtém o valor de pessoas que compareceram
            const faltantes = aptos - compareceram; // Calcula os faltantes

            faltInput.value = faltantes >= 0 ? faltantes : 0; // Preenche o campo de faltantes
        }

        // Adiciona um evento para calcular os faltantes quando o valor do campo de comparecimento mudar
        if (compInput && aptoInput && faltInput) {
            compInput.addEventListener('input', calcularFaltantes);
        }

        document.querySelector('.sections-panel-button').addEventListener('click', function() {
            const sectionsInfo = document.querySelector('.sections-info');

            // Toggle display
            if (sectionsInfo.style.display === 'none') {
                // Mostrar e buscar dados via AJAX
                fetch('/secoesrestantes')
                    .then(response => response.json())
                    .then(data => {
                        let secaoList = '';
                        data.forEach(secao => {
                            secaoList +=
                                `<li>Seção: ${secao.id} - ${secao.localidade.nome}</li>`;
                        });
                        document.getElementById('secoesRestantes').innerHTML = secaoList;
                    });
                sectionsInfo.style.display = 'block';
            } else {
                // Esconder
                sectionsInfo.style.display = 'none';
            }
        });
    </script>
</body>
<footer>
    <h1>Juntos é possível!</h1>
</footer>

</html>
