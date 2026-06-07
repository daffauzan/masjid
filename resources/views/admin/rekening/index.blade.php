@extends('admin.layouts.app')

@section('title', 'No Rekening')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar No Rekening Tujuan</h6>
        <a href="{{ route('admin.rekening.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah No Rekening
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Bank</th>
                        <th>No Rekening</th>
                        <th>Nama Pemilik</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekeningList as $rekening)
                        <tr>
                            <td>{{ $rekeningList->firstItem() + $loop->index }}</td>
                            <td>{{ $rekening->bank }}</td>
                            <td>{{ $rekening->no_rekening }}</td>
                            <td>{{ $rekening->nama_pemilik }}</td>
                            <td>
                                @if($rekening->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.rekening.edit', $rekening->id) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.rekening.destroy', $rekening->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data rekening tujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $rekeningList->links() }}
        </div>
    </div>
</div>
@endsection
