@extends('layout.layout')
@section('content')

    <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <h5 class="mt-5 text-center"> Perbandingan Peramalan Produksi @php
                    if($category == 10){
                        echo 'Karet Kering';
                    }
                    if($category == 20){
                        echo 'Minyak Sawit';

                    }
                    if($category == 30){
                        echo 'Biji Sawit';
                    }
                    if($category == 40){
                        
                        echo 'Teh';
                    }
                    if($category == 50){
                        echo 'Gula Tebu';

                    }
                @endphp</h5>
                <div class="row">
                    <div class="text-center">
                        <h6>Metode Holts Exponential Smoothing dan Winters Exponential Smoothing</h6>
                    </div>

                    <div class="card mb-3 mt-3">
                        <div class="card-body p-3">
                          <div class="chart">
                            <canvas id="line-chart" class="chart-canvas" height="300px"></canvas>
                          </div>
                        </div>
                      </div>

                      <div class="col-12 ">
                        <div class="d-flex d-flex-row justify-content-center">

                            <table class="table table-row-bordered" style="width: 200px">
                                <tr>
                                <th>Metode Holts</th>
                                <th>-</th>
                                <th>Metode Winter</th>
                            </tr>
                            <tr>
                                <th>{{ $akurasi_holts->smape ?? 0 }}</th>
                                <th>SMAPE</th>
                                <th>{{ $akurasi_winter->smape ?? 0 }}</th>
                            </tr>
                            <tr>
                                <th>{{ $akurasi_holts->akurasi ?? 0 }}</th>
                                <th>Akurasi</th>
                                <th>{{ $akurasi_winter->akurasi ?? 0 }}</th>
                            </tr>
                            </table>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function(){
            var category = "{{ $category }}";
            var id = 0;

            if(category == 10){
                id = 1;
            }else if(category ==20){
                id = 2;
            }else if(category == 30){
                id = 3;
            }else if(category == 40){
                id = 4;
            
            }else if(category == 50){
                id = 5;
            }
            var route = "{{ route('.perbandingan.chartPerbandingan') }}";
            

            var ctx1 = document.getElementById("line-chart").getContext("2d");
            let token = $("meta[name='csrf-token']").attr("content");
              
            $.ajax({
                type:'POST',
                dataType:'json',
                url: route,
                data: {
                    _token:token,
                    id_categories: id,
                },
                success: function(response){
                    if(response.status == 200){

                        const holts = response['data']['holts'].map(function(item){
                            return item.forecast;
                            
                        }) 
                        const produksi = response['data']['holts'].map(function(item){
                            return item.produksi;
                        })
                        const tahun = response['data']['holts'].map(function(item){
                            return item.tahun;
                        })
                        const winter = response['data']['winter'].map(function(item){
                            return item.forecast;
                        })
                        const color = response['data']['holts'].map(function(item){
                                var r = Math.floor(Math.random() * 255);
                                var g = Math.floor(Math.random() * 255);
                                var b = Math.floor(Math.random() * 255);
                                return "rgb(" + r + "," + g + "," + b + ")";
                        })

                        const data = {
                        labels: tahun,
                        datasets: [
                            {
                            label: 'Produksi',
                            data: produksi,
                            backgroundColor: ["orange"],
                            borderColor: ["orange"],
                            borderWidth: 1
                            },
                            {
                            label: 'Holts Exponential Smoothing',
                            data: holts,
                            backgroundColor: ["#d63384"],
                            borderColor: ["#d63384"],
                            borderWidth: 1
                            },
                            {
                            label: 'Winter Exponential Smoothing',
                            data: winter,
                            borderColor: ["#98ec2d"],
                            backgroundColor: ["#98ec2d"],
                            borderWidth: 1
                            }
                            ]
                        };
                        new Chart(ctx1, {
                        type: "line",
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                            legend: {
                                display: true,
                            }
                            },
                            interaction: {
                            intersect: false,
                            mode: 'index',
                            },
                            
                            },
                        });

                    }
                }

            })
        })

    
    </script>
@endsection