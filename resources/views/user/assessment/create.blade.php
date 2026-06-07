@extends('user.layouts.app')

@section('title', 'Assessment Zakat User')

@section('content')
<section class="section" style="padding-top:120px; padding-bottom:60px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Assessment Zakat</h5>
            <a href="{{ route('user.assessment.index') }}" class="btn btn-outline-secondary btn-sm">Riwayat Saya</a>
          </div>
          <div class="card-body">
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
              </div>
            @endif

            <form action="{{ route('user.assessment.store') }}" method="POST">
              @csrf

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Gaji (Rp)</label>
                  <input type="number" name="gaji" min="0" step="1000" class="form-control" value="{{ old('gaji', 0) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tabungan (Rp)</label>
                  <input type="number" name="tabungan" min="0" step="1000" class="form-control" value="{{ old('tabungan', 0) }}" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Emas (gram)</label>
                  <input type="number" name="emas_gram" min="0" step="0.01" class="form-control" value="{{ old('emas_gram', 0) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Hutang (Rp)</label>
                  <input type="number" name="hutang" min="0" step="1000" class="form-control" value="{{ old('hutang', 0) }}" required>
                </div>
              </div>

              <div class="row">
                 <div class="col-md-4 mb-3">
                  <label class="form-label">Jumlah Jiwa Fitrah</label>
                  <input type="number" name="jumlah_jiwa_fitrah" min="1" class="form-control" value="{{ old('jumlah_jiwa_fitrah', 1) }}" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
              </div>

              <button class="btn btn-primary" type="submit">Simpan Assessment</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
