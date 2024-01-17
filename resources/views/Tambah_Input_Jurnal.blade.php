<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}} INPUT JURNAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>
<body>
    @Component('Components.LoginBar')
    @endcomponent
    @Component('Components.Sidebar')
    @endcomponent
    <div class="container-fluid">
        <div class="card mt-3 p-3">
            <h3 class="card-title mt-2">TAMBAH INPUT JURNAL</h3>
        <hr>
        <form class="text-end" action="{{ $action }}">
            @csrf
            <input type="hidden" name="_method" value="{{ $method }}" />
            <!-- nama akun coa -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Nama_akun" class="form-label">NAMA AKUN</label>
                </div>
                <div class="col">
                  <select class="form-select" id="Nama_akun" name="Nama_akun">
                    <option selected>Choose...</option>
                    @foreach($data as $d)
                    <option value="{{$d->id}}" {{ isset($dataJ) && $dataJ->id == $d->id ? 'selected' : '' }}>{{$d->Nama_akun}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
            <!-- Tanggal Masuk -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Tanggal" class="form-label">TANGGAL</label>
                </div>
                <div class="col">
                    <input type="date" class="form-control" id="Tanggal" name="tanggal" value="{{ isset($dataJ)?$dataJ->tanggal:'' }}">
                </div>
            </div>
            <!-- TRANSAKSI -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Transaksi" class="form-label">TRANSAKSI</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Transaksi" name="transaksi" value="{{ isset($dataJ)?$dataJ->transaksi:'' }}">
                </div>
            </div>
            <!-- KETERANGAN -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="KeteranganInputJurnal" class="form-label">KETERANGAN</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ isset($dataJ)?$dataJ->keterangan:'' }}">
                </div>
            </div>
            <!-- Bukti -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Bukti" class="form-label">BUKTI</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Bukti" name="bukti" value="{{ isset($dataJ)?$dataJ->bukti:'' }}">
                </div>
            </div>
            <!-- Jumlah -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="jumlah" class="form-label">JUMLAH</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ isset($dataJ)?$dataJ->jumlah:'' }}">
                </div>
            </div>
            <!-- Debet -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="akunD" class="form-label">DEBET</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Debet" name="akunD" value="{{ isset($dataJ)?$dataJ->akunD:'' }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-2">
                    <label for="rpD" class="form-label">Rp</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="rpD" name="rpD" value="{{ isset($dataJ)?$dataJ->rpD:'' }}">
                </div>
            </div>
            
            <!-- KREDIT -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="akunK" class="form-label">KREDIT</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="akunK" name="akunK" value="{{ isset($dataJ)?$dataJ->akunK:'' }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-2">
                    <label for="rpK" class="form-label">Rp</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="rpK" name="rpK" value="{{ isset($dataJ)?$dataJ->rpK:'' }}">
                </div>
            </div>
            <!-- btn -->
            <div class="row mb-3">
                <div class="col">
                    <div class="d-flex justify-content-end mt-3 ">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="submit" class="btn btn-danger mx-1">Reset</button>
                        <button type="submit" class="btn btn-warning mx-1">Kembali</button>
                    </div>
                </div>
            </div>           
          </form>
        </div>      
    </div>
</body>
</html>