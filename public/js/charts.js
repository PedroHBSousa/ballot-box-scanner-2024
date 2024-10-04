let refreshInterval; // Variável para armazenar o intervalo de atualização

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

    const ctxPrefeitos = document
        .getElementById("piechart-prefeitos")
        .getContext("2d");
    window.chartInstancePrefeitos = new Chart(
        ctxPrefeitos,
        createPieChartConfig("prefeitos")
    );

    const ctxPrefeitosGeral = document
        .getElementById("piechart-prefeitos-geral")
        .getContext("2d");
    window.chartInstancePrefeitosGeral = new Chart(
        ctxPrefeitosGeral,
        createPieChartConfig("prefeitos-geral")
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

function getChartOptions() {
    const isMobile = window.innerWidth <= 440;

    return {
        layout: {
            padding: {
                margin: isMobile ? 0 : 0,
                top: isMobile ? -10 : 10,
                bottom: isMobile ? -10 : 20,
                left: isMobile ? 10 : 20,
                right: isMobile ? 10 : 20,
            },
        },
        maintainAspectRatio: true,
        aspectRatio: isMobile ? 1.2 : 1.2,
        plugins: {
            title: {
                display: true,
                padding: {
                    top: isMobile ? 10 : 10,
                    bottom: isMobile ? 10 : 10,
                },
                font: {
                    size: isMobile ? 12 : 22,
                },
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    padding: isMobile ? 5 : 15,
                    font: {
                        size: isMobile ? 12 : 15,
                    },
                    generateLabels: function (chart) {
                        return chart.data.labels.map(function (label) {
                            return {
                                text: "   ",
                                color: "transparent",
                                fillStyle: "transparent",
                                strokeStyle: "transparent",
                                hidden: false,
                                pointStyle: "none", // Remove os quadrados
                            };
                        });
                    },
                },
            },
            datalabels: {
                color: "#000",
                formatter: (value, context) => {
                    const totalVotes =
                        context.chart.data.datasets[0].data.reduce(
                            (a, b) => a + b,
                            0
                        );
                    const percentage = ((value / totalVotes) * 100).toFixed(2); // Agora com duas casas decimais
                    return `${value}\n(${percentage}%)`;
                },
                font: {
                    weight: "bold",
                    size: isMobile ? 10 : 14,
                    lineHeight: 1,
                },
                anchor: "end",
                align: "end",
                offset: isMobile ? 0 : 0,
            },
        },
    };
}

// Função para redimensionar o gráfico com base no tamanho da tela
function resizeChart(chart) {
    chart.options = getChartOptions();
    chart.update();
}

function createPieChartConfig() {
    return {
        type: "pie",
        data: {
            labels: [],
            datasets: [
                {
                    data: [],
                    backgroundColor: [
                        "rgba(30,144,255)", // Reinaldinho
                        "rgba(255, 136, 0)", // Dr Ruan
                        "rgba(1, 110, 17)", // Dr Nill
                        "rgba(245, 69, 242)", // Gleivison
                        "rgba(252, 3, 3)", // Vinicius
                        "rgba(220,220, 220)", // Votos Brancos
                        "rgba(143, 62, 0)", // Abstenções
                        "rgba(128, 128, 128)", // Votos Nulos
                    ],
                    borderColor: [
                        "#FFF",
                        "#FFF",
                        "#FFF",
                        "#FFF",
                        "#FFF",
                        "#FFF", // Abstenções
                        "#FFF", // Votos Brancos
                        "#FFF", // Votos Nulos
                    ],
                    borderWidth: 2,
                },
            ],
        },
        options: {
            ...getChartOptions(),
            plugins: {
                ...getChartOptions().plugins,
                title: {
                    display: true,
                    text: "", // Título será preenchido dinamicamente
                    font: {
                        size: 12,
                    },
                },
            },
        },
    };
}

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
            indexAxis: "y",
            scales: {
                x: {
                    beginAtZero: true,
                    offset: true,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: label, // Atualiza o título com base na configuração fornecida
                    font: {
                        size: 12,
                    },
                },
                datalabels: {
                    color: "#000", // Branco para contraste com as barras
                    formatter: (value) => {
                        return `${value}`;
                    },
                    font: {
                        weight: "bold",
                        size: 12,
                    },
                    anchor: "end", // Posiciona o texto no final da barra
                    align: "end", // Alinha o texto dentro da barra
                    clip: true, // Permite que o texto ultrapasse a borda da barra
                    padding: 0, // Remove o padding para centralizar o texto dentro da barra
                },
            },
        },
    };
}

function loadInitialData() {
    // Função para atualizar os gráficos com dados iniciais
    const updateInitialData = () => {
        axios
            .get("/data/geral")
            .then((response) => {
                const data = response.data;

                // Atualiza o gráfico de prefeitos
                updateChartInstance(
                    window.chartInstancePrefeitos,
                    data.prefeitos,
                    "prefeitos"
                );

                updateChartInstance(
                    window.chartInstancePrefeitosGeral,
                    {
                        prefeitos: data.prefeitos, // Dados dos prefeitos
                        brancos: data.votos_brancos, // Votos brancos
                        abstenções: data.abstencoes, // Abstenções

                        nulos: data.votos_nulos, // Votos nulos
                    },
                    "prefeitos-geral"
                );

                // Atualiza o gráfico de vereadores
                updateChartInstance(
                    window.chartInstanceVereadores,
                    data.vereadores,
                    "vereadores"
                );
            })
            .catch((error) => {
                console.error("Erro ao buscar dados iniciais:", error);
            });
    };

    // Chama a função para carregar os dados iniciais imediatamente
    updateInitialData();

    // Limpa qualquer intervalo anterior se já houver um ativo
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }

    // Define o intervalo para atualizar os dados iniciais a cada 10 segundos
    refreshInterval = setInterval(updateInitialData, 60000);

    // Esconde certos gráficos que não são exibidos inicialmente
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
                '<option value="">Selecione o bairro</option>';

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
                '<option value="">Selecione a escola</option>';

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
                '<option value="">Selecione a região</option>';

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

function loadPartidosSubfilters() {
    const updateData = () => {
        axios
            .get("/get-partidos") // Ajuste a URL conforme necessário
            .then((response) => {
                const subfilterSelect = document.getElementById(
                    "partido-subfilter-select"
                );
                subfilterSelect.innerHTML =
                    '<option value="">Selecione o partido</option>';

                response.data.forEach((partido) => {
                    const option = document.createElement("option");
                    option.value = partido;
                    option.textContent = partido;
                    subfilterSelect.appendChild(option);
                });

                document.getElementById(
                    "partido-subfilter-container"
                ).style.display = "block";

                subfilterSelect.addEventListener(
                    "change",
                    handlePartidoSubfilterChange // Chame a função que irá lidar com a mudança do filtro
                );
            })
            .catch((error) => {
                console.error("Erro ao buscar partidos:", error);
            });
    };
    updateData();

    // Limpa o intervalo anterior se já houver um ativo
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }

    // Define o intervalo para atualizar os dados a cada 10 segundos
    refreshInterval = setInterval(updateData, 60000);
}

function handleFilterChange(event) {
    const selectedFilter = event.target.value;

    // Oculta todos os subfiltros antes de exibir o subfiltro selecionado
    hideAllSubfilters();

    if (selectedFilter) {
        axios
            .get(`/data/${selectedFilter}`)
            .then((response) => {
                updateChart(
                    selectedFilter,
                    response.data,
                    window.chartInstancePrefeitos
                );
                updateChart(
                    selectedFilter,
                    response.data,
                    window.chartInstanceVereadores
                );

                // Exibe o subfiltro correspondente, se houver
                if (selectedFilter === "bairros") {
                    loadBairrosSubfilters();
                } else if (selectedFilter === "regioes") {
                    loadRegioesSubfilters();
                } else if (selectedFilter === "localidades") {
                    loadEscolasSubfilters();
                } else if (selectedFilter === "partidos-vereador") {
                    loadPartidosSubfilters();
                } else {
                    // Caso o filtro não tenha subfiltro, escondemos todos os subfiltros
                    document.getElementById(
                        "subfilter-container"
                    ).style.display = "none";
                    document.getElementById(
                        "school-filter-container"
                    ).style.display = "none";
                }
                document.getElementById(
                    "button-download-chart-pdf"
                ).style.display = "none";
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
    document.getElementById("partido-subfilter-container").style.display =
        "none";
}

function handleBairroSubfilterChange(event) {
    const selectedBairroId = event.target.value;
    const selectedBairroName =
        event.target.options[event.target.selectedIndex].text.trim();

    if (selectedBairroId) {
        const updateData = () => {
            axios
                .get(`/data/bairros/${selectedBairroId}`)
                .then((response) => {
                    const data = response.data;

                    // Atualiza o gráfico de prefeitos
                    updateChartInstance(
                        window.chartInstancePrefeitos,
                        data.prefeitos,
                        "prefeitos",
                        selectedBairroName
                    );

                    // Atualiza o gráfico de vereadores
                    updateChartInstance(
                        window.chartInstanceVereadores,
                        data.vereadores,
                        "vereadores",
                        selectedBairroName
                    );
                    document.getElementById(
                        "button-download-chart-pdf"
                    ).style.display = "block";
                })
                .catch((error) => {
                    console.error("Erro ao buscar dados para o bairro:", error);
                });
        };
        // Chama a função de atualização imediatamente ao selecionar a escola
        updateData();

        // Limpa o intervalo anterior se já houver um ativo
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }

        // Define o intervalo para atualizar os dados a cada 10 segundos
        refreshInterval = setInterval(updateData, 60000);
    } else {
        console.error("Nenhum bairro selecionado.");
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
}

function handleEscolaSubfilterChange(event) {
    const selectedLocalidadeId = event.target.value;
    const selectedLocalidadeName =
        event.target.options[event.target.selectedIndex].text.trim();

    if (selectedLocalidadeId) {
        // Função responsável por buscar os dados e atualizar o gráfico
        const updateData = () => {
            axios
                .get(`/data/localidades/${selectedLocalidadeId}`)
                .then((response) => {
                    const data = response.data;

                    // Atualiza o gráfico de prefeitos, passando o nome da escola como título
                    updateChartInstance(
                        window.chartInstancePrefeitos,
                        data.prefeitos,
                        "prefeitos",
                        selectedLocalidadeName
                    );

                    // Atualiza o gráfico de vereadores, passando o nome da escola como título
                    updateChartInstance(
                        window.chartInstanceVereadores,
                        data.vereadores,
                        "vereadores",
                        selectedLocalidadeName
                    );
                })
                .catch((error) => {
                    console.error("Erro ao buscar votos para a escola:", error);
                });
        };

        // Chama a função de atualização imediatamente ao selecionar a escola
        updateData();

        // Limpa o intervalo anterior se já houver um ativo
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        30000;
        // Define o intervalo para atualizar os dados a cada 10 segundos
        refreshInterval = setInterval(updateData, 60000);
    } else {
        console.error("Nenhuma escola selecionada.");
        // Se não houver escola selecionada, para a atualização automática
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
}

function handleRegioesSubfilterChange(event) {
    const selectedRegiao = event.target.value;
    const selectedRegiaoName =
        event.target.options[event.target.selectedIndex].text.trim();

    if (selectedRegiao) {
        const updateData = () => {
            axios
                .get(`/data/regioes/${selectedRegiao}`)
                .then((response) => {
                    const data = response.data;

                    // Atualiza o gráfico de prefeitos
                    updateChartInstance(
                        window.chartInstancePrefeitos,
                        data.prefeitos,
                        "prefeitos",
                        selectedRegiaoName
                    );

                    // Atualiza o gráfico de vereadores
                    updateChartInstance(
                        window.chartInstanceVereadores,
                        data.vereadores,
                        "vereadores",
                        selectedRegiaoName
                    );
                })
                .catch((error) => {
                    console.error("Erro ao buscar votos para a região:", error);
                });
        };
        // Chama a função de atualização imediatamente ao selecionar a escola
        updateData();

        // Limpa o intervalo anterior se já houver um ativo
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }

        // Define o intervalo para atualizar os dados a cada 10 segundos
        refreshInterval = setInterval(updateData, 60000);
    } else {
        console.error("Nenhuma região selecionada.");
        // Se não houver escola selecionada, para a atualização automática
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
}

function handlePartidoSubfilterChange(event) {
    const selectedPartido = event.target.value;
    const selectedPartidoName =
        event.target.options[event.target.selectedIndex].text.trim();

    if (selectedPartido) {
        const updateData = () => {
            axios
                .get(`/data/partidos/${encodeURIComponent(selectedPartido)}`)
                .then((response) => {
                    const data = response.data;

                    // Aqui você pode atualizar os gráficos ou fazer outras operações com os dados retornados
                    const subtitle = `${selectedPartidoName} | Total de Votos: ${data.total_votos_partido}`;
                    updateChartInstance(
                        window.chartInstanceVereadores,
                        data.vereadores,
                        "vereadores",
                        subtitle
                    );
                })
                .catch((error) => {
                    console.error(
                        "Erro ao buscar candidatos para o partido:",
                        error
                    );
                });
        };
        // Chama a função de atualização imediatamente ao selecionar a escola
        updateData();

        // Limpa o intervalo anterior se já houver um ativo
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }

        // Define o intervalo para atualizar os dados a cada 10 segundos
        refreshInterval = setInterval(updateData, 60000);

        document.getElementById("button-download-chart-pdf").style.display =
            "block";
    } else {
        console.error("Nenhum partido selecionado.");
        // Se não houver escola selecionada, para a atualização automática
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }
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
        loadInitialData();
        // Mostrar os gráficos de prefeitos e vereadores
        toggleChartVisibility(window.chartInstancePrefeitos.canvas.id, true);
        toggleChartVisibility(window.chartInstanceVereadores.canvas.id, true);
        toggleChartVisibility(
            window.chartInstancePrefeitosGeral.canvas.id,
            true
        );

        // Ocultar outros gráficos que não são relevantes para o filtro "geral"
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-partidos", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-regioes", false);
    } else if (filter === "partidos") {
        updateChartInstance(chartInstance, data, filter);
        // Mostrar apenas o gráfico de partidos
        toggleChartVisibility(chartInstance.canvas.id, true);

        // Ocultar os gráficos de prefeitos e vereadores
        toggleChartVisibility(window.chartInstancePrefeitos.canvas.id, false);
        toggleChartVisibility(window.chartInstanceVereadores.canvas.id, true);

        // Ocultar outros gráficos que não são relevantes para o filtro "partidos"
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-regioes", false);
    } else if (filter === "partidos-vereador") {
        // Atualizar apenas o gráfico de partidos
        updateChartInstance(chartInstance, data, filter);

        // Mostrar apenas o gráfico de partidos
        toggleChartVisibility(chartInstance.canvas.id, true);

        // Ocultar os gráficos de prefeitos e vereadores
        toggleChartVisibility(window.chartInstancePrefeitos.canvas.id, false);
        toggleChartVisibility(window.chartInstanceVereadores.canvas.id, true);

        // Ocultar outros gráficos que não são relevantes para o filtro "partidos"
        toggleChartVisibility("barchart-bairros", false);
        toggleChartVisibility("barchart-escolas", false);
        toggleChartVisibility("barchart-regioes", false);
        toggleChartVisibility("barchart-partidos", false);
    } else if (filter === "bairros") {
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

function updateChartInstance(chartInstance, data, filter, subfilterName = "") {
    // Atualizar o título com o nome do subfiltro
    const titleText = subfilterName || "";

    if (chartInstance.options.plugins && chartInstance.options.plugins.title) {
        chartInstance.options.plugins.title.text = titleText;
    }

    // Verifica se os dados são um objeto (caso do segundo gráfico)
    if (
        filter === "prefeitos-geral" &&
        typeof data === "object" &&
        !Array.isArray(data)
    ) {
        // Supondo que 'data' contém os campos 'prefeitos', 'abstencoes', 'brancos', e 'nulos'
        const candidatos = data.prefeitos || [];
        const brancos = data.brancos || 0;
        const abstenções = parseInt(data.abstenções, 10) || 0;
        const nulos = data.nulos || 0;

        // Atualiza os rótulos e os dados do gráfico
        chartInstance.data.labels = [
            ...candidatos.map((item) => item.nome || "Indefinido"),
            "Votos Brancos",
            "Abstenções",
            "Votos Nulos",
        ];

        chartInstance.data.datasets[0].data = [
            ...candidatos.map((item) => item.total || 0),
            brancos,
            abstenções,
            nulos,
        ];
    } else if (!Array.isArray(data) || data.length === 0) {
        chartInstance.data.labels = [];
        chartInstance.data.datasets[0].data = [];
    } else {
        // Reordena os dados para colocar "Reinaldinho" em primeiro
        const reorderedData = data.sort((a, b) => {
            if (a.nome === "Reinaldinho") return -1; // Coloca "Reinaldinho" em primeiro
            if (b.nome === "Reinaldinho") return 1;
            return 0; // Mantém a ordem para os outros
        });

        // Atualiza os dados do gráfico com base no filtro
        if (filter === "partidos") {
            chartInstance.data.labels = data.map(
                (item) => item.partido || "Indefinido"
            );
            chartInstance.data.datasets[0].data = data.map(
                (item) => item.total || 0
            );
        } else if (filter === "prefeitos" || filter === "vereadores") {
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
