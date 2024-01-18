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
        <h3 class="mt-2">DATA JURNAL</h3>
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
                    <h2>Tabel Jurnal</h2>
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
            <table class="table table-fluid table-bordered" id="myTable">
                <thead>
                    <tr class="table table-primary">
                        <th>TANGGAL</th>
                        <th>TRANSAKSI</th>
                        <th>KETERANGAN</th>
                        <th>BUKTI</th>
                        <th>JUMLAH</th>
                        <th colspan="2">DEBET</th>
                        <th colspan="2">KREDIT</th>
                        <th>PILIHAN</th>
                    </tr>
                    <tr>
                        <td><input type="date" class="w-50" id="searchInputtgl" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputtr" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputkt" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputbk" placeholder="Search..."></td>
                        <td class="row text-center">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingjm">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingjm">
                        </td>
                        <td><input type="text" class="w-50" id="searchInputakd" placeholder="Search..."></td>
                        <td class="row text-center">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingD">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingD">
                        </td>
                        <td><input type="text" class="w-50" id="searchInputakk" placeholder="Search..."></td>
                        <td class="row text-center">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingK">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingK">
                        </td>
                        <td> </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>{{$d->tanggal}}</td>
                        <td class="text-start">{{$d->transaksi}}</td>
                        <td>{{$d->keterangan}}</td>
                        <td>{{$d->bukti}}</td>
                        <td>{{number_format($d->jumlah, 2, ',', '.')}}</td>
                        <td>{{$d->akunD}}</td>
                        <td>{{number_format($d->rpD, 2, ',', '.')}}</td>
                        <td>{{$d->akunK}}</td>
                        <td>{{number_format($d->rpK, 2, ',', '.')}}</td>
                        <td>
                            <a class="dropdown-toggle text-start" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                AKSI
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ $d->id }}/editJ" class="btn btn-primary dropdown-item">Edit</a></li>
                                <li>
                                    <form method="post" action="/j/{{ $d->id }}" style="display:inline"
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#searchInputtgl").on("input", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            $("#searchInputtr").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            $("#searchInputkt").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            $("#searchInputbk").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            $("#searchInputakd").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            $("#searchInputakk").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $("#descendingjm").on("click", function() {
                var column = "jumlah"; 
                var $tbody = $("#myTable tbody");
                var rows = $tbody.find("tr").get();
                rows.sort(function(a, b) {
                    var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    return bValue - aValue;
                });
                $tbody.empty().append(rows);
            });

            $("#ascendingjm").on("click", function() {
                    var column = "jumlah"; 
                    var $tbody = $("#myTable tbody");
                    var rows = $tbody.find("tr").get();
                    rows.sort(function(a, b) {
                        var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        return aValue - bValue;
                    });
                    $tbody.empty().append(rows);
                });
            

            $("#ascendingD").on("click", function() {
                    var column = "debet"; 
                    var $tbody = $("#myTable tbody");
                    var rows = $tbody.find("tr").get();
                    rows.sort(function(a, b) {
                        var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        return aValue - bValue;
                    });
                    $tbody.empty().append(rows);
                });

            $("#descendingD").on("click", function() {
                var column = "debet"; 
                var $tbody = $("#myTable tbody");
                var rows = $tbody.find("tr").get();
                rows.sort(function(a, b) {
                    var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    return bValue - aValue;
                });
                $tbody.empty().append(rows);
            });

            $("#ascendingK").on("click", function() {
                    var column = "kredit"; 
                    var $tbody = $("#myTable tbody");
                    var rows = $tbody.find("tr").get();
                    rows.sort(function(a, b) {
                        var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                        return aValue - bValue;
                    });
                    $tbody.empty().append(rows);
                });

            $("#descendingK").on("click", function() {
                var column = "kredit"; 
                var $tbody = $("#myTable tbody");
                var rows = $tbody.find("tr").get();
                rows.sort(function(a, b) {
                    var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
                    return bValue - aValue;
                });
                $tbody.empty().append(rows);
            });
            
        });
    </script>
</html>