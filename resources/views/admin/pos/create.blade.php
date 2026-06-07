@extends('admin.layouts.app')

@section('title', 'Buat Pesanan POS')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buat Pesanan POS</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.pos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="customer_name">Nama Pelanggan</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Isikan nama pelanggan (opsional)">
            </div>

            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="tunai">Tunai</option>
                    <option value="kartu">Kartu</option>
                    <option value="transfer">Transfer</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            <div class="form-group">
                <label>Produk</label>
                <select name="items[0][product_id]" class="form-control mb-2">
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" placeholder="Jumlah">
            </div>

            <div class="form-group">
                <label for="notes">Catatan</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Buat Pesanan</button>
            <a href="{{ route('admin.pos.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
