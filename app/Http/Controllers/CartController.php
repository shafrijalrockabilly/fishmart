<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = null;

        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        $cart->load(['items.product.images', 'items.variant', 'items.product.brand']);

        $addresses = $user ? $user->addresses : [];
        $defaultAddress = $user ? $user->addresses()->where('is_default', true)->first() : null;

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $cart = $user
            ? Cart::firstOrCreate(['user_id' => $user->id])
            : Cart::firstOrCreate(['session_id' => $request->session()->getId()]);

        $item = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
            ],
            [
                'quantity' => DB::raw('quantity + '.$request->quantity),
                'is_selected' => true,
            ]
        );

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'is_selected' => 'nullable|boolean',
        ]);

        if ($request->has('quantity')) {
            $cartItem->update(['quantity' => $request->quantity]);
        }

        if ($request->has('is_selected')) {
            $cartItem->update(['is_selected' => $request->is_selected]);
        }

        return redirect()->back();
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $cart = Cart::where('user_id', $user->id)->with('items.product', 'items.variant')->first();
        if (! $cart || $cart->items->where('is_selected', true)->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong atau belum ada item yang dipilih.');
        }

        $address = $user->addresses()->where('is_default', true)->first();
        if (! $address) {
            return redirect()->back()->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu.');
        }

        DB::transaction(function () use ($user, $cart, $address) {
            $selectedItems = $cart->items->where('is_selected', true);
            $subtotal = 0;
            $totalWeight = 0;

            foreach ($selectedItems as $item) {
                $product = $item->product;
                $price = $item->variant ? ($item->variant->sale_price ?? $item->variant->price) : ($product->sale_price ?? $product->price);
                $weight = $item->variant->weight_gram ?? $product->weight_gram;

                $subtotal += $price * $item->quantity;
                $totalWeight += $weight * $item->quantity;
            }

            $shippingCost = 0; // Free shipping promo per Figma
            $grandTotal = $subtotal + $shippingCost;

            $invoiceNo = 'INV/'.date('Ymd').'/'.strtoupper(Str::random(4));

            $order = Order::create([
                'invoice_no' => $invoiceNo,
                'user_id' => $user->id,
                'status' => OrderStatus::PendingPayment,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->phone,
                'shipping_address' => $address->detail.', '.$address->city.', '.$address->province.' '.$address->postal_code,
                'shipping_city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
                'courier' => 'jnt',
                'shipping_service' => 'J&T Cargo Express',
                'shipping_etd' => 'Besok',
                'shipping_cost' => $shippingCost,
                'total_weight_gram' => $totalWeight,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'grand_total' => $grandTotal,
                'expires_at' => now()->addHours(24),
            ]);

            foreach ($selectedItems as $item) {
                $product = $item->product;
                $price = $item->variant ? ($item->variant->sale_price ?? $item->variant->price) : ($product->sale_price ?? $product->price);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $product->name,
                    'variant_name' => $item->variant?->name,
                    'image' => $product->images?->first()?->path,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'weight_gram' => $item->variant->weight_gram ?? $product->weight_gram,
                    'subtotal' => $price * $item->quantity,
                ]);

                // Reduce stock
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $product->decrement('stock', $item->quantity);
                }
            }

            // Clear selected cart items
            $cart->items()->where('is_selected', true)->delete();
        });

        return redirect('/akun/pesanan')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
