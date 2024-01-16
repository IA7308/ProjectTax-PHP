<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data COA</title>
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
            <h3 class="card-title mt-2">{{$title}} DATA COA</h3>
            <hr>
            <form class="text-end" action="{{ $action }}">
                @csrf
                <input type="hidden" name="_method" value="{{ $method }}" />
                <!-- JENIS AKUN -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="JenisAkun" class="form-label">JENIS AKUN</label>
                    </div>
                    <div class="col">
                      <select class="form-select" id="JenisAkun" name="jenis_akun">
                        <option selected>Choose...</option>
                        <option value="A REAL" {{ isset($data) && $data->jenis_akun == 'A REAL' ? 'selected' : '' }}>A REAL</option>
                        <option value="A NOMINAL" {{ isset($data) && $data->jenis_akun == 'A NOMINAL' ? 'selected' : '' }}>A NOMINAL</option>
                      </select>
                    </div>
                </div>
                <!-- KELOMPOK AKUN -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="KelompokAkun" class="form-label">KELOMPOK AKUN</label>
                    </div>
                    <div class="col">
                      <select class="form-select" id="KelompokAkun" name="kelompok_akun">
                        <option selected>Choose...</option>
                        <option value="HARTA" {{ isset($data) && $data->kelompok_akun == 'HARTA' ? 'selected' : '' }}>HARTA</option>
                        <option value="HUTANG" {{ isset($data) && $data->kelompok_akun == 'HUTANG' ? 'selected' : '' }}>HUTANG</option>
                        <option value="MODAL" {{ isset($data) && $data->kelompok_akun == 'MODAL' ? 'selected' : '' }}>MODAL</option>
                        <option value="PENDAPATAN" {{ isset($data) && $data->kelompok_akun == 'PENDAPATAN' ? 'selected' : '' }}>PENDAPATAN</option>
                        <option value="BIAYA" {{ isset($data) && $data->kelompok_akun == 'BIAYA' ? 'selected' : '' }}>BIAYA</option>
                      </select>
                    </div>
                </div>
                <!-- KETERANGAN -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="KeteranganTambahDataCoa" class="form-label">KETERANGAN</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="KeteranganTambahDataCoa" name="keterangan" value="{{ isset($data)?$data->keterangan:'' }}" >
                    </div>
                </div>
                <!-- KODE -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="Kode" class="form-label">KODE</label>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="Kode" name="kode" value="{{ isset($data)?$data->kode:'' }}">
                    </div>
                </div>
                <!-- KODE -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="NamaAkun" class="form-label">NAMA AKUN</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="NamaAkun" name="Nama_akun" value="{{ isset($data)?$data->Nama_akun:'' }}">
                    </div>
                </div>
                <!-- SALDO AWAL -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="AngkaSaldoAwal" class="form-label">SALDO AWAL</label>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="AngkaSaldoAwal" name="Saldo_awal" value="{{ isset($data)?$data->Saldo_awal:'' }}">
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mt-3" >
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="submit" class="btn btn-danger mx-1">Reset</button>
                        <button type="submit" class="btn btn-warning mx-1">Kembali</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>