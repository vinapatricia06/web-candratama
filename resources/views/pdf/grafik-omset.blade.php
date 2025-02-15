<!DOCTYPE html>
<html>
<head>
    <title>Rekap Omset</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            table-layout: fixed;  
        }
        th, td { 
            border: 1px solid black; 
            padding: 5px; 
            text-align: center; 
            font-size: 10px; 
        }
        th { 
            background-color: #f2f2f2; 
            font-size: 11px; 
        }
        th:first-child, td:first-child {
            width: 10%; 
        }
        th:nth-child(n+2), td:nth-child(n+2) {
            width: 6%; 
        }
        th:last-child, td:last-child {
            width: 10%;
            font-weight: bold;
        }
        .chart-img { 
            text-align: center; 
            margin-top: 20px; 
        }
        
    </style>
</head>
<body>
    <h2>Rekap Omset Bulanan</h2>
    
    <table>
        <thead>
            <tr>
                <th>Tahun</th>
                @for ($i = 1; $i <= 12; $i++)
                    <th>Bulan {{ $i }}</th>
                @endfor
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $tahun => $omsetBulanan)
                <tr>
                    <td>{{ $tahun }}</td>
                    @for ($i = 1; $i <= 12; $i++)
                        <td>{{ number_format($omsetBulanan[$i] ?? 0, 0, ',', '.') }}</td>
                    @endfor
                    <td><strong>{{ number_format($totalPerTahun[$loop->index] ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total Omset per Tahun</h3>
    <ul>
        @foreach ($labels as $index => $tahun)
            <li><strong>{{ $tahun }}</strong>: Rp {{ number_format($totalPerTahun[$index] ?? 0, 0, ',', '.') }}</li>
        @endforeach
    </ul>

    <div class="chart-img">
        <img src="{{ $chartPath }}" alt="Grafik Omset" width="600">
    </div>
</body>
</html>
