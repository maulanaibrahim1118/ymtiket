<div id="chartsContainer" class="charts-container"></div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        var jsonData = {!! $jsonData !!};
        var chartsContainer = document.getElementById('chartsContainer');

        // Fungsi untuk mengubah setiap kata menjadi huruf kapital
        function capitalizeWords(string) {
            return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        for (var subDivision in jsonData) {
            if (jsonData.hasOwnProperty(subDivision)) {
                var formattedSubDivision = subDivision !== 'tidak ada' ? capitalizeWords(subDivision) : '';

                // Buat container untuk chart jml_ticket
                var ticketContainer = document.createElement('div');
                ticketContainer.className = 'chart-container';
                ticketContainer.id = 'ticketChart_' + subDivision.replace(/\s+/g, '_');
                chartsContainer.appendChild(ticketContainer);

                var ticketChart = echarts.init(ticketContainer);
                ticketChart.setOption({
                    title: {
                        text: formattedSubDivision ? 'Agent Performance - ' + formattedSubDivision : 'Agent Performance',
                        subtext: formattedSubDivision ? 'Based on Total Tickets' : 'Based on Total Tickets',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} tickets ({d}%)'
                    },
                    // legend: {
                    //     orient: 'vertical',
                    //     left: 'left'
                    // },
                    series: [{
                        name: 'Total Tickets',
                        type: 'pie',
                        radius: '50%',
                        data: jsonData[subDivision]['tickets'].map(agent => ({
                            value: agent.value,
                            name: capitalizeWords(agent.name)
                        })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label: {
                            formatter: '{b}: {d}%'
                        }
                    }]
                });

                // Buat container untuk chart jml_process
                var processContainer = document.createElement('div');
                processContainer.className = 'chart-container';
                processContainer.id = 'processChart_' + subDivision.replace(/\s+/g, '_');
                chartsContainer.appendChild(processContainer);

                var processChart = echarts.init(processContainer);
                processChart.setOption({
                    title: {
                        text: formattedSubDivision ? 'Agent Performance - ' + formattedSubDivision : 'Agent Performance',
                        subtext: formattedSubDivision ? 'Based on Ticket Processing Time' : 'Based on Ticket Processing Time',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} seconds ({d}%)'
                    },
                    // legend: {
                    //     orient: 'vertical',
                    //     left: 'left'
                    // },
                    series: [{
                        name: 'Processing Time',
                        type: 'pie',
                        radius: '50%',
                        data: jsonData[subDivision]['process'].map(agent => ({
                            value: agent.value,
                            name: capitalizeWords(agent.name)
                        })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label: {
                            formatter: '{b}: {d}%'
                        }
                    }]
                });
            }
        }

        // Tambahkan event listener untuk resize
        window.addEventListener('resize', () => {
            for (var subDivision in jsonData) {
                if (jsonData.hasOwnProperty(subDivision)) {
                    var ticketChart = echarts.getInstanceByDom(document.getElementById('ticketChart_' + subDivision.replace(/\s+/g, '_')));
                    var processChart = echarts.getInstanceByDom(document.getElementById('processChart_' + subDivision.replace(/\s+/g, '_')));
                    if (ticketChart) ticketChart.resize();
                    if (processChart) processChart.resize();
                }
            }
        });
    });
</script>