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
                <div class="col">
                    <input type="date" class="form-control" id="Tanggal" name="tanggal" value="{{ isset($dataJ)?$dataJ->tanggal:'' }}" required>
                </div>
            </div>
            <!-- KETERANGAN -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="KeteranganInputJurnal" class="form-label">KETERANGAN</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ isset($dataJ)?$dataJ->keterangan:'' }}" required>
                </div>
            </div>
            <!-- TRANSAKSI -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Transaksi" class="form-label">TRANSAKSI</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Transaksi" name="transaksi" value="{{ isset($dataJ)?$dataJ->transaksi:'' }}" required>
                </div>
            </div>
            <!-- Bukti -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Bukti" class="form-label">BUKTI</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Bukti" name="bukti" value="{{ isset($dataJ)?$dataJ->bukti:'' }}" required>
                </div>
            </div>
            <!-- Jumlah -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="jumlah" class="form-label">JUMLAH</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ isset($dataJ)?$dataJ->jumlah:'' }}" required>
                </div>
            </div>
            <!-- nama akun debit -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Nama_akun_debit" class="form-label">NAMA AKUN Debit</label>
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
            <!-- Debet -->
            <!-- <div class="row mb-3">
                <div class="col-2">
                    <label for="akunD" class="form-label">DEBET</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Debet" name="akunD" value="{{ isset($dataJ)?$dataJ->akunD:'' }}">
                </div>
            </div> -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="rpD" class="form-label">Rp</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="rpD" name="rpD" value="{{ isset($dataJ)?$dataJ->rpD:'' }}" required>
                </div>
            </div>
            <!-- nama akun kredit -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Nama_akun_kredit" class="form-label">NAMA AKUN KREDIT</label>
                </div>
                <div class="col">
                  <select class="form-select" id="Nama_akun_kredit" name="Nama_akun_kredit">
                    <option selected>Choose...</option>
                    @foreach($dataKredit as $dk)
                    <option value="{{$dk->id}}" {{ isset($dataJ) && $dataJ->akunK == $dk->Nama_akun ? 'selected' : '' }}>{{$dk->Nama_akun}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
            <!-- KREDIT -->
            <!-- <div class="row mb-3">
                <div class="col-2">
                    <label for="akunK" class="form-label">KREDIT</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="akunK" name="akunK" value="{{ isset($dataJ)?$dataJ->akunK:'' }}">
                </div>
            </div> -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="rpK" class="form-label">Rp</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="rpK" name="rpK" value="{{ isset($dataJ)?$dataJ->rpK:'' }}" required>
                </div>
            </div>
            <!-- btn -->
            <div class="row mb-3">
                <div class="col">
                    <div class="d-flex justify-content-end mt-3 ">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="/jTambahData"><button type="button" class="btn btn-danger mx-1">Reset</button></a>
                        <a href="/jurnal"><button type="button" class="btn btn-warning mx-1">Kembali</button></a>
                    </div>
                </div>
            </div>           
          </form>
        </div>      
    </div>
    @endsection
</body>
@push('styles')
<style></style>
@endpush
@push('scripts')
<script></script>
@endpush
</html>