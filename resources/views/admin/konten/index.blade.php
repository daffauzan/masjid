@extends('admin.layouts.app')

@section('title', 'Manajemen Konten')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 text-gray-800">Manajemen Konten</h1>

    <a href="{{ route('admin.konten.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Konten
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Konten</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th>File</th>
                        <th>Author</th>
                        <th>Tanggal</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                @forelse ($konten as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>
                            @if($item->kategori == 'informasi')
                                <span class="badge badge-primary">Informasi</span>
                            @else
                                <span class="badge badge-success">Dakwah</span>
                            @endif
                        </td>
                        {{-- Gambar --}}
                        <td class="text-center">
                            @if($item->gambar)
                                <img src="{{ asset('storage/konten/' . $item->gambar) }}"
                                     alt="Gambar Konten"
                                     width="80">
                            @else  
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        {{-- File --}}
                        <td class="text-center">
                            @if($item->file)
                                <a href="{{ asset('storage/konten/' . $item->file) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-file-download"></i> Unduh
                                </a>
                            @else  
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        {{-- Author --}}
                        <td>
                            {{ $item->admin->name ?? '-' }}
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.konten.edit', $item->id) }}"
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- delete nanti --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada konten
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
