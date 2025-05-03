@extends('template.dashboard');

@section('content')
    @include('dashboard.card')
    @include('dashboard.chart')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        let chart;

        const categories = @json($months);
        // const series = @json($series);
        const series = [
            {
                name: 'Gedung',
                data: [5, 2, 3, 4, 5, 3, 6, 10, 8, 7, 5, 8]
            },
            {
                name: 'Publikasi',
                data: [6, 6, 2, 7, 8, 4, 2, 8, 10, 6, 8, 5]
            }
        ];

        function loadData(timeframe) {
            const options = {
                series: series,
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: true,
                        tools: {
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                colors: ['#22c55e', '#ff5722'],
                stroke: {
                    curve: 'smooth',
                    width: 3,
                },
                xaxis: {
                    categories: categories,
                    title: {
                        text: 'Bulan'
                    }
                },
                tooltip: {
                    theme: 'light'
                },
                title: {
                    text: 'Grafik Pesanan Gedung dan Publikasi',
                    align: 'center'
                }
            };

            if (chart) {
                chart.updateOptions(options);
            } else {
                chart = new ApexCharts(document.querySelector("#overview"), options);
                chart.render();
            }
        }

        loadData('thisYear');
    </script>
    <script>
        // const chartData = @json($chartData);
        const chartData = [10, 8, 20, 30, 5];

        var options = {
            chart: {
                type: 'bar',
                height: 338,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Jumlah',
                data: chartData
            }],
            xaxis: {
                categories: ['Menunggu Konfirmasi', 'Dikonfirmasi', 'Belum Bayar', 'Lunas', 'Ditolak'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false
                }
            },
            yaxis: {
                show: false
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    distributed: true,
                    dataLabels: {
                        position: 'start'
                    }
                }
            },
            dataLabels: {
                enabled: false,
            },
            grid: {
                show: false
            },
            legend: {
                show: false
            },
            colors: ['#ff9f43', '#0d6efd', '#ff9f43', '#22c55e', '#ef4d56'],
        };

        var chart2 = new ApexCharts(document.querySelector("#status"), options);
        chart2.render();
    </script>
@endpush
