@extends('layout.layout')
@section('content')

    <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <h5 class="mt-5 text-center"> Peramalan Produksi @php
                    if($category == 1){
                        echo 'Karet Kering';
                    }
                    if($category == 2){
                        echo 'Minyak Sawit';

                    }
                    if($category == 3){
                        echo 'Biji Sawit';
                    }
                    if($category == 4){
                        
                        echo 'Teh';
                    }
                    if($category == 5){
                        echo 'Gula Tebu';

                    }
                @endphp</h5>
                <div class="d-flex  d-flex-row justify-content-start">
                    <div>
                        <input type="number" min="0.1" max="0.9" step="any" style="width:100px" placeholder="Alpha" id="alpha" class="form-control mt-5 ms-2">
                    </div>
                    <div>
                        <input type="number" step="any" style="width:100px" placeholder="Beta" id="beta" class="form-control mt-5 ms-2">
                    </div>
                    <div>
                        <input type="number" step="any" style="width:100px" placeholder="Gamma" id="gamma" class="form-control mt-5 ms-2">
                    </div>
                    <div>
                        <button class="btn bg-gradient-success mt-5 ms-3" id="hitung" data-category = "{{ $category }}" >Hitung Peramalan</button>
                    </div> 
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <h6>Metode Winters Exponentian Smoothing</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tahun</th>
                                    <th>Produksi</th>
                                    <th>X(L-t)-Xt</th>
                                    <th>st</th>
                                    <th>Bt</th>
                                    <th>lmt-L</th>
                                    <th>lmt</th>
                                    <th>Forecast</th>
                                    <th>error</th>
                                    <th>error2</th>
                                    <th>Smape</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($peramalan == null)
                                    <tr>
                                        <td>Tidak ada data</td>
                                    </tr>
                                @endif
                                @foreach ($peramalan as $key => $val )
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $val->category->name }}</td>
                                        <td>{{ $val->tahun }}</td>
                                        <td>{{ number_format($val->produksi, 2) }}</td>
                                        <td>{{  number_format($val->xt,2) }}</td>
                                        <td>{{  number_format($val->st,2) }}</td>
                                        <td>{{ number_format($val->bt,  2)  }}</td>
                                        <td>{{  number_format($val->lmt_l,2) }}</td>
                                        <td>{{  number_format($val->lmt,2) }}</td>
                                        <td>{{  number_format($val->forecast,2) }}</td>
                                        <td>{{  number_format($val->error,2) }}</td>
                                        <td>{{  number_format($val->error2,2) }}</td>
                                        <td>{{  number_format($val->smape,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <table class="table table-bordered mt-3" style="width: 200px">
                        <tr>
                            <td>Alpha</td>
                            <td>{{ $akurasi->alpha ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Beta</td>
                            <td>{{ $akurasi->beta ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Gamma</td>
                            <td>{{ $akurasi->beta ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>SMAPE</td>
                            <td>{{ $akurasi->smape ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Akurasi</td>
                            <td>{{ $akurasi->akurasi ?? 0 }}</td>
                        </tr>
                    </table>

                    <div class="card mb-3 mt-3">
                        <div class="card-body p-3">
                          <div class="chart">
                            <canvas id="line-chart" class="chart-canvas" height="300px"></canvas>
                          </div>
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
            $('#hitung').on('click', function(){
                var category = $(this).attr('data-category');
                var alpha = $('#alpha').val();
                var beta = $('#beta').val();
                var gamma = $('#gamma').val();

                if(alpha == '' || beta == '' || gamma == ''){
                    Swal.fire({
                        title: "Lengkapi data kosong",
                        icon:'error',
                    })
                }else{

                    var cek = parseFloat(alpha);

                    if(cek <= 0 || cek >= 1){
                        Swal.fire({
                            title: "Alpha tidak boleh lebih dari 0.9 dan kurang dari 0",
                            icon:'error',
                        })
                    }else{
                    var route = "{{ route('.peramalanWinter.hitungWinter', ':category') }}";
                        route = route.replace(':category', category);
    
                    $.ajax({
                        type:'get',
                        dataType:'json',
                        url: route,
                        data: {
                            alpha : alpha,
                            beta : beta,
                            gamma : gamma,
                        },
                        beforeSend:function(){
                            Swal.fire({
                                    title: 'Mohon tunggu !!!',
                                    html: 'Sedang memproses data',// add html attribute if you want or remove
                                    allowOutsideClick: false,
                                    onBeforeOpen: () => {
                                        Swal.showLoading()
                                    },
                                    })
                        },
                        success:function(response){
                            if(response.status == 200){
                                                Swal.fire(
                                                    'Berhasil!', 
                                                    response.message,
                                                    'success').then((result) => {
                                                            if(result.isConfirmed){
                                                                location.reload();
                                                            }else{
                                                                location.reload();
                                                            }
                                                        })
                                            }else{
                                                Swal.fire(
                                                    'Gagal!', 
                                                    response.message,
                                                    'error').then((result) => {
                                                            if(result.isConfirmed){
                                                            }else{
                                                            }
                                                        })
                                            }
                        }
                    })

                }

                }

            })
        });


        $(document).ready(function(){
            var category = "{{ $category }}";
            var route = "{{ route('.peramalanWinter.chartWinters', ':category') }}";
            route = route.replace(':category', category);

            var ctx1 = document.getElementById("line-chart").getContext("2d");

            $.ajax({
                type:'GET',
                dataType:'json',
                url: route,
                success: function(response){
                    if(response.status == 200){

                        const tahun = response['data'].map(function(item){
                            return item.tahun;
                        })
                        const produksi = response['data'].map(function(item){
                            return item.produksi;
                        })
                        const forecast = response['data'].map(function(item){
                            return item.forecast;
                        })
                        const color = response['data'].map(function(item){
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
                            backgroundColor: ["#d63384"],
                            borderColor: ["#d63384"],
                            borderWidth: 1
                            },
                            {
                            label: 'Forecast',
                            data: forecast,
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