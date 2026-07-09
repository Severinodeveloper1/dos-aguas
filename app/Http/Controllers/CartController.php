<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart list.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        if (!empty($cart)) {
            $variants = ProductVariant::with('product')
                ->whereIn('id', array_keys($cart))
                ->get();

            foreach ($variants as $variant) {
                $quantity = $cart[$variant->id] ?? 0;
                if ($quantity <= 0) continue;

                $total = $variant->price * $quantity;
                $subtotal += $total;

                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'total' => $total,
                ];
            }
        }

        return view('pages.cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Add a product variant to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variantId = $request->product_variant_id;
        $quantity = (int) $request->quantity;

        // Verify variant and stock availability
        $variant = ProductVariant::findOrFail($variantId);
        if (!$variant->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Esta variante no está disponible.'
            ], 422);
        }

        $cart = session()->get('cart', []);

        $currentQty = $cart[$variantId] ?? 0;
        $newQuantity = $currentQty + $quantity;

        if ($variant->stock < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $variant->stock . ' unidades disponibles.'
            ], 422);
        }

        $cart[$variantId] = $newQuantity;
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito con éxito.',
            'cartCount' => array_sum($cart),
        ]);
    }

    /**
     * Update variant quantity in the cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variantId = $request->product_variant_id;
        $quantity = (int) $request->quantity;

        $variant = ProductVariant::findOrFail($variantId);

        if ($variant->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $variant->stock . ' unidades disponibles.'
            ], 422);
        }

        $cart = session()->get('cart', []);
        $cart[$variantId] = $quantity;
        session()->put('cart', $cart);

        // Recalculate totals
        $subtotal = 0;
        $variants = ProductVariant::whereIn('id', array_keys($cart))->get();
        foreach ($variants as $v) {
            $qty = $cart[$v->id] ?? 0;
            $subtotal += $v->price * $qty;
        }

        $itemTotal = $variant->price * $quantity;

        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado.',
            'cartCount' => array_sum($cart),
            'itemTotal' => number_format($itemTotal, 2),
            'subtotal' => number_format($subtotal, 2),
        ]);
    }

    /**
     * Remove a variant from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        $variantId = $request->product_variant_id;
        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }

        // Recalculate subtotal
        $subtotal = 0;
        if (!empty($cart)) {
            $variants = ProductVariant::whereIn('id', array_keys($cart))->get();
            foreach ($variants as $v) {
                $qty = $cart[$v->id] ?? 0;
                $subtotal += $v->price * $qty;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto removido del carrito.',
            'cartCount' => array_sum($cart),
            'subtotal' => number_format($subtotal, 2),
        ]);
    }
}
