@extends('main')

@section('title', 'Resume')

<body>
    @section('content')
    <div class="loader-wrapper" id="loader-wrapper" style="display: none;">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <div class="container-fluid text-center" id="container">
        <hr>
        <div class="card p-3">
            <!-- Pagination -->

            <div class="row sticky-top" style="background-color: white;">
                <div class="col text-start">
                    <form action="/beranda" method="GET">
                        <p>Show
                            <select name="pagination" id="paginate" onchange="this.form.submit()">
                                <option value="5" {{ request('pagination', 10) == 5 ? 'selected' : '' }}>5</option>
                                <option value="all" {{ strtolower(request('pagination')) == 'all' ? 'selected' : '' }}>ALL
                                </option>
                                <option value="10" {{ request('pagination', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('pagination', 10) == 15 ? 'selected' : '' }}>15</option>
                            </select> entries
                        </p>
                    </form>
                    <div class="col">
                        <form action="{{ route('import.coa') }}" method="POST" enctype="multipart/form-data"
                            id="import-form">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" required>
                                <button type="submit" class="btn btn-primary ms-2">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col text-center">
                    <h2>TABEL LAPORAN STOCK KELUAR</h2>
                </div>
                <div class="col">
                    <div class="text-end">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                id="searchInput">
                        </form>
                        <input type="text" id="kalkulasiJumlah" class="form-control my-3 text-end"
                            placeholder="kalkulasi jumlah" disabled hidden>
                        <input type="text" id="kalkulasiJumlahSaldo" class="form-control my-3 text-end"
                            data-saldo="{{session('saldo')}}" value="{{ number_format(session('saldo'), 2, ',', '.') }}"
                            disabled>
                    </div>
                </div>
            </div>
            <!-- DATA TABEL -->
            <table id="myTable" class="table table-bordered table-hover text-start" name="tabelCOA">
                <thead>
                    <tr class="table table-dark">
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok Awal</th>
                        <th>Barang Masuk</th>
                        <th>Barang Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Harga Masuk</th>
                        <th>Harga Keluar</th>
                        <th>Total Harga</th>
                        <th>PILIHAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $dstock)
                        <tr>
                            <td>{{ $dstock->kode_barang }}</td>
                            <td>{{ $dstock->nama_barang }}</td>
                            <td>{{ $dstock->stock_awal }}</td>
                            <td>{{ $barang_masuk[$index] }}</td>
                            <td>{{ $barang_keluar[$index]*-1 }}</td>
                            <td>{{$dstock->stock_akhir}}</td>
                            <td>Rp{{number_format(($dstock->harga_masuk), 2, ',', '.')}}</td>
                            <td>Rp{{number_format(($dstock->harga_keluar), 2, ',', '.')}}</td>
                            <td>Rp{{number_format(($dstock->stock_awal*$dstock->harga_masuk), 2, ',', '.')}}</td>
                            <td>
                                <form method="post" action="/{{$dstock->id}}/itemsdelete" style="display:inline"
                                    onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                            <!-- <td class="text-end">{{number_format($dstock->harga, 2, ',', '.')}}</td> -->
                            <!-- <td><button type="button" class="btn btn-success">Select</button></td> -->
                        </tr>
                    @endforeach                                        
                </tbody>
            </table>

            <!-- AING NAMBAHIN INI BUAT NGURUTIN KODE DARI TERKECIL KE BESAR, TAPI FUNCTION SORTING NYA MALAH ILANG !-->
            <!-- PENGENNYA NGURUTIN KODE NYA DARI TERKECIL KE BESAR TAPI SORTINGAN PADA TABEL GA ILANG, BISA GA ??-->

            <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    var table = $('#myTable').DataTable({
                        "order": [[3, "asc"]] // Urutkan berdasarkan kolom ke-4 (indeks dimulai dari 0)
                    });
                });
            </script> -->

            @if(session('paginate'))
                <div class="row-fluid d-flex justify-content-end pagination mt-4">
                    {{ $data->links() }}
                </div>
            @endif
        </div>
        <div class="sticky-sm-bottom text-end p-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#exampleModal">+</button>
        </div>
        <!-- MODAL -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Input Barang Masuk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="text-end" action="/LaporanStockKeluar">
                            @csrf
                            <input type="hidden" name="_method" value="POST" />
                            <!-- Tanggal -->
                            <!-- nama_penjual -->
                            <!-- KODE BARANG -->
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="kode_barang" class="form-label">KODE BARANG</label>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                                        value="">
                                </div>
                            </div>
                            <!-- NAMA BARANG -->
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="nama_barang" class="form-label">NAMA BARANG</label>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                        value="">
                                </div>
                            </div>
                            <!-- KETERANGAN -->
                            <!-- STOCK AWAL -->
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="stock_awal" class="form-label">STOCK AWAL</label>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" id="stock_awal" name="stock_awal"
                                        value="">
                                </div>
                            </div>
                            <!-- harga masuk -->
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="harga_masuk" class="form-label">HARGA MASUK</label>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" id="harga_masuk" name="harga_masuk"
                                        value="">
                                </div>
                            </div>
                            <!-- harga keluar -->
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="harga_keluar" class="form-label">HARGA KELUAR</label>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" id="harga_keluar" name="harga_keluar"
                                        value="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="#"><button type="button" class="btn btn-danger mx-1">Reset</button></a>
                                <a href="#"><button type="button" class="btn btn-warning mx-1">Kembali</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p class="text-start mt-3">@2024 <b>CV.REGENCY</b></p>
        </div>
        @endsection
</body>

@push('styles')
    <style>
        p {
            margin-top: 20px;
        }

        svg {
            width: 20px;
        }

        .loader-wrapper {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #242f3f;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Menempatkan loader di atas konten lainnya */
        }

        .loader-hidden {
            opacity: 20;
            visibility: hidden;
        }

        .loader {
            display: inline-block;
            width: 30px;
            height: 30px;
            position: relative;
            border: 4px solid #Fff;
            animation: loader 2s infinite ease;
        }

        .loader-inner {
            vertical-align: top;
            display: inline-block;
            width: 100%;
            background-color: #fff;
            animation: loader-inner 2s infinite ease-in;
        }

        @keyframes loader {
            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(180deg);
            }

            50% {
                transform: rotate(180deg);
            }

            75% {
                transform: rotate(360deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes loader-inner {
            0% {
                height: 0%;
            }

            25% {
                height: 0%;
            }

            50% {
                height: 100%;
            }

            75% {
                height: 100%;
            }

            100% {
                height: 0%;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(window).on("load", function () {
            $(".loader-wrapper").fadeOut("slow");
        });

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('import-form');
            if (form) {
                form.addEventListener('submit', function (event) {
                    // Menampilkan loader-wrapper saat form di-submit
                    document.getElementById('loader-wrapper').style.display = 'flex';
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            // var tableRowsSaldo = document.querySelectorAll("tr[data-jumlah-saldo]");
            // var kalkulasiJumlahInput = document.getElementById("kalkulasiJumlah");
            // var totalSaldo = 0;

            // tableRowsSaldo.forEach(function (row) {
            //     var saldoValue = parseFloat(row.dataset.jumlahSaldo);
            //     totalSaldo += saldoValue;
            // });

            // var displayValue = totalSaldo.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
            // kalkulasiJumlahInput.value = displayValue;

            var saldo = document.getElementById("kalkulasiJumlahSaldo").getAttribute('data-saldo');
            if (saldo != 0) {
                alert("SALDO TIDAK WAJAR, CEK KEMBALI");
            }
        });

        $(document).ready(function () {
            $("#searchInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            if ($.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            $('#myTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'Print PDF',
                        className: 'btn rounded-pill btn-warning p-2 mx-2 mb-2 justify-content-start',
                        extend: 'pdf',
                        download: 'open'
                    },
                    {
                        text: 'Print Excel',
                        className: 'btn rounded-pill btn-light p-2 mx-2 mb-2 justify-content-start',
                        extend: 'excel',
                        download: 'open'
                    }
                ],
                order: [[3, 'asc']]
            });

        });

    </script>
@endpush

</html>