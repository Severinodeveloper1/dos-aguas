<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedAdminMail;
use App\Mail\OrderPlacedCustomerMail;
use App\Models\CompanyInfo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'shipping_type' => 'required|string|in:national,international',
        ]);

        if ($validated['shipping_type'] === 'international' && empty($validated['country'])) {
            return redirect()->back()->withErrors(['country' => 'El país de destino es obligatorio para envíos internacionales.'])->withInput();
        }

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
        $paymentSettings = PaymentSetting::first();

        // Shipping cost logic:
        // National: S/ 15 flat rate, or free if subtotal is >= S/ 200
        // International: S/ 0.00 (cotizado por correo)
        $shippingType = $shippingInfo['shipping_type'] ?? 'national';
        $shippingCost = 0.00;
        
        if ($shippingType === 'national') {
            $shippingCost = $subtotal >= 200 ? 0.00 : 15.00;
        }

        $total = $subtotal + $shippingCost;

        return view('pages.checkout-payment', compact('cartItems', 'subtotal', 'shippingInfo', 'shippingCost', 'total', 'paymentSettings'));
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
        $customerFullName = trim($shipping['first_name'] . ' ' . $shipping['last_name']);

        $paymentId = null;
        $paymentStatus = 'pending';
        $orderStatus = 'pending';

        // Procesar cobro con Culqi si el usuario seleccionó tarjeta
        if ($request->payment_method === 'card') {
            $paymentSettings = PaymentSetting::first();

            if (!$paymentSettings || !$paymentSettings->gateway_enabled || $paymentSettings->gateway_provider !== 'culqi') {
                return redirect()->route('checkout.payment')->with('error', 'La pasarela de pago con tarjeta no está activa en este momento. Por favor elija transferencia bancaria.');
            }

            $culquiResult = $this->processCulquiPayment($paymentSettings, [
                'amount' => $total,
                'email' => $shipping['email'],
                'name' => $customerFullName,
                'phone' => $shipping['phone'],
            ]);

            if (!$culquiResult['success']) {
                return redirect()->route('checkout.payment')->with('error', $culquiResult['error'] ?? 'Falló el procesamiento del pago con tarjeta.');
            }

            $paymentId = $culquiResult['charge_id'];
            $paymentStatus = 'paid';
            $orderStatus = 'preparing';
        }

        // Perform transactional operation
        try {
            $order = DB::transaction(function () use ($shipping, $shippingType, $cartItems, $subtotal, $shippingCost, $total, $request, $customerFullName, $paymentId, $paymentStatus, $orderStatus) {
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

                $countryStr = !empty($shipping['country']) ? ', País: ' . $shipping['country'] : '';

                // Create Order record
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'customer_name' => $customerFullName,
                    'customer_email' => $shipping['email'],
                    'customer_phone' => $shipping['phone'],
                    'status' => $orderStatus,
                    'subtotal' => $subtotal,
                    'tax' => $subtotal * 0.18, // 18% IGV (included in price)
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'payment_id' => $paymentId,
                    'shipping_address' => $shipping['address'] . ' (' . ($shipping['reference'] ?? 'Sin referencia') . '), ' . $shipping['city'] . $countryStr . ' [' . ($shippingType === 'national' ? 'Envío Nacional' : 'Envío Internacional') . ']',
                    'billing_address' => $shipping['address'] . ', ' . $shipping['city'] . $countryStr,
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
     * Process payment with Culqi API v2
     */
    protected function processCulquiPayment(PaymentSetting $paymentSetting, array $paymentData)
    {
        try {
            $secretKey = trim($paymentSetting->gateway_private_key ?? '');

            if (empty($secretKey)) {
                return [
                    'success' => false,
                    'error' => 'La pasarela de pago Culqi no está configurada correctamente en el servidor. Falta la llave privada.',
                ];
            }

            $fullName = $paymentData['name'] ?? 'Cliente Dos Aguas';
            $parts = explode(' ', trim($fullName));
            $firstName = $parts[0] ?? 'Cliente';
            $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : $firstName;

            $culquiToken = request('culqui_token');

            if (empty($culquiToken)) {
                return [
                    'success' => false,
                    'error' => 'No se recibió el token de seguridad de la tarjeta. Por favor intente nuevamente.',
                ];
            }

            // Petición HTTP POST a Culqi API v2 charges
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.culqi.com/v2/charges', [
                'amount' => (int) round($paymentData['amount'] * 100),
                'currency_code' => 'PEN',
                'email' => $paymentData['email'],
                'source_id' => $culquiToken,
                'antifraud_details' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $paymentData['phone'] ?? '999999999',
                ],
                'metadata' => [
                    'cliente' => $fullName,
                    'telefono' => $paymentData['phone'] ?? '',
                ]
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['object']) && $result['object'] === 'charge') {
                return [
                    'success' => true,
                    'charge_id' => $result['id'],
                ];
            }

            $errorMessage = $result['user_message'] ?? ($result['description'] ?? 'Error desconocido al procesar la transacción en Culqi.');
            return [
                'success' => false,
                'error' => $errorMessage,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error de comunicación con Culqi: ' . $e->getMessage(),
            ];
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
