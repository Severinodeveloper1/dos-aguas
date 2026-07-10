@extends('layouts.app')

@section('title', __('messages.collections.title') . ' | Dos Aguas')

@section('styles')
<style>
    .hero-pattern {
        background-image: radial-gradient(#eabcb8 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        opacity: 0.05;
    }
</style>
@endsection

@section('content')

    <!-- Catalog Header -->
    <header class="relative pt-24 pb-16 overflow-hidden border-b border-outline-variant/10">
        <div class="absolute inset-0 hero-pattern pointer-events-none"></div>
        <div class="max-w-container-max mx-auto px-margin-edge relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <span class="font-label-caps text-xs text-primary mb-4 block tracking-[0.2em] uppercase">
                        {{ app()->getLocale() == 'es' ? 'CATÁLOGO EXCLUSIVO' : 'EXCLUSIVE CATALOG' }}
                    </span>
                    <h1 class="font-headline text-4xl md:text-5xl font-bold leading-tight">
                        {{ __('messages.collections.title') }}
                    </h1>
                    <p class="font-body text-sm text-on-surface-variant leading-relaxed mt-4">
                        {{ __('messages.collections.subtitle') }}
                    </p>
                </div>
                <div class="flex items-center gap-4 border-l border-outline-variant/20 pl-8 h-fit">
                    <div class="text-right">
                        <p class="font-label-caps text-[10px] tracking-wider text-outline">{{ __('messages.collections.showing') }}</p>
                        <p class="font-headline text-2xl text-primary font-bold">
                            {{ $totalProducts }} <span class="text-xs font-body font-normal text-on-surface-variant uppercase">{{ __('messages.collections.varieties') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Catalog Section -->
    <section class="max-w-container-max mx-auto px-margin-edge py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Filters Sidebar -->
            <aside class="lg:col-span-3">
                <form id="filter-form" action="{{ route('collections') }}" method="GET" class="sticky top-28 space-y-10">
                    
                    <!-- Preserving search if exists -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}" />
                    @endif

                    <!-- Categories Filter -->
                    <div>
                        <h3 class="font-label-caps text-xs tracking-wider text-on-surface mb-6 flex items-center gap-2 font-bold uppercase">
                            <span class="w-1.5 h-1.5 bg-primary rotate-45"></span>
                            {{ __('messages.collections.categories') }}
                        </h3>
                        <div class="flex flex-col gap-3 text-sm font-body">
                            <a href="{{ route('collections', request()->only(['search', 'intensity', 'sort'])) }}" 
                               class="text-left py-1 transition-all duration-300 {{ !request('category') ? 'text-primary font-bold border-l-2 border-primary pl-3' : 'text-on-surface-variant hover:text-primary pl-0' }}">
                                {{ __('messages.collections.all') }}
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('collections', array_merge(request()->only(['search', 'intensity', 'sort']), ['category' => $cat->slug])) }}" 
                                   class="text-left py-1 transition-all duration-300 {{ request('category') === $cat->slug ? 'text-primary font-bold border-l-2 border-primary pl-3' : 'text-on-surface-variant hover:text-primary pl-0' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}" />
                        @endif
                    </div>

                    <!-- Cocoa Intensity Filter -->
                    <div>
                        <h3 class="font-label-caps text-xs tracking-wider text-on-surface mb-6 flex items-center gap-2 font-bold uppercase">
                            <span class="w-1.5 h-1.5 bg-primary rotate-45"></span>
                            {{ __('messages.collections.intensity') }}
                        </h3>
                        <div class="space-y-3 font-body text-sm text-on-surface-variant">
                            @php
                                $selectedIntensities = (array) request('intensity');
                            @endphp
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="intensity[]" value="low" onchange="this.form.submit()" 
                                       class="w-4 h-4 rounded-none bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary checked:border-primary"
                                       {{ in_array('low', $selectedIntensities) ? 'checked' : '' }} />
                                <span class="group-hover:text-primary transition-colors">40% - 55% ({{ app()->getLocale() == 'es' ? 'Suave' : 'Mild' }})</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="intensity[]" value="medium" onchange="this.form.submit()" 
                                       class="w-4 h-4 rounded-none bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary checked:border-primary"
                                       {{ in_array('medium', $selectedIntensities) ? 'checked' : '' }} />
                                <span class="group-hover:text-primary transition-colors">60% - 75% ({{ app()->getLocale() == 'es' ? 'Equilibrado' : 'Balanced' }})</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="intensity[]" value="high" onchange="this.form.submit()" 
                                       class="w-4 h-4 rounded-none bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary checked:border-primary"
                                       {{ in_array('high', $selectedIntensities) ? 'checked' : '' }} />
                                <span class="group-hover:text-primary transition-colors">80% - 100% ({{ app()->getLocale() == 'es' ? 'Intenso' : 'Intense' }})</span>
                            </label>
                        </div>
                    </div>

                    <!-- Sort Selection -->
                    <div>
                        <h3 class="font-label-caps text-xs tracking-wider text-on-surface mb-6 flex items-center gap-2 font-bold uppercase">
                            <span class="w-1.5 h-1.5 bg-primary rotate-45"></span>
                            {{ __('messages.collections.sort_by') }}
                        </h3>
                        <select name="sort" onchange="this.form.submit()" 
                                class="w-full bg-[#1c1b1b] border border-outline-variant/30 text-xs px-3 py-2 font-body focus:ring-0 focus:outline-none focus:border-primary text-on-surface">
                            <option value="" {{ !request('sort') ? 'selected' : '' }}>{{ __('messages.collections.relevance') }}</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>{{ __('messages.collections.price_low') }}</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>{{ __('messages.collections.price_high') }}</option>
                        </select>
                    </div>

                </form>
            </aside>

            <!-- Products Catalog Grid -->
            <main class="lg:col-span-9">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    @if($products->isNotEmpty())
                        @foreach($products as $product)
                            @php
                                $firstVariant = $product->variants->first();
                                $firstImage = is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null;
                            @endphp
                            <div class="group flex flex-col justify-between border border-outline-variant/5 bg-[#181818] p-4 hover:border-outline-variant/20 transition-all duration-300">
                                <div>
                                    <div class="aspect-[3/4] overflow-hidden bg-surface-container-lowest mb-6 relative">
                                        @if($firstImage)
                                            <img alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                                                 src="{{ str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage) }}"/>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-outline/30">
                                                <span class="material-symbols-outlined text-4xl">broken_image</span>
                                            </div>
                                        @endif
                                        <!-- Hover Action Overlay -->
                                        <div class="absolute inset-0 bg-[#131313]/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                                            <a href="{{ route('product.detail', $product->slug) }}" 
                                               class="w-11 h-11 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors duration-300 flex items-center justify-center font-bold"
                                               title="{{ __('messages.collections.view_details') }}">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            @if($firstVariant)
                                                <button type="button" 
                                                        onclick="addToCartFromGrid({{ $firstVariant->id }})"
                                                        class="w-11 h-11 bg-secondary text-on-secondary hover:bg-primary hover:text-on-primary transition-colors duration-300 flex items-center justify-center font-bold"
                                                        title="{{ __('messages.product.add_to_cart') }}">
                                                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="font-headline text-lg mb-1 hover:text-primary transition-colors duration-300">
                                        <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h4>
                                    
                                    @if($product->tasting_notes)
                                        <p class="text-xs text-on-surface-variant font-body line-clamp-2 mt-2 leading-relaxed italic">
                                            "{{ $product->tasting_notes }}"
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-6 flex justify-between items-center pt-4 border-t border-outline-variant/10">
                                    @if($firstVariant)
                                        <span class="text-secondary font-bold text-sm">S/ {{ number_format($firstVariant->price, 2) }}</span>
                                    @else
                                        <span class="text-outline text-xs">{{ __('messages.product.out_of_stock') }}</span>
                                    @endif
                                    
                                    <a href="{{ route('product.detail', $product->slug) }}" class="text-xs text-primary hover:text-secondary hover:underline transition-colors font-body flex items-center gap-1">
                                        {{ __('messages.collections.view_details') }}
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full py-20 text-center">
                            <span class="material-symbols-outlined text-outline text-5xl mb-4">sentiment_dissatisfied</span>
                            <p class="text-on-surface-variant text-sm font-body">{{ __('messages.collections.no_products') }}</p>
                        </div>
                    @endif
                </div>
            </main>

        </div>
    </section>

@endsection
