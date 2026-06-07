@extends('user.layouts.app')

@section('title', 'Riwayat Assessment Saya')

@section('content')
<section class="section" style="padding-top:120px; padding-bottom:60px;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Riwayat Assessment Saya</h4>
      <a href="{{ route('user.assessment.create') }}" class="btn btn-primary btn-sm">Isi Assessment</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body table-responsive">
        <table class="table table-bordered table-hover mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Total Harta Bersih</th>
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
                <td>Rp {{ number_format($item->total_harta_bersih, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->nisab_mal_rupiah, 0, ',', '.') }}</td>
                <td>
                  @if($item->wajib_zakat_mal)
                    <span class="badge bg-success">Wajib</span>
                  @else
                    <span class="badge bg-secondary">Tidak Wajib</span>
                  @endif
                </td>
                <td>
                  @if($item->fitrah_paid_at)
                    <span class="badge bg-success">Lunas</span>
                  @else
                    <span class="badge bg-warning text-dark">Belum</span>
                  @endif
                </td>
                <td>
                  @if($item->maal_paid_at)
                    <span class="badge bg-success">Lunas</span>
                  @else
                    <span class="badge bg-warning text-dark">Belum</span>
                  @endif
                </td>
                <td>Rp {{ number_format($item->nominal_zakat_mal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->nominal_zakat_fitrah, 0, ',', '.') }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr><td colspan="9" class="text-center">Belum ada assessment.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-3">
      {{ $assessments->links() }}
    </div>
  </div>
</section>
@endsection
