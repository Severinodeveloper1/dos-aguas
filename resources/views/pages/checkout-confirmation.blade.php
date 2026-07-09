@extends('layouts.app')

@section('title', __('messages.checkout.success_title') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">
        
        <div class="max-w-3xl mx-auto bg-[#161616] border border-outline-variant/10 p-8 md:p-12 space-y-10 text-center">
            
            <!-- Success Status Icon -->
            <div class="flex justify-center">
                <div class="w-20 h-20 bg-leaf-green/10 border border-leaf-green/30 flex items-center justify-center text-leaf-green">
                    <span class="material-symbols-outlined text-[48px]">check</span>
                </div>
            </div>

            <!-- Headers -->
            <div class="space-y-4">
                <h1 class="font-headline text-3xl font-bold uppercase tracking-wider text-on-surface">
                    {{ __('messages.checkout.success_title') }}
                </h1>
                <p class="text-sm text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                    {{ __('messages.checkout.success_message') }}
                </p>
            </div>

            <!-- Receipt Info Header -->
            <div class="border-t border-b border-outline-variant/10 py-6 text-left grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-xs font-body leading-relaxed">
                <div>
                    <span class="text-outline uppercase font-bold text-[9px] tracking-wider block mb-1">
                        {{ __('messages.checkout.order_number') }}
                    </span>
                    <span class="font-bold text-on-surface text-sm">{{ $order->order_number }}</span>
                </div>
                <div>
                    <span class="text-outline uppercase font-bold text-[9px] tracking-wider block mb-1">
                        {{ app()->getLocale() == 'es' ? 'FECHA' : 'DATE' }}
                    </span>
                    <span class="font-bold text-on-surface">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-outline uppercase font-bold text-[9px] tracking-wider block mb-1">
                        {{ app()->getLocale() == 'es' ? 'MÉTODO DE PAGO' : 'PAYMENT METHOD' }}
                    </span>
                    <span class="font-bold text-on-surface uppercase">{{ $order->payment_method === 'transfer' ? __('messages.checkout.bank_transfer') : 'Tarjeta' }}</span>
                </div>
                <div>
                    <span class="text-outline uppercase font-bold text-[9px] tracking-wider block mb-1">
                        TOTAL
                    </span>
                    <span class="font-bold text-secondary text-sm">S/ {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="text-left bg-[#1c1b1b] border border-outline-variant/10 p-6 space-y-4 text-xs">
                <h3 class="font-label-caps text-[10px] tracking-wider text-primary font-bold uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">local_shipping</span>
                    {{ __('messages.checkout.delivery_info') }}
                </h3>
                <div class="text-on-surface-variant space-y-1">
                    <p class="font-bold text-on-surface">{{ $order->customer_name }}</p>
                    <p>{{ $order->customer_phone }} | {{ $order->customer_email }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->notes)
                        <p class="pt-2 italic text-[11px] border-t border-outline-variant/5 mt-2">
                            <strong>{{ app()->getLocale() == 'es' ? 'Nota:' : 'Note:' }}</strong> {{ $order->notes }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Purchased items receipt table -->
            <div class="text-left space-y-4">
                <h3 class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                    {{ app()->getLocale() == 'es' ? 'DETALLE DEL PEDIDO' : 'ORDER DETAILS' }}
                </h3>
                
                <div class="border border-outline-variant/10 divide-y divide-outline-variant/10 text-xs">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center p-4">
                            <div>
                                <h4 class="font-headline font-bold text-on-surface">{{ $item->variant->product->name }}</h4>
                                <span class="text-[10px] text-outline mt-0.5 block">
                                    {{ $item->variant->name }} ({{ $item->variant->weight }}g) x {{ $item->quantity }}
                                </span>
                            </div>
                            <span class="font-bold text-on-surface font-body">S/ {{ number_format($item->total, 2) }}</span>
                        </div>
                    @endforeach
                    
                    <!-- Subtotal + Shipping summary -->
                    <div class="p-4 space-y-3 font-body text-[11px] text-on-surface-variant bg-[#1a1a1a]">
                        <div class="flex justify-between">
                            <span>{{ __('messages.cart.subtotal') }}</span>
                            <span>S/ {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('messages.cart.shipping') }}</span>
                            <span>{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : __('messages.cart.free_shipping') }}</span>
                        </div>
                        <div class="flex justify-between border-t border-outline-variant/10 pt-3 text-xs font-bold text-on-surface">
                            <span class="uppercase tracking-wider">TOTAL PAID</span>
                            <span class="text-secondary">S/ {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="pt-6">
                <a href="{{ route('home') }}" 
                   class="inline-block bg-primary text-on-primary px-12 py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold">
                    {{ __('messages.checkout.back_home') }}
                </a>
            </div>

        </div>

    </main>

@endsection
