@extends('admin.layouts.app')

@section('title', 'POS Zakat Fitrah & Mal')

@section('content')

{{-- Summary Cards --}}
<div class="row mb-3">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHari }} transaksi</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Terkumpul Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jiwa Fitrah Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalFitrah, 0) }} jiwa</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Zakat Mal Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalMaal, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-coins fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    {{-- LEFT: POS Form --}}
    <div class="col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-cash-register mr-2"></i>Pembayaran Zakat
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('admin.pos.zakat.store') }}" method="POST" id="formZakat">
                    @csrf

                    {{-- Nama Muzakki --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Nama Muzakki <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="id_user" id="idUser" class="form-control @error('id_user') is-invalid @enderror" required autofocus>
                                <option value="">Pilih Nama Muzakki</option>
                                @foreach($muzakkiUsers as $muzakki)
                                    @php $assessment = $latestAssessments->get($muzakki->id); @endphp
                                    <option
                                        value="{{ $muzakki->id }}"
                                        data-has-assessment="{{ $assessment ? 1 : 0 }}"
                                        data-emas-gram="{{ $assessment->emas_gram ?? '' }}"
                                        data-beras-per-kg="{{ $assessment->harga_beras_per_kg ?? '' }}"
                                        data-harga-emas-per-gram="{{ $assessment->harga_emas_per_gram ?? '' }}"
                                        data-jiwa="{{ $assessment->jumlah_jiwa_fitrah ?? '' }}"
                                        data-nilai-emas="{{ $assessment->nilai_emas_rupiah ?? '' }}"
                                        data-fitrah-paid="{{ $assessment && $assessment->fitrah_paid_at ? 1 : 0 }}"
                                        data-maal-paid="{{ $assessment && $assessment->maal_paid_at ? 1 : 0 }}"
                                        {{ old('id_user') == $muzakki->id ? 'selected' : '' }}>
                                        {{ $muzakki->nama }}
                                    </option>
                                @endforeach
                            </select>
                                            @error('id_user')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <small class="text-muted">Rincian aset otomatis diambil dari assessment terakhir user terpilih.</small>
                            <div id="assessmentPaymentInfo" class="small mt-2 text-muted"></div>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Jenis Zakat <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-primary {{ old('kategori', 'fitrah') === 'fitrah' ? 'active' : '' }} w-50">
                                    <input type="radio" name="kategori" value="fitrah" id="radioFitrah"
                                        {{ old('kategori', 'fitrah') === 'fitrah' ? 'checked' : '' }}>
                                    <i class="fas fa-wheat-alt mr-1"></i> Zakat Fitrah
                                </label>
                                <label class="btn btn-outline-success {{ old('kategori') === 'maal' ? 'active' : '' }} w-50">
                                    <input type="radio" name="kategori" value="maal" id="radioMaal"
                                        {{ old('kategori') === 'maal' ? 'checked' : '' }}>
                                    <i class="fas fa-coins mr-1"></i> Zakat Mal
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- FITRAH SECTION --}}
                    <div id="sectionFitrah">
                        <div class="alert alert-light border-left-info mb-3 py-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Zakat Fitrah = Jumlah jiwa × Tarif per jiwa (ekuivalen 2,5 kg – 3,5 kg beras)
                            </small>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Jumlah Jiwa <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" name="jumlah_jiwa" id="jumlahJiwa"
                                        class="form-control @error('jumlah_jiwa') is-invalid @enderror"
                                        value="{{ old('jumlah_jiwa', 1) }}" min="1" placeholder="1" readonly>
                                    <div class="input-group-append"><span class="input-group-text">jiwa</span></div>
                                    @error('jumlah_jiwa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Tarif per Jiwa <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="harga_per_jiwa" id="hargaPerJiwa"
                                        class="form-control @error('harga_per_jiwa') is-invalid @enderror"
                                        value="{{ old('harga_per_jiwa', 45000) }}" min="0" step="500" placeholder="40000" readonly>
                                    @error('harga_per_jiwa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <small class="text-muted">Tarif otomatis dari assessment (beras/kg × 2,5 kg).</small>
                            </div>
                        </div>
                    </div>

                    {{-- MAAL SECTION --}}
                    <div id="sectionMaal" style="display:none;">
                        <div class="alert alert-light border-left-warning mb-3 py-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-warning mr-1"></i>
                                Zakat Mal dihitung otomatis 2,5% dari nilai emas berdasarkan harga Pegadaian.
                            </small>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Rincian Aset/Kewajiban <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <textarea name="rincian_maal" id="rincianMaal" class="form-control @error('rincian_maal') is-invalid @enderror" rows="2" placeholder="Contoh: Emas 95 gram, stok dagang, piutang usaha" readonly>{{ old('rincian_maal') }}</textarea>
                                @error('rincian_maal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Rincian otomatis dari assessment user: emas/gram dan beras/kg.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Nilai Konversi Aset (Opsional)</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="jumlah_harta" id="jumlahHarta"
                                        class="form-control @error('jumlah_harta') is-invalid @enderror"
                                        value="{{ old('jumlah_harta', '') }}" min="0" step="1000" placeholder="0" readonly>
                                    @error('jumlah_harta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <small class="text-muted">Nilai konversi otomatis dari assessment terakhir.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Harga Emas Pegadaian</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="hargaEmasPegadaian" class="form-control bg-light" readonly value="{{ number_format($pegadaianGoldPrice ?? 0, 0, ',', '.') }}">
                                    <div class="input-group-append"><span class="input-group-text">/gram</span></div>
                                </div>
                                <small class="text-muted">Jika API gagal, sistem fallback ke harga emas dari assessment user.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Zakat 2,5%</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="previewMaal" class="form-control bg-light font-weight-bold text-success" readonly value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tagihan Zakat --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold text-primary">Tagihan Zakat <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="number" name="jumlah_zakat" id="jumlahZakat"
                                    class="form-control font-weight-bold text-primary @error('jumlah_zakat') is-invalid @enderror"
                                    value="{{ old('jumlah_zakat', 0) }}" min="0" step="1000" placeholder="0" readonly>
                                @error('jumlah_zakat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Metode Pembayaran --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Metode Bayar <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-secondary {{ old('metode_pembayaran', 'tunai') === 'tunai' ? 'active' : '' }}" style="width:50%">
                                    <input type="radio" name="metode_pembayaran" value="tunai" id="metodeTunai"
                                        {{ old('metode_pembayaran', 'tunai') === 'tunai' ? 'checked' : '' }}>
                                    <i class="fas fa-money-bill-alt mr-1"></i> Tunai
                                </label>
                                <label class="btn btn-outline-secondary {{ old('metode_pembayaran') === 'transfer' ? 'active' : '' }}" style="width:50%">
                                    <input type="radio" name="metode_pembayaran" value="transfer" id="metodeTransfer"
                                        {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }}>
                                    <i class="fas fa-university mr-1"></i> Transfer
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Transfer --}}
                    <div id="transferFields" style="display:none;">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">No. Rekening Tujuan <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="rekening_tujuan_id" id="rekeningTujuan" class="form-control @error('rekening_tujuan_id') is-invalid @enderror">
                                    <option value="">Pilih rekening tujuan</option>
                                    @foreach($rekeningTujuan as $rekening)
                                        <option
                                            value="{{ $rekening->id }}"
                                            data-bank="{{ $rekening->bank }}"
                                            data-no-rekening="{{ $rekening->no_rekening }}"
                                            data-nama-pemilik="{{ $rekening->nama_pemilik }}"
                                            {{ (string) old('rekening_tujuan_id') === (string) $rekening->id ? 'selected' : '' }}>
                                            {{ $rekening->no_rekening }} - {{ $rekening->bank }} (a/n {{ $rekening->nama_pemilik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rekening_tujuan_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Bank</label>
                            <div class="col-sm-8">
                                <input type="text" id="bankTransfer" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Atas Nama</label>
                            <div class="col-sm-8">
                                <input type="text" id="namaRekening" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Jumlah Bayar --}}
                    <div class="form-group row" id="rowJumlahBayar">
                        <label class="col-sm-4 col-form-label font-weight-bold">Jumlah Dibayar <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="number" name="jumlah_bayar" id="jumlahBayar"
                                    class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                    value="{{ old('jumlah_bayar', '') }}" min="0" step="1000" placeholder="0">
                                @error('jumlah_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Kembalian (tunai only) --}}
                    <div class="form-group row" id="rowKembalian">
                        <label class="col-sm-4 col-form-label">Kembalian</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" id="displayKembalian" class="form-control bg-light font-weight-bold text-success" readonly value="0">
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control" rows="2"
                                placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fas fa-clock mr-1"></i> {{ now()->format('d M Y, H:i') }}
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="btnProses">
                            <i class="fas fa-check-circle mr-2"></i>Proses Pembayaran
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Recent Transactions --}}
    <div class="col-lg-5 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-history mr-2"></i>Riwayat Transaksi
                </h5>

                <span class="badge badge-light px-3 py-2">
                    {{ $transaksi->count() }} Transaksi
                </span>
            </div>

            <div class="card-body p-2" style="max-height:650px; overflow-y:auto; background:#f8f9fc;">
                @forelse($transaksi as $t)

                    @php
                        $isFitrah = $t->zakat && $t->zakat->kategori === 'fitrah';
                        $isPaid = $t->status === 'paid';
                        $isPending = $t->status === 'pending';
                        $isCancelled = $t->status === 'cancelled';
                    @endphp

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body py-3 px-3">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <div class="d-flex align-items-center mb-1">

                                        @if($isFitrah)
                                            <span class="badge badge-info px-3 py-2 mr-2">
                                                <i class="fas fa-seedling mr-1"></i> FITRAH
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-3 py-2 mr-2">
                                                <i class="fas fa-coins mr-1"></i> MAL
                                            </span>
                                        @endif

                                        @if($isPaid)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>LUNAS
                                            </span>
                                        @elseif($isPending)
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-clock mr-1"></i>PENDING
                                            </span>
                                        @elseif($isCancelled)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i>BATAL
                                            </span>
                                        @endif

                                    </div>

                                    <div class="font-weight-bold text-dark" style="font-size:1rem;">
                                        {{ $t->zakat->nama_zakat ?? '-' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $t->nomor_transaksi }}
                                    </div>

                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $t->tanggal_bayar
                                            ? $t->tanggal_bayar->format('d M Y H:i')
                                            : $t->created_at->format('d M Y H:i') }}
                                    </div>

                                    <div class="text-muted small">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        {{ ucfirst($t->metode_pembayaran) }}

                                        @if($isFitrah && $t->zakat->jumlah_jiwa)
                                            • {{ $t->zakat->jumlah_jiwa }} jiwa
                                        @endif
                                    </div>
                                </div>

                                {{-- Nominal --}}
                                <div class="text-right">
                                    <div class="h5 font-weight-bold text-success mb-1">
                                        Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}
                                    </div>

                                    <small class="text-muted">
                                        Total Bayar
                                    </small>
                                </div>
                            </div>

                            <hr class="my-3">

                            {{-- ACTION BUTTONS --}}
                            <div class="d-flex justify-content-between align-items-center">

                                <a href="{{ route('admin.pos.zakat.receipt', $t->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-print mr-1"></i> 
                                    Struk
                                </a>

                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                        <i class="fas fa-cogs mr-1"></i> 
                                        Action
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right shadow">

                                        {{-- Detail --}}
                                        <button type="button"
                                                class="dropdown-item"
                                                data-toggle="modal"
                                                data-target="#detailModal{{ $t->id }}">
                                            <i class="fas fa-eye text-info mr-2"></i>
                                            Detail
                                        </button>

                                        {{-- Transfer confirm --}}
                                        @if($isPending && $t->metode_pembayaran === 'transfer')
                                            <form action="{{ route('admin.pos.transaksi.confirm', $t->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button class="dropdown-item text-success">
                                                    <i class="fas fa-check mr-2"></i>
                                                    Konfirmasi Pembayaran
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Cancel --}}
                                        @if($t->status !== 'cancelled')
                                            <form action="{{ route('admin.pos.zakat.cancel', $t->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Batalkan transaksi ini?')">
                                                @csrf

                                                <button class="dropdown-item text-danger">
                                                    <i class="fas fa-ban mr-2"></i>
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- DETAIL MODAL --}}
                    <div class="modal fade" id="detailModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                        Detail Transaksi
                                    </h5>

                                    <button type="button"
                                            class="close text-white"
                                            data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <table class="table table-bordered">

                                        <tr>
                                            <th width="35%">Nomor Transaksi</th>
                                            <td>{{ $t->nomor_transaksi }}</td>
                                        </tr>

                                        <tr>
                                            <th>Nama Muzakki</th>
                                            <td>{{ $t->zakat->nama_zakat ?? '-' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Jenis Zakat</th>
                                            <td>{{ strtoupper($t->zakat->kategori ?? '-') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Metode Pembayaran</th>
                                            <td>{{ ucfirst($t->metode_pembayaran) }}</td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge badge-{{ $isPaid ? 'success' : ($isPending ? 'secondary' : 'danger') }}">
                                                    {{ strtoupper($t->status) }}
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Total Pembayaran</th>
                                            <td>
                                                Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $t->keterangan ?? '-' }}</td>
                                        </tr>

                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>

                @empty

                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>

                        <h6 class="font-weight-bold">
                            Belum Ada Transaksi
                        </h6>

                        <small>
                            Transaksi pembayaran zakat akan muncul di sini.
                        </small>
                    </div>

                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ---- Element references ----
    var sectionFitrah  = document.getElementById('sectionFitrah');
    var sectionMaal    = document.getElementById('sectionMaal');
    var idUser         = document.getElementById('idUser');
    var jumlahJiwa     = document.getElementById('jumlahJiwa');
    var hargaPerJiwa   = document.getElementById('hargaPerJiwa');
    var jumlahHarta    = document.getElementById('jumlahHarta');
    var previewMaal    = document.getElementById('previewMaal');
    var jumlahZakat    = document.getElementById('jumlahZakat');
    var rincianMaal    = document.getElementById('rincianMaal');
    var jumlahBayar    = document.getElementById('jumlahBayar');
    var displayKembalian = document.getElementById('displayKembalian');
    var rowKembalian   = document.getElementById('rowKembalian');
    var radioFitrah    = document.getElementById('radioFitrah');
    var radioMaal      = document.getElementById('radioMaal');
    var metodeTunai    = document.getElementById('metodeTunai');
    var metodeTransfer = document.getElementById('metodeTransfer');
    var transferFields = document.getElementById('transferFields');
    var rekeningTujuan = document.getElementById('rekeningTujuan');
    var bankTransfer   = document.getElementById('bankTransfer');
    var namaRekening   = document.getElementById('namaRekening');
    var assessmentPaymentInfo = document.getElementById('assessmentPaymentInfo');
var pegadaianGoldPrice = Number('{{ $pegadaianGoldPrice ?? 0 }}') || 0;

    function setKategoriEnabled(radio, enabled, disabledClass) {
        if (!radio) return;

        radio.disabled = !enabled;

        var label = radio.closest('label');
        if (!label) return;

        label.classList.toggle(disabledClass, !enabled);
        label.classList.toggle('disabled', !enabled);
        label.style.opacity = enabled ? '1' : '0.6';
        label.style.pointerEvents = enabled ? '' : 'none';
    }

    function updateKategoriAvailability(selected) {
        var fitrahPaid = selected && selected.getAttribute('data-fitrah-paid') === '1';
        var maalPaid = selected && selected.getAttribute('data-maal-paid') === '1';

        setKategoriEnabled(radioFitrah, !fitrahPaid, 'btn-outline-secondary');
        setKategoriEnabled(radioMaal, !maalPaid, 'btn-outline-secondary');

        if (assessmentPaymentInfo) {
            if (!selected || !selected.value) {
                assessmentPaymentInfo.textContent = '';
            } else if (fitrahPaid && maalPaid) {
                assessmentPaymentInfo.textContent = 'Assessment terbaru user ini sudah lunas untuk zakat fitrah dan zakat mal.';
            } else if (fitrahPaid) {
                assessmentPaymentInfo.textContent = 'Zakat fitrah sudah lunas. Zakat mal masih bisa diproses dari assessment ini.';
            } else if (maalPaid) {
                assessmentPaymentInfo.textContent = 'Zakat mal sudah lunas. Zakat fitrah masih bisa diproses dari assessment ini.';
            } else {
                assessmentPaymentInfo.textContent = 'Zakat fitrah dan zakat mal masih tersedia untuk assessment ini.';
            }
        }

        if (fitrahPaid && radioFitrah.checked && !maalPaid) {
            radioMaal.checked = true;
        }

        if (maalPaid && radioMaal.checked && !fitrahPaid) {
            radioFitrah.checked = true;
        }

        if (fitrahPaid && maalPaid) {
            radioFitrah.checked = false;
            radioMaal.checked = false;
        } else if (!radioFitrah.checked && !radioMaal.checked) {
            if (!fitrahPaid) {
                radioFitrah.checked = true;
            } else if (!maalPaid) {
                radioMaal.checked = true;
            }
        }
    }

    function applyAssessmentValues() {
        if (!idUser) return;

        var selected = idUser.options[idUser.selectedIndex];
        if (!selected || !selected.value) {
            jumlahJiwa.value = 1;
            hargaPerJiwa.value = 0;
            if (rincianMaal) rincianMaal.value = '';
            if (jumlahHarta) jumlahHarta.value = '';
            updateTotal();
            return;
        }

        var hasAssessment = selected.getAttribute('data-has-assessment') === '1';
        if (!hasAssessment) {
            updateKategoriAvailability(null);
            jumlahJiwa.value = 1;
            hargaPerJiwa.value = 0;
            if (rincianMaal) rincianMaal.value = '';
            if (jumlahHarta) jumlahHarta.value = '';
            updateTotal();
            return;
        }

        updateKategoriAvailability(selected);

        var emasGram = parseFloat(selected.getAttribute('data-emas-gram') || '0');
        var berasPerKg = parseFloat(selected.getAttribute('data-beras-per-kg') || '0');
        var hargaEmasPerGram = parseFloat(selected.getAttribute('data-harga-emas-per-gram') || '0');
        var jiwa = parseInt(selected.getAttribute('data-jiwa') || '1', 10);
        var nilaiEmas = parseFloat(selected.getAttribute('data-nilai-emas') || '0');

        jumlahJiwa.value = jiwa > 0 ? jiwa : 1;
        hargaPerJiwa.value = berasPerKg > 0 ? Math.round(berasPerKg * 2.5) : 0;

        if (rincianMaal) {
            var effectiveGoldPrice = pegadaianGoldPrice > 0 ? pegadaianGoldPrice : hargaEmasPerGram;

            rincianMaal.value = 'Emas/gram: Rp ' + formatRp(effectiveGoldPrice)
                + ' | Beras/kg: Rp ' + formatRp(berasPerKg);
        }

        if (jumlahHarta) {
            if (pegadaianGoldPrice > 0 && emasGram > 0) {
                jumlahHarta.value = Math.round(emasGram * pegadaianGoldPrice);
            } else if (nilaiEmas > 0) {
                jumlahHarta.value = Math.round(nilaiEmas);
            } else {
                jumlahHarta.value = Math.round(emasGram * hargaEmasPerGram);
            }
        }

        updateTotal();
    }

    function updateRekeningPreview() {
        if (!rekeningTujuan) return;

        var selected = rekeningTujuan.options[rekeningTujuan.selectedIndex];

        if (!selected || !selected.value) {
            if (bankTransfer) bankTransfer.value = '';
            if (namaRekening) namaRekening.value = '';
            return;
        }

        if (bankTransfer) {
            bankTransfer.value = selected.getAttribute('data-bank') || '';
        }
        if (namaRekening) {
            namaRekening.value = selected.getAttribute('data-nama-pemilik') || '';
        }
    }

    function formatRp(val) {
        return Number(val).toLocaleString('id-ID');
    }

    function currentKategori() {
        if (!radioFitrah.checked && !radioMaal.checked) {
            return '';
        }

        return radioMaal.checked ? 'maal' : 'fitrah';
    }

    function calcFitrah() {
        var jiwa  = parseInt(jumlahJiwa.value) || 0;
        var tarif = parseFloat(hargaPerJiwa.value) || 0;
        return jiwa * tarif;
    }

    function calcMaal() {
        var harta = parseFloat(jumlahHarta.value) || 0;
        return Math.round(harta * 0.025);
    }

    function updateTotal() {
        var total;

        if (!currentKategori()) {
            jumlahZakat.value = 0;
            previewMaal.value = '0';
            updateKembalian(0);
            return;
        }

        if (currentKategori() === 'fitrah') {
            total = calcFitrah();
        } else {
            total = calcMaal();
        }

        jumlahZakat.value = total;

        // preview maal
        if (currentKategori() === 'maal') {
            previewMaal.value = formatRp(total);
        } else {
            previewMaal.value = '0';
        }

        updateKembalian(total);
    }

    function updateKembalian(total) {
        if (metodeTunai.checked) {
            rowKembalian.style.display = '';
            jumlahBayar.readOnly = false;
            jumlahBayar.classList.remove('bg-light');
            var bayar     = parseFloat(jumlahBayar.value) || 0;
            var kembalian = bayar - total;
            displayKembalian.value      = formatRp(kembalian < 0 ? 0 : kembalian);
            displayKembalian.className  = 'form-control bg-light font-weight-bold ' + (kembalian < 0 ? 'text-danger' : 'text-success');
        } else {
            rowKembalian.style.display = 'none';
            jumlahBayar.value = (parseFloat(jumlahZakat.value) || 0).toString();
            jumlahBayar.readOnly = true;
            jumlahBayar.classList.add('bg-light');
        }
    }

    function updateTransferFields() {
        var isTransfer = metodeTransfer.checked;

        if (transferFields) {
            transferFields.style.display = isTransfer ? '' : 'none';
        }

        if (rekeningTujuan) rekeningTujuan.required = isTransfer;

        if (!isTransfer) {
            if (rekeningTujuan) rekeningTujuan.value = '';
            if (bankTransfer) bankTransfer.value = '';
            if (namaRekening) namaRekening.value = '';
        } else {
            updateRekeningPreview();
        }
    }

    function switchKategori() {
        if (!currentKategori()) {
            sectionFitrah.style.display = 'none';
            sectionMaal.style.display = 'none';
            jumlahZakat.value = 0;
            updateKembalian(0);
            return;
        }

        if (currentKategori() === 'fitrah') {
            sectionFitrah.style.display = '';
            sectionMaal.style.display   = 'none';
            jumlahJiwa.required   = true;
            hargaPerJiwa.required = true;
            if (jumlahHarta) jumlahHarta.required = false;
            if (rincianMaal) rincianMaal.required = false;
            jumlahZakat.readOnly = true;
            jumlahZakat.classList.add('bg-light');
        } else {
            sectionFitrah.style.display = 'none';
            sectionMaal.style.display   = '';
            jumlahJiwa.required   = false;
            hargaPerJiwa.required = false;
            if (jumlahHarta) jumlahHarta.required = false;
            if (rincianMaal) rincianMaal.required = false;
            jumlahZakat.readOnly = true;
            jumlahZakat.classList.add('bg-light');
        }
        updateTotal();
    }

    // Bind events
    radioFitrah.addEventListener('change', switchKategori);
    radioMaal.addEventListener('change', switchKategori);
    if (idUser) idUser.addEventListener('change', applyAssessmentValues);
    jumlahJiwa.addEventListener('input', updateTotal);
    hargaPerJiwa.addEventListener('input', updateTotal);
    if (jumlahHarta) jumlahHarta.addEventListener('input', updateTotal);
    jumlahZakat.addEventListener('input', function () { updateKembalian(parseFloat(jumlahZakat.value) || 0); });
    jumlahBayar.addEventListener('input', function () { updateKembalian(parseFloat(jumlahZakat.value) || 0); });
    metodeTunai.addEventListener('change', function () {
        updateKembalian(parseFloat(jumlahZakat.value) || 0);
        updateTransferFields();
    });
    metodeTransfer.addEventListener('change', function () {
        updateKembalian(parseFloat(jumlahZakat.value) || 0);
        updateTransferFields();
    });
    if (rekeningTujuan) {
        rekeningTujuan.addEventListener('change', updateRekeningPreview);
    }

    // Initial state
    switchKategori();
    applyAssessmentValues();
    updateTotal();
    updateTransferFields();
    updateRekeningPreview();

    // Form submit guard
    document.getElementById('formZakat').addEventListener('submit', function (e) {
        if (idUser) {
            var selected = idUser.options[idUser.selectedIndex];
            var hasAssessment = selected && selected.getAttribute('data-has-assessment') === '1';
            if (!hasAssessment) {
                e.preventDefault();
                alert('User yang dipilih belum memiliki assessment. Silakan isi assessment user terlebih dahulu.');
                return false;
            }

            var fitrahPaid = selected.getAttribute('data-fitrah-paid') === '1';
            var maalPaid = selected.getAttribute('data-maal-paid') === '1';

            if ((currentKategori() === 'fitrah' && fitrahPaid) || (currentKategori() === 'maal' && maalPaid)) {
                e.preventDefault();
                alert('Jenis zakat yang dipilih pada assessment terbaru user ini sudah lunas.');
                return false;
            }

            if (!currentKategori()) {
                e.preventDefault();
                alert('Assessment terbaru user ini sudah lunas untuk semua jenis zakat.');
                return false;
            }
        }

        var total = parseFloat(jumlahZakat.value) || 0;
        if (total <= 0) {
            e.preventDefault();
            alert('Total zakat tidak boleh 0. Periksa kembali input Anda.');
            return false;
        }
        if (metodeTunai.checked) {
            var bayar = parseFloat(jumlahBayar.value) || 0;
            if (bayar < total) {
                e.preventDefault();
                alert('Jumlah dibayar kurang dari total zakat (Rp ' + formatRp(total) + ')');
                return false;
            }
        }
        document.getElementById('btnProses').disabled = true;
        document.getElementById('btnProses').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    });
})();
</script>
@endpush
