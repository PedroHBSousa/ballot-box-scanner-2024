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
    axios
        .get("/data/prefeitos")
        .then((response) => {
            const data = response.data;
            updateChartInstance(window.chartInstancePrefeitos, data);
        })
        .catch((error) => {
            console.error(
                "Erro ao buscar dados do gráfico de prefeitos:",
                error
            );
        });

    document.getElementById("barchart-bairros").parentElement.style.display =
        "none";
    document.getElementById("barchart-escolas").parentElement.style.display =
        "none";
    document.getElementById("barchart-partidos").parentElement.style.display =
        "none";
}

function handleFilterChange(event) {
    const selectedFilter = event.target.value;
    console.log("Filtro selecionado:", selectedFilter);
    if (selectedFilter) {
        axios
            .get(`/data/${selectedFilter}`)
            .then((response) => {
                console.log("Dados recebidos:", response.data);
                updateChart(selectedFilter, response.data);
                if (selectedFilter === "bairros") {
                    loadBairrosSubfilters();
                } else if (selectedFilter === "localidades") {
                    loadEscolasSubfilters();
                } else {
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

            subfilterSelect.addEventListener("change", handleFilterChange);
        })
        .catch((error) => {
            console.error("Erro ao buscar bairros:", error);
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
                console.log("Dados recebidos:", response.data);
                updateChart(selectedFilter, response.data);

                // Exibe o subfiltro correspondente, se houver
                if (selectedFilter === "bairros") {
                    loadBairrosSubfilters();
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

function handleEscolaSubfilterChange(event) {
    const selectedLocalidadeId = event.target.value;
    console.log("Selected Localidade ID:", selectedLocalidadeId);

    if (selectedLocalidadeId) {
        axios
            .get(`/data/localidades/${selectedLocalidadeId}`)
            .then((response) => {
                updateChartInstance(window.chartInstanceEscolas, response.data);
            })
            .catch((error) => {
                console.error("Erro ao buscar votos para a escola:", error);
            });
    } else {
        console.error("Nenhuma escola selecionada.");
    }
}

function updateChartInstance(chartInstance, data, filter) {
    console.log(data); //Isso irá verificar os dados no console

    if (!Array.isArray(data) || data.length === 0) {
        console.log("Nenhum dado encontrado para este filtro.");
        chartInstance.data.labels = [];
        chartInstance.data.datasets[0].data = [];
    } else {
        if (filter === "partidos") {
            // Para partidos, usa a propriedade 'partido'
            chartInstance.data.labels = data.map(
                (item) => item.partido || "Indefinido"
            );
            chartInstance.data.datasets[0].data = data.map(
                (item) => item.total || 0
            );
        } else {
            // Para prefeitos e vereadores, usa a propriedade 'nome'
            chartInstance.data.labels = data.map(
                (item) => item.nome || "Indefinido"
            );
            chartInstance.data.datasets[0].data = data.map(
                (item) => item.total || 0
            );
        }
    }
    chartInstance.update();
}

function updateChart(filter, data) {
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

    if (filter === "prefeitos") {
        updateChartInstance(window.chartInstancePrefeitos, data, filter);
        toggleChartVisibility("barchart-vereadores", false);
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-partidos", false);
    } else if (filter === "vereadores") {
        updateChartInstance(window.chartInstanceVereadores, data, filter);
        toggleChartVisibility("barchart-vereadores", true);
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-partidos", false);
    } else if (filter === "bairros") {
        updateChartInstance(window.chartInstanceBairros, data, filter);
        toggleChartVisibility("barchart-vereadores", false);
        toggleChartVisibility("barchart-bairros", true);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-partidos", false);
    } else if (filter === "partidos") {
        updateChartInstance(window.chartInstancePartidos, data, filter);
        toggleChartVisibility("barchart-vereadores", false);
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-partidos", true);
    } else if (filter === "localidades") {
        updateChartInstance(window.chartInstanceEscolas, data, filter);
        toggleChartVisibility("barchart-vereadores", false);
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", true);
        toggleChartVisibility("barchart-partidos", false);
    }
}
