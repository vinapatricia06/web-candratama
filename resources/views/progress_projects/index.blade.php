@extends('layouts.admin.app')

@section('title', 'Kelola Progress Project')

@section('content')
    <div class="container-fluid">
        <h2>Daftar Progress Project</h2>

        <!-- Flexbox layout for positioning "Tambah Project" button -->
        <div class="d-flex justify-content-between mb-3" style="max-width: 650px;">
            <a href="{{ route('progress_projects.create') }}" class="btn btn-primary">Tambah Project</a>
        </div>

        <!-- Form untuk memilih bulan dengan desain yang lebih rapi -->
        <form action="{{ route('progress_projects.index') }}" method="GET" class="mb-3 d-flex" style="max-width: 500px;">
            <div class="input-group" style="max-width: 300px;">
                <select name="bulan" id="bulan" class="form-control">
                    <option value="">Pilih Bulan</option>
                    @foreach (range(1, 12) as $bulan)
                        <option value="{{ $bulan }}" {{ request()->get('bulan') == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary ml-3">Cari</button>
        </form>

        <!-- Tombol untuk hapus semua data bulan yang dipilih -->
        @if(request()->get('bulan'))
            <form action="{{ route('progress_projects.hapusBulan') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="bulan" value="{{ request()->get('bulan') }}">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus semua data bulan ini? Semua data akan hilang.')">Hapus Semua Data Bulan Ini</button>
            </form>
        @endif

        <!-- Menampilkan pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Positioning the "Download PDF" button above the "Aksi" column -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('progress_projects.downloadPdf') }}" class="btn btn-success">Download PDF</a>
        </div>

        <!-- Tabel untuk menampilkan progress project -->
        <table class="table table-bordered" style="font-size: 18px; width: 100%; table-layout: fixed;">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Teknisi</th>
                    <th>Klien</th>
                    <th>Alamat</th>
                    <th>Project</th>
                    <th>Tanggal Setting</th>
                    <th>Dokumentasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $key => $project)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $project->teknisi->nama ?? 'Tidak Ada' }}</td>
                        <td>{{ $project->klien }}</td>
                        <td>{{ $project->alamat }}</td>
                        <td>{{ $project->project }}</td>
                        <td>{{ $project->tanggal_setting }}</td>
                        <td>
                            @if ($project->dokumentasi)
                                <img src="{{ asset($project->dokumentasi) }}" alt="Dokumentasi" width="120">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td>{{ $project->status }}</td>
                        <td>
                            <a href="{{ route('progress_projects.edit', $project->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('progress_projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
