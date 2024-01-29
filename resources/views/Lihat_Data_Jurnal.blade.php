@extends('main')

@section('title', 'LIHAT DATA JURNAL')
<body>
    @section('content')
    <div class="container-fluid text-center">
        <h3 class="mt-2">DATA JURNAL</h3>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            <div class="row">
                <div class="col-12 text-center">
                    <h2>TABEL JURNAL</h2>
                </div>
                <div class="col-4 text-start">
                    <form action="/jurnal" method="GET">
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
                        <td><input type="date" class="w-75" id="searchInputtgl" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputtr" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputkt" placeholder="Search..."></td>
                        <td><input type="text" class="w-75" id="searchInputbk" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingjm">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingjm"> -->
                        </td>
                        <td><input type="text" class="w-75" id="searchInputakd" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingD">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingD"> -->
                        </td>
                        <td><input type="text" class="w-75" id="searchInputakk" placeholder="Search..."></td>
                        <td > 
                            <!-- <input type="button" class="btn btn-light col-3 mx-auto" value="\/" id="descendingK">
                            <input type="button" class="btn btn-light col-3 mx-auto" value="/\" id="ascendingK"> -->
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
                        <td class="text-end">{{number_format($d->jumlah, 2, ',', '.')}}</td>
                        <td>{{$d->akunD}}</td>
                        <td class="text-end">{{number_format($d->rpD, 2, ',', '.')}}</td>
                        <td>{{$d->akunK}}</td>
                        <td class="text-end">{{number_format($d->rpK, 2, ',', '.')}}</td>
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
            columnDefs: [
                { targets: [0, 1, 2, 3, 5, 7, 9], orderable: false }
            ]
        });

        });
    </script>
    @endpush
</html>