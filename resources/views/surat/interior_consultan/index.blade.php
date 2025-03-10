@extends('layouts.admin.app')

@section('title', 'Kelola Surat Interior Consultant')
@section('content')

    <h1>Daftar Surat Interior Consultant</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('surat.interior_consultan.create') }}" class="btn btn-primary">Tambah Surat</a>
    </div>

    <table border="1" cellpadding="10" style="width: 100%; margin: 0 auto; border-collapse: collapse; text-align: center;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>No</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Keperluan</th>
                <th>File Surat</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $index => $surat)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }};">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->nama }}</td>
                    <td>{{ $surat->divisi }}</td>
                    <td>{{ $surat->keperluan }}</td>
                    <td>
                        @if ($surat->file_path)
                            <a href="{{ route('surat.interior_consultan.downloadfile', $surat->id) }}" class="btn btn-success">Download File</a>
                        @else
                            Tidak Ada File
                        @endif
                    </td>

                    <td>
                        <form action="{{ route('surat.interior_consultan.updateStatusPengajuan', $surat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status_pengajuan" class="form-select">
                                <option value="Pending" {{ $surat->status_pengajuan == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="ACC" {{ $surat->status_pengajuan == 'ACC' ? 'selected' : '' }}>ACC</option>
                                <option value="Tolak" {{ $surat->status_pengajuan == 'Tolak' ? 'selected' : '' }}>Tolak</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                        </form>
                    </td>

                    <td>
                        @if ($surat->file_path)
                            <a href="{{ route('surat.interior_consultan.viewPDF', $surat->id) }}" class="btn btn-primary">View File</a>
                        @endif
                        <a href="{{ route('surat.interior_consultan.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('surat.interior_consultan.destroy', $surat->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
