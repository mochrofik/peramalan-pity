
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <title>
    Sistem Peramalan Produksi Perkebunan Besar
  </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class= " navbar-brand m-0" href=" " target="_blank">
        <span class="ms-1 font-weight-bold">Dashboard</span>
      </a>
    </div>

    @php
         
    $route = Route::current()->getName();
    $cek = explode('.', $route);

    $nama_route = '';
    if(count($cek) > 1 ){
        $nama_route = $cek[1];
    }else{
        $nama_route = $cek[0];
    }

    @endphp
    <hr class="horizontal dark mt-0">
    <div class=" navbar-collapse w-auto h-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#masterPegawaiPages" class="nav-link {{ $nama_route == 'produksi' ? 'active' : '' }} border rounded border-primary {{ \Request::route()->getPrefix() == 'produksi' ? 'active' : '' }}" href="./pages/dashboard.html">
            <span class="nav-link-text ms-1">Data Produksi</span>
          </a>
          <div class="collapse {{ $nama_route == 'produksi' ? 'show' : '' }}"   id="masterPegawaiPages">
          <ul class="nav ms-4 ps-2">
            <li class="nav-item ">
                <a class="nav-link {{ \Request::route()->getName() == '.produksi.karetKering' ? 'active' : '' }}"
                    href="{{ route('.produksi.karetKering') }}">
                    <span class="sidenav-normal"> Karet Kering </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route()->getName() == '.produksi.minyakSawit' ? 'active' : '' }} "
                    href="{{ route('.produksi.minyakSawit') }}">
                    <span class="sidenav-normal"> Minyak Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route()->getName() == '.produksi.bijiSawit' ? 'active' : '' }} "
                    href="{{ route('.produksi.bijiSawit') }}">
                    <span class="sidenav-normal"> Biji Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route()->getName() == '.produksi.teh' ? 'active' : '' }} "
                    href="{{ route('.produksi.teh') }}">
                    <span class="sidenav-normal"> Teh </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route()->getName() == '.produksi.gulaTebu' ? 'active' : '' }}"
                    href="{{ route('.produksi.gulaTebu') }}">
                    <span class="sidenav-normal"> Gula Tebu </span>
                </a>
            </li>
          </ul>
          </div>
        </li>
        <li class="nav-item mt-2">
          <a data-bs-toggle="collapse" href="#holtES" class="nav-link  border rounded border-primary {{ $nama_route == 'peramalanHolts' ? 'active' : '' }}" href="./pages/dashboard.html">
            <span class="nav-link-text ms-1">Peramalan Holt's ES</span>
          </a>
          <div class="collapse {{ $nama_route == 'peramalanHolts' ? 'show' : '' }}"  id="holtES">
          <ul class="nav ms-4 ps-2">
            <li class="nav-item">
                <a class="nav-link {{ \Request::route('category') == 'Karet Kering' ? 'active' : '' }}"
                    href="{{ route('.peramalanHolts.index','Karet Kering') }}">
                    <span class="sidenav-normal"> Karet Kering </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route('category') == 'Minyak Sawit' ? 'active' : '' }}"
                    href="{{ route('.peramalanHolts.index','Minyak Sawit') }}">
                    <span class="sidenav-normal"> Minyak Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route('category') == 'Biji Sawit' ? 'active' : '' }}"
                    href="{{ route('.peramalanHolts.index','Biji Sawit') }}">
                    <span class="sidenav-normal"> Biji Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route('category') == 'Teh' ? 'active' : '' }}"
                    href="{{ route('.peramalanHolts.index','Teh') }}">
                    <span class="sidenav-normal"> Teh </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Request::route('category') == 'Gula Tebu' ? 'active' : '' }}"
                    href="{{ route('.peramalanHolts.index','Gula Tebu') }}">
                    <span class="sidenav-normal"> Gula Tebu </span>
                </a>
            </li>
          </ul>
          </div>
        </li>
        <li class="nav-item mt-2">
          <a data-bs-toggle="collapse" href="#winterES" class="nav-link  border rounded border-primary" href="./pages/dashboard.html">
            <span class="nav-link-text ms-1">Peramalan Winter ES</span>
          </a>
          <div class="collapse"  id="winterES">
          <ul class="nav ms-4 ps-2">
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route('.peramalanWinter.index', 'Karet Kering') }}">
                    <span class="sidenav-normal"> Karet Kering </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Minyak Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Biji Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Teh </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Gula Tebu </span>
                </a>
            </li>
          </ul>
          </div>
        </li>
        <li class="nav-item mt-2">
          <a data-bs-toggle="collapse" href="#perbandingan" class="nav-link border rounded border-primary  active" href="./pages/dashboard.html">
            <span class="nav-link-text ms-1 col-2">Perbandingan Hasil <br>
                Peramalan</span>
          </a>
          <div class="collapse"  id="perbandingan">
          <ul class="nav ms-4 ps-2">
            <li class="nav-item">
                <a class="nav-link"
                    href="">
                    <span class="sidenav-normal"> Karet Kering </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Minyak Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Biji Sawit </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Teh </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="">
                    <span class="sidenav-normal"> Gula Tebu </span>
                </a>
            </li>
          </ul>
          </div>
        </li>
        
      </ul>
    </div>

  </aside>