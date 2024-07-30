<!-- Column Chart -->
<div id="columnChartContainer" class="charts-container"></div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        var jsonData = {!! $jsonData !!};
        var chartsContainer = document.getElementById('columnChartContainer');

        // Fungsi untuk mengubah setiap kata menjadi huruf kapital
        function capitalizeWords(string) {
            return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // Fungsi untuk mengonversi detik ke jam
        function secondsToHours(seconds) {
            return (seconds / 3600).toFixed(2);
        }

        for (var subDivision in jsonData) {
            if (jsonData.hasOwnProperty(subDivision)) {
                var formattedSubDivision = subDivision !== 'tidak ada' ? capitalizeWords(subDivision) : '';

                // Buat container untuk chart kolom
                var columnContainer = document.createElement('div');
                columnContainer.className = 'chart-container';
                columnContainer.id = 'columnChart_' + subDivision.replace(/\s+/g, '_');
                chartsContainer.appendChild(columnContainer);

                var ticketData = jsonData[subDivision]['tickets'].map(agent => ({
                    name: capitalizeWords(agent.name),
                    value: agent.value
                }));

                var processData = jsonData[subDivision]['process'].map(agent => ({
                    name: capitalizeWords(agent.name),
                    value: secondsToHours(agent.value) // Mengonversi detik ke jam
                }));

                new ApexCharts(columnContainer, {
                    series: [
                        {
                            name: 'Total Tickets',
                            data: ticketData.map(agent => agent.value)
                        },
                        {
                            name: 'Processing Time (hours)',
                            data: processData.map(agent => agent.value)
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    title: {
                        text: formattedSubDivision ? 'Agent Performance - ' + formattedSubDivision : 'Agent Performance',
                        align: 'center'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: ticketData.map(agent => agent.name),
                    },
                    yaxis: {
                        title: {
                            text: 'Value'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val;
                            }
                        }
                    }
                }).render();
            }
        }

        // Tambahkan event listener untuk resize
        window.addEventListener('resize', () => {
            for (var subDivision in jsonData) {
                if (jsonData.hasOwnProperty(subDivision)) {
                    var columnChart = ApexCharts.getChartByID('columnChart_' + subDivision.replace(/\s+/g, '_'));
                    if (columnChart) columnChart.resize();
                }
            }
        });
    });
</script>
<!-- End Column Chart -->
