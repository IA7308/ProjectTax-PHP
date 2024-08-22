<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Component</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script> -->
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar bg-body-tertiary fixed-bottom">
        <div class="container-fluid justify-content-start">
            <!-- Menu Utama -->
            <button class="navbar-brand navbar-toggler" type="button" data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" data-bs-backdrop="false">
    <span class="navbar-toggler-icon"></span>
</button>

<!-- Offcanvas Menu -->
<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasNavbar"
    aria-labelledby="offcanvasNavbarLabel" data-bs-backdrop="true">
    <div class="offcanvas-header"> 
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AKT VERSION 1.0</h5>
        <button type="button" class="btn navbar-toggler-icon" data-bs-dismiss="offcanvas" data-bs-backdrop="false"
            aria-label="Close"></button>
        </div>
                <div class="offcanvas-body">
                        <ul class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <b>AKUNTANSI</b>
                            </a>
                                <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                                    <li><a class="dropdown-item" href="/beranda"><b>COA</b></a></li>
                                    <li><a class="dropdown-item" href="/cTambahData"><b>TAMBAH DATA COA</b></a></li>
                                    <li><a class="dropdown-item" href="/jurnal"><b>JURNAL</b></a></li>
                                    <li><a class="dropdown-item" href="/jTambahData"><b>TAMBAH DATA JURNAL</b></a></li>
                                    <li><a class="dropdown-item" href="/penyesuaian"><b>PENYESUAIAN</b></a></li>
                                    <li><a class="dropdown-item" href="/pTambahData"><b>TAMBAH DATA PENYESUAIAN</b></a></li>
                                    <li><a class="dropdown-item" href="/bukubesar/{{session('idDataterpilih')}}"><b>BUKU BESAR</b></a></li>
                                    <li><a class="dropdown-item" href="/neracalajur"><b>NERACA LAJUR</b></a></li>
                                    <li><a class="dropdown-item" href="/konsep"><b>KONSEP</b></a></li>
                                    <li><a class="dropdown-item" href="/laporanneraca"><b>NERACA</b></a></li>
                                    <li><a class="dropdown-item" href="/labarugi"><b>RUGILABA</b></a></li>
                                </ul>
                        </ul>
                        <ul class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <b>STOCK BARANG</b>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                                <li><a class="dropdown-item" href="/stock"><b>BARANG MASUK</b></a></li>
                                <li><a class="dropdown-item" href="/inputstock"><b>INPUT BARANG MASUK</b></a></li>
                                <li><a class="dropdown-item" href="/stock-out"><b>BARANG KELUAR</b></a></li>
                                <li><a class="dropdown-item" href="/inputstockkeluar"><b>INPUT BARANG KELUAR</b></a></li>
                                <li><a class="dropdown-item" href="/resume"><b>RESUME</b></a></li>
                            </ul>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>

<style>
    .navbar-nav .nav-item:hover {
        background-color: #666 ; 
    }
</style>