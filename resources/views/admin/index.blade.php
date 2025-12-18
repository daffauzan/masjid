@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- ================= INFORMASI ================= -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informasi Masjid</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>File</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- isi nanti -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= DAKWAH ================= -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Dakwah Jumat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>File</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- isi nanti -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= ZAKAT ================= -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">Data Zakat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Zakat</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- isi nanti -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
  <script src="{{ asset('assets/admin/vendor/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/demo/chart-area-demo.js') }}"></script>
  <script src="{{ asset('assets/admin/js/demo/chart-pie-demo.js') }}"></script>
@endpush
