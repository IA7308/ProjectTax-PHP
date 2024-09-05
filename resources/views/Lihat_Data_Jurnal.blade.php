@extends('main')

@section('title', 'LIHAT DATA JURNAL')
<body>
    @section('content')
    <div class="loader-wrapper" id="loader-wrapper" style="display: none;">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <div class="container-fluid text-center">
        <hr>
        <div class="card p-3">
            <!-- Pagination -->
            <div class ="row sticky-top" style="background-color: white;">
                <div class="col-3 text-start">
                    <form action="/jurnal" method="GET">
                        <p>Show 
                            <select name="pagination" id="paginate" onchange="this.form.submit()">
                                <option value="10" {{ request('pagination', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('pagination', 50) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('pagination', 100) == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ strtolower(request('pagination')) == 'all' ? 'selected' : '' }}>ALL</option>
                            </select> entries
                        </p>
                    </form>
                </div>
                <div class="col-6 text-start">
                    <h2 class="text-center">TABEL JURNAL</h2>
                </div>
                <div class="col-3">

                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4">
                    <form action="/import-jurnal" method="post" enctype="multipart/form-data" class="d-flex align-items-center"  id="import-form">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="file" id="file" class="form-control" required>
                            <button type="submit" class="btn btn-primary ms-2">Import</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <a href="/export-jurnal" class="btn btn-outline-primary">Export (.XLSX)</a>
                </div>
                <div class="col-md-5 text-end">
                    <div class="row">
                        @if (session('saldoDebit') == session('saldoKredit'))
                            <p>Jumlah Debit : {{ number_format(session('saldoDebit'), 2, ',', '.') }}</p>
                            <p>Jumlah Kredit : {{ number_format(session('saldoKredit'), 2, ',', '.') }}</p>
                        @else
                            <p>Cek Ulang {{ number_format(session('saldoDebit'), 2, ',', '.') }}</p>
                            <p>TDK Balance {{ number_format(session('saldoKredit'), 2, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            </div>                 
            <!-- DATA TABEL -->
            <table class="table table-fluid table-bordered" id="myTable">
                <thead>
                    <tr class="table table-primary">
                        <th>TANGGAL</th>
                        <th>NO. REFF</th>
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
                <?php
                    function wrapText($text, $length = 20) {
                        return wordwrap($text, $length, "\n", true);
                    }
                ?>
                <tbody>
                    <tr></tr>
                    @foreach($data as $index => $d)
                        @php
                            $maxRows = max(count($d->debit), count($d->kredit));
                        @endphp
                        
                        @for ($i = 0; $i < $maxRows; $i++)
                           
                            <tr>
                                @if($i == 0)
                                    <td class="fixed-width-date">{{ $d->debit[$i]['tanggal'] ?? '' }}</td>
                                    <td class="fixed-width-date">NOREFF{{ $d->JurnalId ?? '' }}</td>
                                    <td>{{ wrapText($d->debit[$i]['keterangan'] ?? '') }}</td>
                                    <td class="text-start">{{ wrapText($d->debit[$i]['transaksi'] ?? '') }}</td>
                                    <td>{{ wrapText($d->debit[$i]['bukti'] ?? '') }}</td>
                                    <td class="text-end" rowspan="{{ $maxRows }}">{{ number_format($d->jumlah, 2, ',', '.') }}</td>
                                @else
                                    <td class="fixed-width-date">{{ $d->debit[$i]['tanggal'] ?? '' }}</td>
                                    <td class="fixed-width-date">NOREFF{{ $d->JurnalId ?? '' }}</td>
                                    <td>{{ wrapText($d->debit[$i]['keterangan'] ?? '') }}</td>
                                    <td class="text-start">{{ wrapText($d->debit[$i]['transaksi'] ?? '') }}</td>
                                    <td>{{ wrapText($d->debit[$i]['bukti'] ?? '') }}</td>
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
                    {{ $data->appends(['pagination' => request('pagination')])->links() }}
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

        .loader-wrapper {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #242f3f;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Menempatkan loader di atas konten lainnya */
        }

        .loader-hidden{
            opacity: 20;
            visibility: hidden;
        }
        
        .loader {
            display: inline-block;
            width: 30px;
            height: 30px;
            position: relative;
            border: 4px solid #Fff;
            animation: loader 2s infinite ease;
        }

        .loader-inner {
            vertical-align: top;
            display: inline-block;
            width: 100%;
            background-color: #fff;
            animation: loader-inner 2s infinite ease-in;
        }

        @keyframes loader {
            0% {
                transform: rotate(0deg);
            }
            
            25% {
                transform: rotate(180deg);
            }
            
            50% {
                transform: rotate(180deg);
            }
            
            75% {
                transform: rotate(360deg);
            }
            
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes loader-inner {
            0% {
                height: 0%;
            }
            
            25% {
                height: 0%;
            }
            
            50% {
                height: 100%;
            }
            
            75% {
                height: 100%;
            }
            
            100% {
                height: 0%;
            }
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        $(window).on("load", function() {
            $(".loader-wrapper").fadeOut("slow");
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('import-form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Menampilkan loader-wrapper saat form di-submit
                    document.getElementById('loader-wrapper').style.display = 'flex';
                });
            }
        });
        
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

        });
    </script>
    @endpush
</html>