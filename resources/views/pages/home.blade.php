@extends('layouts.app')

@section('title', 'Dos Aguas')

@section('styles')
    <style>
        /* Continuous Marquee Scrolling Animations */
        @keyframes marquee-ltr {
            0% {
                transform: translateX(-50%);
            }

            100% {
                transform: translateX(0);
            }
        }

        @keyframes marquee-rtl {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-ltr-container {
            display: flex;
            width: max-content;
            animation: marquee-ltr 30s linear infinite;
        }

        .marquee-rtl-container {
            display: flex;
            width: max-content;
            animation: marquee-rtl 30s linear infinite;
        }

        .marquee-ltr-container:hover,
        .marquee-rtl-container:hover {
            animation-play-state: paused;
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

    <!-- Hero Section: Dynamic Auto-sliding Banner Slider -->
    @php
        $bannerList = $banners->isNotEmpty()
            ? $banners
            : collect([
                (object) [
                    'title' => 'Naranja 70%',
                    'subtitle' => __('messages.home.featured_origin'),
                    'media_path' =>
                        'https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4',
                    'button_text' => __('messages.home.explore_collection'),
                    'button_url' => '/colecciones',
                ],
            ]);
    @endphp

    <section
        class="relative h-[90vh] min-h-[600px] w-full overflow-hidden bg-surface-container-lowest border-b border-outline-variant/10"
        x-data="{
            activeSlide: 0,
            slideCount: {{ $bannerList->count() }},
            timer: null,
            init() {
                this.playActiveSlide();
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slideCount;
                this.playActiveSlide();
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slideCount) % this.slideCount;
                this.playActiveSlide();
            },
            playActiveSlide() {
                if (this.timer) {
                    clearTimeout(this.timer);
                    this.timer = null;
                }
                this.$nextTick(() => {
                    const activeSlideEl = this.$el.querySelector('[data-slide-index=\'' + this.activeSlide + '\']');
                    if (!activeSlideEl) return;

                    const video = activeSlideEl.querySelector('video');
                    if (video) {
                        video.currentTime = 0;
                        video.onended = () => {
                            this.next();
                        };
                        video.play().catch(err => {
                            console.warn('Video playback was prevented or failed:', err);
                            // Fallback if autoplay gets blocked by browser policies
                            this.timer = setTimeout(() => { this.next() }, 6000);
                        });
                    } else {
                        this.timer = setTimeout(() => { this.next() }, 5500);
                    }
                });
            }
        }">

        <!-- Slides -->
        <div class="relative w-full h-full">
            @foreach ($bannerList as $idx => $banner)
                @php
                    $hasText = !empty($banner->title) || !empty($banner->subtitle);
                    $hasButton = !empty($banner->button_text) && !empty($banner->button_url);

                    // Determine Desktop Media
                    $mediaUrl = str_starts_with($banner->media_path, 'http')
                        ? $banner->media_path
                        : asset('storage/' . $banner->media_path);
                    $parsedPath = parse_url($mediaUrl, PHP_URL_PATH);
                    $extension = strtolower(pathinfo($parsedPath, PATHINFO_EXTENSION));
                    $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);

                    // Determine Mobile Media
                    $mobileMediaUrl = null;
                    $isMobileVideo = false;
                    if (!empty($banner->mobile_media_path)) {
                        $mobileMediaUrl = str_starts_with($banner->mobile_media_path, 'http')
                            ? $banner->mobile_media_path
                            : asset('storage/' . $banner->mobile_media_path);
                        $parsedMobilePath = parse_url($mobileMediaUrl, PHP_URL_PATH);
                        $mobileExtension = strtolower(pathinfo($parsedMobilePath, PATHINFO_EXTENSION));
                        $isMobileVideo = in_array($mobileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
                    } else {
                        // Fallback to desktop media
                        $mobileMediaUrl = $mediaUrl;
                        $isMobileVideo = $isVideo;
                    }
                @endphp
                <div x-show="activeSlide === {{ $idx }}" data-slide-index="{{ $idx }}"
                    x-transition:enter="transition opacity-100 ease-out duration-1000" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition opacity-0 ease-in duration-1000"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="absolute inset-0 w-full h-full flex items-center" x-cloak>

                    <div class="absolute inset-0 z-0">
                        <!-- Desktop Media (Hidden on Mobile) -->
                        <div class="hidden md:block w-full h-full">
                            @if ($isVideo)
                                <video class="w-full h-full object-cover opacity-55" src="{{ $mediaUrl }}" autoplay muted
                                    playsinline @if ($bannerList->count() === 1) loop @endif></video>
                            @else
                                <img alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-full object-cover opacity-55"
                                    src="{{ $mediaUrl }}" />
                            @endif
                        </div>

                        <!-- Mobile Media (Hidden on Desktop) -->
                        <div class="block md:hidden w-full h-full">
                            @if ($isMobileVideo)
                                <video class="w-full h-full object-cover opacity-55" src="{{ $mobileMediaUrl }}" autoplay muted
                                    playsinline @if ($bannerList->count() === 1) loop @endif></video>
                            @else
                                <img alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-full object-cover opacity-55"
                                    src="{{ $mobileMediaUrl }}" />
                            @endif
                        </div>

                        <!-- Shading Gradient overlay only if there is text -->
                        @if ($hasText)
                            <div class="absolute inset-0 bg-gradient-to-r from-background via-background/40 to-transparent">
                            </div>
                        @endif
                    </div>

                    @if ($hasText || $hasButton)
                        <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-edge">
                            <div class="max-w-xl space-y-6">
                                @if (!empty($banner->subtitle))
                                    <span
                                        class="font-label-caps text-xs text-secondary tracking-[0.3em] uppercase block font-bold">
                                        {{ $banner->subtitle }}
                                    </span>
                                @endif
                                @if (!empty($banner->title))
                                    <h1 class="font-headline text-5xl md:text-7xl text-on-surface leading-[1.1] font-bold">
                                        {{ $banner->title }}
                                    </h1>
                                @endif
                                @if ($hasButton)
                                    <div class="pt-4">
                                        <a class="inline-block px-10 py-4 bg-primary text-on-primary font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold"
                                            href="{{ $banner->button_url }}">
                                            {{ $banner->button_text }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        <!-- Slider Navigation Controls (shown only if more than 1 slide) -->
        @if ($bannerList->count() > 1)
            <div class="absolute bottom-12 right-margin-edge z-20 flex gap-4">
                <button
                    class="w-12 h-12 border border-outline/20 flex items-center justify-center hover:bg-white/5 hover:border-primary/50 transition-colors"
                    @click="prev()">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                <button
                    class="w-12 h-12 border border-outline/20 flex items-center justify-center hover:bg-white/5 hover:border-primary/50 transition-colors"
                    @click="next()">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
            </div>
        @endif

    </section>

    <!-- Parallax Separator Quote -->
    <section class="relative h-[45vh] w-full overflow-hidden flex items-center justify-center">
        <div class="parallax-bg absolute inset-0 opacity-40"
            style="background-image: url('{{ asset('img/DOS_AGUAS_VISTA_PANORAMICA.jpg') }}'); background-attachment: fixed;">
        </div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-center px-4 max-w-2xl">
            <h2 class="font-headline text-2xl md:text-3xl italic text-on-surface mb-4">
                "{{ __('messages.home.parallax_quote') }}"
            </h2>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>
    </section>

    <!-- Curated Shop / Featured Products Grid -->
    <section class="py-section-gap px-margin-edge max-w-container-max mx-auto" id="shop">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6 mb-16">
            <div>
                <span
                    class="font-label-caps text-xs text-secondary mb-3 block tracking-[0.2em] uppercase">{{ __('messages.nav.collections') }}</span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold">{{ __('messages.collections.title') }}</h2>
            </div>
            <a class="font-label-caps text-xs text-primary border-b border-primary/20 pb-1 hover:border-primary transition-all duration-300 tracking-wider font-bold w-fit"
                href="{{ route('collections') }}">
                {{ app()->getLocale() == 'es' ? 'VER TODO EL CATÁLOGO' : (app()->getLocale() == 'de' ? 'GESAMTEN KATALOG ANSEHEN' : 'VIEW FULL CATALOG') }}
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            @if ($products->isNotEmpty())
                @foreach ($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $firstImage =
                            is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null;
                    @endphp
                    <div class="group flex flex-col justify-between">
                        <div>
                            <div
                                class="aspect-[3/4] overflow-hidden bg-surface-container mb-6 relative border border-outline-variant/5">
                                @if ($firstImage)
                                    <img alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                        src="{{ str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage) }}" />
                                @else
                                    <div
                                        class="w-full h-full bg-surface-container flex items-center justify-center text-outline/30">
                                        <span class="material-symbols-outlined text-4xl">broken_image</span>
                                    </div>
                                @endif

                                <!-- Dual Action Overlay -->
                                <div
                                    class="absolute inset-0 bg-[#131313]/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                                    <a href="{{ route('product.detail', $product->slug) }}"
                                        class="w-11 h-11 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors duration-300 flex items-center justify-center font-bold"
                                        title="{{ __('messages.collections.view_details') }}">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    @if ($firstVariant)
                                        <button type="button" onclick="addToCartFromGrid({{ $firstVariant->id }})"
                                            class="w-11 h-11 bg-secondary text-on-secondary hover:bg-primary hover:text-on-primary transition-colors duration-300 flex items-center justify-center font-bold"
                                            title="{{ __('messages.product.add_to_cart') }}">
                                            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <h4
                                class="font-headline text-lg mb-1 hover:text-primary transition-colors duration-300 font-bold">
                                <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                            </h4>
                        </div>
                        <div>
                            @if ($firstVariant)
                                <p class="text-secondary font-bold text-sm mt-1">S/
                                    {{ number_format($firstVariant->price, 2) }}</p>
                            @else
                                <p class="text-outline text-xs mt-1">{{ __('messages.product.out_of_stock') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="col-span-full text-center text-on-surface-variant text-sm">
                    {{ __('messages.collections.no_products') }}</p>
            @endif
        </div>
    </section>

    <!-- Detailed Craftsmanship Process -->
    <section class="py-section-gap px-margin-edge bg-surface-container-lowest border-y border-outline-variant/10">
        <div class="max-w-container-max mx-auto grid grid-cols-1 md:grid-cols-12 gap-gutter items-stretch">

            <div class="md:col-span-7 h-[600px] md:h-[650px] overflow-hidden group border border-outline-variant/5">
                <img alt="Craftsmanship Process"
                    class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700"
                    src="{{ asset('img/proceso.jpeg') }}" />
            </div>

            <div class="md:col-span-5 flex flex-col justify-center p-8 md:p-12 bg-surface-container">
                <span
                    class="font-label-caps text-xs text-secondary mb-6 block tracking-widest font-bold">{{ app()->getLocale() == 'es' ? 'NUESTRO PROCESO' : 'OUR PROCESS' }}</span>
                <h3 class="font-headline text-3xl mb-6 font-bold">
                    {{ app()->getLocale() == 'es' ? 'Del origen a la excelencia' : 'From origin to excellence' }}</h3>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                    {{ app()->getLocale() == 'es'
                        ? 'En Dos Aguas, cada producto nace de un proceso cuidadosamente controlado, desde la selección del cacao en nuestra hacienda de Curimaná, Ucayali,
                                                                                                                                                                                                                                                                                        hasta su transformación en nuestra planta de Lima. Aplicamos altos estándares de calidad en cada etapa para preservar el origen, los aromas y la excelencia que distinguen a nuestros chocolates, derivados del cacao y frutas liofilizadas.'
                        : 'At Dos Aguas, every product is born from a carefully controlled process, from the selection of the cacao at our farm in Curimaná, Ucayali, to its transformation at our plant in Lima. We apply high quality standards at every stage to preserve the origin, aromas,
                                                                                                                                                                                                                                                                                        and excellence that distinguish our chocolates, cacao derivatives, and freeze-dried fruits.' }}
                </p>
                <a href="{{ route('origin') }}"
                    class="inline-flex items-center gap-2 mt-8 px-6 py-3 border border-primary text-primary font-label-caps text-[10px] tracking-widest hover:bg-primary hover:text-on-primary transition-all duration-300 w-fit">
                    {{ app()->getLocale() == 'es' ? 'CONOCER NUESTRO PROCESO' : (app()->getLocale() == 'de' ? 'UNSEREN PROZESS ENTDECKEN' : 'DISCOVER OUR PROCESS') }}
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

        </div>
    </section>

    <!-- Awards & Recognitions Panel -->
    @if ($awards->isNotEmpty())
        <section class="py-section-gap px-margin-edge max-w-container-max mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <span
                        class="font-label-caps text-xs text-secondary mb-4 block tracking-widest font-bold">{{ app()->getLocale() == 'es' ? 'EXCELENCIA' : 'EXCELLENCE' }}</span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold">{{ __('messages.home.awards_title') }}</h2>
                </div>
                <p class="max-w-md text-on-surface-variant font-body text-sm leading-relaxed">
                    {{ app()->getLocale() == 'es' ? 'Galardonados internacionalmente por nuestro firme compromiso con el comercio justo, la calidad del cacao y la pureza en taza.' : 'Internationally recognized for our solid commitment to fair trade, cacao quality, and flavor purity.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($awards as $award)
                    @php
                        $bgImage = $award->product_image
                            ? (str_starts_with($award->product_image, 'http')
                                ? $award->product_image
                                : asset('storage/' . $award->product_image))
                            : '';
                    @endphp
                    <div
                        class="relative group h-full min-h-[420px] bg-surface-container border border-outline-variant/10 p-8 flex flex-col items-center justify-between overflow-hidden text-center reveal">

                        <!-- Product Background Image -->
                        @if ($bgImage)
                            <div class="absolute inset-0 z-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                                style="background-image: url('{{ $bgImage }}')"></div>
                        @endif
                        <!-- Dark contrast overlay -->
                        <div
                            class="absolute inset-0 bg-[#131313]/85 group-hover:bg-[#131313]/90 transition-colors duration-500 z-0">
                        </div>

                        <!-- Content Wrapper -->
                        <div class="relative z-10 w-full h-full flex flex-col items-center justify-between py-4">
                            <!-- Medal Image -->
                            <div class="h-44 flex items-center justify-center">
                                @if ($award->medal_image)
                                    <img alt="Medalla"
                                        class="h-36 max-h-full object-contain filter drop-shadow-lg transition-transform duration-500 group-hover:scale-105"
                                        src="{{ str_starts_with($award->medal_image, 'http') ? $award->medal_image : asset('storage/' . $award->medal_image) }}" />
                                @else
                                    <span
                                        class="material-symbols-outlined text-[80px] text-primary">workspace_premium</span>
                                @endif
                            </div>

                            <!-- Text Details -->
                            <div class="space-y-2 mt-4">
                                <h4 class="font-headline text-lg font-bold text-on-surface line-clamp-2">
                                    {{ $award->title }}</h4>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    {{ $award->description }}</p>
                                <p
                                    class="font-label-caps text-[10px] text-primary tracking-widest font-bold uppercase mt-1">
                                    {{ $award->country }} @if ($award->date)
                                        • {{ $award->date->format('Y') }}
                                    @endif
                                </p>
                            </div>

                            <!-- Download Certificate Action -->
                            @if ($award->certificate_image)
                                <div class="pt-4 mt-2">
                                    <a href="{{ str_starts_with($award->certificate_image, 'http') ? $award->certificate_image : asset('storage/' . $award->certificate_image) }}"
                                        target="_blank" download
                                        class="inline-flex items-center gap-2 border border-primary/30 hover:border-primary px-6 py-2.5 text-[9px] font-label-caps tracking-widest text-primary hover:bg-primary hover:text-on-primary transition-all duration-300 font-bold uppercase">
                                        <span class="material-symbols-outlined text-xs">download</span>
                                        {{ app()->getLocale() == 'es' ? 'Descargar Certificado' : 'Download Certificate' }}
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Corporate Brand Services Section (Desarrollo y Producción para Marcas) -->
    <section
        class="py-20 md:py-28 px-margin-edge bg-surface-container-lowest border-t border-outline-variant/10 overflow-hidden relative">
        <!-- Accent decorative blur element for Liquid Glass feeling -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 rounded-full filter blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary/5 rounded-full filter blur-3xl pointer-events-none">
        </div>

        <div class="max-w-container-max mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center relative z-10"
            x-data="{ activeTab: 'services' }">

            <!-- Left Side: Copy and Interactive Tabs -->
            <div class="lg:col-span-7 space-y-8">
                <div class="space-y-4">
                    <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                        {{ __('messages.services.subtitle') }}
                    </span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold uppercase tracking-wider text-on-surface">
                        {{ __('messages.services.title') }}
                    </h2>
                    <div class="w-16 h-px bg-primary"></div>
                    <p class="font-body text-sm text-on-surface-variant leading-relaxed">
                        {{ __('messages.services.description') }}
                    </p>
                </div>

                <!-- Tab Navigation Buttons -->
                <div
                    class="flex flex-wrap gap-2 border-b border-outline-variant/10 pb-1 text-[10px] md:text-xs font-label-caps font-bold">
                    <button type="button" @click="activeTab = 'services'"
                        class="px-5 py-3 border-b-2 transition-all duration-300 tracking-wider uppercase cursor-pointer"
                        :class="activeTab === 'services' ? 'border-primary text-primary' :
                            'border-transparent text-outline hover:text-on-surface'">
                        {{ __('messages.services.services_title') }}
                    </button>
                    <button type="button" @click="activeTab = 'develop'"
                        class="px-5 py-3 border-b-2 transition-all duration-300 tracking-wider uppercase cursor-pointer"
                        :class="activeTab === 'develop' ? 'border-primary text-primary' :
                            'border-transparent text-outline hover:text-on-surface'">
                        {{ __('messages.services.can_develop_title') }}
                    </button>
                    <button type="button" @click="activeTab = 'why'"
                        class="px-5 py-3 border-b-2 transition-all duration-300 tracking-wider uppercase cursor-pointer"
                        :class="activeTab === 'why' ? 'border-primary text-primary' :
                            'border-transparent text-outline hover:text-on-surface'">
                        {{ __('messages.services.why_us_title') }}
                    </button>
                </div>

                <!-- Tab Content Panels -->
                <div class="relative min-h-[300px]">

                    <!-- Panel 1: Services -->
                    <div x-show="activeTab === 'services'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                        <ul
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 font-body text-xs text-on-surface-variant leading-relaxed">
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-primary text-lg flex-shrink-0 mt-0.5">design_services</span>
                                <span>{{ __('messages.services.item_dev') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-primary text-lg flex-shrink-0 mt-0.5">bookmark</span>
                                <span>{{ __('messages.services.item_brand') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-primary text-lg flex-shrink-0 mt-0.5">precision_manufacturing</span>
                                <span>{{ __('messages.services.item_maquila') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-primary text-lg flex-shrink-0 mt-0.5">deployed_code</span>
                                <span>{{ __('messages.services.item_design') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3 md:col-span-2">
                                <span
                                    class="material-symbols-outlined text-primary text-lg flex-shrink-0 mt-0.5">public</span>
                                <span>{{ __('messages.services.item_local_intl') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Panel 2: What We Can Develop -->
                    <div x-show="activeTab === 'develop'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4" x-cloak>
                        <ul class="grid grid-cols-2 md:grid-cols-3 gap-3 font-body text-xs text-on-surface-variant">
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_bars') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_cover') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_dragees') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_fruits') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_paste') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_butter') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_powder') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_nibs') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-3 hover:border-primary/40 transition-colors duration-300 flex items-center gap-2 col-span-2 md:col-span-3">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                <span>{{ __('messages.services.dev_custom') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Panel 3: Why Choose Us -->
                    <div x-show="activeTab === 'why'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4" x-cloak>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 font-body text-xs text-on-surface-variant">
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-leaf-green text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <span>{{ __('messages.services.why_origin') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-leaf-green text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <span>{{ __('messages.services.why_tailored') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-leaf-green text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <span>{{ __('messages.services.why_quality') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3">
                                <span
                                    class="material-symbols-outlined text-leaf-green text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <span>{{ __('messages.services.why_flexible') }}</span>
                            </li>
                            <li
                                class="bg-[#161616]/40 border border-outline-variant/10 p-4 hover:border-primary/40 transition-colors duration-300 flex items-start gap-3 md:col-span-2">
                                <span
                                    class="material-symbols-outlined text-leaf-green text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <span>{{ __('messages.services.why_support') }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Right Side: Service Product Image with glass effect border -->
            <div
                class="lg:col-span-5 relative group overflow-hidden border border-outline-variant/15 p-2 bg-[#161616]/40 backdrop-blur-sm rounded-sm">
                <div class="overflow-hidden aspect-[4/3] md:aspect-square relative">
                    <img src="{{ asset('img/chocolate_production_service.jpg') }}"
                        alt="Desarrollo y Producción para Marcas"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-103" />
                    <!-- Sub-border overlay matching Liquid Glass -->
                    <div class="absolute inset-0 border border-white/5 pointer-events-none"></div>
                </div>
            </div>

        </div>
    </section>

    <!-- Moving Infinite Ribbon Loop Gallery Section -->
    @php
        $photos = is_array($company->gallery_photos) ? $company->gallery_photos : [];
        if (empty($photos)) {
            $photos = [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4',
                'https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GFdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac',
                'https://lh3.googleusercontent.com/aida-public/AB6AXuCMjIjbQezVbdWabzkrGNdze2_zQ3umglIvC7b0TMaM1iSZ02hGTQBbifYtEpyexm1ZbshF9SFBd1rZcGqAPiBBdwLkNCtpAJIyyYuQDHPv-iPSe1kpEmcWO1di8i-RB6L1w0-3bgWo9CU8gzQ6pnSfeI3Ie8asUdcYvn5xRthpQy-bDcRNgpNRjj4MZ9msCnqG2GyGhTetslOby228jsTAJ3sV2lRFP_TOSPapFuhK8HQuhE4uoxk855njunPeTRkmJQLSjzgHf5Y',
                'https://lh3.googleusercontent.com/aida-public/AB6AXuApNlqM0SXA-48zWCr3bsWpwKeN2BBNJu0AqcNnPdvGLcc3ChzWDYPaDNujXAp4P2AxpSLUVoFI0_RXRlorHUtdobJr1arO-C9KIg9KW4OWp3Ky2V2GkNoE8LBaHSFPsUsyzmGSKg2f3WHtuITvHtTnEX0bNti0BGCB3ag-Eery0nUR1duAgpT2LHRZ_k76_BZirFokju5ptlsj40TXWgAp_32mH9O8F8nFiCeUsk_deUgsk5CcUO6NAsPeCrPiZWuooIDjO9hIFs8',
            ];
        }

        $photosResolved = array_map(function ($path) {
            return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
        }, $photos);

        $count = count($photosResolved);
        $galleryImagesRow1 = [];
        $galleryImagesRow2 = [];
        $galleryImagesRow3 = [];

        for ($i = 0; $i < $count; $i++) {
            if ($i % 3 === 0) {
                $galleryImagesRow1[] = $photosResolved[$i];
            } elseif ($i % 3 === 1) {
                $galleryImagesRow2[] = $photosResolved[$i];
            } else {
                $galleryImagesRow3[] = $photosResolved[$i];
            }
        }

        if (empty($galleryImagesRow1)) {
            $galleryImagesRow1 = $photosResolved;
        }
        if (empty($galleryImagesRow2)) {
            $galleryImagesRow2 = $photosResolved;
        }
        if (empty($galleryImagesRow3)) {
            $galleryImagesRow3 = $photosResolved;
        }
    @endphp

    <section class="py-20 bg-background overflow-hidden relative border-t border-outline-variant/10"
        x-data="{ activeImage: null }">
        <div class="max-w-3xl mx-auto text-center space-y-4 mb-16 px-margin-edge">
            <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                {{ app()->getLocale() == 'es' ? 'GALERÍA DE LA HACIENDA' : 'ESTATE GALLERY' }}
            </span>
            <h2 class="font-headline text-3xl font-bold uppercase tracking-wider">
                {{ app()->getLocale() == 'es' ? 'Momentos y Territorio' : 'Moments & Territory' }}
            </h2>
        </div>

        <!-- Marquee Rows -->
        <div class="space-y-6">
            <!-- Row 1: LTR -->
            <div class="overflow-hidden w-full flex">
                <div class="marquee-ltr-container flex gap-6">
                    @foreach ($galleryImagesRow1 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 1"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                    @foreach ($galleryImagesRow1 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 1 duplicate"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Row 2: RTL -->
            <div class="overflow-hidden w-full flex">
                <div class="marquee-rtl-container flex gap-6">
                    @foreach ($galleryImagesRow2 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 2"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                    @foreach ($galleryImagesRow2 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 2 duplicate"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Row 3: LTR -->
            <div class="overflow-hidden w-full flex">
                <div class="marquee-ltr-container flex gap-6">
                    @foreach ($galleryImagesRow3 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 3"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                    @foreach ($galleryImagesRow3 as $img)
                        <div class="w-72 aspect-[4/3] bg-surface-container border border-outline-variant/10 overflow-hidden cursor-pointer flex-shrink-0"
                            @click="activeImage = '{{ $img }}'">
                            <img src="{{ $img }}" alt="Gallery 3 duplicate"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lightbox Pop-up Modal Dialog -->
        <div x-show="activeImage" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm" x-cloak
            @click.away="activeImage = null" @keydown.escape.window="activeImage = null">
            <div class="relative bg-[#161616] border border-outline-variant/20 max-w-4xl w-full p-4 flex flex-col gap-4">
                <button
                    class="absolute -top-12 right-0 text-on-surface hover:text-primary flex items-center gap-2 font-label-caps text-xs tracking-wider"
                    @click="activeImage = null">
                    {{ app()->getLocale() == 'es' ? 'CERRAR' : 'CLOSE' }}
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
                <div class="aspect-[16/10] bg-black overflow-hidden border border-outline-variant/10">
                    <img :src="activeImage" class="w-full h-full object-contain" alt="Gallery Large View" />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        // Custom AOS-like scroll reveal
        function revealOnScroll() {
            const reveals = document.querySelectorAll(".reveal");
            const windowHeight = window.innerHeight;
            const revealThreshold = 100;

            reveals.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                if (elementTop < windowHeight - revealThreshold) {
                    element.classList.add("active");
                }
            });
        }

        window.addEventListener("scroll", revealOnScroll);
        document.addEventListener("DOMContentLoaded", () => {
            revealOnScroll();
        });
    </script>
@endsection
