@extends('layouts.app')

@section('title', __('messages.checkout.shipping_title') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body" x-data="{
        subtotal: {{ $subtotal }},
        shippingType: '{{ old('shipping_type', $shippingInfo['shipping_type'] ?? 'national') }}',
        paymentMethod: '{{ old('payment_method', ($paymentSettings?->gateway_enabled && $paymentSettings?->gateway_provider === 'culqi') ? 'card' : 'transfer') }}',
        processing: false,

        get shippingCost() {
            if (this.shippingType === 'international') return 0;
            return this.subtotal >= 200 ? 0 : 15;
        },

        get total() {
            return this.subtotal + this.shippingCost;
        }
    }">

        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300"
                href="{{ route('home') }}">{{ app()->getLocale() == 'es' ? 'INICIO' : 'HOME' }}</a>
            <span class="text-[8px] opacity-40">/</span>
            <a class="hover:text-primary transition-colors duration-300"
                href="{{ route('cart.index') }}">{{ __('messages.nav.cart') }}</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">CHECKOUT</span>
        </nav>

        <!-- Flash messages / Errors -->
        @if (session('error'))
            <div class="mb-8 p-4 bg-error/10 border border-error/30 text-error text-xs font-body font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 p-4 bg-error/10 border border-error/30 text-error text-xs font-body space-y-1">
                <p class="font-bold">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <!-- Left Column: Unified Checkout Form (Shipping & Contact + Payment Method) -->
            <div class="lg:col-span-8 bg-[#161616] border border-outline-variant/10 p-8 space-y-10">

                <div>
                    <h1 class="font-headline text-2xl font-bold uppercase tracking-wider border-b border-outline-variant/10 pb-4">
                        {{ __('messages.checkout.shipping_title') }} & {{ __('messages.checkout.payment_title') }}
                    </h1>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-unified-form" class="space-y-10 text-sm text-on-surface">
                    @csrf
                    <input type="hidden" name="culqui_token" id="culqui_token" value="" />

                    <!-- ── SECCIÓN 1: DATOS DE ENVÍO Y CONTACTO ───────────────────────────── -->
                    <div class="space-y-6">
                        <h2 class="font-label-caps text-xs tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                            1. {{ __('messages.checkout.delivery_info') }}
                        </h2>

                        <!-- Shipping Type Selection -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                {{ __('messages.checkout.shipping_type') }} *
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="border p-4 cursor-pointer transition-all duration-300 flex flex-col justify-between"
                                    :class="shippingType === 'national' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="shipping_type" value="national" x-model="shippingType"
                                            class="text-primary focus:ring-0" />
                                        <span class="font-bold text-xs">{{ __('messages.checkout.national') }}</span>
                                    </div>
                                    <span class="text-[10px] text-outline mt-1.5 block leading-relaxed">{{ __('messages.checkout.national_cost_info') }}</span>
                                </label>

                                <label class="border p-4 cursor-pointer transition-all duration-300 flex flex-col justify-between"
                                    :class="shippingType === 'international' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="shipping_type" value="international" x-model="shippingType"
                                            class="text-primary focus:ring-0" />
                                        <span class="font-bold text-xs">{{ __('messages.checkout.international') }}</span>
                                    </div>
                                    <span class="text-[10px] text-outline mt-1.5 block leading-relaxed">{{ __('messages.checkout.international_info') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Name & Last Name -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label for="first_name" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.first_name') }} *
                                </label>
                                <input type="text" id="first_name" name="first_name" required
                                    value="{{ old('first_name', $shippingInfo['first_name'] ?? '') }}"
                                    placeholder="Ej: Juan"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="last_name" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.last_name') }} *
                                </label>
                                <input type="text" id="last_name" name="last_name" required
                                    value="{{ old('last_name', $shippingInfo['last_name'] ?? '') }}"
                                    placeholder="Ej: Pérez"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label for="email" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.email') }} *
                                </label>
                                <input type="email" id="email" name="email" required
                                    value="{{ old('email', $shippingInfo['email'] ?? '') }}"
                                    placeholder="ejemplo@correo.com"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="phone" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.phone') }} *
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                    value="{{ old('phone', $shippingInfo['phone'] ?? '') }}"
                                    placeholder="+51 987654321"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex flex-col gap-2">
                            <label for="address" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                {{ __('messages.checkout.address') }} *
                            </label>
                            <input type="text" id="address" name="address" required
                                value="{{ old('address', $shippingInfo['address'] ?? '') }}"
                                placeholder="{{ app()->getLocale() == 'es' ? 'Av. Javier Prado Este 1234, Dpto 301' : '123 Main Street, Suite 4B' }}"
                                class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                        </div>

                        <!-- Reference & City -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label for="reference" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.reference') }}
                                </label>
                                <input type="text" id="reference" name="reference"
                                    value="{{ old('reference', $shippingInfo['reference'] ?? '') }}"
                                    placeholder="{{ app()->getLocale() == 'es' ? 'Frente a parque Grau' : 'Near City Park' }}"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="city" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                    {{ __('messages.checkout.city') }} *
                                </label>
                                <input type="text" id="city" name="city" required
                                    value="{{ old('city', $shippingInfo['city'] ?? '') }}"
                                    placeholder="{{ app()->getLocale() == 'es' ? 'Lima, Miraflores' : 'Piura, Castilla' }}"
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            </div>
                        </div>

                        <!-- Destination Country (visible/required for International) -->
                        <div class="flex flex-col gap-2" x-show="shippingType === 'international'" x-cloak>
                            <label for="country" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                {{ app()->getLocale() == 'es' ? 'País de Destino' : 'Destination Country' }}
                                <span class="text-primary font-bold">*</span>
                            </label>
                            <input type="text" id="country" name="country" :required="shippingType === 'international'"
                                value="{{ old('country', $shippingInfo['country'] ?? '') }}"
                                placeholder="{{ app()->getLocale() == 'es' ? 'Ingresar país (ej. Estados Unidos, Alemania, España...)' : 'Country name (e.g. USA, Germany, Spain...)' }}"
                                class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                        </div>

                        <!-- Notes -->
                        <div class="flex flex-col gap-2">
                            <label for="notes" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                                {{ app()->getLocale() == 'es' ? 'Notas para la Entrega (Opcional)' : 'Delivery Notes (Optional)' }}
                            </label>
                            <textarea id="notes" name="notes" rows="2"
                                placeholder="{{ app()->getLocale() == 'es' ? 'Dejar en recepción por favor...' : 'Please leave with reception...' }}"
                                class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none">{{ old('notes', $shippingInfo['notes'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- ── SECCIÓN 2: MÉTODO DE PAGO ────────────────────────────────────── -->
                    <div class="space-y-6 border-t border-outline-variant/10 pt-8">
                        <h2 class="font-label-caps text-xs tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">payments</span>
                            2. {{ __('messages.checkout.payment_method') }}
                        </h2>

                        <div class="space-y-4">
                            <!-- Credit/Debit Card Option (Culqi) - PRIMERA OPCIÓN -->
                            @if ($paymentSettings?->gateway_enabled && $paymentSettings?->gateway_provider === 'culqi')
                                <label class="border p-5 cursor-pointer transition-all duration-300 flex items-start gap-4"
                                    :class="paymentMethod === 'card' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                    <input type="radio" name="payment_method" value="card" x-model="paymentMethod"
                                        class="mt-1 text-primary focus:ring-0" />
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-on-surface">
                                                {{ app()->getLocale() == 'es' ? 'Tarjeta de Crédito / Débito o Yape (Culqi Seguro)' : 'Credit / Debit Card or Yape (Culqi)' }}
                                            </span>
                                            <span class="bg-primary/20 text-primary text-[9px] px-2 py-0.5 font-bold uppercase tracking-wider">Recomendado</span>
                                        </div>
                                        <span class="text-xs text-outline block leading-relaxed">
                                            {{ app()->getLocale() == 'es' ? 'Visa, Mastercard, AMEX, Diners o Yape procesados instantáneamente y de forma encriptada.' : 'Visa, Mastercard, AMEX, Diners or Yape processed instantly and securely.' }}
                                        </span>
                                    </div>
                                </label>
                            @endif

                            <!-- Bank Transfer / Yape Option - SEGUNDA OPCIÓN -->
                            <label class="border p-5 cursor-pointer transition-all duration-300 flex items-start gap-4"
                                :class="paymentMethod === 'transfer' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod"
                                    class="mt-1 text-primary focus:ring-0" />
                                <div class="space-y-1">
                                    <span class="font-bold text-sm block text-on-surface">
                                        {{ __('messages.checkout.bank_transfer') }} / Yape / Plin Manual
                                    </span>
                                    <span class="text-xs text-outline block leading-relaxed">
                                        {{ app()->getLocale() == 'es' ? 'Depósito directo en BCP, CCI o Yape/Plin. Enviarás el comprobante para confirmar.' : 'Direct bank deposit in BCP, CCI or Yape. You will send receipt to confirm.' }}
                                    </span>
                                </div>
                            </label>
                        </div>

                        <!-- Details Panel for Bank Transfer -->
                        <div x-show="paymentMethod === 'transfer'" x-cloak class="bg-[#1c1b1b] border border-outline-variant/10 p-6 space-y-4 text-xs font-body">
                            <h3 class="font-label-caps text-xs tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">account_balance</span>
                                {{ app()->getLocale() == 'es' ? 'Instrucciones de Transferencia' : 'Transfer Instructions' }}
                            </h3>
                            <div class="text-on-surface-variant leading-relaxed space-y-2">
                                {!! $paymentSettings?->bank_transfer_details !!}
                            </div>
                        </div>

                        <!-- Details Panel for Card / Culqi -->
                        <div x-show="paymentMethod === 'card'" x-cloak class="bg-[#1c1b1b] border border-outline-variant/10 p-6 space-y-4 text-xs font-body">
                            <h3 class="font-label-caps text-xs tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">lock</span>
                                {{ app()->getLocale() == 'es' ? 'Pasarela de Pago Segura Culqi' : 'Culqi Secure Payment Gateway' }}
                            </h3>
                            <p class="text-on-surface-variant leading-relaxed">
                                {{ app()->getLocale() == 'es' ? 'Al hacer clic en "Procesar Pedido / Realizar Pago", se abrirá el modal seguro de Culqi para ingresar los datos de tu tarjeta o pagar por Yape.' : 'When clicking "Place Order", the secure Culqi popup will open for entering your card details or paying via Yape.' }}
                            </p>
                            <div class="flex items-center gap-3 pt-2 text-xs font-bold text-on-surface">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-primary">verified_user</span> 256-bit SSL</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-primary">shield</span> PCI-DSS Compliant</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="submit-unified-btn" :disabled="processing"
                            class="w-full bg-primary text-on-primary py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold flex items-center justify-center gap-2">
                            <span x-show="processing" class="w-4 h-4 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="processing ? 'Procesando...' : '{{ __('messages.checkout.place_order') }}'"></span>
                        </button>
                    </div>

                </form>

            </div>

            <!-- Right Column: Live Order Summary Sidebar -->
            <div class="lg:col-span-4 bg-[#161616] border border-outline-variant/10 p-6 space-y-6 text-xs sticky top-28">
                <h3 class="font-label-caps text-xs tracking-wider text-on-surface font-bold uppercase pb-3 border-b border-outline-variant/10">
                    {{ __('messages.checkout.order_summary') }}
                </h3>

                <div class="divide-y divide-outline-variant/10 max-h-[350px] overflow-y-auto pr-2">
                    @foreach ($cartItems as $item)
                        @php
                            $thumb = is_array($item['variant']->product->images) && count($item['variant']->product->images) > 0
                                ? $item['variant']->product->images[0]
                                : null;
                        @endphp
                        <div class="flex items-center gap-4 py-3">
                            <div class="w-12 h-16 bg-surface-container flex-shrink-0 border border-outline-variant/5">
                                @if ($thumb)
                                    <img src="{{ str_starts_with($thumb, 'http') ? $thumb : asset('storage/' . $thumb) }}"
                                        class="w-full h-full object-cover" />
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-headline font-bold truncate text-on-surface">
                                    {{ $item['variant']->product->display_name }}</h4>
                                <p class="text-[10px] text-outline mt-0.5">{{ $item['variant']->display_name }}
                                    ({{ $item['variant']->weight }}g) x {{ $item['quantity'] }}</p>
                            </div>
                            <span class="font-bold text-secondary font-body">S/ {{ number_format($item['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4 border-t border-outline-variant/10 pt-4 font-body">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart.subtotal') }}</span>
                        <span class="font-bold text-on-surface">S/ {{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart.shipping') }}</span>
                        <template x-if="shippingType === 'international'">
                            <span class="text-primary font-bold uppercase tracking-wider text-[10px] text-right">
                                {{ app()->getLocale() == 'es' ? 'Por cotizar por correo' : (app()->getLocale() == 'de' ? 'Wird per E-Mail berechnet' : 'To quote via email') }}
                            </span>
                        </template>
                        <template x-if="shippingType === 'national'">
                            <div>
                                <span x-show="shippingCost > 0" class="font-bold text-on-surface" x-text="'S/ ' + shippingCost.toFixed(2)"></span>
                                <span x-show="shippingCost === 0" class="text-leaf-green font-bold uppercase tracking-wider">{{ __('messages.cart.free_shipping') }}</span>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-between text-[10px] text-outline italic">
                        <span>{{ app()->getLocale() == 'es' ? 'Impuesto (18% IGV Incl.)' : 'Tax (18% VAT Incl.)' }}</span>
                        <span>S/ {{ number_format($subtotal * 0.18, 2) }}</span>
                    </div>

                    <div class="flex justify-between border-t border-outline-variant/10 pt-4 text-sm font-bold">
                        <span class="uppercase tracking-wider">{{ __('messages.cart.total') }}</span>
                        <span class="text-secondary text-base" x-text="'S/ ' + total.toFixed(2)"></span>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @section('scripts')
        <script src="https://js.culqi.com/checkout-js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.getElementById('checkout-unified-form');
                if (!form) return;

                var culquiPublicKey = '{{ $paymentSettings?->gateway_public_key ?? '' }}';
                var culquiEnabled = {{ $paymentSettings?->gateway_enabled && $paymentSettings?->gateway_provider === 'culqi' ? 'true' : 'false' }};

                function ensureCulqiLoaded(callback) {
                    if (typeof CulqiCheckout !== 'undefined' || typeof Culqi !== 'undefined') {
                        callback();
                        return;
                    }

                    var existingScript = document.querySelector('script[src="https://js.culqi.com/checkout-js"]');
                    if (existingScript) {
                        existingScript.addEventListener('load', callback);
                        return;
                    }

                    var script = document.createElement('script');
                    script.src = 'https://js.culqi.com/checkout-js';
                    script.onload = callback;
                    script.onerror = function() {
                        alert('No se pudo conectar con la pasarela de Culqi. Verifica tu conexión a internet o intenta más tarde.');
                    };
                    document.head.appendChild(script);
                }

                form.addEventListener('submit', function(e) {
                    var selectedMethod = form.querySelector('input[name="payment_method"]:checked')?.value || 'transfer';

                    if (selectedMethod === 'card' && culquiEnabled && culquiPublicKey) {
                        if (document.getElementById('culqui_token')?.value) {
                            return true;
                        }

                        e.preventDefault();

                        var emailInput = document.getElementById('email');
                        if (!emailInput || !emailInput.checkValidity()) {
                            form.reportValidity();
                            return;
                        }

                        // Calculate total in cents
                        var shippingTypeInput = form.querySelector('input[name="shipping_type"]:checked')?.value || 'national';
                        var subtotal = {{ $subtotal }};
                        var shippingCost = (shippingTypeInput === 'national' && subtotal < 200) ? 15 : 0;
                        var totalAmountCents = Math.round((subtotal + shippingCost) * 100);

                        ensureCulqiLoaded(function() {
                            // Strict JS Config adhering to Culqi JS SDK schema (prevents console ValidationError)
                            const config = {
                                settings: {
                                    title: 'DOS AGUAS',
                                    currency: 'PEN',
                                    amount: totalAmountCents
                                },
                                client: {
                                    email: emailInput.value
                                },
                                options: {
                                    lang: 'auto',
                                    installments: true,
                                    paymentMethods: {
                                        tarjeta: true,
                                        yape: true
                                    }
                                },
                                appearance: {
                                    theme: 'default'
                                }
                            };

                            var CulqiInstance = null;
                            if (typeof CulqiCheckout !== 'undefined') {
                                CulqiInstance = new CulqiCheckout(culquiPublicKey, config);
                            } else if (typeof Culqi !== 'undefined') {
                                Culqi.publicKey = culquiPublicKey;
                                Culqi.settings(config.settings);
                                Culqi.options(config.options);
                                Culqi.appearance(config.appearance);
                                CulqiInstance = Culqi;
                            }

                            if (!CulqiInstance) {
                                alert('La pasarela de Culqi no se cargó correctamente. Por favor recargue la página.');
                                return;
                            }

                            CulqiInstance.culqi = function() {
                                if (CulqiInstance.token) {
                                    var token = CulqiInstance.token.id;
                                    var tokenInput = document.getElementById('culqui_token');
                                    if (!tokenInput) {
                                        tokenInput = document.createElement('input');
                                        tokenInput.type = 'hidden';
                                        tokenInput.name = 'culqui_token';
                                        tokenInput.id = 'culqui_token';
                                        form.appendChild(tokenInput);
                                    }
                                    tokenInput.value = token;
                                    CulqiInstance.close();
                                    form.submit();
                                } else if (CulqiInstance.error) {
                                    CulqiInstance.close();
                                    alert('Error de pago: ' + CulqiInstance.error.user_message);
                                }
                            };

                            window.culqi = CulqiInstance.culqi;

                            CulqiInstance.open();
                        });
                    }
                });
            });
        </script>
    @endsection

@endsection
