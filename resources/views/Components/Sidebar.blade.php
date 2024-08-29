<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Component</title>
    <!-- Tambahkan CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleajah.css">
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .navbar-toggler-icon {
            background-image: url('https://i0.wp.com/cvsolusikita.com/wp-content/uploads/2024/03/cv-solusi-kita-1.jpeg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            width: 30px;  /* Sesuaikan ukuran lebar */
            height: 30px; /* Sesuaikan ukuran tinggi */
            border: none; /* Menghapus border default jika ada */
        }
    </style>


</head>

<body>
    <nav class="navbar bg-body-tertiary fixed-bottom">
        <div class="container-fluid justify-content-start">
            <!-- Menu Utama -->
            <button class="navbar-brand navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Offcanvas Menu -->
            <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AKT VERSION 1.0</h5>
                    <button type="button" class="btn navbar-toggler-icon" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-line"></i> <b>AKUNTANSI</b>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                                <li><a class="dropdown-item" href="/beranda"><b>COA</b></a></li>
                                <li><a class="dropdown-item" href="/cTambahData"><b>TAMBAH DATA COA</b></a></li>
                                <li><a class="dropdown-item" href="/jurnal"><b>JURNAL</b></a></li>
                                <li><a class="dropdown-item" href="/jTambahData"><b>TAMBAH DATA JURNAL</b></a></li>
                                <li><a class="dropdown-item" href="/penyesuaian"><b>PENYESUAIAN</b></a></li>
                                <li><a class="dropdown-item" href="/pTambahData"><b>TAMBAH DATA PENYESUAIAN</b></a></li>
                                <li><a class="dropdown-item" href="/bukubesar/{{session('idDataterpilih')}}"><b>BUKU
                                            BESAR</b></a></li>
                                <li><a class="dropdown-item" href="/neracalajur"><b>NERACA LAJUR</b></a></li>
                                <li><a class="dropdown-item" href="/konsep"><b>KONSEP</b></a></li>
                                <li><a class="dropdown-item" href="/laporanneraca"><b>NERACA</b></a></li>
                                <li><a class="dropdown-item" href="/labarugi"><b>RUGILABA</b></a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="#" id="stockDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-box"></i> <b>STOCK BARANG</b>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="stockDropdown">
                                <li><a class="dropdown-item" href="/resume"><b>RESUME</b></a></li>
                                <li><a class="dropdown-item" href="/stock"><b>BARANG MASUK</b></a></li>
                                <li><a class="dropdown-item" href="/inputstock"><b>INPUT BARANG MASUK</b></a></li>
                                <li><a class="dropdown-item" href="/stock-out"><b>BARANG KELUAR</b></a></li>
                                <li><a class="dropdown-item" href="/inputstockkeluar"><b>INPUT BARANG KELUAR</b></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function (dropdown) {
                dropdown.addEventListener('click', function () {
                    event.stopPropagation();
                    var menu = this.nextElementSibling;                    
                    if (menu.classList.contains('show')) {
                        menu.classList.remove('show');
                    } else {
                        // Tutup dropdown lain yang terbuka
                        dropdowns.forEach(function (otherDropdown) {
                            var otherMenu = otherDropdown.nextElementSibling;
                            if (otherMenu !== menu) {
                                otherMenu.classList.remove('show');
                            }
                        });
                        menu.classList.add('show');
                    }
                });
            });

            // Tutup dropdown ketika klik di luar menu
            document.addEventListener('click', function (event) {
                // Check if the click happened outside .nav
                if (!event.target.closest('.nav') && !event.target.closest('.dropdown-menu')) {
                    dropdowns.forEach(function (dropdown) {
                        var menu = dropdown.nextElementSibling;
                        if (menu.classList.contains('show')) {
                            menu.classList.remove('show');
                        }
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var offcanvas = document.getElementById('offcanvasNavbar');
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvas);
            bsOffcanvas._config.backdrop = false; // Nonaktifkan backdrop

            offcanvas.addEventListener('show.bs.offcanvas', function () {
                document.querySelector('.offcanvas-backdrop').style.display = 'none';
            });
        });

    </script>

</body>

</html>