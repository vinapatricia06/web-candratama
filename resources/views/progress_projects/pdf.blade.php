<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Progress Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Daftar Progress Project</h1>
        <p>Berikut adalah daftar progress project yang tercatat.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Teknisi</th>
                <th>Klien</th>
                <th>Alamat</th>
                <th>Project</th>
                <th>Tanggal Setting</th>
                <th>Dokumentasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $key => $project)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $project->teknisi->nama ?? 'Tidak Ada' }}</td>
                    <td>{{ $project->klien }}</td>
                    <td>{{ $project->alamat }}</td>
                    <td>{{ $project->project }}</td>
                    <td>{{ $project->tanggal_setting }}</td>
                    <td>
                        @if ($project->dokumentasi)
                            <img src="{{ asset($project->dokumentasi) }}" alt="Dokumentasi" width="100">
                        @else
                            Tidak ada gambar
                        @endif
                    </td>
                    <td>{{ $project->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
