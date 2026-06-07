<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrak Zakat - {{ $transaksi->nomor_transaksi }}</title>

    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, sans-serif;
            background:#f3f4f6;
            padding:30px;
            color:#222;
        }

        .contract-wrapper{
            width:210mm;
            min-height: 297mm;
            margin:auto;
            background:#fff;
            border-radius:8px;
            padding:40px;
            border:1px solid #ddd;
            box-shadow:0 4px 12px rgba(0,0,0,.08);
        }

        .contract-header{
            text-align:center;
            margin-bottom:30px;
        }

        .contract-header h1{
            font-size:24px;
            text-transform:uppercase;
            margin-bottom:8px;
        }

        .contract-header p{
            font-size:14px;
            color:#666;
        }

        .contract-number{
            margin-top:12px;
            font-weight:bold;
            font-size:14px;
        }

        .section{
            margin-bottom:28px;
        }

        .section-title{
            font-size:16px;
            font-weight:bold;
            margin-bottom:12px;
            border-bottom:1px solid #ccc;
            padding-bottom:6px;
        }

        .row{
            display:flex;
            margin-bottom:8px;
        }

        .row .label{
            width:220px;
            font-weight:bold;
        }

        .row .value{
            flex:1;
        }

        .status{
            display:inline-block;
            padding:5px 12px;
            border-radius:4px;
            font-size:12px;
            font-weight:bold;
            text-transform:uppercase;
        }

        .status-paid{
            background:#d4edda;
            color:#155724;
            border:1px solid #c3e6cb;
        }

        .status-pending{
            background:#fff3cd;
            color:#856404;
            border:1px solid #ffeeba;
        }

        .status-failed{
            background:#f8d7da;
            color:#721c24;
            border:1px solid #f5c6cb;
        }

        .status-cancelled{
            background:#e2e3e5;
            color:#383d41;
            border:1px solid #d6d8db;
        }

        .agreement{
            margin-top:10px;
            line-height:1.8;
            text-align:justify;
        }

        .signature-wrapper{
            display:flex;
            justify-content:space-between;
            margin-top:60px;
        }

        .signature{
            width:45%;
            text-align:center;
        }

        .signature .line{
            margin-top:70px;
            border-top:1px solid #333;
            padding-top:6px;
        }

        .footer{
            margin-top:40px;
            text-align:center;
            font-size:12px;
            color:#666;
        }

        .actions{
            max-width:800px;
            margin:20px auto 0;
            display:flex;
            gap:12px;
        }

        .btn{
            flex:1;
            padding:12px;
            border:none;
            border-radius:6px;
            font-weight:bold;
            cursor:pointer;
            color:#fff;
        }

        .btn-print{
            background:#1cc88a;
        }

        .btn-back{
            background:#4e73df;
        }

        @page {
            size: A4;
            margin: 20mm;
        }

        @media print{
            body{
                background:#fff;
                padding:0;
            }

            .contract-wrapper{
                box-shadow:none;
                border:none;
            }

            .actions{
                display:none;
            }
        }
    </style>
</head>
<body>

<div class="contract-wrapper">

    {{-- HEADER --}}
    <div class="contract-header">
        <h1>Pembayaran Zakat</h1>

        <p>
            Dokumen Persetujuan Pembayaran dan Penyaluran Zakat
        </p>

        <div class="contract-number">
            Nomor Kontrak:
            CTR-{{ $transaksi->nomor_transaksi }}
        </div>
    </div>

    {{-- PIHAK --}}
    <div class="section">
        <div class="section-title">
            Data Pihak
        </div>

        <div class="row">
            <div class="label">Lembaga</div>
            <div class="value">Masjid Abaabil</div>
        </div>

        <div class="row">
            <div class="label">Muzakki</div>
            <div class="value">
                {{ $transaksi->zakat->nama_zakat ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Tanggal Transaksi</div>
            <div class="value">
                {{ $transaksi->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Nomor Transaksi</div>
            <div class="value">
                {{ $transaksi->nomor_transaksi }}
            </div>
        </div>
    </div>

    {{-- DETAIL ZAKAT --}}
    <div class="section">
        <div class="section-title">
            Detail Pembayaran Zakat
        </div>

        <div class="row">
            <div class="label">Jenis Zakat</div>
            <div class="value">
                {{ ucfirst($transaksi->zakat->kategori) }}
            </div>
        </div>

        @if($transaksi->zakat->kategori === 'fitrah')
        <div class="row">
            <div class="label">Jumlah Jiwa</div>
            <div class="value">
                {{ number_format($transaksi->zakat->jumlah_jiwa ?? 0, 0) }} Jiwa
            </div>
        </div>
        @endif

        <div class="row">
            <div class="label">Total Pembayaran</div>
            <div class="value">
                Rp {{ number_format($transaksi->zakat->jumlah, 0, ',', '.') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Metode Pembayaran</div>
            <div class="value">
                {{ strtoupper($transaksi->metode_pembayaran) }}
            </div>
        </div>

        @if($transaksi->metode_pembayaran === 'transfer' && $transaksi->rekening_tujuan)

            <div class="row">
                <div class="label">Bank</div>
                <div class="value">
                    {{ $transaksi->rekening_tujuan->bank ?? '-' }}
                </div>
            </div>

            <div class="row">
                <div class="label">No. Rekening</div>
                <div class="value">
                    {{ $transaksi->rekening_tujuan->nomor_rekening ?? '-' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Atas Nama</div>
                <div class="value">
                    {{ $transaksi->rekening_tujuan->atas_nama ?? '-' }}
                </div>
            </div>

        @endif
    </div>

    {{-- STATUS --}}
    <div class="section">
        <div class="section-title">
            Status Pembayaran
        </div>

        <div class="row">
            <div class="label">Status</div>
            <div class="value">

                @if($transaksi->status === 'paid')
                    <span class="status status-paid">
                        LUNAS
                    </span>

                @elseif($transaksi->status === 'pending')
                    <span class="status status-pending">
                        PENDING
                    </span>

                @elseif($transaksi->status === 'failed')
                    <span class="status status-failed">
                        GAGAL
                    </span>

                @elseif($transaksi->status === 'cancelled')
                    <span class="status status-cancelled">
                        DIBATALKAN
                    </span>
                @endif

            </div>
        </div>
    </div>

    {{-- AGREEMENT --}}
    <div class="section">
        <div class="section-title">
            Ketentuan dan Persetujuan
        </div>

        <div class="agreement">
            Dengan melakukan transaksi pembayaran zakat ini, muzakki menyetujui bahwa:
            <br><br>

            1. Data transaksi yang tercatat pada sistem dianggap sah.
            <br>

            2. Pembayaran transfer wajib diverifikasi oleh admin/panitia zakat.
            <br>

            3. Dana zakat yang telah dinyatakan paid akan disalurkan sesuai ketentuan syariat dan kebijakan panitia zakat.
            <br>

            4. Lembar ini berlaku sebagai bukti transaksi digital dan persetujuan pembayaran zakat.
            <br>
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="signature-wrapper">

        <div class="signature">
            Panitia Zakat

            <div class="line">
                Admin Masjid
            </div>
        </div>

        <div class="signature">
            Muzakki

            <div class="line">
                {{ $transaksi->zakat->nama_zakat ?? '-' }}
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem pada
        {{ now()->format('d/m/Y H:i') }}
    </div>

</div>

<div class="actions">

    <button class="btn btn-print" onclick="window.print()">
        <i class="fas fa-print"></i>
        Cetak
    </button>

    <button
        class="btn btn-back"
        onclick="window.location='{{ route('admin.pos.zakat.index') }}'"
    >
        <i class="fas fa-arrow-left"></i>
        Kembali
    </button>

</div>

</body>
</html>