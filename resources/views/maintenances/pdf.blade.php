<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Maintenance Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;  /* Agar lebar kolom lebih terkontrol */
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            word-wrap: break-word;  /* Memastikan teks panjang bisa terpecah */
            max-width: 150px;  /* Batasan lebar kolom */
            overflow: hidden;
            text-overflow: ellipsis;
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
        <h1>Daftar Maintenance Project</h1>
        <p>Berikut adalah daftar maintenance project yang tercatat.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Klien</th>
                <th>Alamat</th>
                <th>Project</th>
                <th>Tanggal Setting</th>
                <th>Tanggal Serah Terima</th>
                <th>Maintenance</th>
                <th>Dokumentasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($maintenances as $key => $maintenance)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $maintenance->nama_klien }}</td>
                    <td>{{ $maintenance->alamat }}</td>
                    <td>{{ $maintenance->project }}</td>
                    <td>{{ $maintenance->tanggal_setting }}</td>
                    <td>{{ $maintenance->tanggal_serah_terima }}</td>
                    <td>{{ $maintenance->maintenance }}</td>
                    <td>
                        @if ($maintenance->dokumentasi)
                            <img src="{{ url('storage/dokumentasi/' . $maintenance->dokumentasi) }}" alt="Dokumentasi" width="120">
                            
                        @else
                            Tidak ada gambar
                        @endif
                    </td>
                    <td>{{ $maintenance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
