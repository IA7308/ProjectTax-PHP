<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERANDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>
</head>

<body>
    @Component('Components.LoginBar')
    @endcomponent
    @Component('Components.Sidebar')
    @endcomponent
    <div class="container-fluid text-center">
        <h3 class="mt-2">DATA COA</h3>
        <hr>
        <div class="d-flex justify-content-center">
            <a class="btn rounded-pill btn-warning p-2 mx-2" href="#">
                Print PDF</a>
            <a class="btn rounded-pill btn-light p-2 mx-2" href="#">
                Print Excel</a>
        </div>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            
            <div class="row">
                <div class="col text-start">
                <form action="/" method="GET">
                    <p>Show 
                        <select name="pagination" id="paginate" onchange="this.form.submit()">
                            <option value="5" {{ request('pagination', 10) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('pagination', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('pagination', 10) == 15 ? 'selected' : '' }}>15</option>
                            <!-- Tambahkan opsi lain sesuai kebutuhan -->
                        </select> entries
                    </p>
                </form>
                </div>
                <div class="col text-center">
                    <h2>Tabel COA</h2>
                </div>
                <div class="col">
                    <div class="text-end">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                id="searchInput">
                        </form>
                        <input type="text" id="kalkulasiJumlah" class="form-control my-3" placeholder="kalkulasi jumlah" disabled>
                    </div>
                </div>
            </div>
            <!-- DATA TABEL -->
            <table class="table table-bordered table-hover" id="myTable" name="tabelCOA">
                <thead>
                <tr class="table table-primary">
                    <th>JENIS AKUN</th>
                    <th>KELOMPOK AKUN</th>
                    <th>KETERANGAN</th>
                    <th>KODE</th>
                    <th>NAMA AKUN</th>
                    <th>SALDO AWAL</th>
                    <th>PILIHAN</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $d)
                <tr data-saldo-awal="{{ $d->Saldo_awal }}">
                    <td>{{$d->jenis_akun}}</td>
                    <td>{{$d->kelompok_akun}}</td>
                    <td>{{$d->keterangan}}</td>
                    <td>{{$d->kode}}</td>
                    <td class="text-start">{{$d->Nama_akun}}</td>
                    <td>{{number_format($d->Saldo_awal, 2, ',', '.')}}</td>
                    <td>
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
            <div class="row d-flex justify-content-end pagination">
                {{ $data->links() }}
            </div>
        </div>
        <p class="text-start mt-3">@2024 <b>CV.REGENCY</b></p>
    </div>

</body>

<style>
    p {
        margin-top: 20px;
    }
    svg {
        width: 20px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll("tr[data-saldo-awal]");
        var kalkulasiJumlahInput = document.getElementById("kalkulasiJumlah");

        tableRows.forEach(function(row) {
            row.addEventListener("click", function() {
                var saldoValue = row.dataset.saldoAwal;
                var formattedValue = formatNumberWithCommas(saldoValue, 2);

                kalkulasiJumlahInput.value = formattedValue;
            });
        });

        function formatNumberWithCommas(number, decimalPlaces) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        if (decimalPlaces && parts[1]) {
            parts[1] = parseFloat("0." + parts[1]).toFixed(decimalPlaces).split(".")[1];
        }

        return parts.join(",");
    }
    });

    
    $(document).ready(function () {
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

</html>