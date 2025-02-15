@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Omset</h2>
    <form action="{{ route('omsets.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Klien</label>
            <input type="text" name="nama_klien" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Project</label>
            <input type="text" name="project" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="sumber_lead">Sumber Lead</label>
            <input type="text" name="sumber_lead" id="sumber_lead" class="form-control" value="{{ old('sumber_lead', $omset->sumber_lead ?? '') }}">
        </div>
        <div class="form-group">
            <label for="nominal">Nominal</label>
            <input type="number" name="nominal" id="nominal" class="form-control" required>
        </div>
        <br>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
