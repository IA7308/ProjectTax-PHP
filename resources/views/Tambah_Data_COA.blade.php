@extends('main')

<body>
    @section('content')
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
                        <option value="HARTA" data-jenis="A REAL" {{ isset($data) && $data->kelompok_akun == 'HARTA' ? 'selected' : '' }}>HARTA</option>
                        <option value="HUTANG" data-jenis="A REAL" {{ isset($data) && $data->kelompok_akun == 'HUTANG' ? 'selected' : '' }}>HUTANG</option>
                        <option value="MODAL" data-jenis="A REAL" {{ isset($data) && $data->kelompok_akun == 'MODAL' ? 'selected' : '' }}>MODAL</option>
                        <option value="PENDAPATAN" data-jenis="A NOMINAL" {{ isset($data) && $data->kelompok_akun == 'PENDAPATAN' ? 'selected' : '' }}>PENDAPATAN</option>
                        <option value="BIAYA" data-jenis="A NOMINAL" {{ isset($data) && $data->kelompok_akun == 'BIAYA' ? 'selected' : '' }}>BIAYA</option>
                      </select>
                    </div>
                </div>
                <!-- KETERANGAN -->
                <div class="row mb-3">
                    <div class="col-2">
                        <label for="KeteranganTambahDataCoa" class="form-label">KETERANGAN</label>
                    </div>
                    <div class="col">
                      <select class="form-select" id="keterangan" name="keterangan">
                        <option selected>Choose...</option>
                        <option value="Akun, Debit" {{ isset($data) && $data->keterangan == 'Akun, Debit' ? 'selected' : '' }}>Akun, Debit</option>
                        <option value="Akun, Kredit" {{ isset($data) && $data->keterangan == 'Akun, Kredit' ? 'selected' : '' }}>Akun, Kredit</option>
                        <option value="Jumlah" {{ isset($data) && $data->keterangan == 'Jumlah' ? 'selected' : '' }} style="font-weight:bold;">Jumlah</option>
                        <option value="Header" {{ isset($data) && $data->keterangan == 'Header' ? 'selected' : '' }} style="font-weight:bold;">Header</option>

                      </select>
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
                        <a href="/cTambahData"><button type="button" class="btn btn-danger mx-1">Reset</button></a>
                        <a href="/"><button type="button" class="btn btn-warning mx-1">Kembali</button></a>
                    </div>
                </div>
            </form>
            <!-- NOTIF KODE DUPLIKAT -->
            <div class="alert alert-warning" id="Alert">
                <p><b>KODE DUPLIKAT</b></p>
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

</html>
@push('styles')
<style></style>
@endpush
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#JenisAkun').change(function() {
            var selectedJenis = $(this).val();
            $('#KelompokAkun option').hide();
            $('#KelompokAkun option[data-jenis="' + selectedJenis + '"]').show();
            $('#KelompokAkun option[data-jenis="' + selectedJenis + '"]:visible').first().prop('selected', true);
        });
        $('#JenisAkun').change();

        $('#Kode').on('input', function () {
            var kodeValue = $(this).val();
            var notif = $('#Alert');
            if (isDuplicateKode(kodeValue)) {
                notif.show();
            }else {
                notif.hide();
            }
        });

        function isDuplicateKode(kodeValue) {
            var dataKode = @json($dataKode);

            return dataKode.includes(parseInt(kodeValue));
        }
        // var dataKode = @json($dataKode);
        // console.log(dataKode);
        var notif = $('#Alert');
        notif.hide();

    });
</script>
@endpush
