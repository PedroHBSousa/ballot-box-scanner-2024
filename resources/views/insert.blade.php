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
                <div id="infoSecoes"></div>
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
                <h2 class="form-header">Local da seção {{ $secao->id }} <span>{{ $secao->localidade->nome }}</span>
                </h2>
                <form class="form" action="{{ route('insert.data') }}" method="POST" id="voting-form">
                    @csrf
                    <input type="hidden" name="secao_id" value="{{ $secao->id }}">
                    <div class="form-group-container">
                        <div class="form-group-eleitor">
                            <label class="eleitores-title">Aptos</label>
                            <h1 class="qtd-eleitores">{{ $secao->aptos }}</h1>
                            <input type="hidden" name="apto" id="apto" placeholder="Número de pessoas da seção"
                                value="{{ $secao->aptos }}" required>
                        </div>
                        <div class="form-group-eleitor">
                            <label class="eleitores-title">Eleitores que compareceram</label>
                            <h1 class="qtd-eleitores" id="comp-display">0</h1>
                            <input type="hidden" name="comp" id="comp" placeholder="Número de pessoas que votaram"
                                required>
                        </div>
                        <div class="form-group-eleitor">
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
                            <label for="votos_branco_prefeito">Votos branco de prefeito</label>
                            <input type="number" id="votos_branco_prefeito" name="votos_branco_prefeito" min="0"
                                placeholder="Votos branco para prefeito" required>
                        </div>
                        <div class="form-group">
                            <label for="votos_nulo_prefeito">Votos nulo de prefeito</label>
                            <input type="number" id="votos_nulo_prefeito" name="votos_nulo_prefeito" min="0"
                                placeholder="Votos nulos para prefeito" required>
                        </div>
                    </div>
                    <!-- Seção para buscar e adicionar candidato para vereador -->
                    <h2>Adicionar votos para vereador</h2>
                    <div class="form-group" id="vereadors">
                        <div>
                            <label for="vereador_search">Buscar vereador</label>
                            <div class="search-buscar-vereador-container">
                                <input type="number" id="vereador_search" name="vereador_search"
                                    placeholder="Digite o número do vereador">
                                <button id="button-buscar-vereador" class="form-submit-btn" type="button"
                                    onclick="buscarVereador()">Buscar
                                    vereador<span class="material-symbols-outlined">search</span></button>
                            </div>
                        </div>
                        <!-- Mensagem de erro, se existir -->
                        <div id="erro_vereador" style="color: red;"></div>
                        <!-- Lista de candidatos adicionados -->
                        <div id="vereador_resultado"></div>
                    </div>

                    <div class="form-group-container">
                        <!-- Campos para votos em branco e nulos -->
                        <div class="form-group">
                            <label for="votos_branco_vereador">Votos branco de vereador</label>
                            <input type="number" id="votos_branco_vereador" name="votos_branco_vereador" min="0"
                                placeholder="Votos branco para vereador">
                        </div>
                        <div class="form-group">
                            <label for="votos_nulo_vereador">Votos nulo de vereador</label>
                            <input type="number" id="votos_nulo_vereador" name="votos_nulo_vereador" min="0"
                                placeholder="Votos nulos para vereador">
                        </div>
                        <div class="form-group">
                            <label for="votos_legenda_vereador">Votos de legenda</label>
                            <input type="number" id="votos_legenda_vereador" name="votos_legenda_vereador"
                                min="0" placeholder="Votos de legenda">
                        </div>
                    </div>
                    <button id="form-submit-btn-boletim" class="form-submit-btn" type="button" name="action"
                        value="inserir_votos" onclick="openConfirmationModal()">Enviar boletim</button>
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

    <!-- Modal de Confirmação -->
    <div id="confirmationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Você tem certeza de que deseja enviar os dados?</h3>
            <div class="modal-buttons">
                <button id="confirm-btn" class="confirm-btn">Sim</button>
                <button id="cancel-btn" class="cancel-btn">Não</button>
            </div>
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
                        erroVereador.innerHTML =
                            '<p class="custom-error-message">Este candidato já foi adicionado!</p>';
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
                        erroVereador.innerHTML = '<p class="custom-error-message">Candidato não encontrado!</p>';
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
        // Função para calcular a soma dos votos e validar
        function calcularSomaVotos() {
            const comp = parseInt(document.getElementById('comp').value) || 0;


            // Obtém os valores dos votos brancos, nulos e de legenda para vereador
            const votosBranco = parseInt(document.getElementById('votos_branco_vereador').value) || 0;
            const votosNulo = parseInt(document.getElementById('votos_nulo_vereador').value) || 0;
            const votosLegenda = parseInt(document.getElementById('votos_legenda_vereador').value) || 0;

            // Inicializa a soma com votos brancos, nulos e de legenda
            let totalVotos = votosBranco + votosNulo + votosLegenda;

            // Soma os votos de cada vereador adicionado
            const vereadorInputs = document.querySelectorAll('#vereador_resultado input[type="number"]');
            vereadorInputs.forEach(input => {
                totalVotos += parseInt(input.value) || 0;
            });

            // Verifica se a soma dos votos é maior que o número de comparecimentos
            if (totalVotos > comp) {
                showErrorMessage('Erro: A soma dos votos de vereador não pode ser maior que o número de comparecimentos.');
                return false; // Impede o envio do formulário
            }

            return true; // Permite o envio se a soma estiver correta
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
                        data.secoesRestantes.forEach(secao => {
                            secaoList += `<li>Seção: ${secao.id} - ${secao.localidade.nome}</li>`;
                        });

                        // Exibir seções restantes
                        document.getElementById('secoesRestantes').innerHTML = secaoList;

                        // Exibir informações de contagem
                        document.getElementById('infoSecoes').innerHTML = `
                    <p>Total de Seções: ${data.totalSecoes}</p>
                    <p>Seções Lidas: ${data.secoesLidas} (${data.porcentagemLidas.toFixed(2)}%)</p>
                    <p>Seções Faltantes: ${data.secoesFaltantes} (${data.porcentagemFaltantes.toFixed(2)}%)</p>
                `;
                    });
                sectionsInfo.style.display = 'block';
            } else {
                // Esconder
                sectionsInfo.style.display = 'none';
            }
        });


        // Função para abrir o modal de confirmação
        function openConfirmationModal() {
            if (calcularSomaVotos()) {
                document.getElementById('confirmationModal').style.display = 'flex';
            }
        }

        // Função para fechar o modal
        function closeConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        // Ação ao clicar no botão "Sim"
        document.getElementById('confirm-btn').addEventListener('click', function() {
            const form = document.getElementById('voting-form');


            // Obtém os valores dos inputs "comp", "falt" e "aptos"
            const comp = parseInt(document.getElementById('comp').value) || 0;
            const falt = parseInt(document.getElementById('falt').value) || 0;
            const aptos = parseInt(document.getElementById('apto').value) || 0;

            // Verifica se a soma de "comp" e "falt" é maior que "aptos"
            if ((comp + falt) > aptos) {
                closeConfirmationModal();
                // Exibe a mensagem de erro
                showErrorMessage(
                    'Erro: A soma de comparecimentos e faltas não pode ser maior que o número de eleitores aptos.'
                );
                return; // Impede o envio do formulário
            }

            // Verifica se o formulário é válido
            if (form.reportValidity()) {
                // Se o formulário for válido, envia o formulário
                form.submit();
            } else {
                // Se o formulário for inválido, fecha o modal e permite que as mensagens de erro apareçam
                closeConfirmationModal();
            }
        });

        function showErrorMessage(message) {
            // Cria o elemento de mensagem de erro
            const errorDiv = document.createElement('div');
            errorDiv.className = 'custom-error-message'; // Adiciona a classe personalizada
            errorDiv.textContent = message;

            // Insere a mensagem no topo do formulário ou em outro lugar da página
            const formContainer = document.getElementById('voting-form');
            formContainer.insertBefore(errorDiv, formContainer.firstChild);

            // Remove a mensagem após 5 segundos
            setTimeout(function() {
                errorDiv.remove();
            }, 10000);
        }

        // Ação ao clicar no botão "Não"
        document.getElementById('cancel-btn').addEventListener('click', function() {
            // Fecha o modal sem enviar o formulário
            closeConfirmationModal();
        });
    </script>
</body>
<footer>
    <h1>Juntos é possível!</h1>
</footer>

</html>
