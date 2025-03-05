@extends('layouts.admin.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100" style="border-radius: 50px; overflow: hidden;">
        <!-- Left side: Login form -->
        <div class="col-md-6 p-5" style="background-color: #ffffff;">
            <h3 class="text-center mb-4 text-primary">Login</h3>

            @if (session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Login</button>
            </form>
        </div>

        <!-- Right side: Custom Section -->
        <div class="col-md-6 p-5 bg-primary text-white">
            <h4 class="text-center">Welcome, User!</h4>
            <p class="text-center">Login to start using our services</p>
            <div class="text-center mt-4">
                <img src="/path/to/your/logo.jpg" alt="Logo" class="img-fluid" style="max-width: 150px; margin-bottom: 20px;">
            </div>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Arial', sans-serif;
    }
    .form-control {
        border-radius: 8px;
    }
    .btn-primary {
        background-color: #2575fc;
        border: none;
    }
    .btn-primary:hover {
        background-color: #6a11cb;
    }
    .bg-primary {
        background-color: #2575fc !important;
    }
</style>
@endsection
