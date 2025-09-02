<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script> -->
    <!-- <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script> -->
</head>
<body>
<div class="container-fluid">
        <div class="row d-flex mt-3 text-end mx-3">
            <h5><i class="fa-solid fa-user"></i> {{ session('name') }}</h5>
        </div>

</body>
</html>
<style>
    .dropdown-menu .dropdown-item:hover{
        background-color: #666 ;
    }

</style>
