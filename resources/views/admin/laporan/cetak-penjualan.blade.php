<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Custom Bowl</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; background: #fff; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 30px; gap: 10px; }
        .box { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; }
        .box h4 { margin: 0 0 10px; font-size: 10px; color: #777; text-transform: uppercase; }
        .box h2 { margin: 0; font-size: 18px; color: #000; }
        
        .flex-row { display: flex; justify-content: space-between; gap: 20px; margin-bottom: 30px; }
        .half { width: 48%; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 11px; color: #555;}
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 50px; font-size: 11px; color: #777; text-align: center; }
        .signature-area { display: flex; justify-content: flex-end; margin-top: 40px; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
    <script type="text/javascript">
        window.onafterprint = window.close;
    </script>
</head>
<body>

    <div class="header">
        <h1>Laporan Volume Penjualan</h1>
        <p>Custom Bowl | Analisis Tren dan Pembayaran</p>
        <p>Periode: <strong>{{ $tanggalMulai }}</strong> s/d <strong>{{ $tanggalAkhir }}</strong></p>
    </div>

    <div class="summary-box">
        <div class="box">
            <h4>Total Transaksi</h4>
            <h2>{{ number_format($totalTransaksi, 0, ',', '.') }} Order</h2>
        </div>
        <div class="box">
            <h4>Item Terjual</h4>
            <h2>{{ number_format($totalItemTerjual, 0, ',', '.') }} Porsi</h2>
        </div>
        <div class="box" style="background-color: #f5f3ff; border-color: #4f46e5;">
            <h4 style="color: #4f46e5;">Total Pendapatan</h4>
            <h2 style="color: #4f46e5;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
        <div class="box">
            <h4>Rata-rata Order (AOV)</h4>
            <h2>Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="flex-row">
        <div class="half">
            <h3>Breakdown Tipe Pesanan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th class="text-center">Transaksi</th>
                        <th class="text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanTipePesanan as $item)
                    <tr>
                        <td style="text-transform: uppercase;">{{ $item->tipe_pesanan }}</td>
                        <td class="text-center">{{ $item->jumlah_transaksi }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="half">
            <h3>Breakdown Pembayaran</h3>
            <table>
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th class="text-center">Transaksi</th>
                        <th class="text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanMetodeBayar as $item)
                    <tr>
                        <td>{{ $item->metode_pembayaran ?: 'Lainnya' }}</td>
                        <td class="text-center">{{ $item->jumlah_transaksi }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <h3>Tren Penjualan Harian</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Tanggal</th>
                <th class="text-center" style="width: 20%;">Jumlah Order</th>
                <th class="text-right" style="width: 30%;">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trenHarian as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                    <td class="text-center">{{ $item->jumlah_transaksi }} Order</td>
                    <td class="font-bold text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center" style="padding: 20px;">Tidak ada data penjualan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <div class="signature-line"></div>
            <p><strong>Owner / Manager</strong></p>
        </div>
    </div>
    <div class="footer">Dicetak oleh Sistem Custom Bowl pada {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</div>
</body>
</html> 