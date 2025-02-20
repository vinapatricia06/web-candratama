@extends('layouts.admin.app')

@section('content')
    <h1>Daftar Surat Pengajuan</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('surat.marketing.generate') }}" class="btn btn-primary">Tambah Surat</a>
    </div>
    <table border="1" cellpadding="10" style="width: 100%; margin: 0 auto; border-collapse: collapse; text-align: center;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>No</th>
                <th>No. Surat</th>
                <th>Dari Divisi</th>
                <th>Ke Divisi</th>
                <th>File Surat</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Mapping divisi untuk efisiensi
                $divisiMapping = [
                    'FNC' => 'Finance',
                    'PCH' => 'Purchasing',
                    'DM' => 'Digital Marketing',
                    'ADM' => 'Administrasi',
                    'WRH' => 'Warehouse',
                    'IC' => 'Interior Consultant',
                ];
            @endphp

            @foreach($suratMarketings as $index => $surat)
            <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }};">
                <td>{{ $index + 1 }}</td>
                <td>{{ $surat->formatted_nomor_surat }}</td>
                <td>{{ $divisiMapping[$surat->divisi_pembuat] ?? $surat->divisi_pembuat }}</td>
                <td>{{ $divisiMapping[$surat->divisi_tujuan] ?? $surat->divisi_tujuan }}</td>
                <td>
                    @if ($surat->file_path)
                        <a href="{{ route('surat.marketing.downloadfile', $surat->id) }}" class="btn btn-success">Download File</a>
                    @else
                        Tidak Ada File
                    @endif
                </td>

                <td>
                    <!-- Form untuk update status -->
                    <form action="{{ route('surat.marketing.updateStatusPengajuan', $surat->id) }}" method="POST">
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
                        <a href="{{ route('surat.marketing.viewPDF', $surat->id) }}" class="btn btn-primary">View File</a>
                    @endif

                    <!-- Tombol Edit -->
                    <a href="{{ route('surat.marketing.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                    
                    <!-- Tombol Delete -->
                    <form action="{{ route('surat.marketing.destroy', $surat->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus surat ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
