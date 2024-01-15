<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah INPUT JURNAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="card mt-3 p-3">
            <h3 class="card-title mt-2">TAMBAH INPUT JURNAL</h3>
        <hr>
        <form class="text-end">
            <!-- Tanggal Masuk -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Tanggal" class="form-label">TANGGAL </label>
                </div>
                <div class="col">
                    <input type="date" class="form-control" id="Tanggal">
                </div>
            </div>
            <!-- TRANSAKSI -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Transaksi" class="form-label">TRANSAKSI</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="Transaksi">
                </div>
            </div>
            <!-- KETERANGAN -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="KeteranganInputJurnal" class="form-label">KETERANGAN</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="KeteranganInputJurnal">
                </div>
            </div>
            <!-- Bukti -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Bukti" class="form-label">BUKTI</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="Bukti">
                </div>
            </div>
            <!-- Debet -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Debet" class="form-label">DEBET</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="Debet">
                </div>
            </div>
            
            <!-- KREDIT -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Kredit" class="form-label">KREDIT</label>
                </div>
                <div class="col">
                    <input type="number" class="form-control" id="Kredit">
                </div>
            </div>
            <!-- Alamat -->
            <div class="row mb-3">
                <div class="col-2">
                    <label for="Alamat" class="form-label">Alamat</label>
                </div>
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