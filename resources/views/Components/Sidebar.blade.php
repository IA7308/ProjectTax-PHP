<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Component</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
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
            <!-- isi Menu -->
            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-5" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel" data-bs-backdrop="true">
                <div class="offcanvas-header"> 
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AKT VERSION 1.0</h5>
                    <button type="button" class="btn navbar-toggler-icon" data-bs-dismiss="offcanvas" data-bs-backdrop="false"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/beranda">BERANDA</a>
                        </li>
                        <!-- DATA KARYAWAN -->
                        <li class="nav-item">
                            <a class="nav-link" href="/jurnal">Jurnal</a>
                        </li>
                        <li class="nav-item">
                            <a href="/jTambahData" class="nav-link">Tambah Data Jurnal</a>
                        </li>
                        <li class="nav-item">
                            <a href="/cTambahData" class="nav-link">Tambah Data COA</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bukubesar" class="nav-link">Buku Besar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>
