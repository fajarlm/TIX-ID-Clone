@extends('templates.app')

@section('content')
    <div class="container mt-5">
        {{-- <h5>Grafik Pembelian</h5> --}}
        <div class="row">
            <div class="col-6">
                <h5>Data Pembelian Tiket Bulan {{ now()->format('F') }}</h5>
                <canvas id="chartBar"></canvas>
            </div>
            <div class="col-6 ">
                <h5>Data Film Sesuai Status {{ now()->format('F') }}</h5>
                {{-- <canvas id="chartBarActive" style="width: 300px;
            height:300px; margin: auto;"></canvas> --}}
                <canvas id="chartPie" class="w-75 h-75 p-auto m-auto"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let labelBar = [];
        let dataBar = [];
        let labelPie = [];
        let dataPie = [];

        $(function() {
            $.ajax({

                url: "{{ route('admin.ticket.charts') }}",
                method: "GET",
                success: function(res) {
                    // console.log(res);
                    const ctx = document.getElementById('chartBar').getContext('2d');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: res.label, // ini labelBar
                            datasets: [{
                                label: 'Jumlah Pembelian',
                                data: res.data, // ini dataBar
                                borderWidth: 1,

                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });


                    const canvas = document.getElementById('chartPie').getContext('2d');
                    let colorRandom = res.label.map(() => {
                        let r = Math.floor((Math.random() * 200) + 100)
                        let g = Math.floor((Math.random() * 200) + 100)
                        let b = Math.floor((Math.random() * 200) + 100)
                        return `rgb(${r}, ${g}, ${b})`
                    })
                    new Chart(canvas, {
                        type: 'pie',
                        data: {
                            labels: ['Active', 'Tidak Active'], // ini labelBar
                            datasets: [{
                                label: res.labelActive,
                                data: res.dataActive, // ini dataBar
                                borderWidth: 1,
                                backgroundColor: colorRandom
                            }]
                        },
                        options: {
                            responsive: true,
                           
                        }
                    });

                },
                error: function(err) {
                    console.log(err);
                    alert('gagal mengambil data chart Pie');
                }
            })
            // $.ajax({
            //     url: "{{ route('admin.movies.chart') }}",
            //     method: "GET",
            //     success: function(res) {
            //         // console.log(res);

            //         labelPie = res.data.label;
            //         dataPie = res.data.data;

            //         const ctxpie = document.getElementById('chartPie').getContext('2d');

            //                             let colorRandom = res.label.map(() => {
            //             let r = Math.floor((Math.random() * 200) + 100)
            //             let g = Math.floor((Math.random() * 200) + 100)
            //             let b = Math.floor((Math.random() * 200) + 100)
            //             return `rgb(${r}, ${g}, ${b})`
            //         })

            //         new Chart(ctxpie, {
            //             type: 'pie',
            //             data: {
            //                 labels: res.label, // ini labelBar
            //                 datasets: [{
            //                     label: 'Jumlah Pembelian',
            //                     data: res.data, // ini dataBar
            //                     borderWidth: 1,

            //                 }]
            //             },
            //             options: {
            //                 responsive: true,
            //                 scales: {
            //                     y: {
            //                         beginAtZero: true
            //                     }
            //                 }
            //             }
            //         });
            // }})
        })
    </script>
@endpush
