@extends('admin.layouts.app')

@section('title', 'Riwayat Assessment')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Assessment Zakat</h6>
        <span class="badge badge-info">Read Only Admin</span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Muzakki</th>
                        <th>Gaji</th>
                        <th>Tabungan</th>
                        <th>Emas (gram)</th>
                        <th>Hutang</th>
                        <th>Jiwa Fitrah</th>
                        <th>Harga Emas/gram</th>
                        <th>Harga Beras/kg</th>
                        <th>Nilai Emas</th>
                        <th>Nisab Mal</th>
                        <th>Status Mal</th>
                        <th>Pembayaran Fitrah</th>
                        <th>Pembayaran Mal</th>
                        <th>Zakat Mal</th>
                        <th>Zakat Fitrah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assessments as $item)
                        <tr>
                            <td>{{ $assessments->firstItem() + $loop->index }}</td>
                            <td>{{ $item->user->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($item->gaji, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->tabungan, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->emas_gram, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->hutang, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah_jiwa_fitrah }}</td>
                            <td>Rp {{ number_format($item->harga_emas_per_gram, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->harga_beras_per_kg, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->nilai_emas_rupiah, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->nisab_mal_rupiah, 0, ',', '.') }}</td>
                            <td>
                                @if($item->wajib_zakat_mal)
                                    <span class="badge badge-success">Wajib</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Wajib</span>
                                @endif
                            </td>
                            <td>
                                @if($item->fitrah_paid_at)
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Belum</span>
                                @endif
                            </td>
                            <td>
                                @if($item->maal_paid_at)
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Belum</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->nominal_zakat_mal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->nominal_zakat_fitrah, 0, ',', '.') }}</td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="text-center">Belum ada data assessment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection
