<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Jurnal</title>
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
        <h3 class="mt-2">BUKU BESAR</h3>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            <div class="row">
                <div class="col text-start">
                    <p>Show <input type="number" name="pagination" id="paginate"> entries</p>
                </div>
                <div class="col text-center">
                    <h2>Tabel Jurnal</h2>
                </div>
                <div class="col">
                    <div class="text-end">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success mx-1" type="submit">Search</button>
                            <div class="col">
                                <select class="form-select" id="Nama_akun" name="Nama_akun">
                                    <option selected>Choose...</option>
                                    @foreach($data as $d)
                                    <option value="{{$d->id}}" {{ isset($dataJ) && $dataJ->id == $d->id ? 'selected' :
                                        '' }}>{{$d->Nama_akun}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p class="text-start">Showing 1 to 1 of 1 entries</p>
            <!-- DATA TABEL -->
            <table class="table table-fluid table-bordered" id="myTable">
                <thead>
                    <tr class="table table-primary">
                        <th>TANGGAL</th>
                        <th>TRANSAKSI</th>
                        <th>KETERANGAN</th>
                        <th>No.Bukti</th>
                        <th colspan="2">Debit</th>
                        <th colspan="2">Kredit</th>
                        <th colspan="2">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>{{$d->tanggal}}</td>
                        <td class="text-start">{{$d->transaksi}}</td>
                        <td>{{$d->keterangan}}</td>
                        <td>{{$d->bukti}}</td>
                        <td>{{number_format($d->rpD, 2, ',', '.')}}</td>
                        <td>{{number_format($d->rpK, 2, ',', '.')}}</td>
                        <td>{{number_format($d->Saldo_awal, 2, ',', '.')}}</td>
                        <td>
                            <a class="dropdown-toggle text-start" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                AKSI
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ $d->id }}/editJ" class="btn btn-primary dropdown-item">Edit</a></li>
                                <li>
                                    <form method="post" action="/j{{ $d->id }}" style="display:inline"
                                        onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item">Hapus</button>
                                    </form>
                                </li>
                            </ul>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
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