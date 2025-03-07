@extends('layouts.app')

@section('content')
    <h1>Form Surat Ekspedisi Baru</h1>

    <form action="{{ route('surat_ekspedisi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="nama">Nama</label>
            <input type="text" name="nama" value="{{ $nama }}" disabled>
        </div>

        <div>
            <label for="divisi">Divisi</label>
            <input type="text" name="divisi" value="{{ $divisi }}" disabled>
        </div>

        <div>
            <label for="keperluan">Keperluan</label>
            <textarea name="keperluan" required></textarea>
        </div>

        <div>
            <label for="file_surat">File Surat Ekspedisi (PDF)</label>
            <input type="file" name="file_surat" accept="application/pdf">
        </div>

        <button type="submit">Simpan</button>
    </form>
@endsection
