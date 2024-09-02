@extends('main')

@section('title', $title)

<head>
    <title>Verifikasi Akun</title>
</head>
<body>
    <p>Halo <b>{{$details['username']}}</b>!</p>
    <p>Berikut ini adalah Data Anda:</p>
    <table>
      <tr>
        <td>Username</td>
        <td>:</td>
        <td>{{$details['username']}}</td>
      </tr>
      <tr>
        <td>Role</td>
        <td>:</td>
        <td>{{$details['role']}}</td>
      </tr>
      <tr>
        <td>Website</td>
        <td>:</td>
        <td>{{$details['website']}}</td>
      </tr>
      <tr>
        <td>Tanggal Register</td>
        <td>:</td>
        <td>{{$details['datetime']}}</td>
      </tr>
    </table>

    <center>
      <h3>Copy link di bawah ini ke browser Anda untuk Verifikasi Akun:</h3>
      <b style="color:blue">{{$details['url']}}</b>
    </center>

  <p>Terima kasih telah melakukan registrasi.</p>
</body>
</html>
@push('styles')
<style></style>
@endpush
@push('scripts')
@endpush