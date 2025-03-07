@extends('layouts.admin.app')

@section('title', 'Dashboard Surat Purchasing')
@section('content')

    {{-- Notifikasi surat masuk ke PCH --}}
    @if(session('suratDM') || session('suratADM') || session('suratWRH'))
        <div class="alert alert-warning">
            <strong>Notifikasi!</strong> Ada surat masuk ke <strong>Purchasing</strong> untuk segera ditindak lanjuti :
            <ul>
                @if(session('suratDM'))
                    <li>Dari <strong>Marketing</strong>: {{ session('suratDM') }} surat</li>
                @endif
                @if(session('suratADM'))
                    <li>Dari <strong>Admin</strong>: {{ session('suratADM') }} surat</li>
                @endif
                @if(session('suratWRH'))
                    <li>Dari <strong>Warehouse</strong>: {{ session('suratWRH') }} surat</li>
                @endif
            </ul>
        </div>
    @endif

    {{-- Notifikasi status surat yang telah diperbarui --}}
    @if(session('statusUpdated'))
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span>{{ session('statusUpdated') }}</span>
            <form action="{{ route('notif.clear') }}" method="POST" style="margin-left: 10px;">
                @csrf
                <button type="submit" class="btn-close"></button>
            </form>
        </div>
    @endif

    <h1>Rekap Surat Purchasing</h1>
    <div class="row">
        <div class="col-md-6">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctxStatus = document.getElementById('statusChart').getContext('2d');
        var statusChart = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Pending', 'ACC', 'Tolak'],
                datasets: [{
                    label: 'Status Pengajuan',
                    data: @json([$pending, $acc, $tolak]),
                    backgroundColor: ['#f39c12', '#2ecc71', '#e74c3c']
                }]
            }
        });

        var ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        var monthlyChart = new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Jumlah Surat per Bulan',
                    data: @json($monthlyCounts),
                    backgroundColor: '#3498db'
                }]
            }
        });

        // Fungsi untuk memutar suara notifikasi
        function playNotificationSound() {
            var audio = new Audio('{{ asset('sounds/notv.wav') }}');
            audio.play();
        }

        // Cek apakah ada notifikasi surat masuk ke Purchasing
        @if(session('suratDM') || session('suratADM') || session('suratWRH'))
            playNotificationSound();
        @endif
    </script>
@endsection
