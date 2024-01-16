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
                    <p>Show <input type="number" name="pagination" id="paginate"> entries</p>
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
                    </div>
                </div>
            </div>
            <p class="text-start">Showing 1 to 1 of 1 entries</p>
            <!-- DATA TABEL -->
            <table class="table" id="myTable">
                <tr class="table table-primary">
                    <th>JENIS AKUN</th>
                    <th>KELOMPOK AKUN</th>
                    <th>KETERANGAN</th>
                    <th>KODE</th>
                    <th>NAMA AKUN</th>
                    <th>INDEN</th>
                    <th>PILIHAN</th>
                </tr>
                @foreach($data as $d)
                <tr>
                    <td>{{$d->jenis_akun}}</td>
                    <td>{{$d->kelompok_akun}}</td>
                    <td>{{$d->keterangan}}</td>
                    <td>{{$d->kode}}</td>
                    <td>{{$d->Nama_akun}}</td>
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
            </table>
            <div class="text-end">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <p class="text-start mt-3">@2024 <b>CV.MWI</b> Program by Zou</p>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
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