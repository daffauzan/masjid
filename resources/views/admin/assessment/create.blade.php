@extends('admin.layouts.app')

@section('title', 'Assessment Zakat')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Form Assessment Zakat</h6>
        <a href="{{ route('admin.assessment.index') }}" class="btn btn-secondary btn-sm">Riwayat Assessment</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.assessment.store') }}" method="POST">
            @csrf

            <div class="form-group row">
                <label class="col-sm-3 col-form-label font-weight-bold">Muzakki (Role User)</label>
                <div class="col-sm-9">
                    <select name="user_id" class="form-control" required>
                        <option value="">Pilih User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nama }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Gaji (Rp)</label>
                <div class="col-sm-9"><input type="number" step="1000" min="0" name="gaji" class="form-control" value="{{ old('gaji', 0) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tabungan (Rp)</label>
                <div class="col-sm-9"><input type="number" step="1000" min="0" name="tabungan" class="form-control" value="{{ old('tabungan', 0) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Emas (gram)</label>
                <div class="col-sm-9"><input type="number" step="0.01" min="0" name="emas_gram" class="form-control" value="{{ old('emas_gram', 0) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Hutang (Rp)</label>
                <div class="col-sm-9"><input type="number" step="1000" min="0" name="hutang" class="form-control" value="{{ old('hutang', 0) }}" required></div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Harga Emas per Gram (Rp)</label>
                <div class="col-sm-9"><input type="number" step="100" min="1" name="harga_emas_per_gram" class="form-control" value="{{ old('harga_emas_per_gram', 1900000) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Harga Beras per Kg (Rp)</label>
                <div class="col-sm-9"><input type="number" step="100" min="1" name="harga_beras_per_kg" class="form-control" value="{{ old('harga_beras_per_kg', 16000) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jumlah Jiwa Fitrah</label>
                <div class="col-sm-9"><input type="number" min="1" name="jumlah_jiwa_fitrah" class="form-control" value="{{ old('jumlah_jiwa_fitrah', 1) }}" required></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Catatan</label>
                <div class="col-sm-9"><textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea></div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan & Hitung Assessment</button>
        </form>
    </div>
</div>
@endsection
