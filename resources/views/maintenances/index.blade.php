@extends('layouts.admin.app')

@section('title', 'Kelola Maintenance Project')

@section('content')
    <div class="container">
        <h2>Daftar Maintenance Project</h2>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary mb-3">Tambah Maintenance</a>
        <a href="{{ route('maintenances.downloadPdf') }}" class="btn btn-success mb-3">Download PDF</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Klien</th>
                    <th>Alamat</th>
                    <th>Project</th>
                    <th>Tanggal Setting</th>
                    <th>Tanggal Serah Terima</th>
                    <th>Maintenance</th> <!-- Kolom maintenance ditambahkan -->
                    <th>Dokumentasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenances as $key => $maintenance)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $maintenance->nama_klien }}</td>
                        <td>{{ $maintenance->alamat }}</td>
                        <td>{{ $maintenance->project }}</td>
                        <td>{{ $maintenance->tanggal_setting }}</td>
                        <td>{{ $maintenance->tanggal_serah_terima }}</td>
                        <td>{{ $maintenance->maintenance ?? 'Tidak Ada' }}</td> <!-- Menampilkan data maintenance -->
                        <td>
                            @if ($maintenance->dokumentasi)
                                <img src="{{ asset('storage/' . $maintenance->dokumentasi) }}" alt="Dokumentasi" width="100">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td>{{ $maintenance->status }}</td>
                        <td>
                            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" style="display:inline;">
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
