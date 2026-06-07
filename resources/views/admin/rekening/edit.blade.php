@extends('admin.layouts.app')

@section('title', 'Edit No Rekening')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Edit No Rekening Tujuan</h6>
        <a href="{{ route('admin.rekening.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.rekening.update', $rekening->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="bank">Bank <span class="text-danger">*</span></label>
                <input type="text" name="bank" id="bank" class="form-control @error('bank') is-invalid @enderror" value="{{ old('bank', $rekening->bank) }}" placeholder="Contoh: BSI" required>
            </div>

            <div class="form-group">
                <label for="no_rekening">No Rekening <span class="text-danger">*</span></label>
                <input type="text" name="no_rekening" id="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" value="{{ old('no_rekening', $rekening->no_rekening) }}" placeholder="Masukkan nomor rekening" required>
            </div>

            <div class="form-group">
                <label for="nama_pemilik">Nama Pemilik <span class="text-danger">*</span></label>
                <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control @error('nama_pemilik') is-invalid @enderror" value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" placeholder="Nama pemilik rekening" required>
            </div>

            <div class="form-group">
                <label for="is_active">Status <span class="text-danger">*</span></label>
                <select name="is_active" id="is_active" class="form-control" required>
                    <option value="1" {{ old('is_active', $rekening->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $rekening->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Perbarui
            </button>
        </form>
    </div>
</div>
@endsection
