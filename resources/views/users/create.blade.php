@extends('layouts.admin.app')

@section('title', 'Tambah User')

@section('content')
    <div class="container">
        <h2>Tambah User</h2>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin">Admin</option>
                    <option value="marketing">Marketing</option>
                    <option value="interior_consultan">Interior_Consultan</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="finance">Finance</option>
                    <option value="project_production">Project_Production</option>
                    <option value="teknisi">Teknisi</option>
                </select>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-danger mr-2">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
