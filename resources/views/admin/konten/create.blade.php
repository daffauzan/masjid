@extends('admin.layouts.app')

@section('title', 'Tambah Konten')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 text-gray-800">Tambah Konten</h1>
</div>

<div class="card shadow mb-4">

    <div class="card-body">

        <form action="{{ route('admin.konten.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Judul -->
            <div class="form-group">
                <label>Judul Konten</label>
                <input type="text"
                       name="judul"
                       class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul') }}"
                       required>

                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori"
                        class="form-control @error('kategori') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="informasi" {{ old('kategori') == 'informasi' ? 'selected' : '' }}>
                        Informasi
                    </option>
                    <option value="dakwah" {{ old('kategori') == 'dakwah' ? 'selected' : '' }}>
                        Dakwah
                    </option>
                </select>

                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Konten -->
            <div class="form-group">
                <label>Isi Konten</label>
                <textarea name="konten"
                          rows="6"
                          class="form-control @error('konten') is-invalid @enderror"
                          required>{{ old('konten') }}</textarea>

                @error('konten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Gambar -->
            <div class="form-group">
                <label>Gambar (jpg/png Opsional)</label>
                <input type="file"
                       name="gambar"
                       class="form-control-file @error('gambar') is-invalid @enderror"
                       accept="image/*"
                       >

                @error('gambar')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- File PDF -->
            <div class="form-group">
                <label>File PDF (opsional)</label>
                <input type="file"
                       name="file"
                       class="form-control-file @error('file') is-invalid @enderror"
                       accept="application/pdf">

                @error('file')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
