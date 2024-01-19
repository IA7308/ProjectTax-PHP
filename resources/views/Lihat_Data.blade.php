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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.2/css/buttons.bootstrap5.min.css">    
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
</head>
</head>

<body>
    @Component('Components.LoginBar')
    @endcomponent
    @Component('Components.Sidebar')
    @endcomponent
    <div class="container-fluid text-center" id="container">
        <h3 class="mt-2">DATA COA</h3>
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
                            <option value="all" {{ strtolower(request('pagination')) == 'all' ? 'selected' : '' }}>ALL</option>
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
            <table id="myTable" class="table table-bordered table-hover text-start" name="tabelCOA">
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
            <div class="row-fluid d-flex justify-content-end pagination mt-4">
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

    $('#myTable').DataTable({
        buttons: [
            'pdf'
        ]
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
            ]
        });

    });
    
</script>

</html>