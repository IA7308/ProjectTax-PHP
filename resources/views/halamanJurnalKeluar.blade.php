@extends('main')
@section('title', $title.' INPUT BARANG KELUAR')
<body>
    @section('content')
    <div class="container-fluid">
        <div class="card mt-3 p-3">
            <h3 class="card-title mt-2">{{$title}} INPUT BARANG KELUAR</h3>
        <hr>
        <form class="text-end" action="{{ $action }}">
            @csrf
            <input type="hidden" name="_method" value="{{ $method }}" />
            <!-- Lihat Barang -->
            <div class="row mb-3">
                <div class="col-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalBarang">Lihat Barang</button>
                    <!-- Modal Barang -->
                    <div class="modal fade" id="exampleModalBarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">List Barang</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <thead class="table table-dark">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Kode Barang</th>
                                            <th>Stock</th>
                                            <th>Harga Barang /unit</th>
                                            <!-- <th>Aksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataBarang as $dbarang)
                                            <tr>
                                                <td>{{$dbarang->nama_barang}}</td>
                                                <td>{{$dbarang->kode_barang}}</td>
                                                <td>{{$dbarang->stock_akhir}}</td>
                                                <td class="text-end">{{number_format($dbarang->harga_keluar, 2, ',', '.')}}</td>
                                                <!-- <td><button type="button" class="btn btn-success">Select</button></td> -->
                                            </tr>
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!session('editMode'))
                <!-- <div class="row mb-3">
                    <div class="col-2">
                        <label for="type" class="form-label">Jenis Jurnal</label>
                    </div>
                    <div class="col">
                        <select class="form-select" id="JenisAkun" name="jenis_akun">
                            <option selected>Choose...</option>
                            <option value="Harian">Harian</option>
                            <option value="Penjualan">Penjualan</option>
                            <option value="Pembelian">Pembelian</option>
                        </select>
                    </div>                
                </div> -->
            @endif
            <!-- Tanggal -->
            <div class="row mb-3">
                            <div class="col-2">
                                <label for="Tanggal" class="form-label">Tanggal</label>
                            </div>
                            <div class="col">                                
                                <input type="date" class="form-control" id="Tanggal" name="tanggal" value="{{ isset($data)?$data->tanggal:'' }}">
                            </div>
                        </div>
                        <!-- nama_penjual -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="namapembeli" id="namapembeli" value="{{ isset($data)?$data->nama_penjual:'' }}">
                            </div>
                        </div>
                        
                        <!-- NAMA BARANG -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="nama_barang" class="form-label">NAMA BARANG</label>
                            </div>
                            <div class="col">
                                <select class="form-select" id="nama_barang" name="nama_barang">
                                    <option selected>Choose...</option>
                                    @foreach($dataBarang as $db)
                                        <option value="{{$db->id}}" {{ isset($namaBarang) && $namaBarang->nama_barang == $data->nama_barang ? 'selected' : '' }} data-kode_barang="{{$db->kode_barang}}" data-nama_penjual="{{$db->nama_penjual}}" data-harga="{{$db->harga_keluar}}">{{$db->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- KODE BARANG -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="kode_barang" class="form-label">KODE BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="{{ isset($data)?$data->kode_barang:'' }}" readonly>
                            </div>
                        </div>
                        <!-- KETERANGAN -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="keterangan" class="form-label">KETERANGAN</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ isset($data)?$data->keterangan:'' }}">
                            </div>
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
                        <!-- UNIT KELUAR -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="unit_keluar" class="form-label">UNIT KELUAR</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="unit_keluar" name="unit_keluar" value="{{ isset($data)?$data->unit_keluar:'' }}" placeholder="0">
                            </div>
                        </div>
                        <!-- harga -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="harga" class="form-label">HARGA JUAL /UNIT</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="harga" name="harga" value="{{ isset($data)?$data->harga:'' }}" placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="alert alert-warning text-center" id="Alert">
                <p><b>KREDIT MASIH TERSEDIA</b></p>
            </div>
            <!-- NOTIF KODE DUPLIKAT (SETELAH SUBMIT) -->
            @if(session('error'))
            <div class="alert alert-warning">
                <b>{{ session('error') }}</b>
            </div>
            @endif
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
                                        <a href="/{{session('jurnalid')}}/{{session('jurnalid')}}/{{$MD->id}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}/editJ">Select</a>
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
                                    <a href="/{{session('jurnalid')}}/{{session('jurnalid')}}/{{$MK->id}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}/editJ">Select</a>
                                    @else
                                        <a href="/{{$MK->id}}/{{session('jurnalid')}}/{{session('namaBkt')}}/{{session('namaTgl')}}/{{session('namaKtr')}}/{{session('namaTr')}}/editKredit">Select</a>
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
                        @if(!session('Multiple'))
                            <a href="/jTambahData"><button type="button" class="btn btn-danger mx-1">Reset</button></a>
                            <a href="/jurnal"><button type="button" class="btn btn-warning mx-1">Kembali</button></a>
                         @elseif (session('editMode'))
                            <form action="{{ route('resetJurnal', ['jurnalid' => session('jurnalid')]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger mx-1">Reset</button>
                            </form>
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

        function updateAlert(selisih) {
            if (parseFloat(selisih) != 0) {
                notif.html('<p><b>KREDIT MASIH TERSEDIA: ' + selisih + '</b></p>').show();
            } else {
                notif.html('<p><b>KREDIT MASIH TERSEDIA: ' + selisih + '</b></p>').hide();
            }
        }

        // Hitung selisih jumlah debit dan jumlah kredit saat halaman dimuat
        var jumlahKredit = parseFloat('{{ session('jumlahKredit') }}');
        var JumlahHarga = parseFloat($('#harga').val() * $('#unit_keluar').val());
        var selisihJumlah = jumlahKredit - JumlahHarga;
        console.log(jumlahKredit);
        console.log(JumlahHarga);
        console.log(selisihJumlah);
        var notif = $('#Alert');
        updateAlert(selisihJumlah.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

        // Fungsi untuk mengupdate notifikasi saat input berubah
        $('#unit_keluar').on('input', function () {
            var jumlahKredit = parseFloat('{{ session('jumlahKredit') }}');
            var JumlahHarga = (parseFloat($('#harga').val()) * parseFloat($('#unit_keluar').val()));
            var selisihJumlah = jumlahKredit - JumlahHarga;


            updateAlert(selisihJumlah.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        });
        
        $('#nama_barang').on('change', function() {
            // Ambil data dari option yang dipilih
            var selectedOption = $(this).find('option:selected');
            var kodeBarang = selectedOption.data('kode_barang');
            var keterangan = selectedOption.data('keterangan');
            var namapenjual = selectedOption.data('nama_penjual');
            var harga =selectedOption.data('harga');

            // Isi input dengan nilai yang sesuai
            $('#kode_barang').val(kodeBarang);
            $('#keterangan').val(keterangan);
            $('#harga').val(harga);

        });

        $('#submitDebit').click(function() {
            var tanggal = $('#Tanggal').val();
            var bukti = $('#Bukti').val();
            var akunD = $('#Nama_akun_debit').val();
            var rpD = $('#rpD').val();
            var transaksi = $('#Transaksi').val();
            var keterangan = $('#keterangan').val();
            var jurnalid = $('#jurnalid').val();

            var pathArray = window.location.pathname.split('/');
            var id = pathArray[1];
            var idakun = pathArray[3];
            var editj =pathArray[8];

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
                    window.location.href = '/'+jurnalid + '/inputstock-out';
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

            var pathArray = window.location.pathname.split('/');
            var id = pathArray[1];
            var idakun = pathArray[3];
            var editj = pathArray[8];
            

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
                    $('#ModalKredit').modal('hide');
                    window.location.href = '/'+ jurnalid + '/inputstock-out';
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
            var namapembeli = $('#namapembeli').val();
            var kode_barang = $('#kode_barang').val();
            var nama_barang = $('#nama_barang').val();
            var unit_keluar = $('#unit_keluar').val();
            var harga = $('#harga').val();

            var pathArray = window.location.pathname.split('/');
            var id = pathArray[1];
            var idakun = pathArray[3];
            var editj =pathArray[2];
            var update =pathArray[7];

            var url;
            if (editj === 'bkEdit') {
                url = '/' + id + '/bkUpdate';
            } else if (update === 'editDebit') {
                url = '/' + id + '/' + jurnalid + '/' +bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi+'/updateD'; // Gantilah dengan rute yang benar untuk 'editDebit'
            } else if (update === 'editKredit') {
                url = '/' + id + '/' + jurnalid + '/' +bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi+'/updateK'; // Gantilah dengan rute yang benar untuk 'editDebit'
            } else {
                url = "/bOutStore";
            }

            // Kirim data menggunakan AJAX
            $.ajax({
                url: url,
                type: editj ? "GET" : "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    transaksi: transaksi,
                    keterangan: keterangan,
                    tanggal: tanggal,
                    jumlah: jumlah,
                    bukti: bukti,
                    namapembeli:namapembeli,
                    nama_barang:nama_barang,
                    kode_barang:kode_barang,
                    unit_keluar: unit_keluar,
                    harga: harga,
                    jurnalid:jurnalid
                },
                success: function(response) {
                    // Handle response jika diperlukan
                    console.log(response);
                    if (update === 'editDebit' || update === 'editKredit') {
                        if(session('editMode')){
                            window.location.href = '/'+id+'/'+jurnalid+'/'+idakun+'/'+bukti+'/'+tanggal+'/'+keterangan+'/'+transaksi+'/editJ';
                        }else{
                            window.location.href = '/jTambahData/' + jurnalid + '/' + bukti + '/' + tanggal + '/' + keterangan + '/' + transaksi;
                        }
                        
                    } else {
                        window.location.href = '/stock-out';
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