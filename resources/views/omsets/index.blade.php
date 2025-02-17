@extends('layouts.admin.app')

@section('title', 'Kelola Omset')

@section('content')
    <div class="container">
        <h2>Data Omset</h2>

        <div class="d-flex justify-content-end">
            <button><a href="{{ route('omsets.rekap') }}" class="btn btn-primary">Rekap Omset</a></button>
        </div>        

        <!-- Form untuk pencarian dengan bulan -->
        <form action="{{ route('omsets.index') }}" method="GET" class="mb-3">
            <div class="input-group" style="max-width: 400px;">
                <input type="text" name="search" class="form-control" placeholder="Cari klien..."
                    value="{{ request()->get('search') }}">
                <select name="bulan" class="form-control">
                    <option value="">Pilih Bulan</option>
                    @foreach (range(1, 12) as $bulan)
                        <option value="{{ $bulan }}" {{ request()->get('bulan') == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <!-- Tombol untuk download Excel -->
        <a href="{{ route('omsets.export') }}" class="btn btn-success mb-3">Download Excel</a>

        <a href="{{ route('omsets.create') }}" class="btn btn-primary mb-3">Tambah Omset</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nama Klien</th>
                    <th>Alamat</th>
                    <th>Project</th>
                    <th>Sumber Lead</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($omsets as $omset)
                    <tr>
                        <td>{{ $omset->id_omset }}</td>
                        <td>{{ $omset->tanggal }}</td>
                        <td>{{ $omset->nama_klien }}</td>
                        <td>{{ $omset->alamat }}</td>
                        <td>{{ $omset->project }}</td>
                        <td>{{ $omset->sumber_lead }}</td>
                        <td>Rp {{ number_format($omset->nominal, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('omsets.edit', $omset->id_omset) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('omsets.destroy', $omset->id_omset) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
