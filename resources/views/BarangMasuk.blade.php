@extends('main')

@section('title', $title)

<body>
    @section('content')
    <div class="container-fluid text-center" id="container">
        <hr>
        <div class="card p-3">
            <!-- Pagination -->

            <div class ="row sticky-top" style="background-color: white;">
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
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div  class="input-group">
                                <input type="file" name="file"  class="form-control" required>
                                <button type="submit" class="btn btn-primary ms-2">Import</button>
                            </div>  
                        </form>
                    </div>
                </div>
                <div class="col text-center">
                    <h2>TABEL BARANG {{$title}}</h2>
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
                           data-saldo="{{session('saldo')}}" value= "{{ number_format(session('saldo'), 2, ',', '.') }}"disabled>
                    </div>
                </div>
            </div>
            <!-- DATA TABEL -->
            <table id="myTable" class="table table-bordered table-hover text-start" name="tabelCOA">
                <thead>
                    <tr class="table table-primary">
                        <th>TANGGAL</th>
                        <th>NAMA PENJUAL</th>
                        <th>KODE BARANG</th>
                        <th>NAMA BARANG</th>
                        <th>UNIT {{$title}}</th>
                        <th>HARGA BELI/UNIT</th>
                        <th>DPP</th>
                        <th>PPN</th>
                        <th>TOTAL PIUTANG</th>
                        <th>KETERANGAN</th>
                        <th>PILIHAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr data-jumlah-saldo="{{ $d->unit_keluar }}" class="{{ $d->backgroundClass }}">
                        <td>{{$d->tanggal}}</td>
                        <td>{{$d->nama_penjual}}</td>
                        <td>{{$d->kode_barang}}</td>
                        <td>{{$d->nama_barang}}</td>
                        <td class="text-end {{ $d->backgroundCell }}">{{$d->unit_keluar}}</td>
                        <td class="text-end">{{number_format($d->harga, 2, ',', '.')}}</td>
                        <td class="text-end {{ $d->backgroundCell }}">{{number_format(($d->harga*$d->unit_keluar), 2, ',', '.')}}</td>
                        <td class="text-end {{ $d->backgroundCell }}">{{number_format((($d->harga*$d->unit_keluar)*0.1), 2, ',', '.')}}</td>
                        <td class="text-end {{ $d->backgroundCell }}">{{number_format((($d->harga*$d->unit_keluar)-(($d->harga*$d->unit_keluar)*0.1)), 2, ',', '.')}}</td>
                        <td>{{$d->keterangan}}</td>
                        <td class="text-center">
                            <a href="{{$d->id}}{{$editAction}}" class="btn btn-primary">Edit</a>
                            <form method="post" action="{{$d->id}}{{$actionDelete}}" style="display:inline"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
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
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
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
                    <form class="text-end" action="{{ $action }}">
                        @csrf
                        <input type="hidden" name="_method" value="{{ $method }}" />
                        <!-- Tanggal -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="tanggal" class="form-label">Tanggal</label>
                            </div>
                            <div class="col">                                
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="">
                            </div>
                        </div>
                        <!-- nama_penjual -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="nama_penjual" class="form-label">Nama Penjual</label>
                            </div>
                            <div class="col">
                                <select class="form-select" id="nama_penjual" name="nama_penjual">
                                    <option selected>Choose...</option>
                                    @foreach($nama_penjual as $np)
                                        <option value="{{$np->id}}">{{$np->Nama_akun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- KODE BARANG -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="kode_barang" class="form-label">KODE BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="">
                            </div>
                        </div>
                        <!-- NAMA BARANG -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="nama_barang" class="form-label">NAMA BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="">
                            </div>
                        </div>
                        <!-- KETERANGAN -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="keterangan" class="form-label">KETERANGAN</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="keterangan" name="keterangan" value="">
                            </div>
                        </div>
                        <!-- UNIT KELUAR -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="unit_keluar" class="form-label">UNIT KELUAR</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="unit_keluar" name="unit_keluar" value="">
                            </div>
                        </div>
                        <!-- harga -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="harga" class="form-label">HARGA BELI /UNIT</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="harga" name="harga" value="">
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
        <!-- BORDER BOTTOM -->
        <p class="text-start mt-3">@2024 <b>CV. SOLUSIKITA</b></p>
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
    
</style>
@endpush
@push('scripts')
<script>
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
        if(saldo != 0){
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