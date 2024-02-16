@extends('main')

@section('title', 'RUGILABA')
<body>
    @section('content')
    <div class="container-fluid text-center">
        <!-- <h3 class="mt-2">LABARUGI</h3>
        <hr> -->
        <div class="card p-3">
            <!-- Pagination -->
            <div class="row">
                <div class="col-12 text-center sticky-top" style="background-color: white;">
                    <h2>LABARUGI</h2>
                </div>
                <div class="col-4 text-start">
                    <!-- <form action="/jurnal" method="GET">
                        <p>Show 
                            <select name="pagination" id="paginate" onchange="this.form.submit()">
                                <option value="5" {{ request('pagination', 10) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('pagination', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('pagination', 10) == 15 ? 'selected' : '' }}>15</option>
                                <option value="all" {{ strtolower(request('pagination')) == 'all' ? 'selected' : '' }}>ALL</option>
                            </select> entries
                        </p>
                    </form> -->
                </div>                
            <!-- DATA TABEL -->
            <table class="table table-fluid table-bordered" id="myTable">
                <thead>
                    <tr class="table table-primary">
                        <th>GOLONGAN</th>
                        <th>SALDO NORMAL (KETERANGAN)</th>
                        <th>KETERANGAN</th>
                        <th>AWAL PERIODE</th>
                        <th>PERIODE BERJALAN</th>
                        <th>AKHIR PERIODE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr class="{{ $d->backgroundClass }}">
                        <td class="text-start">{{$d->golongan}}</td>
                        <td class="text-start {{ $d->backgroundCell }}">{{$d->keterangan}}</td>
                        <td class="text-start">{{$d->nama_akun}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->Saldo_awal)), 2, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->saldo_periode)), 2, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->saldo_akhir)), 2, ',', '.')}}</td>
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
    <style>
        p {
        margin-top: 20px;
        }

        svg {
            width: 20px;
        }
        .dt-buttons{
            display: flex;
            align-items: start;
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                // Dapatkan semua elemen dalam tabel dengan nilai
                var cellsWithValue = document.querySelectorAll('table#myTable td');
                
                // Iterasi melalui setiap elemen
                cellsWithValue.forEach(function(cell) {
                    // Dapatkan nilai dari setiap sel
                    var textValue = cell.innerText.trim();
                    
                    // Pastikan nilai tidak kosong dan berisi karakter '-'
                    if (textValue !== '' && textValue.includes('-')) {
                        // Parse teks ke bilangan bulat
                        var value = parseInt(textValue.replace(/[^0-9-]/g, ''), 10);
                        
                        // Ubah nilai menjadi positif dan tambahkan tanda '-'
                        cell.innerText = '(' + Math.abs(value).toLocaleString() + ')';
                    }
                });
            });
        $(document).ready(function(){
            
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
    @endpush
</html>