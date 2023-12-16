@extends('layout.layout')
@section('content')

    <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <h5 class="mt-5"> Data Produksi Karet Kering</h5>
                <a href="{{ route('.produksi.import') }}" class="btn bg-gradient-success mt-3">Import</a>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered align-items-center mb-0">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tahun</th>
                            <th>Produksi</th>
                            <th>Aksi</th>
                        </tr>
                        <tbody>
                            @foreach ($produksi as $key => $val )
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ $val->category->name }}</td>
                                    <td>{{ $val->tahun }}</td>
                                    <td>{{ $val->jumlah }}</td>
                                    <td class="text-center">
                                        <button data-id="{{ $val->id }}" data-id-categories="{{ $val->id_categories }}" data-tahun="{{ $val->tahun }}"
                                            data-nama="{{ $val->category->name }}"
                                            data-jumlah="{{ $val->jumlah }}"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm bg-gradient-info editBtn">Edit</button>
                                        <button class="btn btn-sm bg-gradient-danger btnHapus"
                                        data-id="{{ $val->id }}"
                                        >Hapus</button>

                                    </td>
                                </tr>
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="">
                    Nama
                </label>
                <input type="hidden" id="form_id"  class="form-control">
                <input type="hidden" id="form_id_categories"  class="form-control">
                <input type="text" name="nama" disabled class="form-control">
              </div>
              <div class="form-group">
                <label for="">
                    Tahun
                </label>
                <input type="number" name="tahun" id="tahun"  class="form-control">
              </div>
              <div class="form-group">
                <label for="">
                    Jumlah
                </label>
                <input type="number" name="jumlah" id="jumlah"  class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="button" id="simpanEdit" class="btn bg-gradient-primary">Simpan</button>
            </div>
          </div>
        </div>
      </div>
    
@endsection

@section('scripts')
      <script>
            $(document).ready(function(){
                $('.editBtn').on('click', function(){

                    var nama = $(this).attr('data-nama');
                    var tahun = $(this).attr('data-tahun');
                    var jumlah = $(this).attr('data-jumlah');
                    var id = $(this).attr('data-id');
                    var id_categories = $(this).attr('data-id-categories');
                     $("#form_id").val(id);
                     $("#form_id_categories").val(id_categories);
                     $("[name='nama']").val(nama);
                     $("[name='tahun']").val(tahun);
                     $("[name='jumlah']").val(jumlah);
                })
            })

            $(document).on('click', '.btnHapus', function(){
                let token = $("meta[name='csrf-token']").attr("content");
                var id = $(this).attr('data-id');

                if(id != ''){
                    Swal.fire({
                        title: 'Yakin Ingin Hapus?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: "Batal",
                        denyButtonText: `Batal`,
                        confirmButtonText: 'Hapus',
                        focusConfirm:false,
                        focusCancel:false,
                        focusDeny:false,
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-warning rounded-pill ',
                            cancelButton: 'btn btn-light btn-outline-secondary rounded-pill border border-secondary '
                        }
                    }).then((result) => {
                            if(result.isConfirmed){
                                var route = "{{ route('.produksi.delete', ':id') }}";
                                route = route.replace(':id', id);
                                $.ajax({
                                        type:'GET',
                                        dataType: 'json',
                                        url: route,
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
                            }else{
                            }
                    })
                }
              

            })

            $(document).on('click', '#simpanEdit', function(){
                let token = $("meta[name='csrf-token']").attr("content");
                   var id = $("#form_id").val();
                   var id_categories = $("#form_id_categories").val();
                     var tahun =  $("[name='tahun']").val();
                     var jumlah = $("[name='jumlah']").val();

                     if(tahun == '' || jumlah == ''){
                        Swal.fire( {title: "Gagal", icon:'error', text: "Lengkapi data"})
                     }

                     $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url: "{{ route('.produksi.editProduksi') }}",
                        data: {
                            _token:token,
                            id:id,
                            id_categories:id_categories,
                            tahun:tahun,
                            jumlah:jumlah,
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
                                                location.reload();
                                            }else{
                                                location.reload();
                                            }
                                        })
                            }
                        }
                     })
            })
      </script>
@endsection