<!-- resources/views/dashboard/index.blade.php -->

@extends('layouts.admin.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Direktur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <style>
        .container { max-width: 1200px; margin-top: 30px; }
        .card { margin-bottom: 20px; }
        .navbar { background-color: #007bff; }
        .navbar-brand { color: #fff; }
        /* .sidebar { background-color: #f8f9fa; padding: 20px; }
        .sidebar a { text-decoration: none; color: #000; padding: 10px 0; display: block; } */
        .sidebar a:hover { background-color: #007bff; color: #fff; }
        .main-content { padding: 20px; }
        .welcome-text { font-size: 24px; font-weight: bold; }
        .date-time { font-size: 16px; color: #888; }
        .section-title { font-size: 20px; margin-top: 30px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <!-- Sidebar -->
        {{-- <div class="col-md-3 sidebar">
            <h5>Menu</h5>
            <a href="#">Beranda</a>
            <a href="#">Keuangan</a>
            <a href="#">Proyek</a>
            <a href="#">Kinerja Tim</a>
            <a href="#">Pengaturan</a>
        </div> --}}

        <!-- Main Content -->
        <div class="col-md-9 main-content">
            <div class="card">
                <div class="card-body">
                    <h3 class="welcome-text">Selamat Datang, {{ $directorName }}</h3>
                    <p class="date-time">{{ $dateTime }}</p>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-body">
                    <h4 class="section-title">Informasi Terkini</h4>
                    <ul>
                        <li>Proyek A: Sedang Berjalan</li>
                        <li>Proyek B: Selesai</li>
                        <li>Proyek C: Belum Dimulai</li>
                    </ul>
                </div>
            </div> --}}

            {{-- <div class="card">
                <div class="card-body">
                    <h4 class="section-title">Agenda Hari Ini</h4>
                    <ul>
                        <li>08:00 - Rapat Tim Proyek A</li>
                        <li>12:00 - Makan Siang</li>
                        <li>14:00 - Pembaruan Keuangan</li>
                    </ul>
                </div>
            </div> --}}

            {{-- <div class="card">
                <div class="card-body">
                    <h4 class="section-title">Berita Perusahaan</h4>
                    <ul>
                        <li>Pengumuman: Perusahaan mendapat penghargaan</li>
                        <li>Berita: Perubahan kebijakan internal</li>
                        <li>Berita: Pembaruan sistem IT</li>
                    </ul>
                </div>
            </div> --}}

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
