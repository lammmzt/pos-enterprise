<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan & Margin - Custom Bowl</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            background: #fff;
        }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .box { width: 23%; border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; }
        .box h4 { margin: 0 0 10px; font-size: 11px; color: #777; text-transform: uppercase; }
        .box h2 { margin: 0; font-size: 18px; color: #000; }
        
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
        <h1>Laporan Keuangan & Laba Kotor</h1>
        <p>Custom Bowl | Metode HPP Berdasarkan FEFO Batch</p>
        <p>Periode: <strong>{{ $tanggalMulai }}</strong> s/d <strong>{{ $tanggalAkhir }}</strong></p>
    </div>

    <div class="summary-box">
        <div class="box">
            <h4>Total Pendapatan</h4>
            <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
        <div class="box">
            <h4>Beban Pokok (HPP)</h4>
            <h2>Rp {{ number_format($totalHpp, 0, ',', '.') }}</h2>
        </div>
        <div class="box" style="border-color: #4f46e5; background-color: #f5f3ff;">
            <h4 style="color: #4f46e5;">Laba Kotor</h4>
            <h2 style="color: #4f46e5;">Rp {{ number_format($labaKotor, 0, ',', '.') }}</h2>
        </div>
        <div class="box">
            <h4>Rata-rata Margin</h4>
            <h2>{{ $marginPersen }}%</h2>
        </div>
    </div>

    <h3>Rincian Profitabilitas Per Produk</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Menu / Topping</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Pendapatan</th>
                <th class="text-right" style="width: 15%;">HPP</th>
                <th class="text-right" style="width: 15%;">Laba Kotor</th>
                <th class="text-center" style="width: 10%;">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanProduk as $index => $item)
                @php
                    $laba = $item->pendapatan_produk - $item->hpp_produk;
                    $margin = $item->pendapatan_produk > 0 ? round(($laba / $item->pendapatan_produk) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td class="text-center">{{ $item->qty_terjual }}</td>
                    <td class="text-right">Rp {{ number_format($item->pendapatan_produk, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->hpp_produk, 0, ',', '.') }}</td>
                    <td class="font-bold text-right">Rp {{ number_format($laba, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $margin }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 30px;">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="3" class="text-right">GRAND TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                <td class="text-center">{{ $marginPersen }}%</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <div class="signature-line"></div>
            <p><strong>Owner / Manager</strong></p>
        </div>
    </div>

    <div class="footer">
        Dicetak oleh Sistem ERP Custom Bowl pada {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>