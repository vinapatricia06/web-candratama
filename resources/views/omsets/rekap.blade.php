@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Grafik Omset Tahunan</h1>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('omset.download-pdf') }}" class="btn btn-danger" id="downloadPdfBtn">Download PDF</a>
    </div>
    
    <div class="d-flex justify-content-end">
        <button><a href="{{ route('omsets.index') }}" class="btn btn-primary">Kembali</a></button>
    </div> 

    {{-- Tabel Rekap Omset --}}
      {{-- Tabel Rekap Omset --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>THN</th>
                <th>JANUARI</th>
                <th>FEBRUARI</th>
                <th>MARET</th>
                <th>APRIL</th>
                <th>MEI</th>
                <th>JUNI</th>
                <th>JULI</th>
                <th>AGUSTUS</th>
                <th>SEPTEMBER</th>
                <th>OKTOBER</th>
                <th>NOVEMBER</th>
                <th>DESEMBER</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $year => $months)
                @php
                    $total = 0;
                    $maxOmset = max($months); // Nilai tertinggi per tahun
                    $minOmset = min($months); // Nilai terendah per tahun
                @endphp
                <tr>
                    <td>{{ $year }}</td>
                    @for ($month = 1; $month <= 12; $month++)
                        @php
                            $omset = $months[$month] ?? 0;
                            $total += $omset;

                            // Tentukan kelas warna
                            $class = '';
                            if ($omset == $maxOmset) {
                                $class = 'table-success'; // Hijau untuk nilai tertinggi
                            } elseif ($omset == $minOmset) {
                                $class = 'table-danger'; // Merah untuk nilai terendah
                            } else {
                                $class = 'table-info'; // Biru untuk nilai lainnya
                            }
                        @endphp
                        <td class="{{ $class }}">Rp {{ number_format($omset, 0, ',', '.') }}</td>
                    @endfor
                    <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <h2 class="mt-5">Grafik Omset Tahunan</h2>
    <canvas id="chartOmset"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var labels = @json($labels);
    var totalPerTahun = @json($totalPerTahun);

    var maxOmset = Math.max(...totalPerTahun);
    var minOmset = Math.min(...totalPerTahun);

    var backgroundColors = totalPerTahun.map(value => {
        if (value === maxOmset) {
            return 'rgba(0, 255, 0, 0.5)'; 
        } else if (value === minOmset) {
            return 'rgba(255, 0, 0, 0.5)'; 
        } else {
            return 'rgba(0, 153, 255, 0.5)';
        }
    });

    var ctx = document.getElementById('chartOmset').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Omset',
                data: totalPerTahun,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(color => color.replace('0.5', '1')), // Border lebih gelap
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    document.getElementById('downloadPdfBtn').addEventListener('click', function(e) {
        e.preventDefault();
        var canvas = document.getElementById('chartOmset');
        var imageData = canvas.toDataURL('image/png'); 

        fetch("{{ route('omset.upload-chart') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ chart: imageData })
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = "{{ route('omset.download-pdf') }}";
        });
    });
</script>

@endsection
