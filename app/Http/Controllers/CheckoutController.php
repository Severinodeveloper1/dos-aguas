<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedAdminMail;
use App\Mail\OrderPlacedCustomerMail;
use App\Models\CompanyInfo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Helper to get current cart items and subtotal.
     */
    private function getCartData()
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

        return [$cartItems, $subtotal];
    }

    /**
     * Show checkout shipping details form.
     */
    public function shippingForm()
    {
        list($cartItems, $subtotal) = $this->getCartData();

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        $shippingInfo = session()->get('checkout.shipping', []);

        return view('pages.checkout-shipping', compact('cartItems', 'subtotal', 'shippingInfo'));
    }

    /**
     * Save shipping information in session.
     */
    public function saveShipping(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'reference' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'shipping_type' => 'required|string|in:national,international',
        ]);

        session()->put('checkout.shipping', $validated);

        return redirect()->route('checkout.payment');
    }

    /**
     * Show payment method and order preview page.
     */
    public function paymentForm()
    {
        list($cartItems, $subtotal) = $this->getCartData();

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        if (!session()->has('checkout.shipping')) {
            return redirect()->route('checkout.shipping')->with('error', 'Por favor complete la información de envío.');
        }

        $shippingInfo = session()->get('checkout.shipping');

        // Shipping cost logic:
        // National: S/ 15 flat rate, or free if subtotal is >= S/ 200
        // International: S/ 0.00 (cotizado por correo)
        $shippingType = $shippingInfo['shipping_type'] ?? 'national';
        $shippingCost = 0.00;
        
        if ($shippingType === 'national') {
            $shippingCost = $subtotal >= 200 ? 0.00 : 15.00;
        }

        $total = $subtotal + $shippingCost;

        return view('pages.checkout-payment', compact('cartItems', 'subtotal', 'shippingInfo', 'shippingCost', 'total'));
    }

    /**
     * Process checkout form, complete transaction, decrement stock, and redirect.
     */
    public function processOrder(Request $request)
    {
        list($cartItems, $subtotal) = $this->getCartData();

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        if (!session()->has('checkout.shipping')) {
            return redirect()->route('checkout.shipping')->with('error', 'Por favor complete la información de envío.');
        }

        $request->validate([
            'payment_method' => 'required|string|in:transfer,card',
        ]);

        $shipping = session()->get('checkout.shipping');
        
        $shippingType = $shipping['shipping_type'] ?? 'national';
        $shippingCost = 0.00;
        
        if ($shippingType === 'national') {
            $shippingCost = $subtotal >= 200 ? 0.00 : 15.00;
        }
        
        $total = $subtotal + $shippingCost;

        // Perform transactional operation
        try {
            $order = DB::transaction(function () use ($shipping, $shippingType, $cartItems, $subtotal, $shippingCost, $total, $request) {
                $year = now()->year;
                
                // Atomic lock for next order number
                $lastOrder = Order::whereYear('created_at', $year)
                    ->lockForUpdate()
                    ->latest('id')
                    ->first();

                $nextNum = 1;
                if ($lastOrder) {
                    $parts = explode('-', $lastOrder->order_number);
                    $lastNum = (int) end($parts);
                    $nextNum = $lastNum + 1;
                }

                $sequence = str_pad($nextNum, 5, '0', STR_PAD_LEFT);
                $orderNumber = "DA-{$year}-{$sequence}";

                // Create Order record
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'customer_name' => $shipping['first_name'] . ' ' . $shipping['last_name'],
                    'customer_email' => $shipping['email'],
                    'customer_phone' => $shipping['phone'],
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax' => $subtotal * 0.18, // 18% IGV (included in price)
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'shipping_address' => $shipping['address'] . ' (' . ($shipping['reference'] ?? 'Sin referencia') . '), ' . $shipping['city'] . ' [' . ($shippingType === 'national' ? 'Envío Nacional' : 'Envío Internacional') . ']',
                    'billing_address' => $shipping['address'] . ', ' . $shipping['city'],
                    'notes' => $shipping['notes'] ?? null,
                ]);

                // Create OrderItem records & decrement stock
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item['variant']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['variant']->price,
                        'total' => $item['total'],
                    ]);

                    // Decrement stock
                    $variant = ProductVariant::lockForUpdate()->find($item['variant']->id);
                    if ($variant->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para " . $variant->product->name . " (" . $variant->name . ")");
                    }
                    $variant->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            // Clear session cart
            session()->forget('cart');
            session()->forget('checkout.shipping');

            // ── Email Notifications ──────────────────────────────────────────
            $order->load('items.variant.product');
            $company    = CompanyInfo::first();
            $adminEmail = $company?->contact_email_receiver ?: $company?->email;

            // 1. Notify the store manager
            if ($adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new OrderPlacedAdminMail($order));
                } catch (\Exception $mailEx) {
                    Log::error('Error enviando email al manager: ' . $mailEx->getMessage());
                }
            }

            // 2. Confirm to the customer
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->send(new OrderPlacedCustomerMail($order));
                } catch (\Exception $mailEx) {
                    Log::error('Error enviando confirmacion al cliente: ' . $mailEx->getMessage());
                }
            }
            // ────────────────────────────────────────────────────────────────

            return redirect()->route('checkout.confirmation', ['orderNumber' => $order->order_number]);

        } catch (\Exception $e) {
            return redirect()->route('checkout.payment')->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Show order confirmation page.
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.variant.product')
            ->firstOrFail();

        return view('pages.checkout-confirmation', compact('order'));
    }
}
