<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Besar</title>
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
    <div class="container-fluid text-center">
        <h3 class="mt-2">BUKU BESAR</h3>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            <div class="row">
                <div class="col-12 text-center">
                @if(session('pilihC'))
                    <h2>{{$dataPilih->Nama_akun}}</h2>
                @endif
                </div>
                <div class="col-4 text-start">
                @if(session('paginate'))
                    <form action="/bukubesar" method="GET">
                        <p>Show 
                            <select name="pagination" id="paginate" onchange="this.form.submit()">
                                <option value="5" {{ request('pagination', 10) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('pagination', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('pagination', 10) == 15 ? 'selected' : '' }}>15</option>
                                <option value="all" {{ strtolower(request('pagination')) == 'all' ? 'selected' : '' }}>ALL</option>
                            </select> entries
                        </p>
                    </form>
                @endif
                </div>
                <div class="col text-end">
                    
                    <form action="/bukubesar" method="GET">
                        <select name="pilihakun" id="pilihakun" onchange="this.form.submit()">
                        <option value="#">Choose</option>
                            @foreach($dataC as $c)
                                <option value="{{$c->id}}">{{$c->kode}} {{$c->Nama_akun}}</option>
                            @endforeach
                        </select>
                    </form>
                    
                </div>
                @if(session('pilihC'))
                    <div class="row d-flex justify-content-start">
                        <h5 class="text-start mx-2">Kode Akun : {{$dataPilih->kode}}</h5>
                    </div>
                    <div class="d-flex justify-content-start">
                        <div class="mx-2">
                            <h6 class="text-start">{{$dataPilih->keterangan}}</h5>
                        </div>
                        <div class="mx-2">
                            <h6 class="text-start">{{number_format($dataPilih->jumlah_saldo, 2, ',', '.')}}</h5>
                        </div>
                    </div>
                @endif
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
                        <th>Saldo</th>
                    </tr>
                    <tr>
                        <td><input type="date" class="w-50" id="searchInputtgl" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputtr" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputkt" placeholder="Search..."></td>
                        <td><input type="text" class="w-50" id="searchInputbk" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingjm">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingjm"> -->
                        </td>
                        <td><input type="text" class="w-50" id="searchInputakd" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingD">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingD"> -->
                        </td>
                        <td><input type="text" class="w-50" id="searchInputakk" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingK">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingK"> -->
                        </td>
                        <td> </td>
                    </tr>
                </thead>
                <tbody>
                @if(session('pilihC'))
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
                            {{number_format($d->histori_saldo, 2, ',', '.')}}                           
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            @if(session('paginate'))
            <div class="row-fluid d-flex justify-content-end pagination mt-4">
                {{ $data->links() }}
            </div>
            @endif
        </div>
        <p class="text-start mt-3">@2024 <b>CV.SOLUSIKITA</b></p>
    </div>

</body>
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

            // $("#descendingjm").on("click", function() {
            //     var column = "jumlah"; 
            //     var $tbody = $("#myTable tbody");
            //     var rows = $tbody.find("tr").get();
            //     rows.sort(function(a, b) {
            //         var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         return bValue - aValue;
            //     });
            //     $tbody.empty().append(rows);
            // });

            // $("#ascendingjm").on("click", function() {
            //         var column = "jumlah"; 
            //         var $tbody = $("#myTable tbody");
            //         var rows = $tbody.find("tr").get();
            //         rows.sort(function(a, b) {
            //             var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             return aValue - bValue;
            //         });
            //         $tbody.empty().append(rows);
            //     });
            

            // $("#ascendingD").on("click", function() {
            //         var column = "debet"; 
            //         var $tbody = $("#myTable tbody");
            //         var rows = $tbody.find("tr").get();
            //         rows.sort(function(a, b) {
            //             var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             return aValue - bValue;
            //         });
            //         $tbody.empty().append(rows);
            //     });

            // $("#descendingD").on("click", function() {
            //     var column = "debet"; 
            //     var $tbody = $("#myTable tbody");
            //     var rows = $tbody.find("tr").get();
            //     rows.sort(function(a, b) {
            //         var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         return bValue - aValue;
            //     });
            //     $tbody.empty().append(rows);
            // });

            // $("#ascendingK").on("click", function() {
            //         var column = "kredit"; 
            //         var $tbody = $("#myTable tbody");
            //         var rows = $tbody.find("tr").get();
            //         rows.sort(function(a, b) {
            //             var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //             return aValue - bValue;
            //         });
            //         $tbody.empty().append(rows);
            //     });

            // $("#descendingK").on("click", function() {
            //     var column = "kredit"; 
            //     var $tbody = $("#myTable tbody");
            //     var rows = $tbody.find("tr").get();
            //     rows.sort(function(a, b) {
            //         var aValue = parseFloat($(a).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         var bValue = parseFloat($(b).find("td:eq(" + $("th:contains('" + column + "')").index() + ")").text().replace(/\D/g, ''));
            //         return bValue - aValue;
            //     });
            //     $tbody.empty().append(rows);
            // });
            
            $('#myTable').DataTable({
                searching: false,
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
            
            $('#pilihakun').change(function () {
            if ($(this).val()) {
                window.location.href = '/bukubesar/' + $(this).val();
            }
        });
        });
        
    </script>
</html>