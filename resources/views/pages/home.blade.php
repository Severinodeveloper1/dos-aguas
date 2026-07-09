@extends('layouts.app')

@section('title', 'Dos Aguas | Esencia de Cacao')

@section('styles')
<style>
    .slider-container {
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .slider-container::-webkit-scrollbar {
        display: none;
    }
    .award-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .award-card:hover {
        transform: translateY(-6px);
        border-color: rgba(234, 188, 184, 0.3);
    }
    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    @media (max-width: 768px) {
        .parallax-bg {
            background-attachment: scroll;
        }
    }
</style>
@endsection

@section('content')

    <!-- Hero Section: Dynamic Banner Slider -->
    <section class="relative h-[90vh] min-h-[600px] w-full overflow-hidden bg-surface-container-lowest border-b border-outline-variant/10">
        <div class="slider-container flex h-full overflow-x-auto snap-x snap-mandatory" id="hero-slider">
            
            @if($banners->isNotEmpty())
                @foreach($banners as $banner)
                    <div class="flex-none w-full h-full snap-start relative flex items-center">
                        <div class="absolute inset-0 z-0">
                            <!-- Show image or fallback -->
                            <img alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-50" 
                                 src="{{ str_starts_with($banner->media_path, 'http') ? $banner->media_path : asset('storage/' . $banner->media_path) }}"/>
                            <div class="absolute inset-0 bg-gradient-to-r from-background via-background/40 to-transparent"></div>
                        </div>
                        <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-edge">
                            <div class="max-w-xl">
                                <span class="font-label-caps text-xs text-secondary mb-4 block tracking-[0.3em] uppercase">
                                    {{ $banner->subtitle }}
                                </span>
                                <h1 class="font-headline text-5xl md:text-7xl text-on-surface mb-6 leading-[1.1] font-bold">
                                    {{ $banner->title }}
                                </h1>
                                <a class="inline-block px-10 py-4 bg-primary text-on-primary font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300" 
                                   href="{{ $banner->button_url ?? route('collections') }}">
                                    {{ $banner->button_text ?? __('messages.home.explore_collection') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback Banner 1 -->
                <div class="flex-none w-full h-full snap-start relative flex items-center">
                    <div class="absolute inset-0 z-0">
                        <img alt="Dos Aguas Naranja" class="w-full h-full object-cover opacity-55" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4"/>
                        <div class="absolute inset-0 bg-gradient-to-r from-background via-background/40 to-transparent"></div>
                    </div>
                    <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-edge">
                        <div class="max-w-xl">
                            <span class="font-label-caps text-xs text-secondary mb-4 block tracking-[0.3em]">{{ __('messages.home.featured_origin') }}</span>
                            <h1 class="font-headline text-5xl md:text-7xl text-on-surface mb-6 leading-[1.1] font-bold">Naranja 70%</h1>
                            <p class="font-body text-base text-on-surface-variant mb-10 max-w-md">
                                {{ app()->getLocale() == 'es' ? 'Perfil cítrico balanceado con ralladura de naranja orgánica seleccionada y cacao nativo de Ucayali.' : 'Balanced citrus profile with hand-peeled organic orange zest and native Ucayali cacao.' }}
                            </p>
                            <a class="inline-block px-10 py-4 bg-primary text-on-primary font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300" href="{{ route('collections') }}">
                                {{ __('messages.home.explore_collection') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
        
        <!-- Slider Navigation Controls -->
        <div class="absolute bottom-12 right-margin-edge z-20 flex gap-4">
            <button class="w-12 h-12 border border-outline/20 flex items-center justify-center hover:bg-white/5 hover:border-primary/50 transition-colors" onclick="scrollSlider(-1)">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
            </button>
            <button class="w-12 h-12 border border-outline/20 flex items-center justify-center hover:bg-white/5 hover:border-primary/50 transition-colors" onclick="scrollSlider(1)">
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </button>
        </div>
    </section>

    <!-- Parallax Separator Quote -->
    <section class="relative h-[45vh] w-full overflow-hidden flex items-center justify-center">
        <div class="parallax-bg absolute inset-0 opacity-40" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GFdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac');"></div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-center px-4 max-w-2xl">
            <h2 class="font-headline text-2xl md:text-3xl italic text-on-surface mb-4">
                "{{ app()->getLocale() == 'es' ? 'La esencia de dos ríos convertida en cacao artesanal.' : 'The essence of two rivers crafted into single-origin chocolate.' }}"
            </h2>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>
    </section>

    <!-- Curated Shop / Featured Products Grid -->
    <section class="py-section-gap px-margin-edge max-w-container-max mx-auto" id="shop">
        <div class="flex justify-between items-end mb-16">
            <div>
                <span class="font-label-caps text-xs text-secondary mb-3 block tracking-[0.2em] uppercase">{{ __('messages.nav.collections') }}</span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold">{{ __('messages.collections.title') }}</h2>
            </div>
            <a class="font-label-caps text-xs text-primary border-b border-primary/20 pb-1 hover:border-primary transition-all duration-300 tracking-wider" href="{{ route('collections') }}">
                {{ app()->getLocale() == 'es' ? 'VER TODO EL CATÁLOGO' : 'VIEW FULL CATALOG' }}
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            @if($products->isNotEmpty())
                @foreach($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $firstImage = is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null;
                    @endphp
                    <div class="group flex flex-col justify-between">
                        <div>
                            <div class="aspect-[3/4] overflow-hidden bg-surface-container mb-6 relative border border-outline-variant/5">
                                @if($firstImage)
                                    <img alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                                         src="{{ str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage) }}"/>
                                @else
                                    <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline/30">
                                        <span class="material-symbols-outlined text-4xl">broken_image</span>
                                    </div>
                                @endif
                                <a href="{{ route('product.detail', $product->slug) }}" 
                                   class="absolute bottom-4 left-4 right-4 bg-primary text-on-primary text-center py-3 font-label-caps text-[10px] tracking-widest uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-secondary hover:text-on-secondary">
                                    {{ __('messages.collections.view_details') }}
                                </a>
                            </div>
                            <h4 class="font-headline text-lg mb-1 hover:text-primary transition-colors duration-300">
                                <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                            </h4>
                        </div>
                        <div>
                            @if($firstVariant)
                                <p class="text-secondary font-bold text-sm mt-1">S/ {{ number_format($firstVariant->price, 2) }}</p>
                            @else
                                <p class="text-outline text-xs mt-1">{{ __('messages.product.out_of_stock') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="col-span-full text-center text-on-surface-variant text-sm">{{ __('messages.collections.no_products') }}</p>
            @endif
        </div>
    </section>

    <!-- Detailed Craftsmanship Process -->
    <section class="py-section-gap px-margin-edge bg-surface-container-lowest border-y border-outline-variant/10">
        <div class="max-w-container-max mx-auto grid grid-cols-1 md:grid-cols-12 gap-gutter items-stretch">
            
            <div class="md:col-span-7 h-[400px] md:h-[500px] overflow-hidden group border border-outline-variant/5">
                <img alt="Craftsmanship Process" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4"/>
            </div>
            
            <div class="md:col-span-5 flex flex-col justify-center p-8 md:p-12 bg-surface-container">
                <span class="font-label-caps text-xs text-secondary mb-6 block tracking-widest">{{ app()->getLocale() == 'es' ? 'NUESTRO PROCESO' : 'OUR PROCESS' }}</span>
                <h3 class="font-headline text-3xl mb-6 font-bold">{{ app()->getLocale() == 'es' ? 'Molienda Lenta en Piedra' : 'Slow-Stone Grinding' }}</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                    {{ app()->getLocale() == 'es' ? 'Nos tomamos más de 72 horas para refinar nuestro cacao en molinos de piedra tradicionales. Este proceso paciente y a baja temperatura preserva los delicados aromas florales y frutales que definen al cacao nativo.' : 'We take over 72 hours to refine our cocoa in traditional stone mills. This patient, low-temperature process preserves the delicate floral and fruity aromas that define our native cocoa.' }}
                </p>
            </div>
            
        </div>
    </section>

    <!-- Awards & Recognitions Panel -->
    @if($awards->isNotEmpty())
        <section class="py-section-gap px-margin-edge max-w-container-max mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <span class="font-label-caps text-xs text-secondary mb-4 block tracking-widest">{{ app()->getLocale() == 'es' ? 'EXCELENCIA' : 'EXCELLENCE' }}</span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold">{{ __('messages.home.awards_title') }}</h2>
                </div>
                <p class="max-w-md text-on-surface-variant font-body text-sm leading-relaxed">
                    {{ app()->getLocale() == 'es' ? 'Galardonados internacionalmente por nuestro firme compromiso con el comercio justo, la calidad del cacao y la pureza en taza.' : 'Internationally recognized for our solid commitment to fair trade, cacao quality, and flavor purity.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($awards as $award)
                    <div class="award-card bg-surface-container border border-outline-variant/10 p-8 flex flex-col items-center text-center">
                        @if($award->medal_image)
                            <img alt="Medal" class="h-24 mb-8 grayscale hover:grayscale-0 transition-all duration-500" 
                                 src="{{ str_starts_with($award->medal_image, 'http') ? $award->medal_image : asset('storage/' . $award->medal_image) }}"/>
                        @else
                            <span class="material-symbols-outlined text-[64px] text-primary mb-8">workspace_premium</span>
                        @endif
                        <h4 class="font-headline text-xl mb-2 font-bold">{{ $award->title }}</h4>
                        <p class="font-label-caps text-xs text-primary mb-4 tracking-wider font-bold">{{ $award->description }}</p>
                        <div class="h-px w-8 bg-outline/20 mb-4"></div>
                        <p class="text-xs text-on-surface-variant">
                            {{ $award->country }} @if($award->date) • {{ $award->date->format('Y') }} @endif
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection

@section('scripts')
<script>
    // Slider Navigation scroll action
    function scrollSlider(direction) {
        const slider = document.getElementById('hero-slider');
        if (!slider) return;
        const scrollAmount = slider.offsetWidth;
        slider.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
</script>
@endsection
