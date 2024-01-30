@extends('main')

@section('title', 'BERANDA')

<body>
    @section('content')
    <div class="container-fluid text-center" id="container">
        <h3 class="mt-2">DATA COA</h3>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->

            <div class="row">
                <div class="col text-start">
                    <form action="/beranda" method="GET">
                        <p>Show
                            <select name="pagination" id="paginate" onchange="this.form.submit()">
                                <option value="5" {{ request('pagination', 10)==5 ? 'selected' : '' }}>5</option>
                                <option value="all" {{ strtolower(request('pagination'))=='all' ? 'selected' : '' }}>ALL
                                </option>
                                <option value="10" {{ request('pagination', 10)==10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('pagination', 10)==15 ? 'selected' : '' }}>15</option>
                            </select> entries
                        </p>
                    </form>
                </div>
                <div class="col text-center">
                    <h2>TABEL COA</h2>
                </div>
                <div class="col">
                    <div class="text-end">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                id="searchInput">
                        </form>
                        <!-- <input type="text" id="kalkulasiJumlah" class="form-control my-3 text-end"
                            placeholder="kalkulasi jumlah" disabled> -->
                        <input type="text" id="kalkulasiJumlahSaldo" class="form-control my-3 text-end"
                            value= "{{ number_format(session('saldo'), 2, ',', '.') }}"disabled>
                    </div>
                </div>
            </div>
            <!-- DATA TABEL -->
            <table id="myTable" class="table table-bordered table-hover text-start" name="tabelCOA">
                <thead>
                    <tr class="table table-primary">
                        <th>JENIS AKUN</th>
                        <th>KELOMPOK AKUN</th>
                        <th>KETERANGAN</th>
                        <th>KODE AKUN</th>
                        <th>NAMA AKUN</th>
                        <th>SALDO AWAL</th>
                        <th>PILIHAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr data-jumlah-saldo="{{ $d->jumlah_saldo }}" class="{{ $d->backgroundClass }}">
                        <td>{{$d->jenis_akun}}</td>
                        <td>{{$d->kelompok_akun}}</td>
                        <td>{{$d->keterangan}}</td>
                        <td>{{$d->kode}}</td>
                        <td class="text-start">{{$d->Nama_akun}}</td>
                        <td class="text-end {{ $d->backgroundCell }}">{{number_format($d->Saldo_awal, 2, ',', '.')}}
                        </td>
                        <td class="text-center">
                            <a href="{{ $d->id }}/edit" class="btn btn-primary">Edit</a>
                            <form method="post" action="/{{ $d->id }}" style="display:inline"
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
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tableRowsSaldo = document.querySelectorAll("tr[data-jumlah-saldo]");
        var kalkulasiJumlahInput = document.getElementById("kalkulasiJumlah");
        var totalSaldo = 0;

        tableRowsSaldo.forEach(function (row) {
            var saldoValue = parseFloat(row.dataset.jumlahSaldo);
            totalSaldo += saldoValue;
        });

        var displayValue = totalSaldo.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        kalkulasiJumlahInput.value = displayValue;
        
        if(totalSaldo != 0){
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