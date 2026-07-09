@extends('layouts.app')

@section('title', __('messages.cart.title') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body"
          x-data="{
              cart: [
                  @foreach($cartItems as $item)
                  {
                      variantId: {{ $item['variant']->id }},
                      name: '{{ addslashes($item['variant']->product->name) }}',
                      variantName: '{{ addslashes($item['variant']->name) }}',
                      weight: {{ $item['variant']->weight }},
                      price: {{ $item['variant']->price }},
                      image: '{{ is_array($item['variant']->product->images) && count($item['variant']->product->images) > 0 ? (str_starts_with($item['variant']->product->images[0], 'http') ? $item['variant']->product->images[0] : asset('storage/' . $item['variant']->product->images[0])) : '' }}',
                      qty: {{ $item['quantity'] }},
                      stock: {{ $item['variant']->stock }},
                      slug: '{{ $item['variant']->product->slug }}'
                  },
                  @endforeach
              ],
              
              get subtotal() {
                  return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
              },
              get shipping() {
                  return this.subtotal >= 150 || this.subtotal === 0 ? 0 : 15;
              },
              get total() {
                  return this.subtotal + this.shipping;
              },
              
              updateQty(item, newQty) {
                  if (newQty < 1) return;
                  if (newQty > item.stock) {
                      alert('Solo hay ' + item.stock + ' unidades disponibles en stock.');
                      return;
                  }
                  item.qty = newQty;
                  
                  fetch('{{ route('cart.update') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                          product_variant_id: item.variantId,
                          quantity: item.qty
                      })
                  })
                  .then(res => res.json())
                  .then(data => {
                      if(data.success) {
                          const badge = document.querySelector('header a[href*=\"carrito\"] span');
                          if (badge) badge.innerText = data.cartCount;
                      } else {
                          alert(data.message);
                      }
                  });
              },
              
              removeItem(item, index) {
                  this.cart.splice(index, 1);
                  
                  fetch('{{ route('cart.remove') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                          product_variant_id: item.variantId
                      })
                  })
                  .then(res => res.json())
                  .then(data => {
                      if(data.success) {
                          const badge = document.querySelector('header a[href*=\"carrito\"] span');
                          if (badge) {
                              if (data.cartCount > 0) {
                                  badge.innerText = data.cartCount;
                              } else {
                                  badge.remove();
                              }
                          }
                      }
                  });
              }
          }">
          
        <h1 class="font-headline text-3xl md:text-4xl font-bold mb-12 text-center uppercase">{{ __('messages.cart.title') }}</h1>

        <!-- Cart Content Wrapper -->
        <template x-if="cart.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Cart Items List Table -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="border border-outline-variant/10 bg-[#161616] p-6">
                        
                        <div class="hidden md:grid grid-cols-12 pb-4 border-b border-outline-variant/10 text-xs font-label-caps text-outline font-bold">
                            <div class="col-span-6">{{ __('messages.cart.item') }}</div>
                            <div class="col-span-2 text-center">{{ __('messages.product.weight') }}</div>
                            <div class="col-span-2 text-center">{{ __('messages.cart.quantity') }}</div>
                            <div class="col-span-2 text-right">{{ __('messages.cart.total') }}</div>
                        </div>

                        <div class="divide-y divide-outline-variant/10">
                            <template x-for="(item, idx) in cart" :key="item.variantId">
                                <div class="grid grid-cols-1 md:grid-cols-12 items-center py-6 gap-4">
                                    
                                    <!-- Image + Name -->
                                    <div class="col-span-6 flex items-center gap-4">
                                        <div class="w-16 h-20 bg-surface-container flex-shrink-0 border border-outline-variant/5">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover"/>
                                        </div>
                                        <div>
                                            <h4 class="font-headline font-bold text-sm">
                                                <a :href="'/productos/' + item.slug" class="hover:text-primary transition-colors" x-text="item.name"></a>
                                            </h4>
                                            <p class="text-xs text-outline mt-1" x-text="item.variantName"></p>
                                            <button type="button" @click="removeItem(item, idx)" 
                                                    class="text-[10px] font-label-caps text-error hover:underline mt-2 tracking-wider flex items-center gap-1 font-bold">
                                                <span class="material-symbols-outlined text-xs">delete</span>
                                                {{ app()->getLocale() == 'es' ? 'REMOVER' : 'REMOVE' }}
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Weight -->
                                    <div class="col-span-2 text-center text-xs text-on-surface-variant font-bold font-body">
                                        <span class="md:hidden text-outline uppercase mr-2 font-bold">{{ __('messages.product.weight') }}:</span>
                                        <span x-text="item.weight + 'g'"></span>
                                    </div>
                                    
                                    <!-- Quantity Adjustment -->
                                    <div class="col-span-2 flex justify-center items-center">
                                        <div class="flex items-center border border-outline-variant/30 px-3 py-1.5 gap-4 font-body select-none text-xs">
                                            <button type="button" @click="updateQty(item, item.qty - 1)" class="hover:text-primary font-bold">-</button>
                                            <span class="font-bold w-4 text-center" x-text="item.qty"></span>
                                            <button type="button" @click="updateQty(item, item.qty + 1)" class="hover:text-primary font-bold">+</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Item Total -->
                                    <div class="col-span-2 text-right text-xs font-bold text-secondary font-body">
                                        <span class="md:hidden text-outline uppercase mr-2 font-bold">{{ __('messages.cart.total') }}:</span>
                                        <span x-text="'S/ ' + (item.price * item.qty).toFixed(2)"></span>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-4 bg-[#161616] border border-outline-variant/10 p-6 space-y-6">
                    <h3 class="font-label-caps text-xs tracking-wider text-on-surface font-bold uppercase pb-3 border-b border-outline-variant/10">
                        {{ __('messages.checkout.order_summary') }}
                    </h3>
                    
                    <div class="space-y-4 text-xs font-body">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>{{ __('messages.cart.subtotal') }}</span>
                            <span class="font-bold text-on-surface" x-text="'S/ ' + subtotal.toFixed(2)"></span>
                        </div>
                        
                        <div class="flex justify-between text-on-surface-variant">
                            <span>{{ __('messages.cart.shipping') }}</span>
                            <template x-if="shipping > 0">
                                <span class="font-bold text-on-surface" x-text="'S/ ' + shipping.toFixed(2)"></span>
                            </template>
                            <template x-if="shipping === 0">
                                <span class="text-leaf-green font-bold uppercase tracking-wider">{{ __('messages.cart.free_shipping') }}</span>
                            </template>
                        </div>
                        
                        <!-- Shipping Threshold Bar -->
                        <div class="pt-2">
                            <template x-if="subtotal < 150">
                                <div class="space-y-2">
                                    <div class="h-1 bg-outline-variant/20 w-full overflow-hidden">
                                        <div class="h-full bg-primary" :style="'width: ' + Math.min((subtotal / 150) * 100, 100) + '%'"></div>
                                    </div>
                                    <p class="text-[10px] text-outline italic">
                                        {{ app()->getLocale() == 'es' ? 'Falta ' : 'Spend ' }} 
                                        <span class="font-bold text-primary" x-text="'S/ ' + (150 - subtotal).toFixed(2)"></span> 
                                        {{ app()->getLocale() == 'es' ? 'más para envío gratuito.' : 'more for free shipping.' }}
                                    </p>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between border-t border-outline-variant/10 pt-4 text-sm font-bold">
                            <span class="uppercase tracking-wider">{{ __('messages.cart.total') }}</span>
                            <span class="text-secondary" x-text="'S/ ' + total.toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('checkout.shipping') }}" 
                           class="w-full bg-primary text-on-primary py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 flex items-center justify-center gap-2 font-bold">
                            {{ __('messages.cart.checkout') }}
                        </a>
                    </div>
                </div>

            </div>
        </template>

        <!-- Cart Empty State -->
        <template x-if="cart.length === 0">
            <div class="text-center py-20 bg-[#161616] border border-outline-variant/10 max-w-xl mx-auto p-12 space-y-6">
                <span class="material-symbols-outlined text-outline text-5xl">shopping_bag</span>
                <h3 class="font-headline text-xl font-bold">{{ __('messages.cart.empty') }}</h3>
                <div class="pt-4">
                    <a href="{{ route('collections') }}" 
                       class="inline-block bg-primary text-on-primary px-10 py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold">
                        {{ __('messages.cart.continue_shopping') }}
                    </a>
                </div>
            </div>
        </template>

    </main>

@endsection
