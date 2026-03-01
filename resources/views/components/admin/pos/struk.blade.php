<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - {{ $pesanan->nomor_invoice }}</title>
    <style>
        /* RESET & SETUP KERTAS 58mm */
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace; /* Wajib monospace agar rata */
            font-size: 12px;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
            width: 58mm; /* Lebar standar printer thermal 58mm */
            max-width: 58mm;
        }
        
        /* CONTAINER STRUK */
        .ticket {
            padding: 5mm;
            width: 48mm; /* Lebar area cetak dikurangi margin */
            margin: 0 auto;
        }

        /* HELPER CLASSES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .flex-between { display: flex; justify-content: space-between; }
        .border-dashed { border-bottom: 1px dashed #000; margin: 5px 0; }
        
        .header h1 { font-size: 16px; margin: 0 0 2px 0; }
        .header p { font-size: 10px; margin: 0; }

        .info-transaksi { font-size: 10px; margin: 5px 0; }
        .info-transaksi div { display: flex; justify-content: space-between; }

        .mangkuk-title { font-size: 11px; font-weight: bold; margin-top: 5px; }
        .mangkuk-desc { font-size: 10px; font-style: italic; margin-bottom: 2px; }

        .item { font-size: 11px; margin-bottom: 3px; }
        .item-detail { display: flex; justify-content: space-between; font-size: 11px; padding-left: 5px;}

        .totals { margin-top: 5px; font-size: 11px; }
        .totals .grand-total { font-size: 13px; font-weight: bold; }

        .footer { font-size: 10px; text-align: center; margin-top: 10px; }

        /* Sembunyikan apapun yang tidak perlu saat mode print */
        @media print {
            .hidden-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        
        <div class="text-center header">
            <h1>CUSTOM BOWL</h1>
            <p>Jl. Contoh Alamat No. 123, Kota</p>
            <p>Telp: 0812-3456-7890</p>
        </div>
        
        <div class="border-dashed"></div>

        <div class="info-transaksi">
            <div><span>Inv:</span> <span>{{ $pesanan->nomor_invoice }}</span></div>
            <div><span>Tgl:</span> <span>{{ $pesanan->created_at->format('d/m/Y H:i') }}</span></div>
            <div><span>Kasir:</span> <span>{{ $pesanan->kasir->nama ?? 'Sistem' }}</span></div>
            <div><span>Plgn:</span> <span>{{ $pesanan->user->nama ?? 'Walk-in' }}</span></div>
            <div><span>Tipe:</span> <span style="text-transform: uppercase;">{{ $pesanan->tipe_pesanan }}</span></div>
        </div>

        <div class="border-dashed"></div>

        @foreach($pesanan->mangkuk as $mangkuk)
            <div class="mangkuk-title">> {{ $mangkuk->nama_pemesan }}</div>
            <div class="mangkuk-desc">
                {{ $mangkuk->tipe_kuah }} | Lvl: {{ $mangkuk->level_pedas }}
                @if($mangkuk->catatan) <br> Note: {{ $mangkuk->catatan }} @endif
            </div>

            @foreach($mangkuk->detailPesanan as $detail)
                <div class="item">
                    <div>{{ $detail->produk->nama }}</div>
                    <div class="item-detail">
                        <span>{{ $detail->jumlah }} x {{ number_format($detail->harga, 0, ',', '.') }}</span>
                        <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
            <div style="height: 3px;"></div>
        @endforeach

        <div class="border-dashed"></div>

        <div class="totals">
            <div class="flex-between">
                <span>Metode:</span>
                <span>{{ $pesanan->metode_pembayaran }}</span>
            </div>
            <div class="flex-between grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
            
            @if($pesanan->metode_pembayaran === 'Tunai')
                @php
                    // Logika Kembalian dari database bisa diambil jika kita menyimpannya, 
                    // namun karena kita tidak menyimpan 'uang_diterima' di database, 
                    // pada saat live-print kembalian otomatis diurus kasir. 
                    // Jika ingin sempurna, tambahkan kolom `uang_diterima` dan `kembalian` di tabel pesanan.
                @endphp
            @endif
        </div>

        <div class="border-dashed"></div>

        @if($pesanan->tipe_pesanan === 'delivery')
            <div style="font-size: 10px; margin-bottom: 5px;">
                <div class="font-bold">Info Pengiriman:</div>
                <div>{{ $pesanan->link_delivery }}</div>
            </div>
            <div class="border-dashed"></div>
        @endif

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Silakan periksa kembali pesanan Anda.</p>
            <p>~ Lunas ~</p>
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
            // Opsional: Otomatis tutup tab setelah print dialog ditutup
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
</body>
</html>