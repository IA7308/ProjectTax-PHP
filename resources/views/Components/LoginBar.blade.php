<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2d0d4e5044.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">
        <div class="row d-flex mt-2">
            <div class="col">
                <a class="dropdown-toggle text-start" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{session('name')}}
                </a>
                <ul class="dropdown-menu position-relative" style="width: 100px; z-index: 1000;">
                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                    <li><a class="dropdown-item" href="/">Login</a></li>
                </ul>
            </div>
        </div>
        <br>
</body>
</html>
<style>
    .dropdown-menu .dropdown-item:hover{
        background-color: #666 ;
    }

</style>
