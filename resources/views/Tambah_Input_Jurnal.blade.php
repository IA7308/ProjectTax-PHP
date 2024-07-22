@extends('main')
@section('title', $title.'INPUT JURNAL')
<body>
    @section('content')
    <div class="container-fluid">
        <div class="card mt-3 p-3">
            <h3 class="card-title mt-2">{{$title}} INPUT JURNAL</h3>
        <hr>
        <form class="text-end" action="{{ $action }}">
            @csrf
            <input type="hidden" name="_method" value="{{ $method }}" />
            <!-- Tanggal Masuk -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Tanggal" class="form-label">TANGGAL</label>
                </div>
                @if(session('Multiple'))
                <div class="col">
                    <input type="date" class="form-control" id="Tanggal" name="tanggal" value="{{ isset($datapilihan)?$datapilihan->tanggal:'' }}" required>
                </div>
                @else
                <div class="col">
                    <input type="date" class="form-control" id="Tanggal" name="tanggal" value="{{ isset($datapilihan)?$datapilihan->tanggal:'' }}" required>
                </div>
                @endif                
            </div>
            <!-- KETERANGAN -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="KeteranganInputJurnal" class="form-label">KETERANGAN</label>
                </div>                
                @if(session('Multiple'))
                <div class="col">
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ isset($datapilihan)?$datapilihan->keterangan:'' }}" required>
                </div>
                @else
                <div class="col">
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ isset($datapilihan)?$datapilihan->keterangan:'' }}" required>
                </div>
                @endif
                
            </div>
            <!-- TRANSAKSI -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Transaksi" class="form-label">TRANSAKSI</label>
                </div>
                @if(session('Multiple'))
                <div class="col">
                    <input type="text" class="form-control" id="Transaksi" name="transaksi" value="{{ isset($datapilihan)?$datapilihan->transaksi:'' }}" required>
                </div>
                @else
                <div class="col">
                    <input type="text" class="form-control" id="Transaksi" name="transaksi" value="{{ isset($datapilihan)?$datapilihan->transaksi:'' }}" required>
                </div>
                @endif                
            </div>
            <!-- Bukti -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Bukti" class="form-label">BUKTI</label>
                </div>
                @if(session('Multiple'))
                <div class="col">
                    <input type="text" class="form-control" placeholder="Ganti / dengan - " id="Bukti" name="bukti" value="{{ isset($datapilihan)?$datapilihan->bukti:'' }}" required>
                </div>
                @else
                <div class="col">
                    <input type="text" class="form-control" placeholder="Ganti / dengan - " id="Bukti" name="bukti" value="{{ isset($datapilihan)?$datapilihan->bukti:'' }}" required>
                </div>
                @endif
            </div>
            <!-- Jumlah -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="jumlah" class="form-label">JUMLAH</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ session('jumlahJurnal') }}" disabled required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col text-start">
                    <h6>DEBIT</h6>
                </div>
                <div class="col">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#ModalDebit">
                        Add New +
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="ModalDebit" tabindex="-1" aria-labelledby="ModalDebitLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="ModalDebitLabel">Tambah Debit</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-2">
                                            <label for="Label_akun_debit" class="form-label">NAMA AKUN Debit</label>
                                        </div>
                                        <div class="col">
                                        <select class="form-select" id="Nama_akun_debit" name="Nama_akun_debit">
                                            <option selected>Choose...</option>
                                            @foreach($dataDebit as $dd)
                                            <option value="{{$dd->id}}" {{ isset($dataJ) && $dataJ->akunD == $dd->Nama_akun ? 'selected' : '' }}>{{$dd->Nama_akun}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-2">
                                            <label for="rpD" class="form-label">Rp</label>
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" id="rpD" name="rpD" value="{{ isset($dataJ)?$dataJ->rpD:'' }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-2">
                                            <input type="checkbox" name="aggrement" id="aggrement" required>
                                        </div>
                                        <div class="col">
                                            <p style="font-size: 12; font-style: italic">Jika sudah Yakin Silahkan Centang, Karena Tanggal, Transaksi, bukti, dan Keterangan tidak akan bisa diubah lagi</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="submitDebit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
            
                </div>
                <div class="col text-start">
                    <h6>Kredit</h6>
                </div>
                <div class="col">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#ModalKredit">
                        Add New +
                    </button>
            
                    <!-- Modal -->
                    <div class="modal fade" id="ModalKredit" tabindex="-1" aria-labelledby="ModalKreditLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="ModalKreditLabel">Tambah Kredit</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ $actionModalKredit }}">
                                    @csrf
                                    <input type="hidden" name="!method" value="{{ $methodModal }}" />
                                <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-2">
                                                <label for="Label_akun_kredit" class="form-label">NAMA AKUN KREDIT</label>
                                            </div>
                                            <div class="col">
                                                <select class="form-select" id="Nama_akun_kredit" name="Nama_akun_kredit">
                                                    <option selected>Choose...</option>
                                                    @foreach($dataKredit as $dk)
                                                    <option value="{{$dk->id}}" {{ isset($dataJ) && $dataJ->akunK == $dk->Nama_akun ? 'selected' : '' }}>{{$dk->Nama_akun}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-2">
                                                    <label for="rpK" class="form-label">Rp</label>
                                                </div>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="rpK" name="rpK" value="{{ isset($dataJ)?$dataJ->rpK:'' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="submitKredit">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <table class="table table-hover table-info">
                        <thead>
                            <tr>
                                <th class="text-start">Debit</th>
                                <th class="text-end">Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($dataMultipleDebit as $MD)
                            <tr>
                                <td>{{$MD->akunD}} 
                                    <a href="/jD/{{$MD->id}}/{{session('jurnalid')}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}">X</a>
                                    @if(session('editMode'))
                                        <a href="/{{session('idjurnal')}}/{{$MD->JurnalId}}/{{$MD->id}}/{{$MD->bukti}}/{{$MD->tanggal}}/{{$MD->keterangan}}/{{$MD->transaksi}}/editJ">Select</a>
                                    @else
                                        <a href="/{{$MD->id}}/{{session('jurnalid')}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}/editDebit">Select</a>
                                    @endif
                                </td>
                                <td class="text-end">{{$MD->rpD}}</td>
                            </tr>
                            @endforeach
                            

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>JUMLAH</th>
                                @if(session('Multiple'))
                                <th class="text-end">{{number_format(session('jumlahDebit'), 2, ',', '.')}}</th>
                                @else
                                <th class="text-end">0</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th class="text-start">Kredit</th>
                                <th class="text-end">Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($dataMultipleKredit as $MK)
                            <tr>
                                <td>{{$MK->akunK}} 
                                    <a href="/jK/{{$MK->id}}/{{session('jurnalid')}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}">X</a>
                                    @if(session('editMode'))
                                    <a href="/{{session('idjurnal')}}/{{$MD->JurnalId}}/{{$MD->id}}/{{$MD->bukti}}/{{$MD->tanggal}}/{{$MD->keterangan}}/{{$MD->transaksi}}/editJ">Select</a>
                                    @else
                                        <a href="/{{$MD->id}}/{{session('jurnalid')}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}/editKredit">Select</a>
                                    @endif
                                </td>
                                
                                <td class="text-end">{{$MK->rpK}}</td>
                            </tr>
                            @endforeach
                
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>JUMLAH</th>
                                @if(session('Multiple'))
                                <th class="text-end">{{number_format(session('jumlahKredit'), 2, ',', '.')}}</th>
                                @else
                                <th class="text-end">0</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <input type="hidden" id="jurnalid" name="jurnalid" value="{{ session('jurnalid') }}" readonly/>
            <div class="row mb-3">
                <div class="col">
                    <div class="d-flex justify-content-end mt-3 ">
                        <button type="submit" class="btn btn-success" id="submitJurnal">Save</button>
                        @if(!session('Multiple') || session('editMode'))
                            <a href="/jTambahData"><button type="button" class="btn btn-danger mx-1">Reset</button></a>
                            <a href="/jurnal"><button type="button" class="btn btn-warning mx-1">Kembali</button></a>
                         @else
                            <form action="{{ route('resetJurnal', ['jurnalid' => session('jurnalid')]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger mx-1">Reset</button>
                            </form>
                            <form action="{{ route('kembaliJurnal', ['jurnalid' => session('jurnalid')]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning mx-1">Kembali</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>           
          </form>
          <!-- NOTIF KODE DUPLIKAT -->
          <div class="alert alert-warning" id="Alert">
                <p><b>KREDIT MASIH TERSEDIA</b></p>
            </div>
            <!-- NOTIF KODE DUPLIKAT (SETELAH SUBMIT) -->
            @if(session('error'))
            <div class="alert alert-warning">
                <b>{{ session('error') }}</b>
            </div>
            @endif
        </div>      
    </div>
    @endsection
</body>
@push('styles')
<style></style>
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        
        // Hitung selisih jumlah debit dan jumlah kredit saat halaman dimuat
        var jumlahDebit = parseFloat('{{ session('jumlahDebit') }}');
        var jumlahKredit = parseFloat('{{ session('jumlahKredit') }}');
        var selisihJumlah = jumlahDebit - jumlahKredit;

        var notif = $('#Alert');
        updateAlert(selisihJumlah);

        // Fungsi untuk mengupdate notifikasi saat input berubah
        $('#jumlah').on('input', function () {
            var jumlahDebit = parseFloat($('#JumlahDebit').val());
            var jumlahKredit = parseFloat($('#JumlahKredit').val());
            var selisihJumlah = jumlahDebit - jumlahKredit;

            updateAlert(number_format(selisihJumlah, 2, ',', '.'));
        });

        function updateAlert(selisih) {
            if (selisih != 0) {
                notif.html('<p><b>KREDIT MASIH TERSEDIA: ' + selisih + '</b></p>').show();
            } else {
                notif.html('<p><b>KREDIT MASIH TERSEDIA: ' + selisih + '</b></p>').hide();
            }
        }

        // $('#Jumlah').on('input', function () {
        //     var jumlahDebit = parseFloat('{{ session('jumlahDebit') }}');
        //     var jumlahKredit = parseFloat('{{ session('jumlahKredit') }}');
        //     var selisihJumlah = jumlahDebit - jumlahKredit;

        //     var notif = $('#Alert');
        //     if (selisihJumlah != 0) {
        //         notif.show();
        //     }else {
        //         notif.hide();
        //     }
        // });

        // function isDuplicateKode(kodeValue) {
        //     var dataKode = @json($dataKode);

        //     return dataKode.includes(kodeValue.toString());
        // }
        // var dataKode = @json($dataKode);
        // console.log(dataKode);
        // var notif = $('#Alert');
        // notif.hide();

        $('#submitDebit').click(function() {
            var tanggal = $('#Tanggal').val();
            var bukti = $('#Bukti').val();
            var akunD = $('#Nama_akun_debit').val();
            var rpD = $('#rpD').val();
            var transaksi = $('#Transaksi').val();
            var keterangan = $('#keterangan').val();
            var jurnalid = $('#jurnalid').val();

            // Kirim data menggunakan AJAX
            $.ajax({
                url: "{{ route('jTambahDebit') }}", // Ganti 'nama.route.anda' dengan route Anda yang mengarah ke fungsi storeDebit
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    transaksi: transaksi,
                    keterangan: keterangan,
                    tanggal: tanggal,
                    bukti: bukti,
                    jurnalid :jurnalid,
                    akunD: akunD,
                    rpD: rpD
                },
                success: function(response) {
                    // Handle response jika diperlukan
                    console.log(response);
                    // Tutup modal
                    $('#ModalDebit').modal('hide');
                    window.location.href = '/jTambahData/'+jurnalid+'/'+bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi;
                },
                error: function(xhr, status, error) {
                    // Handle error jika terjadi
                    console.error(xhr.responseText);
                }
            });
        });

        $('#submitKredit').click(function() {
            var tanggal = $('#Tanggal').val();
            var bukti = $('#Bukti').val();
            var akunK = $('#Nama_akun_kredit').val();
            var rpK = $('#rpK').val();
            var transaksi = $('#Transaksi').val();
            var keterangan = $('#keterangan').val();
            var jurnalid = $('#jurnalid').val();

            // Kirim data menggunakan AJAX
            $.ajax({
                url: "{{ route('jTambahKredit') }}", // Ganti 'nama.route.anda' dengan route Anda yang mengarah ke fungsi storeDebit
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    transaksi: transaksi,
                    keterangan: keterangan,
                    tanggal: tanggal,
                    bukti: bukti,
                    jurnalid :jurnalid,
                    akunK: akunK,
                    rpK: rpK
                },
                success: function(response) {
                    // Handle response jika diperlukan
                    console.log(response);
                    // Tutup modal
                    $('#ModalDebit').modal('hide');
                    window.location.href = '/jTambahData/'+jurnalid+'/'+bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi;
                },
                error: function(xhr, status, error) {
                    // Handle error jika terjadi
                    console.error(xhr.responseText);
                }
            });
        });

        $('#submitJurnal').click(function() {
            var jumlah = $('#jumlah').val();
            var tanggal = $('#Tanggal').val();
            var bukti = $('#Bukti').val();
            var transaksi = $('#Transaksi').val();
            var keterangan = $('#keterangan').val();
            var jurnalid = $('#jurnalid').val();

            var pathArray = window.location.pathname.split('/');
            var id = pathArray[1];
            var idakun = pathArray[3];
            var editj =pathArray[7];

            var url;
            if (editj === 'editJ') {
                url = '/' + id + '/' + jurnalid + '/' + idakun + '/updateJ';
            } else if (editj === 'editDebit') {
                url = '/' + id + '/' + jurnalid + '/' +bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi+'/updateD'; // Gantilah dengan rute yang benar untuk 'editDebit'
            } else if (editj === 'editKredit') {
                url = '/' + id + '/' + jurnalid + '/' +bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi+'/updateK'; // Gantilah dengan rute yang benar untuk 'editDebit'
            } else {
                url = "{{ route('tambahJurnal') }}";
            }

            // Kirim data menggunakan AJAX
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    transaksi: transaksi,
                    keterangan: keterangan,
                    tanggal: tanggal,
                    jumlah: jumlah,
                    bukti: bukti
                },
                success: function(response) {
                    // Handle response jika diperlukan
                    console.log(response);
                    if (editj === 'editDebit' || editj === 'editKredit') {
                        window.location.href = '/jTambahData/' + jurnalid + '/' + bukti + '/' + tanggal + '/' + keterangan + '/' + transaksi;
                    } else {
                        window.location.href = '/jurnal';
                    }       
                },
                error: function(xhr, status, error) {
                    // Handle error jika terjadi
                    console.error(xhr.responseText);
                }
            });
        });

    });
</script>
@endpush
</html>