@extends('layouts.app')

@section('title', $product->name . ' | Dos Aguas')

@section('styles')
<style>
    .accordion-header::after {
        content: '▼';
        font-size: 8px;
        float: right;
        transition: transform 0.3s;
    }
    .accordion-header.active::after {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">
        
        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('collections') }}">{{ __('messages.nav.collections') }}</a>
            <span class="text-[8px] opacity-40">/</span>
            @if($product->category)
                <a class="hover:text-primary transition-colors duration-300" href="{{ route('collections', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                <span class="text-[8px] opacity-40">/</span>
            @endif
            <span class="text-on-surface font-bold">{{ $product->name }}</span>
        </nav>

        <!-- Product Hero Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start"
             x-data="{ 
                variants: @js($product->variants), 
                selectedId: {{ $product->variants->first()?->id ?? 'null' }},
                selectedPrice: '{{ number_format($product->variants->first()?->price ?? 0, 2) }}',
                selectedStock: {{ $product->variants->first()?->stock ?? 0 }},
                selectedSku: '{{ $product->variants->first()?->sku ?? '' }}',
                qty: 1,
                selectedImage: '{{ is_array($product->images) && count($product->images) > 0 ? (str_starts_with($product->images[0], 'http') ? $product->images[0] : asset('storage/' . $product->images[0])) : '' }}',
                loading: false,
                message: '',
                messageType: 'success',

                selectVariant(variant) {
                    this.selectedId = variant.id;
                    this.selectedPrice = parseFloat(variant.price).toFixed(2);
                    this.selectedStock = parseInt(variant.stock);
                    this.selectedSku = variant.sku;
                    this.qty = 1;
                },

                addToCart() {
                    if (this.selectedId === null) return;
                    this.loading = true;
                    this.message = '';

                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_variant_id: this.selectedId,
                            quantity: this.qty
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.message = '{{ app()->getLocale() == 'es' ? '¡Producto agregado al carrito!' : 'Product added to cart!' }}';
                            this.messageType = 'success';
                            // Dynamic update of cart count in header
                            const badge = document.getElementById('cart-badge-count');
                            if (badge) {
                                badge.innerText = data.cartCount;
                                if (data.cartCount > 0) {
                                    badge.classList.remove('hidden');
                                } else {
                                    badge.classList.add('hidden');
                                }
                            }
                        } else {
                            this.message = data.message;
                            this.messageType = 'error';
                        }
                    })
                    .catch(error => {
                        this.loading = false;
                        this.message = 'Error al agregar al carrito.';
                        this.messageType = 'error';
                    });
                }
             }">
             
            <!-- Left: Product Image Gallery -->
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="relative aspect-[4/5] bg-surface-container overflow-hidden border border-outline-variant/10">
                    <template x-if="selectedImage">
                        <img class="w-full h-full object-cover transition-transform duration-700 hover:scale-102" :src="selectedImage" :alt="'{{ $product->name }}'" />
                    </template>
                    <template x-if="!selectedImage">
                        <div class="w-full h-full flex items-center justify-center text-outline/30">
                            <span class="material-symbols-outlined text-6xl">broken_image</span>
                        </div>
                    </template>
                </div>
                
                <!-- Gallery Thumbnails -->
                @if(is_array($product->images) && count($product->images) > 1)
                    <div class="grid grid-cols-5 gap-4">
                        @foreach($product->images as $img)
                            @php
                                $thumbUrl = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                            @endphp
                            <div class="aspect-square bg-surface-container border border-outline-variant/10 hover:border-primary/50 transition-colors cursor-pointer"
                                 @click="selectedImage = '{{ $thumbUrl }}'">
                                <img class="w-full h-full object-cover" src="{{ $thumbUrl }}" alt="Product thumbnail"/>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Information & Cart Flow -->
            <div class="lg:col-span-5 lg:pl-8 flex flex-col gap-8">
                
                <div class="space-y-4">
                    @if($product->category)
                        <span class="font-label-caps text-xs text-secondary tracking-[0.2em] uppercase font-bold">{{ $product->category->name }}</span>
                    @endif
                    <h1 class="font-headline text-3xl md:text-4xl font-bold leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-baseline gap-4 pt-2">
                        <p class="font-headline text-2xl text-primary font-bold">S/ <span x-text="selectedPrice"></span></p>
                        <span class="text-[10px] text-outline font-label-caps" x-text="'SKU: ' + selectedSku"></span>
                    </div>
                </div>

                <div class="font-body text-sm text-on-surface-variant leading-relaxed">
                    {!! $product->description !!}
                </div>

                <!-- Dynamic Variant Selector (Dropdown Select) -->
                <div class="flex flex-col gap-2">
                    <label for="variant-select" class="font-label-caps text-[10px] tracking-widest text-outline uppercase font-bold">
                        {{ app()->getLocale() == 'es' ? 'Presentación' : 'Presentation' }}
                    </label>
                    <select id="variant-select"
                            x-model="selectedId"
                            @change="const v = variants.find(item => item.id == selectedId); if(v) selectVariant(v)"
                            class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3.5 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs font-bold font-body cursor-pointer w-full max-w-md">
                        @foreach($product->variants as $v)
                            <option value="{{ $v->id }}">
                                {{ $v->name }} ({{ number_format($v->weight, 0) }}g) - S/ {{ number_format($v->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock status -->
                <div class="border-t border-b border-outline-variant/10 py-4 flex justify-between items-center text-xs font-body">
                    <span class="text-outline uppercase tracking-wider font-bold">{{ __('messages.product.stock') }}</span>
                    <div>
                        <template x-if="selectedStock > 0">
                            <span class="text-leaf-green font-bold flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-leaf-green rounded-full animate-pulse"></span>
                                {{ __('messages.product.available') }}
                            </span>
                        </template>
                        <template x-if="selectedStock <= 0">
                            <span class="text-error font-bold flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-error rounded-full"></span>
                                {{ __('messages.product.out_of_stock') }}
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Add to Cart forms -->
                <div class="flex flex-col gap-4">
                    
                    <!-- Alert Notification Box -->
                    <div x-show="message" x-transition class="p-3 text-xs font-body font-bold border" 
                         :class="messageType === 'success' ? 'bg-leaf-green/10 border-leaf-green/30 text-leaf-green' : 'bg-error/10 border-error/30 text-error'">
                        <span x-text="message"></span>
                    </div>

                    <div class="flex items-center h-14 w-full gap-4">
                        <!-- Qty adjustment -->
                        <div class="flex border border-outline-variant/30 h-full items-center px-4 gap-6 font-body select-none">
                            <button type="button" class="hover:text-primary font-bold p-1" @click="if(qty > 1) qty--">-</button>
                            <span class="font-bold text-center w-6 text-sm" x-text="qty"></span>
                            <button type="button" class="hover:text-primary font-bold p-1" @click="if(qty < selectedStock) qty++">+</button>
                        </div>
                        
                        <!-- Add to Cart CTA -->
                        <button type="button" 
                                @click="addToCart()"
                                :disabled="selectedStock <= 0 || loading"
                                class="flex-grow bg-primary text-on-primary h-full font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary disabled:bg-surface-container-high disabled:text-outline/40 disabled:cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-2 font-bold">
                            <span x-show="!loading" class="material-symbols-outlined text-[18px]">shopping_bag</span>
                            <span x-show="loading" class="w-4 h-4 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="loading ? '...' : '{{ __('messages.product.add_to_cart') }}'"></span>
                        </button>
                    </div>
                </div>

                <!-- Accordion details (Tasting notes, Process, Origins) -->
                <div class="mt-8 space-y-4 border-t border-outline-variant/10 pt-8" x-data="{ activeTab: null }">
                    
                    <!-- Tasting Notes -->
                    @if($product->tasting_notes)
                        <div class="border-b border-outline-variant/10 pb-4">
                            <button @click="activeTab = activeTab === 'notes' ? null : 'notes'" 
                                    class="w-full text-left font-label-caps text-xs tracking-wider text-on-surface flex justify-between items-center py-2 font-bold hover:text-primary transition-colors">
                                {{ __('messages.product.tasting_notes') }}
                                <span class="material-symbols-outlined text-[16px] transition-transform" :class="activeTab === 'notes' ? 'rotate-180 text-primary' : ''">expand_more</span>
                            </button>
                            <div x-show="activeTab === 'notes'" x-collapse x-cloak class="mt-3 text-xs text-on-surface-variant font-body leading-relaxed pl-2 italic">
                                {!! $product->tasting_notes !!}
                            </div>
                        </div>
                    @endif

                    <!-- Natural Benefits -->
                    @if($product->natural_benefits)
                        <div class="border-b border-outline-variant/10 pb-4">
                            <button @click="activeTab = activeTab === 'benefits' ? null : 'benefits'" 
                                    class="w-full text-left font-label-caps text-xs tracking-wider text-on-surface flex justify-between items-center py-2 font-bold hover:text-primary transition-colors">
                                {{ __('messages.product.benefits') }}
                                <span class="material-symbols-outlined text-[16px] transition-transform" :class="activeTab === 'benefits' ? 'rotate-180 text-primary' : ''">expand_more</span>
                            </button>
                            <div x-show="activeTab === 'benefits'" x-collapse x-cloak class="mt-3 text-xs text-on-surface-variant font-body leading-relaxed pl-2">
                                {!! $product->natural_benefits !!}
                            </div>
                        </div>
                    @endif

                    <!-- Nutritional Values -->
                    @if(is_array($product->nutritional_values) && count($product->nutritional_values) > 0)
                        <div class="border-b border-outline-variant/10 pb-4">
                            <button @click="activeTab = activeTab === 'nutri' ? null : 'nutri'" 
                                    class="w-full text-left font-label-caps text-xs tracking-wider text-on-surface flex justify-between items-center py-2 font-bold hover:text-primary transition-colors">
                                {{ __('messages.product.nutritional') }}
                                <span class="material-symbols-outlined text-[16px] transition-transform" :class="activeTab === 'nutri' ? 'rotate-180 text-primary' : ''">expand_more</span>
                            </button>
                            <div x-show="activeTab === 'nutri'" x-collapse x-cloak class="mt-3 text-xs text-on-surface-variant font-body pl-2">
                                <table class="w-full border-collapse">
                                    <tbody>
                                        @foreach($product->nutritional_values as $item)
                                            <tr class="border-b border-outline-variant/5">
                                                <td class="py-2 text-outline uppercase font-bold text-[10px] tracking-wider">
                                                    {{ is_array($item) ? ($item['label'] ?? '') : '' }}
                                                </td>
                                                <td class="py-2 text-right text-on-surface font-bold">
                                                    {{ is_array($item) ? ($item['value'] ?? '') : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <section class="mt-32 pt-16 border-t border-outline-variant/10">
                <h3 class="font-headline text-2xl font-bold mb-12 text-center">{{ app()->getLocale() == 'es' ? 'Te Podría Interesar' : 'You May Also Like' }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $rel)
                        @php
                            $relVariant = $rel->variants->first();
                            $relImage = is_array($rel->images) && count($rel->images) > 0 ? $rel->images[0] : null;
                        @endphp
                        <div class="group flex flex-col justify-between bg-surface-container border border-outline-variant/5 p-4 hover:border-outline-variant/20 transition-all duration-300">
                            <div>
                                <div class="aspect-[3/4] overflow-hidden bg-surface-container-lowest mb-4 relative">
                                    @if($relImage)
                                        <img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500" 
                                             src="{{ str_starts_with($relImage, 'http') ? $relImage : asset('storage/' . $relImage) }}" alt="{{ $rel->name }}"/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-outline/30">
                                            <span class="material-symbols-outlined text-2xl">broken_image</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('product.detail', $rel->slug) }}" 
                                       class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></a>
                                </div>
                                <h4 class="font-headline text-base mb-1 font-bold">
                                    <a href="{{ route('product.detail', $rel->slug) }}">{{ $rel->name }}</a>
                                </h4>
                            </div>
                            <div class="mt-4 flex justify-between items-center pt-2 border-t border-outline-variant/10 text-xs">
                                @if($relVariant)
                                    <span class="text-secondary font-bold">S/ {{ number_format($relVariant->price, 2) }}</span>
                                @else
                                    <span class="text-outline">{{ __('messages.product.out_of_stock') }}</span>
                                @endif
                                <a href="{{ route('product.detail', $rel->slug) }}" class="text-primary font-bold hover:underline">
                                    {{ __('messages.collections.view_details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

@endsection
