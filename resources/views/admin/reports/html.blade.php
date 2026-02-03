<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SIG Dinas Pariwisata Jepara</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 32px;
            color: #2563eb;
        }
        .stat-card p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #2563eb;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistem Informasi Geografis</h1>
        <h2>Dinas Pariwisata dan Kebudayaan Kabupaten Jepara</h2>
        <p>Laporan Data Spasial: {{ ucwords(str_replace('_', ' ', $type)) }}</p>
        <p>Tanggal: {{ $date }}</p>
        @if(!empty($filterDesc))
            <p style="font-size: 14px; color: #666;">Filter: {{ $filterDesc }}</p>
        @endif
    </div>

    @if($type === 'all')
        <!-- Summary Stats for 'All' view -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>{{ $stats['places_count'] }}</h3>
                <p>Titik Lokasi</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['boundaries_count'] }}</h3>
                <p>Batas Wilayah</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['infrastructures_count'] }}</h3>
                <p>Infrastruktur</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['land_uses_count'] }}</h3>
                <p>Penggunaan Lahan</p>
            </div>
        </div>
    @endif

    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Total Data: {{ count($data) }}</p>
    @else
        <p style="text-align: center; margin-top: 50px; color: #666;">Tidak ada data yang ditemukan untuk kriteria ini.</p>
    @endif

    <div class="footer">
        <p>Dibuat oleh Sistem Informasi Geografis Dinas Pariwisata</p>
        <p>© {{ date('Y') }} Dinas Pariwisata dan Kebudayaan Kabupaten Jepara</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak sebagai PDF
        </button>
    </div>
</body>
</html>

