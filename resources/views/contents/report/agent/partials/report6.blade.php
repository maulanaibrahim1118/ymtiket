<div class="col-12 pb-0">
    <div class="row">
        <div class="col-12">
            <!-- Pie Chart -->
            <div id="pieChart" style="min-height: 350px;" class="echart"></div>
        </div>
        <div class="col-12">
            <!-- Pie Chart -->
            <div id="pieChart2" style="min-height: 350px;" class="echart"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        var jsonData = {!! $jsonData !!};

        // Fungsi untuk mengubah setiap kata menjadi huruf kapital
        function capitalizeWords(string) {
            return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // Proses data untuk pie chart
        var chartData = jsonData.map(data => ({
            value: data.jml_ticket,
            name: capitalizeWords(data.nama_agent) // Memanggil fungsi untuk mengonversi setiap kata menjadi huruf kapital
        }));

        // Inisialisasi pie chart menggunakan data yang sudah diproses
        echarts.init(document.querySelector("#pieChart")).setOption({
            title: {
                text: 'Performa Agent',
                subtext: 'Berdasarkan Jumlah Tiket',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c} tiket ({d}%)'
            },
            // legend: {
            //     orient: 'vertical',
            //     left: 'left',
            //     data: jsonData.map(data => capitalizeWords(data.nama_agent))
            // },
            series: [{
                name: 'Jumlah Tiket',
                type: 'pie',
                radius: '50%',
                data: chartData,
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
    });
</script>

{{-- <div class="col-12 border-bottom mb-4"></div>

<div class="col-12 pb-0">
    <!-- Pie Chart -->
    <div id="pieChart2" style="min-height: 350px;" class="echart"></div>
</div> --}}

{{-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        var jsonData = {!! $jsonData !!};

        // Fungsi untuk mengubah setiap kata menjadi huruf kapital
        function capitalizeWords(string) {
            return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // Mengelompokkan data berdasarkan sub divisi
        var groupedData = jsonData.reduce((acc, data) => {
            if (!acc[data.sub_divisi]) {
                acc[data.sub_divisi] = [];
            }
            acc[data.sub_divisi].push({
                value: data.jml_ticket,
                name: capitalizeWords(data.nama_agent)
            });
            return acc;
        }, {});

        var chartsContainer = document.getElementById('pieChart');

        // Membuat elemen dan inisialisasi pie chart untuk setiap sub divisi
        Object.keys(groupedData).forEach(subDivisi => {
            // Membuat elemen chart
            var chartElement = document.createElement('div');
            chartElement.className = 'mychart';
            chartsContainer.appendChild(chartElement);

            // Inisialisasi pie chart menggunakan data yang sudah diproses
            echarts.init(document.querySelector("#pieChart")).setOption({
                title: {
                    text: `Performa Agent - ${capitalizeWords(subDivisi)}`,
                    subtext: 'Berdasarkan Jumlah Tiket',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b} : {c} ({d}%)'
                },
                series: [{
                    name: 'Jumlah Tiket',
                    type: 'pie',
                    radius: '50%',
                    data: groupedData[subDivisi],
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
        });
    });
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {
        var jsonData = {!! $jsonData !!};

        // Fungsi untuk mengubah setiap kata menjadi huruf kapital
        function capitalizeWords(string) {
            return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // Proses data untuk pie chart
        var chartData = jsonData.map(data => ({
            value: data.jml_process,
            name: capitalizeWords(data.nama_agent) // Memanggil fungsi untuk mengonversi setiap kata menjadi huruf kapital
        }));

        // Inisialisasi pie chart menggunakan data yang sudah diproses
        echarts.init(document.querySelector("#pieChart2")).setOption({
            title: {
                text: 'Performa Agent',
                subtext: 'Berdasarkan Waktu Proses Tiket',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c} detik ({d}%)'
            },
            // legend: {
            //     orient: 'vertical',
            //     left: 'left',
            //     data: jsonData.map(data => capitalizeWords(data.nama_agent))
            // },
            series: [{
                name: 'Waktu Proses Tiket',
                type: 'pie',
                radius: '50%',
                data: chartData,
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
    });
</script>
<!-- End Pie Chart -->