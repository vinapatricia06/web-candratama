@extends('layouts.admin.app')

@section('title', 'Kelola Maintenance Project')

@section('content')
    <div class="container-fluid">
        <h2>Daftar Maintenance Project</h2>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary mb-3">Tambah Maintenance</a>
        <a href="{{ route('maintenances.downloadPdf') }}" class="btn btn-success mb-3">Download PDF</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" style="font-size: 16px; width: 100%; table-layout: auto;">
            <thead class="table-light">
                <tr>
                    <th style="font-size: 18px;">No</th>
                    <th style="font-size: 18px;">Klien</th>
                    <th style="font-size: 18px;">Alamat</th>
                    <th style="font-size: 18px;">Project</th>
                    <th style="font-size: 18px;">Tanggal Setting</th>
                    <th style="font-size: 18px;">Tanggal Serah Terima</th>
                    <th style="font-size: 18px;">Maintenance</th>
                    <th style="font-size: 18px;">Dokumentasi</th>
                    <th style="font-size: 18px;">Status</th>
                    <th style="font-size: 18px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenances as $key => $maintenance)
                    <tr>
                        <td style="font-size: 16px;">{{ $key + 1 }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->nama_klien }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->alamat }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->project }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->tanggal_setting }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->tanggal_serah_terima }}</td>
                        <td style="font-size: 16px;">{{ $maintenance->maintenance ?? 'Tidak Ada' }}</td>
                        <td style="font-size: 16px;">
                            @if ($maintenance->dokumentasi)
                                <img src="{{ asset($maintenance->dokumentasi) }}" alt="Dokumentasi" width="120">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td style="font-size: 16px;">{{ $maintenance->status }}</td>
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
