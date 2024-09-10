document.addEventListener("DOMContentLoaded", function () {
    // Inicializa os gráficos
    initializeCharts();

    // Carrega dados iniciais
    loadInitialData();

    // Adiciona evento para o seletor de filtro
    document
        .getElementById("filter-select")
        .addEventListener("change", handleFilterChange);
});

function initializeCharts() {
    Chart.register(ChartDataLabels);
    // Inicializa os gráficos
    const ctxPrefeitos = document
        .getElementById("piechart-prefeitos")
        .getContext("2d");
    window.chartInstancePrefeitos = new Chart(
        ctxPrefeitos,
        createPieChartConfig()
    );

    const ctxVereadores = document
        .getElementById("barchart-vereadores")
        .getContext("2d");
    window.chartInstanceVereadores = new Chart(
        ctxVereadores,
        createBarChartConfig("Vereadores")
    );

    const ctxBairros = document
        .getElementById("barchart-bairros")
        .getContext("2d");
    window.chartInstanceBairros = new Chart(
        ctxBairros,
        createBarChartConfig("Bairros")
    );

    const ctxPartidos = document
        .getElementById("barchart-partidos")
        .getContext("2d");
    window.chartInstancePartidos = new Chart(
        ctxPartidos,
        createBarChartConfig("Partidos")
    );

    const ctxEscolas = document
        .getElementById("barchart-escolas")
        .getContext("2d");
    window.chartInstanceEscolas = new Chart(
        ctxEscolas,
        createBarChartConfig("Escolas")
    );

    const ctxRegioes = document
        .getElementById("barchart-regioes")
        .getContext("2d");
    window.chartInstanceRegioes = new Chart(
        ctxRegioes,
        createBarChartConfig("Regioes")
    );
}

// Configuração do gráfico de pizza
function createPieChartConfig() {
    return {
        type: "pie",
        data: {
            labels: [],
            datasets: [
                {
                    data: [],
                    backgroundColor: [
                        "rgba(7, 217, 0)",
                        "rgba(255,0,0)",
                        "rgba(252, 186, 3)",
                        "rgba(30,144,255)",
                        "rgba(242, 0, 255)",
                    ],
                    borderColor: ["#FFF", "#FFF", "#FFF", "#FFF", "#FFF"],
                    borderWidth: 2,
                },
            ],
        },

        options: {
            plugins: {
                legend: {
                    display: true,
                },
                datalabels: {
                    color: "#000", // A cor branca pode ser mais visível dentro das fatias
                    formatter: (value, context) => {
                        const totalVotes =
                            context.chart.data.datasets[0].data.reduce(
                                (a, b) => a + b,
                                0
                            );
                        const percentage = ((value / totalVotes) * 100).toFixed(
                            1
                        );
                        return `${value} (${percentage}%)`;
                    },
                    font: {
                        weight: "bold",
                        size: 14,
                    },
                    anchor: "center", // Coloca o texto no centro da fatia
                    align: "center", // Alinha o texto no centro
                    clip: false, // Permite que o texto seja exibido fora dos limites da fatia se necessário
                    padding: 0, // Remove o padding para evitar o recuo
                },
            },
        },
    };
}

// Configuração do gráfico de barras
function createBarChartConfig(label) {
    return {
        type: "bar",
        data: {
            labels: [], // Certifique-se de adicionar as labels necessárias
            datasets: [
                {
                    data: [],
                    backgroundColor: [
                        "rgba(7, 217, 0)",
                        "rgba(255,0,0)",
                        "rgba(252, 186, 3)",
                        "rgba(30,144,255)",
                        "rgba(242, 0, 255)",
                    ],
                    borderColor: ["#FFF", "#FFF", "#FFF", "#FFF", "#FFF"],
                    borderWidth: 2,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                datalabels: {
                    color: "#000", // Branco para contraste com as barras
                    formatter: (value, context) => {
                        const totalVotes =
                            context.chart.data.datasets[0].data.reduce(
                                (a, b) => a + b,
                                0
                            );
                        const percentage = ((value / totalVotes) * 100).toFixed(
                            1
                        );
                        return `${value} (${percentage}%)`;
                    },
                    font: {
                        weight: "bold",
                        size: 12,
                    },
                    anchor: "end", // Posiciona o texto no final da barra
                    align: "start", // Alinha o texto dentro da barra
                    clip: false, // Permite que o texto ultrapasse a borda da barra
                    padding: 0, // Remove o padding para centralizar o texto dentro da barra
                },
            },
        },
    };
}

// Funções para carregar e atualizar gráficos (sem alteração)
function loadInitialData() {
    axios.get('/data/geral')
        .then((response) => {
            const data = response.data;

            // Atualiza o gráfico de prefeitos
            updateChartInstance(window.chartInstancePrefeitos, data.prefeitos, 'prefeitos');

            // Atualiza o gráfico de vereadores
            updateChartInstance(window.chartInstanceVereadores, data.vereadores, 'vereadores');
        })
        .catch((error) => {
            console.error("Erro ao buscar dados iniciais:", error);
        });

    document.getElementById("barchart-bairros").parentElement.style.display =
        "none";
    document.getElementById("barchart-escolas").parentElement.style.display =
        "none";
    document.getElementById("barchart-partidos").parentElement.style.display =
        "none";
    document.getElementById("barchart-regioes").parentElement.style.display =
        "none";
}

function loadBairrosSubfilters() {
    axios
        .get("/get-bairros")
        .then((response) => {
            const subfilterSelect = document.getElementById(
                "bairro-subfilter-select"
            );
            subfilterSelect.innerHTML =
                '<option value="">Selecione um bairro</option>';

            response.data.forEach((bairro) => {
                const option = document.createElement("option");
                option.value = bairro.id;
                option.textContent = bairro.nome;
                subfilterSelect.appendChild(option);
            });

            document.getElementById("subfilter-container").style.display =
                "block";

            subfilterSelect.addEventListener(
                "change",
                handleBairroSubfilterChange
            );
        })
        .catch((error) => {
            console.error("Erro ao buscar bairros:", error);
        });
}
function loadEscolasSubfilters() {
    axios
        .get("/get-localidades")
        .then((response) => {
            const subfilterSelect = document.getElementById(
                "escola-subfilter-select"
            );
            subfilterSelect.innerHTML =
                '<option value="">Selecione uma escola</option>';

            response.data.forEach((localidade) => {
                const option = document.createElement("option");
                option.value = localidade.id;
                option.textContent = localidade.nome;
                subfilterSelect.appendChild(option);
            });

            document.getElementById("school-filter-container").style.display =
                "block";

            subfilterSelect.addEventListener(
                "change",
                handleEscolaSubfilterChange
            );
        })
        .catch((error) => {
            console.error("Erro ao buscar escolas:", error);
        });
}
function loadRegioesSubfilters() {
    axios
        .get("/get-regioes")
        .then((response) => {
            const subfilterSelect = document.getElementById(
                "regiao-subfilter-select"
            );
            subfilterSelect.innerHTML =
                '<option value="">Selecione uma região</option>';

            response.data.forEach((regioes) => {
                const option = document.createElement("option");
                option.value = regioes;
                option.textContent = regioes;
                subfilterSelect.appendChild(option);
            });

            document.getElementById(
                "regiao-subfilter-container"
            ).style.display = "block";

            subfilterSelect.addEventListener(
                "change",
                handleRegioesSubfilterChange
            );
        })
        .catch((error) => {
            console.error("Erro ao buscar escolas:", error);
        });
}

function handleFilterChange(event) {
    const selectedFilter = event.target.value;
    console.log("Filtro selecionado:", selectedFilter);

    // Oculta todos os subfiltros antes de exibir o subfiltro selecionado
    hideAllSubfilters();

    if (selectedFilter) {
        axios
            .get(`/data/${selectedFilter}`)
            .then((response) => {

                updateChart(selectedFilter, response.data, window.chartInstancePrefeitos);
                updateChart(selectedFilter, response.data, window.chartInstanceVereadores);

                // Exibe o subfiltro correspondente, se houver
                if (selectedFilter === "bairros") {
                    loadBairrosSubfilters();
                } else if (selectedFilter === "regioes") {
                    loadRegioesSubfilters();
                } else if (selectedFilter === "localidades") {
                    loadEscolasSubfilters();
                } else {
                    // Caso o filtro não tenha subfiltro, escondemos todos os subfiltros
                    document.getElementById(
                        "subfilter-container"
                    ).style.display = "none";
                    document.getElementById(
                        "school-filter-container"
                    ).style.display = "none";
                }
            })
            .catch((error) => {
                console.error(
                    "Erro ao buscar dados do filtro selecionado:",
                    error
                );
            });
    }
}

function hideAllSubfilters() {
    // Oculta os containers dos subfiltros
    document.getElementById("subfilter-container").style.display = "none";
    document.getElementById("school-filter-container").style.display = "none";
    document.getElementById("regiao-subfilter-container").style.display =
        "none";
}

function handleBairroSubfilterChange(event) {
    const selectedBairroId = event.target.value;
    console.log("Selected Bairro ID:", selectedBairroId);

    if (selectedBairroId) {
        axios
            .get(`/data/bairros/${selectedBairroId}`)
            .then((response) => {
                const data = response.data;

                // Atualiza o gráfico de prefeitos
                updateChartInstance(window.chartInstancePrefeitos, data.prefeitos, 'prefeitos');

                // Atualiza o gráfico de vereadores
                updateChartInstance(window.chartInstanceVereadores, data.vereadores, 'vereadores');
            })
            .catch((error) => {
                console.error("Erro ao buscar dados para o bairro:", error);
            });
    } else {
        console.error("Nenhum bairro selecionado.");
    }
}
function handleEscolaSubfilterChange(event) {
    const selectedLocalidadeId = event.target.value;
    console.log("Selected Localidade ID:", selectedLocalidadeId);

    if (selectedLocalidadeId) {
        axios
            .get(`/data/localidades/${selectedLocalidadeId}`)
            .then((response) => {
                const data = response.data;

                // Atualiza o gráfico de prefeitos
                updateChartInstance(window.chartInstancePrefeitos, data.prefeitos, 'prefeitos');

                // Atualiza o gráfico de vereadores
                updateChartInstance(window.chartInstanceVereadores, data.vereadores, 'vereadores');
            })
            .catch((error) => {
                console.error("Erro ao buscar votos para a escola:", error);
            });
    } else {
        console.error("Nenhuma escola selecionada.");
    }
}
function handleRegioesSubfilterChange(event) {
    const selectedRegiao = event.target.value;
    console.log("Selected Região:", selectedRegiao);

    if (selectedRegiao) {
        axios
            .get(`/data/regioes/${selectedRegiao}`)
            .then((response) => {
                const data = response.data;

                // Atualiza o gráfico de prefeitos
                updateChartInstance(window.chartInstancePrefeitos, data.prefeitos, 'prefeitos');

                // Atualiza o gráfico de vereadores
                updateChartInstance(window.chartInstanceVereadores, data.vereadores, 'vereadores');
            })
            .catch((error) => {
                console.error("Erro ao buscar votos para a região:", error);
            });
    } else {
        console.error("Nenhuma região selecionada.");
    }
}

function updateChartInstance(chartInstance, data, filter) {
    console.log(data); // Isso irá verificar os dados no console

    // Verifica se os dados estão disponíveis
    if (!Array.isArray(data) || data.length === 0) {
        console.log("Nenhum dado encontrado para este filtro.");
        chartInstance.data.labels = [];
        chartInstance.data.datasets[0].data = [];
    } else {
        // Atualiza os dados do gráfico com base no filtro
        if (filter === "partidos") {
            // Para partidos, usa a propriedade 'partido'
            chartInstance.data.labels = data.map(
                (item) => item.partido || "Indefinido"
            );
            chartInstance.data.datasets[0].data = data.map(
                (item) => item.total || 0
            );
        } else if (filter === "prefeitos" || filter === "vereadores") {
            // Para prefeitos e vereadores, usa a propriedade 'nome'
            chartInstance.data.labels = data.map(
                (item) => item.nome || "Indefinido"
            );
            chartInstance.data.datasets[0].data = data.map(
                (item) => item.total || 0
            );
        } else {
            console.error("Filtro não reconhecido:", filter);
            return;
        }
    }

    // Atualiza o gráfico com os novos dados
    chartInstance.update();
}

function updateChart(filter, data, chartInstance) {
    function toggleChartVisibility(chartId, shouldShow) {
        const chartElement = document.getElementById(chartId);
        if (chartElement && chartElement.parentElement) {
            chartElement.parentElement.style.display = shouldShow
                ? "block"
                : "none";
        } else {
            console.error(`Elemento com ID ${chartId} não encontrado.`);
        }
    }

    if (filter === "geral") {
        // Atualizar gráficos de prefeitos e vereadores separadamente
        updateChartInstance(window.chartInstancePrefeitos, data.prefeitos, 'prefeitos');
        updateChartInstance(window.chartInstanceVereadores, data.vereadores, 'vereadores');

        // Mostrar os gráficos de prefeitos e vereadores
        toggleChartVisibility(window.chartInstancePrefeitos.canvas.id, true);
        toggleChartVisibility(window.chartInstanceVereadores.canvas.id, true);

        // Ocultar outros gráficos que não são relevantes para o filtro "geral"
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-partidos", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-regioes", false);

    } else {

        if (filter === "bairros") {
            updateChartInstance(chartInstance, data, filter);
            toggleChartVisibility(chartInstance.canvas.id, true);
        } else if (filter === "partidos") {
            updateChartInstance(chartInstance, data, filter);
            toggleChartVisibility(chartInstance.canvas.id, true);
        } else if (filter === "localidades") {
            updateChartInstance(chartInstance, data, filter);
            toggleChartVisibility(chartInstance.canvas.id, true);
        } else if (filter === "regioes") {
            updateChartInstance(chartInstance, data, filter);
            toggleChartVisibility(chartInstance.canvas.id, true);
        }
    }
}
