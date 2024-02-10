@extends('main')

@section('title', 'NERACA LAJUR')

<body>
    @section('content')
    <div class="container-fluid text-center" id="container">
        <h3 class="mt-2">NERACA LAJUR</h3>
        <hr>
        <div class="card p-3">
            <!-- Pagination -->

            <div class="row">
                <div class="col text-start">
                    
                </div>
                <div class="col text-center">
                    <h2>NERACA LAJUR</h2>
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
            <!-- DATA TABEL -->
            <table id="myTable" class="table table-bordered table-hover text-start" name="tabelCOA">
                <thead>
                    <tr class="table table-primary">
                        <th  rowspan="2">KODE AKUN</th>
                        <th  rowspan="2">NAMA AKUN</th>
                        <th  colspan="2" class="text-center">NERACA SALDO(NS)</th>
                        <th  colspan="2" class="text-center">PENYESUAIAN</th>
                        <th  colspan="2" class="text-center">NS DISESUAIKAN</th>
                        <th  colspan="2" class="text-center">LABA/RUGI</th>
                        <th  colspan="2" class="text-center">NERACA</th>
                    </tr>
                    <tr>
                        <th><span>DEBIT</span></th>
                        <th><span>KREDIT</span></th>
                        <th><span>DEBIT</span></th>
                        <th><span>KREDIT</span></th>
                        <th><span>DEBIT</span></th>
                        <th><span>KREDIT</span></th>
                        <th><span>DEBIT</span></th>
                        <th><span>KREDIT</span></th>
                        <th><span>DEBIT</span></th>
                        <th><span>KREDIT</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr class="{{ $d->backgroundClass }}">
                        <td>{{$d->kode}}</td>
                        <td class="text-start">{{$d->nama_akun}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->rpD)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->rpK)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->rpPD)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->rpPK)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->SaldoPenyesuaianP)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->SaldoPenyesuaianN)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->LRD)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->LRK)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->nD)), 0, ',', '.')}}</td>
                        <td class="text-end">{{number_format(intval(abs($d->nK)), 0, ',', '.')}}</td>
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
        <p class="text-start mt-3">@2024 <b>CV.REGENCY</b></p>
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
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Dapatkan semua elemen dalam tabel dengan nilai
    //     var cellsWithValue = document.querySelectorAll('table#myTable td');
        
    //     // Iterasi melalui setiap elemen
    //     cellsWithValue.forEach(function(cell) {
    //         // Dapatkan nilai dari setiap sel
    //         var textValue = cell.innerText.trim();
            
    //         // Pastikan nilai tidak kosong dan berisi karakter '-'
    //         if (textValue !== '' && textValue.includes('-')) {
    //             // Parse teks ke bilangan bulat
    //             var value = parseInt(textValue.replace(/[^0-9-]/g, ''), 10);
                
    //             // Ubah nilai menjadi positif dan tambahkan tanda '-'
    //             cell.innerText = '(' + Math.abs(value).toLocaleString() + ')';
    //         }
    //     });
    // });
    $(document).ready(function () {
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        if ($.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }

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