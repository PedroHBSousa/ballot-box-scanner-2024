<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Vereador</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/relatorio-vereador.css')
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

    <div class="search-container">
        <div class="search">
            <h2>Digite o nome ou o numero do vereador</h2>
            <form class="form" id="form-buscar-vereador">
                <input class="input-search-vereador" type="text" id="search" name="search"
                    placeholder="Digite o nome ou número do candidato">
                <button class="button-submit-vereador" type="submit">Buscar</button>
                <div id="autocomplete-list" class="autocomplete-items"></div> <!-- Lista de sugestões -->
            </form>
            <div id="error-message" class="error-message" style="display:none;"></div>
            <div id="result-container" class="items-buscar-vereador" style="display:none;">
            </div>
        </div>
    </div>

    <script>
        // Evento de input para o campo de busca
        document.getElementById('search').addEventListener('input', function() {
            let search = this.value.trim(); // Remove espaços desnecessários
            let autocompleteList = document.getElementById('autocomplete-list');

            autocompleteList.innerHTML = ''; // Limpa as sugestões anteriores

            // Evita buscar se o campo de pesquisa tiver menos de 2 caracteres
            if (search.length < 2) {
                return;
            }

            // Se a busca for por nome (ou partido)
            if (isNaN(search)) { // Se for um texto
                searchPorNomeOuPartido(search);
            }
        });

        // Adiciona o evento de submit para o formulário
        document.getElementById('form-buscar-vereador').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            let search = document.getElementById('search').value.trim(); // Captura o valor do campo de busca

            // Verifica se o campo de busca está vazio
            if (search.length < 2) {
                document.getElementById('error-message').innerText = 'Por favor, digite pelo menos 2 caracteres.';
                document.getElementById('error-message').style.display = 'block'; // Exibe a mensagem de erro
                return;
            } else {
                document.getElementById('error-message').style.display = 'none'; // Esconde a mensagem de erro
            }

            // Se a busca for por número (ID do candidato)
            if (!isNaN(search)) { // Se for um número
                searchVereadorPorNumero(search);
            }
        });

        // Função para buscar por nome do vereador ou partido
        function searchPorNomeOuPartido(search) {
            let autocompleteList = document.getElementById('autocomplete-list');

            fetch(`/buscar-vereador?search=${search}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    autocompleteList.innerHTML = ''; // Limpa as sugestões anteriores
                    if (data.error) {
                        autocompleteList.innerHTML = `<div>${data.error}</div>`;
                    } else {
                        if (data.vereadores && data.vereadores.length > 0) {
                            // Se houver vereadores correspondentes, exibe os resultados
                            data.vereadores.forEach(vereador => {
                                let item = document.createElement('div');
                                item.innerHTML = `<strong>${vereador.nome}</strong> (${vereador.partido})`;

                                // Quando o item da lista for clicado, busca os detalhes do vereador
                                item.addEventListener('click', function() {
                                    fetchVereadorDetails(vereador.id);
                                    autocompleteList.innerHTML = ''; // Limpa as sugestões ao selecionar
                                });

                                autocompleteList.appendChild(item);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar vereadores:', error);
                });
        }

        // Função para buscar vereador pelo número (ID)
        function searchVereadorPorNumero(search) {
            let resultContainer = document.getElementById('result-container'); // Para mostrar os detalhes do vereador
            let buttonDownloadPDF = document.querySelector('button'); // Botão de download do PDF

            fetch(`/buscar-vereador?search=${search}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        resultContainer.innerHTML = `<div>${data.error}</div>`;
                        resultContainer.style.display = 'block'; // Exibe a mensagem de erro
                    } else if (data.vereador) {
                        // Exibe os detalhes do vereador diretamente
                        fetchVereadorDetails(data.vereador.id); // Chama a função que exibe os detalhes do vereador
                    } else {
                        resultContainer.innerHTML = '<div>Nenhum vereador encontrado.</div>';
                        resultContainer.style.display = 'block'; // Exibe a mensagem se não encontrar
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar vereador por número:', error);
                });
        }

        // Função para mostrar os dados do vereador
        function fetchVereadorDetails(vereadorId) {
            let resultContainer = document.getElementById('result-container');
            let buttonDownloadPDF = document.getElementById('button-download-pdf');

            fetch(`/buscar-vereador?search=${vereadorId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.vereador) {
                        // Ordena as seções pelo campo 'votos_na_secao' em ordem decrescente
                        let secoesOrdenadas = data.secoes.sort((a, b) => b.votos_na_secao - a.votos_na_secao);

                        // Gera a lista de seções onde o vereador recebeu votos
                        let secoesList = secoesOrdenadas.map(secao => `
                    <tr>
                        <th scope="row">${secao.id}</th>
                        <td>${secao.localidade.nome}</td>
                        <td>${secao.votos_na_secao}</td>
                    </tr>
                `).join('');

                        // Exibe os detalhes do vereador selecionado
                        resultContainer.innerHTML = `
                    <div class="councilor-information">
                        <div class="councilor-information-items">
                            <h4 class="councilor-information-items-title">Número</h4>
                            <h5 class="councilor-information-items-subtitle">${data.vereador.id}</h5>
                        </div>
                        <div class="councilor-information-items">
                            <h4 class="councilor-information-items-title">Nome</h4>
                            <h5 class="councilor-information-items-subtitle">${data.vereador.nome}</h5>
                        </div>
                        <div class="councilor-information-items">
                            <h4 class="councilor-information-items-title">Partido</h4>
                            <h5 class="councilor-information-items-subtitle">${data.vereador.partido}</h5>
                        </div>
                        <div class="councilor-information-items">
                            <h4 class="councilor-information-items-title">Total de votos</h4>
                            <h5 class="councilor-information-items-subtitle">${data.vereador.quantidade_votos}</h5>
                        </div>
                    </div>
                    <div class="section-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Seção</th>
                                    <th scope="col">Escola</th>
                                    <th scope="col">Votos</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${secoesList}
                            </tbody>
                        </table>
                    </div>
                `;

                        resultContainer.style.display = 'block'; // Exibe o container de resultados
                        buttonDownloadPDF.style.display = 'block'; // Exibe o botão de download do PDF
                    } else {
                        resultContainer.innerHTML = `<div>${data.error}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar detalhes do vereador:', error);
                });
        }
    </script>

</body>

</html>