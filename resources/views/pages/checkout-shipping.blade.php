@extends('layouts.app')

@section('title', __('messages.checkout.shipping_title') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Shipping Information Form -->
            <div class="lg:col-span-8 bg-[#161616] border border-outline-variant/10 p-8 space-y-8">
                
                <h1 class="font-headline text-2xl font-bold uppercase tracking-wider border-b border-outline-variant/10 pb-4">
                    {{ __('messages.checkout.shipping_title') }}
                </h1>
                
                <form action="{{ route('checkout.shipping.save') }}" method="POST" class="space-y-6 text-sm text-on-surface">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="flex flex-col gap-2">
                            <label for="first_name" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.first_name') }}
                            </label>
                            <input type="text" name="first_name" id="first_name" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   value="{{ old('first_name', $shippingInfo['first_name'] ?? '') }}"/>
                            @error('first_name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Last Name -->
                        <div class="flex flex-col gap-2">
                            <label for="last_name" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.last_name') }}
                            </label>
                            <input type="text" name="last_name" id="last_name" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   value="{{ old('last_name', $shippingInfo['last_name'] ?? '') }}"/>
                            @error('last_name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.email') }}
                            </label>
                            <input type="email" name="email" id="email" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   value="{{ old('email', $shippingInfo['email'] ?? '') }}"/>
                            @error('email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div class="flex flex-col gap-2">
                            <label for="phone" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.phone') }}
                            </label>
                            <input type="text" name="phone" id="phone" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   value="{{ old('phone', $shippingInfo['phone'] ?? '') }}"/>
                            @error('phone') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex flex-col gap-2">
                        <label for="address" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                            {{ __('messages.checkout.address') }}
                        </label>
                        <input type="text" name="address" id="address" required
                               class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                               placeholder="{{ app()->getLocale() == 'es' ? 'Av. Javier Prado Este 1234, Dpto 301' : '123 Main Street, Suite 4B' }}"
                               value="{{ old('address', $shippingInfo['address'] ?? '') }}"/>
                        @error('address') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Reference -->
                        <div class="flex flex-col gap-2">
                            <label for="reference" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.reference') }}
                            </label>
                            <input type="text" name="reference" id="reference"
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   placeholder="{{ app()->getLocale() == 'es' ? 'Frente a parque Grau o portón negro' : 'Near City Park' }}"
                                   value="{{ old('reference', $shippingInfo['reference'] ?? '') }}"/>
                            @error('reference') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- City -->
                        <div class="flex flex-col gap-2">
                            <label for="city" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                                {{ __('messages.checkout.city') }}
                            </label>
                            <input type="text" name="city" id="city" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs"
                                   placeholder="{{ app()->getLocale() == 'es' ? 'Lima, Miraflores' : 'Piura, Castilla' }}"
                                   value="{{ old('city', $shippingInfo['city'] ?? '') }}"/>
                            @error('city') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="flex flex-col gap-2">
                        <label for="notes" class="font-label-caps text-[10px] tracking-wider text-outline font-bold uppercase">
                            {{ app()->getLocale() == 'es' ? 'Notas de Entrega' : 'Delivery Notes' }}
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none"
                                  placeholder="{{ app()->getLocale() == 'es' ? 'Dejar en recepción por favor...' : 'Please leave with reception...' }}">{{ old('notes', $shippingInfo['notes'] ?? '') }}</textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full md:w-auto bg-primary text-on-primary px-12 py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold">
                            {{ __('messages.checkout.continue_payment') }}
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="lg:col-span-4 bg-[#161616] border border-outline-variant/10 p-6 space-y-6">
                
                <h3 class="font-label-caps text-xs tracking-wider text-on-surface font-bold uppercase pb-3 border-b border-outline-variant/10">
                    {{ __('messages.checkout.order_summary') }}
                </h3>
                
                <!-- Items list -->
                <div class="divide-y divide-outline-variant/10 max-h-[300px] overflow-y-auto pr-2">
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
                            <div class="flex-grow min-w-0 text-xs">
                                <h4 class="font-headline font-bold truncate text-on-surface">{{ $item['variant']->product->name }}</h4>
                                <p class="text-[10px] text-outline mt-0.5">{{ $item['variant']->name }} ({{ $item['variant']->weight }}g) x {{ $item['quantity'] }}</p>
                            </div>
                            <span class="text-xs font-bold text-secondary font-body">S/ {{ number_format($item['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <!-- Totals -->
                <div class="space-y-4 border-t border-outline-variant/10 pt-4 text-xs font-body">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart.subtotal') }}</span>
                        <span class="font-bold text-on-surface">S/ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart.shipping') }}</span>
                        @if($subtotal >= 150)
                            <span class="text-leaf-green font-bold uppercase tracking-wider">{{ __('messages.cart.free_shipping') }}</span>
                        @else
                            <span class="font-bold text-on-surface">S/ 15.00</span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </main>

@endsection
