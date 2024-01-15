<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>
</head>

<body>
    @Component('Components.Sidebar')
    @endcomponent
    <div class="container-fluid text-center">
        <h3 class="mt-2">DATA </h3>
        <hr>
        <div class="d-flex justify-content-center">
            <a class="btn rounded-pill btn-success p-2 mx-2" href="#">
                LIHAT DATA </a>
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
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success mx-1" type="submit">Search</button>
                            <input type="button" class="mx-1" value="Copy">
                            <input type="button" class="mx-1" value="Excel">
                            <input type="button" class="mx-1" value="PDF">
                            <input type="button" class="mx-1" value="Column Visibility">
                        </form>
                    </div>
                </div>
            </div>
            <p class="text-start">Showing 1 to 1 of 1 entries</p>
            <!-- DATA TABEL -->
            <table class="table">
                <tr class="table table-primary">
                    <th>JENIS AKUN</th>
                    <th>KELOMPOK AKUN</th>
                    <th>KETERANGAN</th>
                    <th>KODE</th>
                    <th>NAMA AKUN</th>
                    <th>INDEN</th>
                </tr>
                @foreach($data as $d)
                <tr>
                    <td>{{$d->jenis_akun}}</td>
                    <td>{{$d->kelompok_akun}}</td>
                    <td>{{$d->keterangan}}</td>
                    <td>{{$d->kode}}</td>
                    <td>{{$d->Nama_akun}}</td>
                    <td>{{$d->Saldo_awal}}</td>
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

</html>