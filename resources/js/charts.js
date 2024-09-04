
document.addEventListener('DOMContentLoaded', function () {
    // Inicializa os gráficos
    initializeCharts();

    // Carrega dados iniciais
    loadInitialData();

    // Adiciona evento para o seletor de filtro
    document.getElementById('filter-select').addEventListener('change', handleFilterChange);
});

function initializeCharts() {
    // Inicializa os gráficos
    const ctxPrefeitos = document.getElementById('piechart-prefeitos').getContext('2d');
    window.chartInstancePrefeitos = new Chart(ctxPrefeitos, createPieChartConfig());

    const ctxVereadores = document.getElementById('barchart-vereadores').getContext('2d');
    window.chartInstanceVereadores = new Chart(ctxVereadores, createBarChartConfig('Vereadores'));

    const ctxBairros = document.getElementById('barchart-bairros').getContext('2d');
    window.chartInstanceBairros = new Chart(ctxBairros, createBarChartConfig('Bairros'));

}

Chart.register(ChartDataLabels);

// Configuração do gráfico de pizza
function createPieChartConfig() {
    return {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgba(7, 217, 0)',
                    'rgba(255,0,0)',
                    'rgba(252, 186, 3)',
                    'rgba(30,144,255)',
                    'rgba(255,69,0)'
                ],
                borderColor: ['#FFF', '#FFF', '#FFF', '#FFF', '#FFF'],
                borderWidth: 2,
            
            }]
        },
        
        options: {
            plugins:  {
                legend: {
                    display: true
                },
                datalabels: {
                    display:true,
                    color: '#fff',
                    formatter: (value, context) => {
                        const totalVotes = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / totalVotes) * 100).toFixed(1);
                        return `${value} (${percentage}%)`;
                    },
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    
                }
            }
        }
    };
}

// Configuração do gráfico de barras
function createBarChartConfig(label) {
    return {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Votos',
                data: [],
                backgroundColor: [
                    'rgba(7, 217, 0)',
                    'rgba(255,0,0)',
                    'rgba(252, 186, 3)',
                    'rgba(30,144,255)',
                    'rgba(255,69,0)'
                ],
                borderColor: ['#FFF', '#FFF', '#FFF', '#FFF', '#FFF'],
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                datalabels: {
                    color: '#fff',
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value,  // Exibe o número de votos diretamente
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    display: true
                }
            }
        }
    };
}

function loadInitialData() {
    axios.get('/data/prefeitos')
        .then(response => {
            const data = response.data;
            updateChartInstance(window.chartInstancePrefeitos, data);
        })
        .catch(error => {
            console.error('Erro ao buscar dados do gráfico de prefeitos:', error);
        });

    document.getElementById('barchart-bairros').parentElement.style.display = 'none';
    document.getElementById('barchart-escolas').parentElement.style.display = 'none';
}

function handleFilterChange(event) {
    const selectedFilter = event.target.value;

    if (selectedFilter) {
        axios.get(`/data/${selectedFilter}`)
            .then(response => {
                console.log('Dados recebidos:', response.data);
                updateChart(selectedFilter, response.data);

                if (selectedFilter === 'bairros') {
                    loadBairrosSubfilters();
                } else {
                    document.getElementById('subfilter-container').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erro ao buscar dados do filtro selecionado:', error);
            });
    }
}

function loadBairrosSubfilters() {
    axios.get('/get-bairros')
        .then(response => {
            const subfilterSelect = document.getElementById('subfilter-select');
            subfilterSelect.innerHTML = '<option value="">Selecione um bairro</option>';

            response.data.forEach(bairro => {
                const option = document.createElement('option');
                option.value = bairro.id;
                option.textContent = bairro.nome;
                subfilterSelect.appendChild(option);
            });

            document.getElementById('subfilter-container').style.display = 'block';

            subfilterSelect.addEventListener('change', handleSubfilterChange);
        })
        .catch(error => {
            console.error('Erro ao buscar bairros:', error);
        });
}

function handleSubfilterChange(event) {
    const selectedBairroId = event.target.value;

    if (selectedBairroId) {
        axios.get(`/data/bairros/${selectedBairroId}`)
            .then(response => {
                updateChartInstance(window.chartInstanceBairros, response.data);
            })
            .catch(error => {
                console.error('Erro ao buscar votos para o bairro:', error);
            });
    } else {
        console.error('Nenhum bairro selecionado.');
    }
}

function updateChartInstance(chartInstance, data) {
    if (!Array.isArray(data) || data.length === 0) {
        console.log('Nenhum dado encontrado para este filtro.');
        chartInstance.data.labels = [];
        chartInstance.data.datasets[0].data = [];
    } else {
        chartInstance.data.labels = data.map(item => item.nome);
        chartInstance.data.datasets[0].data = data.map(item => item.total || 0);
    }
    chartInstance.update();
}

function updateChart(filter, data) {
    function toggleChartVisibility(chartId, shouldShow) {
        const chartElement = document.getElementById(chartId);
        if (chartElement && chartElement.parentElement) {
            chartElement.parentElement.style.display = shouldShow ? 'block' : 'none';
        } else {
            console.error(`Elemento com ID ${chartId} não encontrado.`);
        }
    }

    if (filter === 'prefeitos') {
        updateChartInstance(window.chartInstancePrefeitos, data);
        toggleChartVisibility('barchart-vereadores', false);
        toggleChartVisibility('barchart-bairros', false);
        toggleChartVisibility('barchart-escolas', false);
    } else if (filter === 'vereadores') {
        updateChartInstance(window.chartInstanceVereadores, data);
        toggleChartVisibility('barchart-vereadores', true);
        toggleChartVisibility('barchart-bairros', false);
        toggleChartVisibility('barchart-escolas', false);
    } else if (filter === 'bairros') {
        updateChartInstance(window.chartInstanceBairros, data);
    }
}