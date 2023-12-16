@extends('layout.layout')
@section('content')

<div class="col-12">
    <div class="card" style="height: 300px">
        <div class="card-body">
            @if( Session::has( 'success' ))
            <div class="alert alert-success text-white mt-2" role="alert">
                <strong>Berhasil!</strong> {{ Session::get('success') }}
            </div>
            @elseif( Session::has( 'warning' ))
            <div class="alert alert-warning text-white mt-2" role="alert">
                <strong>Perhatian!</strong> {{ Session::get('warning') }}
            </div>
            @elseif( Session::has( 'error' ))
            <div class="alert alert-danger text-white mt-2" role="alert">
                <strong>Perhatian!</strong> {{ Session::get('error') }}
            </div>
            @endif
            <form enctype="multipart/form-data" action="{{ route('.produksi.actionImport') }}" method="POST" >
                @csrf
                <div class="form-group">
                    <label for="Import">Import Data Produksi</label>
                    <input type="file" required name="file" class="form-control" >
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="{{ asset('template-import.xlsx') }}" target="_blank"  class="btn btn-success">Download Template</a>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

