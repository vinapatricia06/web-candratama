@extends('layouts.admin.app')

@section('title', 'Kelola Progress Project')

@section('content')
    <div class="container-fluid">
        <h2>Daftar Progress Project</h2>
        <a href="{{ route('progress_projects.create') }}" class="btn btn-primary mb-3">Tambah Project</a>
        <a href="{{ route('progress_projects.downloadPdf') }}" class="btn btn-success mb-3">Download PDF</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" style="font-size: 18px; width: 100%; table-layout: fixed;">
            <thead class="table-light">
                <tr>
                    <th style="font-size: 20px;">No</th>
                    <th style="font-size: 20px;">Teknisi</th>
                    <th style="font-size: 20px;">Klien</th>
                    <th style="font-size: 20px;">Alamat</th>
                    <th style="font-size: 20px;">Project</th>
                    <th style="font-size: 20px;">Tanggal Setting</th>
                    <th style="font-size: 20px;">Dokumentasi</th>
                    <th style="font-size: 20px;">Status</th>
                    <th style="font-size: 20px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $key => $project)
                    <tr>
                        <td style="font-size: 18px;">{{ $key + 1 }}</td>
                        <td style="font-size: 18px;">{{ $project->teknisi->nama ?? 'Tidak Ada' }}</td>
                        <td style="font-size: 18px;">{{ $project->klien }}</td>
                        <td style="font-size: 18px;">{{ $project->alamat }}</td>
                        <td style="font-size: 18px;">{{ $project->project }}</td>
                        <td style="font-size: 18px;">{{ $project->tanggal_setting }}</td>
                        <td style="font-size: 18px;">
                            @if ($project->dokumentasi)
                                <img src="{{ asset($project->dokumentasi) }}" alt="Dokumentasi" width="120">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td style="font-size: 18px;">{{ $project->status }}</td>
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
