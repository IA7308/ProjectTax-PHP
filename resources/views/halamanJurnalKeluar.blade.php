@extends('main')
@section('title', $title.'INPUT JURNAL')
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
                                            <th>Keterangan</th>
                                            <th>Stock</th>
                                            <th>Harga Barang /unit</th>
                                            <!-- <th>Aksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataBarang as $dbarang)
                                            <tr>
                                                <td>{{$dbarang->nama_barang}}</td>
                                                <td>{{$dbarang->keterangan}}</td>
                                                <td>{{$dbarang->unit_keluar}}</td>
                                                <td class="text-end">{{number_format($dbarang->harga, 2, ',', '.')}}</td>
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
                                <label for="tanggal" class="form-label">Tanggal</label>
                            </div>
                            <div class="col">                                
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="">
                            </div>
                        </div>
                        <!-- nama_penjual -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="nama_penjual" class="form-label">Nama Penjual</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="namapenjual" id="namapenjual" readonly>
                            </div>
                        </div>
                        <!-- KODE BARANG -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="kode_barang" class="form-label">KODE BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="" readonly>
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
                                        <option value="{{$db->id}}" data-kode_barang="{{$db->kode_barang}}" data-keterangan="{{$db->keterangan}}" data-nama_penjual="{{$db->nama_penjual}}" data-harga="{{$db->harga}}">{{$db->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- KETERANGAN -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="keterangan" class="form-label">KETERANGAN</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="keterangan" name="keterangan" value="" readonly>
                            </div>
                        </div>
                        <!-- UNIT KELUAR -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="unit_keluar" class="form-label">UNIT KELUAR</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="unit_keluar" name="unit_keluar" value="">
                            </div>
                        </div>
                        <!-- harga -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="harga" class="form-label">HARGA BELI /UNIT</label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" id="harga" name="harga" value="" readonly>
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
            $('#namapenjual').val(namapenjual);
            $('#harga').val(harga);

        });
    });

</script>
@endpush
</html>