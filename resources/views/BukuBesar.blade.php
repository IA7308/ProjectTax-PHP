@extends('main')

@section('title', 'BUKU BESAR')

<body>
    @section('content')
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
                                <option value="{{$c->id}}" @if($c->keterangan == 'Header' || $c->keterangan == 'Jumlah') style="font-weight: bold;" @endif >
                                    {{$c->kode}} {{$c->Nama_akun}}
                                </option>
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
                    <tr class="table table-primary text-center ">
                        <th>TANGGAL</th>
                        <th>TRANSAKSI</th>
                        <th>KETERANGAN</th>
                        <th>BUKTI</th>
                        <!-- <th>JUMLAH</th> -->
                        <th >DEBET</th>
                        <th >KREDIT</th>
                        <th>Saldo</th>
                    </tr>
                    <tr>
                        <td><input type="date" class="w-75" id="searchInputtgl" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputtr" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputkt" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputbk" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingjm">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingjm"> -->
                        </td>
                        <!-- <td><input type="text" class="w-50" id="searchInputakd" placeholder="Search..."></td> -->
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingD">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingD"> -->
                        </td>
                        <!-- <td><input type="text" class="w-50" id="searchInputakk" placeholder="Search..."></td> -->
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingK">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingK"> -->
                        </td>
                        <!-- <td> </td> -->
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
                        <!-- <td>{{number_format($d->jumlah, 2, ',', '.')}}</td> -->
                        <!-- <td>{{$d->akunD}}</td> -->
                        <td class="text-end">{{number_format($d->rpD, 2, ',', '.')}}</td>
                        <!-- <td>{{$d->akunK}}</td> -->
                        <td class="text-end">{{number_format($d->rpK, 2, ',', '.')}}</td>
                        <td class="text-end">
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
    @endsection
</body>
    @push('styles')
    <style></style>
    @endpush
    @push('scripts')
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
                ],
                columnDefs: [
                    { targets: [0, 1, 2, 3], orderable: false }
                ]
            });
            
            $('#pilihakun').change(function () {
            if ($(this).val()) {
                window.location.href = '/bukubesar/' + $(this).val();
            }
        });
        });
        
    </script>
    @endpush
</html>