@extends('layouts.admin.app')

@section('title', 'Kelola Progress Project')

@section('content')
    <div class="container">
        <h2>Daftar Progress Project</h2>
        <a href="{{ route('progress_projects.create') }}" class="btn btn-primary mb-3">Tambah Project</a>
        <a href="{{ route('progress_projects.downloadPdf') }}" class="btn btn-success mb-3">Download PDF</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
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
                                <img src="{{ asset($project->dokumentasi) }}" alt="Dokumentasi" width="100">
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
