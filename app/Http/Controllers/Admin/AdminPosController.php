<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPosController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'items.product'
        ])->latest()->limit(20)->get();

        $products = Product::where(
            'active',
            true
        )->orderBy('name')->get();

        return view(
            'admin.pos.index',
            compact('orders', 'products')
        );
    }

    public function create()
    {
        $products = Product::where(
            'active',
            true
        )->orderBy('name')->get();

        return view(
            'admin.pos.create',
            compact('products')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'customer_name' =>
                'nullable|string|max:255',

            'payment_method' =>
                'required|in:tunai,transfer',

            'cash_received' =>
                'nullable|numeric|min:0',

            'payment_reference' =>
                'nullable|string|max:255',

            'notes' =>
                'nullable|string',

            'items' =>
                'required|array|min:1',

            'items.*.product_id' =>
                'required|exists:products,id',

            'items.*.quantity' =>
                'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $subtotal = 0;

            $orderItems = [];

            //
            // VALIDASI ITEM
            //

            foreach ($data['items'] as $itemData) {

                $product = Product::findOrFail(
                    $itemData['product_id']
                );

                $quantity =
                    (int) $itemData['quantity'];

                //
                // VALIDASI STOK
                //

                if ($product->stock < $quantity) {

                    throw new \Exception(
                        'Stok produk ' .
                        $product->name .
                        ' tidak cukup'
                    );
                }

                $lineTotal =
                    $product->price * $quantity;

                $subtotal += $lineTotal;

                $orderItems[] = [

                    'product_id' =>
                        $product->id,

                    'quantity' =>
                        $quantity,

                    'unit_price' =>
                        $product->price,

                    'total_price' =>
                        $lineTotal,
                ];
            }

            //
            // DEFAULT CONTRACT
            //

            $paymentStatus = 'pending';

            $contractStatus =
                'waiting_payment';

            $cashReceived = null;

            $changeAmount = 0;

            //
            // CASH CONTRACT
            //

            if (
                $data['payment_method'] ===
                'tunai'
            ) {

                $cashReceived =
                    (float) (
                        $data['cash_received'] ?? 0
                    );

                //
                // VALIDASI UANG
                //

                if ($cashReceived < $subtotal) {

                    throw new \Exception(
                        'Jumlah uang kurang'
                    );
                }

                //
                // HITUNG KEMBALIAN
                //

                $changeAmount =
                    $cashReceived - $subtotal;

                //
                // CONTRACT COMPLETE
                //

                $paymentStatus = 'paid';

                $contractStatus =
                    'completed';
            }

            //
            // TRANSFER CONTRACT
            //

            if (
                $data['payment_method'] ===
                'transfer'
            ) {

                //
                // BELUM LANGSUNG PAID
                //

                $paymentStatus = 'pending';

                $contractStatus =
                    'processing';
            }

            //
            // CREATE ORDER
            //

            $order = Order::create([

                'customer_name' =>
                    $data['customer_name']
                    ?? null,

                'subtotal' =>
                    $subtotal,

                'tax' => 0,

                'total' =>
                    $subtotal,

                'payment_method' =>
                    $data['payment_method'],

                'cash_received' =>
                    $cashReceived,

                'change_amount' =>
                    $changeAmount,

                'payment_reference' =>
                    $data['payment_reference']
                    ?? null,

                'payment_status' =>
                    $paymentStatus,

                'contract_status' =>
                    $contractStatus,

                'status' =>
                    $paymentStatus === 'paid'
                    ? 'paid'
                    : 'pending',

                'paid_at' =>
                    $paymentStatus === 'paid'
                    ? now()
                    : null,

                'order_number' =>
                    'POS-' .
                    now()->format('YmdHis'),

                'notes' =>
                    $data['notes'] ?? null,
            ]);

            //
            // SAVE ITEMS
            //

            foreach ($orderItems as $itemData) {

                $order->items()->create(
                    $itemData
                );
            }

            //
            // UPDATE STOCK
            // HANYA JIKA SUDAH PAID
            //

            if ($paymentStatus === 'paid') {

                foreach ($orderItems as $itemData) {

                    Product::find(
                        $itemData['product_id']
                    )->decrement(
                        'stock',
                        $itemData['quantity']
                    );
                }
            }

            DB::commit();

            //
            // RESPONSE
            //

            if (
                $data['payment_method'] ===
                'transfer'
            ) {

                return redirect()
                    ->route('admin.pos.index')
                    ->with(
                        'success',
                        'Pesanan dibuat. Menunggu verifikasi transfer.'
                    );
            }

            return redirect()
                ->route('admin.pos.index')
                ->with(
                    'success',
                    'Transaksi berhasil.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}