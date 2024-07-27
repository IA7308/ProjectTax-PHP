@extends('main')

@section('title', 'LIHAT DATA JURNAL')
<body>
    @section('content')
    <div class="container-fluid text-center">
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            <div class ="row sticky-top" style="background-color: white;">
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
                <div class="col text-end">
                    <div class="row">
                        @if (session('saldoDebit') == session('saldoKredit'))
                            <div class="col">
                                <p>Jumlah Debit : {{ number_format(session('saldoDebit'), 2, ',', '.') }}</p>
                            </div>
                            <div class="col">
                                <p>Jumlah Kredit : {{ number_format(session('saldoKredit'), 2, ',', '.') }}</p>
                            </div>
                        @else
                            <div class="col">
                                <p>Cek Ulang {{ number_format(session('saldoDebit'), 2, ',', '.') }}</p>
                            </div>
                            <div class="col">
                                <p>TDK Balance {{ number_format(session('saldoKredit'), 2, ',', '.') }}</p>
                            </div>
                        @endif
                        
                    </div>        
                </div>                 
            <!-- DATA TABEL -->
            <table class="table table-fluid table-bordered" id="myTable">
                <thead>
                    <tr class="table table-primary">
                        <th>TANGGAL</th>
                        <th>KETERANGAN</th>
                        <th>TRANSAKSI</th>
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
                    <tr></tr>
                    @foreach($data as $index => $d)
                        @php
                            $maxRows = max(count($d->debit), count($d->kredit));
                        @endphp
                        
                        @for ($i = 0; $i < $maxRows; $i++)
                           
                            <tr>
                                @if($i == 0)
                                    <td>{{ $d->debit[$i]['tanggal'] ?? '' }}</td>
                                    <td>{{ $d->debit[$i]['keterangan'] ?? '' }}</td>
                                    <td class="text-start">{{ $d->debit[$i]['transaksi'] ?? '' }}</td>
                                    <td>{{ $d->debit[$i]['bukti'] ?? '' }}</td>
                                    <td class="text-end" rowspan="{{ $maxRows }}">{{ number_format($d->jumlah, 2, ',', '.') }}</td>
                                @else
                                    <td>{{ $d->debit[$i]['tanggal'] ?? '' }}</td>
                                    <td>{{ $d->debit[$i]['keterangan'] ?? '' }}</td>
                                    <td class="text-start">{{ $d->debit[$i]['transaksi'] ?? '' }}</td>
                                    <td>{{ $d->debit[$i]['bukti'] ?? '' }}</td>
                                @endif
                                    
                                @if(isset($d->debit[$i]))
                                    <td>{{ $d->debit[$i]['akunD'] }}</td>
                                    <td class="text-end">{{ number_format($d->debit[$i]['rpD'], 2, ',', '.') }}</td>
                                @else
                                    <td></td>
                                    <td class="text-end"></td>
                                @endif

                                @if(isset($d->kredit[$i]))
                                    <td>{{ $d->kredit[$i]['akunK'] }}</td>
                                    <td class="text-end">{{ number_format($d->kredit[$i]['rpK'], 2, ',', '.') }}</td>
                                @else
                                    <td></td>
                                    <td class="text-end"></td>
                                @endif

                                @if($i == 0)
                                    <td rowspan="{{ $maxRows }}">
                                        <a class="dropdown-toggle text-start" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            AKSI
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="/{{$d->id}}/{{$d->debit[$i]['JurnalId']}}/{{$d->debit[$i]['id']}}/{{$d->debit[$i]['bukti']}}/{{$d->debit[$i]['tanggal']}}/{{$d->debit[$i]['keterangan']}}/{{$d->debit[$i]['transaksi']}}/editJ" class="btn btn-primary dropdown-item">Edit</a></li>
                                            <li>
                                                <form method="post" action="/j/{{ $d->id }}" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item">Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                @endif
                            </tr>
                        @endfor
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/jurnal", // Ganti dengan rute Anda
                    data: function(d) {
                        d.page = d.page || 1;
                    }
                },
                columns: [
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'transaksi', name: 'transaksi' },
                    { data: 'bukti', name: 'bukti' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'akunD', name: 'akunD' },
                    { data: 'rpD', name: 'rpD' },
                    { data: 'akunK', name: 'akunK' },
                    { data: 'rpK', name: 'rpK' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'DataTable Export',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        title: 'DataTable Export',
                        className: 'btn btn-danger'
                    },
                ]
            });

        });
    </script>
    @endpush
</html>