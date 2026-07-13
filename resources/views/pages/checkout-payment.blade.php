@extends('layouts.app')

@section('title', __('messages.checkout.payment_title') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body"
          x-data="{ 
              method: 'transfer',
              processing: false
          }">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Payment details -->
            <div class="lg:col-span-8 bg-[#161616] border border-outline-variant/10 p-8 space-y-8">
                
                <h1 class="font-headline text-2xl font-bold uppercase tracking-wider border-b border-outline-variant/10 pb-4">
                    {{ __('messages.checkout.payment_title') }}
                </h1>

                <!-- Address Verification Summary -->
                <div class="border border-outline-variant/10 bg-[#1c1b1b] p-6 space-y-4 text-xs font-body">
                    <h3 class="font-label-caps text-[10px] tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                        {{ __('messages.checkout.delivery_info') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-on-surface-variant leading-relaxed">
                        <div>
                            <p class="font-bold text-on-surface">{{ $shippingInfo['first_name'] }} {{ $shippingInfo['last_name'] }}</p>
                            <p>{{ $shippingInfo['email'] }}</p>
                            <p>{{ $shippingInfo['phone'] }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface">{{ $shippingInfo['address'] }}</p>
                            @if($shippingInfo['reference'])
                                <p class="italic text-[11px]">Ref: {{ $shippingInfo['reference'] }}</p>
                            @endif
                            <p>{{ $shippingInfo['city'] }}</p>
                            <p class="text-primary font-bold mt-1 text-[10px]">
                                {{ ($shippingInfo['shipping_type'] ?? 'national') === 'national' ? __('messages.checkout.national') : __('messages.checkout.international') }}
                            </p>
                        </div>
                    </div>
                </div>

                @if(($shippingInfo['shipping_type'] ?? 'national') === 'international')
                    <!-- Alerta de envío internacional -->
                    <div class="p-4 bg-primary/10 border border-primary/30 text-primary text-xs font-body leading-relaxed">
                        <strong>🌐 {{ __('messages.checkout.shipping_type') }}:</strong> 
                        {{ __('messages.checkout.international_info') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-error/10 border border-error/30 text-error text-xs font-bold font-body">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Payment form -->
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-8" @submit="processing = true">
                    @csrf
                    
                    <!-- Method choices -->
                    <div class="space-y-4">
                        <span class="font-label-caps text-[10px] tracking-widest text-outline block uppercase font-bold">
                            {{ __('messages.checkout.payment_method') }}
                        </span>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bank Transfer button -->
                            <label class="border p-4 flex items-center gap-3 cursor-pointer select-none transition-all duration-300 font-body text-xs font-bold"
                                   :class="method === 'transfer' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                <input type="radio" name="payment_method" value="transfer" class="hidden" @click="method = 'transfer'" checked />
                                <span class="material-symbols-outlined text-lg">account_balance</span>
                                {{ __('messages.checkout.bank_transfer') }}
                            </label>
                            
                            <!-- Card payment button -->
                            <label class="border p-4 flex items-center gap-3 cursor-pointer select-none transition-all duration-300 font-body text-xs font-bold"
                                   :class="method === 'card' ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant/20 text-on-surface-variant hover:border-primary'">
                                <input type="radio" name="payment_method" value="card" class="hidden" @click="method = 'card'" />
                                <span class="material-symbols-outlined text-lg">credit_card</span>
                                {{ app()->getLocale() == 'es' ? 'Tarjeta de Crédito / Débito' : 'Credit / Debit Card' }}
                            </label>
                        </div>
                    </div>

                    <!-- Payment details panels -->
                    <div class="bg-[#1c1b1b] border border-outline-variant/10 p-6">
                        
                        <!-- Bank Transfer instructions -->
                        <div x-show="method === 'transfer'" x-transition class="space-y-4 text-xs font-body leading-relaxed text-on-surface-variant">
                            <p class="font-bold text-on-surface uppercase tracking-wider text-[10px] text-primary">
                                {{ app()->getLocale() == 'es' ? 'Instrucciones de Transferencia' : 'Transfer Instructions' }}
                            </p>
                            <p>
                                {{ app()->getLocale() == 'es' ? 'Por favor realiza la transferencia a nuestra cuenta bancaria y envía el comprobante a nuestro canal de WhatsApp para procesar tu pedido de inmediato.' : 'Please perform the transfer to our bank account and send the receipt to our WhatsApp support channel to process your order immediately.' }}
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-outline-variant/10 pt-4 text-[11px]">
                                <div>
                                    <p class="font-bold text-on-surface">Banco de Crédito del Perú (BCP)</p>
                                    <p>Cuenta Corriente: <span class="font-bold text-on-surface">191-9876543-0-12</span></p>
                                    <p>CCI: <span class="font-bold text-on-surface">002-191009876543012050</span></p>
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface">Beneficiario: Dos Aguas S.A.C.</p>
                                    <p>RUC: 20123456789</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Simulated Card Details -->
                        <div x-show="method === 'card'" x-transition class="space-y-4 text-xs font-body">
                            <p class="font-bold text-on-surface uppercase tracking-wider text-[10px] text-primary">
                                {{ app()->getLocale() == 'es' ? 'Ingresa los datos de tu tarjeta' : 'Enter your card details' }}
                            </p>
                            
                            <div class="space-y-4">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[9px] font-label-caps text-outline font-bold">Número de Tarjeta</label>
                                    <input type="text" placeholder="4111 2222 3333 4444" 
                                           class="bg-[#131313] border border-outline-variant/30 text-on-surface py-2.5 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-[9px] font-label-caps text-outline font-bold">Fecha de Expiración</label>
                                        <input type="text" placeholder="MM / YY" 
                                               class="bg-[#131313] border border-outline-variant/30 text-on-surface py-2.5 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs text-center" />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="text-[9px] font-label-caps text-outline font-bold">CVV</label>
                                        <input type="text" placeholder="123" 
                                               class="bg-[#131313] border border-outline-variant/30 text-on-surface py-2.5 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs text-center" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit action -->
                    <div class="pt-4">
                        <button type="submit" :disabled="processing"
                                class="w-full bg-primary text-on-primary py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold flex items-center justify-center gap-2">
                            <span x-show="processing" class="w-4 h-4 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="processing ? '...' : '{{ __('messages.checkout.place_order') }}'"></span>
                        </button>
                    </div>

                </form>

            </div>

            <!-- Right: Summary Sidebar -->
            <div class="lg:col-span-4 bg-[#161616] border border-outline-variant/10 p-6 space-y-6 text-xs">
                <h3 class="font-label-caps text-xs tracking-wider text-on-surface font-bold uppercase pb-3 border-b border-outline-variant/10">
                    {{ __('messages.checkout.order_summary') }}
                </h3>
                
                <div class="divide-y divide-outline-variant/10 pr-2">
                    @foreach($cartItems as $item)
                        @php
                            $thumb = is_array($item['variant']->product->images) && count($item['variant']->product->images) > 0 ? $item['variant']->product->images[0] : null;
                        @endphp
                        <div class="flex items-center gap-4 py-4">
                            <div class="w-12 h-16 bg-surface-container flex-shrink-0 border border-outline-variant/5">
                                @if($thumb)
                                    <img src="{{ str_starts_with($thumb, 'http') ? $thumb : asset('storage/' . $thumb) }}" class="w-full h-full object-cover"/>
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-headline font-bold truncate text-on-surface">{{ $item['variant']->product->name }}</h4>
                                <p class="text-[10px] text-outline mt-0.5">{{ $item['variant']->name }} ({{ $item['variant']->weight }}g) x {{ $item['quantity'] }}</p>
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
                        @if(($shippingInfo['shipping_type'] ?? 'national') === 'international')
                            <span class="text-primary font-bold uppercase tracking-wider text-[10px] text-right">
                                {{ app()->getLocale() == 'es' ? 'Por cotizar por correo' : (app()->getLocale() == 'de' ? 'Wird per E-Mail berechnet' : 'To quote via email') }}
                            </span>
                        @else
                            @if($shippingCost > 0)
                                <span class="font-bold text-on-surface">S/ {{ number_format($shippingCost, 2) }}</span>
                            @else
                                <span class="text-leaf-green font-bold uppercase tracking-wider">{{ __('messages.cart.free_shipping') }}</span>
                            @endif
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-[10px] text-outline italic">
                        <span>{{ app()->getLocale() == 'es' ? 'Impuesto (18% IGV Incl.)' : 'Tax (18% VAT Incl.)' }}</span>
                        <span>S/ {{ number_format($subtotal * 0.18, 2) }}</span>
                    </div>

                    <div class="flex justify-between border-t border-outline-variant/10 pt-4 text-sm font-bold">
                        <span class="uppercase tracking-wider">{{ __('messages.cart.total') }}</span>
                        <span class="text-secondary text-base">S/ {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </main>

@endsection
