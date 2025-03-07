@extends('layouts.app')

@section('content')
    <h1>Daftar Surat Ekspedisi</h1>

    <a href="{{ route('surat_ekspedisi.create') }}">Buat Surat Ekspedisi Baru</a>

    <ul>
        @foreach($surats as $surat)
            <li>
                <strong>{{ $surat->nama }}</strong> ({{ $surat->divisi }}) - {{ $surat->keperluan }}
                @if($surat->file_path)
                    <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank">Lihat File</a>
                @endif
                <a href="{{ route('surat_ekspedisi.edit', $surat->id) }}">Edit</a>
                <form action="{{ route('surat_ekspedisi.destroy', $surat->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
