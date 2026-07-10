<!DOCTYPE html>
<html class="dark" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dos Aguas | Esencia de Cacao')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ICON_ORIGINAL.png') }}" />

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS for interactive client components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }

        /* Custom scrollbar matching dark luxury theme */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0e0e0e;
        }

        ::-webkit-scrollbar-thumb {
            background: #2a2a2a;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #eabcb8;
        }

        /* Premium preloader styling */
        #preloader {
            transition: opacity 800ms cubic-bezier(0.4, 0, 0.2, 1), visibility 800ms ease;
        }
        .ripple-ring {
            position: absolute;
            left: 50%;
            top: 100%;
            width: 12px;
            height: 12px;
            margin-left: -6px;
            margin-top: -6px;
            border-radius: 50%;
            pointer-events: none;
            animation: ripple-expand 2.6s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
        }
        .ripple-ring.delay-1 {
            animation-delay: 400ms;
        }
        .ripple-ring.delay-2 {
            animation-delay: 800ms;
        }
        @keyframes ripple-expand {
            0% {
                transform: scale(1);
                opacity: 0;
            }
            10% {
                opacity: 0.45;
            }
            100% {
                transform: scale(38);
                opacity: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body
    class="bg-background text-on-surface font-body min-h-screen flex flex-col justify-between selection:bg-primary/30 selection:text-primary-fixed overflow-x-hidden">

    <!-- Cinematic Preloader Overlay -->
    <div id="preloader" class="fixed inset-0 z-[99999] bg-[#000000] flex items-center justify-center select-none pointer-events-auto">
        <!-- SVG ripple displacement filter -->
        <svg class="absolute w-0 h-0 overflow-hidden" aria-hidden="true">
            <filter id="ripple-filter">
                <feTurbulence type="fractalNoise" baseFrequency="0.025" numOctaves="3" result="wave" />
                <feDisplacementMap in="SourceGraphic" in2="wave" scale="0" xChannelSelector="R" yChannelSelector="G" id="displacement-map" />
            </filter>
        </svg>

        <div class="relative w-48 h-48 md:w-64 md:h-64 flex items-center justify-center">
            <!-- Expanding Water Ripple Rings (positioned at bottom center) -->
            <div class="ripple-ring border border-primary/20"></div>
            <div class="ripple-ring delay-1 border border-primary/10"></div>
            <div class="ripple-ring delay-2 border border-primary/5"></div>

            <!-- Grayscale Base Image -->
            <img src="{{ asset('img/ICON_MARRON.png') }}" 
                 alt="Logo Loader Base" 
                 class="absolute w-full h-full object-contain filter grayscale contrast-125 opacity-25" />

            <!-- Color Revealed Image (Reveal origin starts from bottom center: 50% 100%) -->
            <img src="{{ asset('img/ICON_MARRON.png') }}" 
                 alt="Logo Loader Reveal" 
                 id="preloader-logo-color"
                 style="clip-path: circle(0% at 50% 100%); filter: url(#ripple-filter);"
                 class="absolute w-full h-full object-contain filter transition-[filter] duration-700" />
        </div>
    </div>

    <!-- Sticky Nav -->
    <header
        class="sticky top-0 z-50 bg-[#131313]/90 backdrop-blur-md border-b border-outline-variant/10 py-5 transition-all duration-300"
        id="main-header">
        <div class="flex justify-between items-center w-full px-margin-edge max-w-container-max mx-auto">

            <!-- Left Navigation Menu -->
            <nav class="flex-1 hidden md:flex items-center gap-8">
                <a class="text-on-surface-variant font-label-caps text-xs tracking-widest hover:text-primary transition-colors duration-300"
                    href="{{ route('origin') }}">
                    {{ __('messages.nav.origin') }}
                </a>
                <a class="text-on-surface-variant font-label-caps text-xs tracking-widest hover:text-primary transition-colors duration-300"
                    href="{{ route('collections') }}">
                    {{ __('messages.nav.collections') }}
                </a>
                <a class="text-on-surface-variant font-label-caps text-xs tracking-widest hover:text-primary transition-colors duration-300"
                    href="{{ route('about') }}">
                    {{ __('messages.nav.story') }}
                </a>
                <a class="text-on-surface-variant font-label-caps text-xs tracking-widest hover:text-primary transition-colors duration-300"
                    href="{{ route('blog') }}">
                    {{ __('messages.nav.blog') }}
                </a>
            </nav>

            <!-- Brand Logo -->
            <a class="flex justify-center flex-shrink-0 items-center" href="{{ route('home') }}">
                @if ($company->logo_path)
                    <img src="{{ str_starts_with($company->logo_path, 'http') ? $company->logo_path : asset('storage/' . $company->logo_path) }}"
                        alt="{{ $company->name }}"
                        class="h-10 w-auto object-contain hover:opacity-90 transition-opacity duration-300" />
                @else
                    <span
                        class="font-headline text-2xl lg:text-3xl text-on-surface hover:text-primary transition-colors duration-300 tracking-tighter font-bold uppercase">
                        {{ $company->name }}
                    </span>
                @endif
            </a>

            <!-- Right Actions Bar -->
            <div class="flex-1 flex items-center justify-end gap-6">
                <!-- Search bar dropdown (minimalist) -->
                {{-- <form action="{{ route('collections') }}" method="GET"
                    class="hidden lg:flex items-center border-b border-outline-variant/20 py-1">
                    <input type="text" name="search" placeholder="{{ __('messages.nav.search_placeholder') }}"
                        class="bg-transparent border-none text-xs text-on-surface placeholder:text-outline/50 focus:ring-0 focus:outline-none w-28 transition-all duration-300 focus:w-40"
                        value="{{ request('search') }}" />
                    <button type="submit" class="hover:text-primary transition-colors duration-300">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </button>
                </form> --}}

                <!-- Cart Button -->
                <a class="hover:text-primary transition-colors duration-300 relative flex items-center"
                    href="{{ route('cart.index') }}">
                    <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                    <span id="cart-badge-count"
                        class="absolute -top-1.5 -right-1.5 bg-secondary text-on-secondary text-[9px] rounded-full w-4 h-4 flex items-center justify-center font-bold {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- Contact link -->
                <a class="hidden md:inline-block text-on-surface-variant font-label-caps text-xs tracking-widest hover:text-primary transition-colors duration-300"
                    href="{{ route('contact') }}">
                    {{ __('messages.nav.contact') }}
                </a>

                <!-- Language Switcher (ES | EN | DE) with Flags -->
                <div
                    class="hidden md:flex items-center gap-2.5 text-xs font-bold border-l border-outline-variant/20 pl-4 font-body">
                    <a href="{{ route('locale.switch', 'es') }}"
                        class="flex items-center gap-1 {{ app()->getLocale() == 'es' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors"
                        title="Español">
                        <span class="text-sm">🇪🇸</span>
                        {{-- <span>ES</span> --}}
                    </a>
                    <span class="text-outline-variant/30 text-[10px]">|</span>
                    <a href="{{ route('locale.switch', 'en') }}"
                        class="flex items-center gap-1 {{ app()->getLocale() == 'en' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors"
                        title="English">
                        <span class="text-sm">🇺🇸</span>
                        {{-- <span>EN</span> --}}
                    </a>
                    <span class="text-outline-variant/30 text-[10px]">|</span>
                    <a href="{{ route('locale.switch', 'de') }}"
                        class="flex items-center gap-1 {{ app()->getLocale() == 'de' ? 'text-primary' : 'text-on-surface-variant hover:text-primary' }} transition-colors"
                        title="Deutsch">
                        <span class="text-sm">🇩🇪</span>
                        {{-- <span>DE</span> --}}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden hover:text-primary"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div class="hidden md:hidden bg-[#131313] border-b border-outline-variant/10 px-8 py-6 space-y-4"
            id="mobile-menu">
            <nav class="flex flex-col gap-4">
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('origin') }}">{{ __('messages.nav.origin') }}</a>
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('collections') }}">{{ __('messages.nav.collections') }}</a>
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('about') }}">{{ __('messages.nav.story') }}</a>
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('blog') }}">{{ __('messages.nav.blog') }}</a>
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                <a class="text-on-surface hover:text-primary transition-colors font-label-caps text-xs tracking-widest"
                    href="{{ route('claim-book') }}">{{ __('messages.nav.claim_book') }}</a>
            </nav>
            <form action="{{ route('collections') }}" method="GET"
                class="flex items-center border border-outline-variant/30 px-3 py-2 w-full">
                <input type="text" name="search" placeholder="{{ __('messages.nav.search_placeholder') }}"
                    class="bg-transparent border-none text-xs text-on-surface placeholder:text-outline/50 focus:ring-0 focus:outline-none w-full"
                    value="{{ request('search') }}" />
                <button type="submit" class="hover:text-primary">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </button>
            </form>

            <!-- Mobile Language Switcher -->
            <div class="flex gap-4 text-xs font-bold border-t border-outline-variant/10 pt-4 font-body">
                <a href="{{ route('locale.switch', 'es') }}"
                    class="flex items-center gap-1 {{ app()->getLocale() == 'es' ? 'text-primary' : 'text-on-surface-variant' }}">
                    ES
                </a>
                <a href="{{ route('locale.switch', 'en') }}"
                    class="flex items-center gap-1 {{ app()->getLocale() == 'en' ? 'text-primary' : 'text-on-surface-variant' }}">
                    EN
                </a>
                <a href="{{ route('locale.switch', 'de') }}"
                    class="flex items-center gap-1 {{ app()->getLocale() == 'de' ? 'text-primary' : 'text-on-surface-variant' }}">
                    DE
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-lowest border-t border-outline-variant/5 pt-16 pb-8">
        <div class="max-w-container-max mx-auto px-margin-edge">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 pb-12 border-b border-outline-variant/5">

                <!-- Brand Summary & Claim Book link -->
                <div class="md:col-span-4 space-y-6">
                    @if ($company->logo_path)
                        <img src="{{ str_starts_with($company->logo_path, 'http') ? $company->logo_path : asset('storage/' . $company->logo_path) }}"
                            alt="{{ $company->name }}"
                            class="h-12 w-auto object-contain hover:opacity-90 transition-opacity duration-300 mb-4" />
                    @else
                        <h3 class="font-headline text-2xl text-on-surface font-bold">{{ $company->name }}</h3>
                    @endif
                    <p class="text-xs text-on-surface-variant font-body max-w-sm leading-relaxed">
                        {{ app()->getLocale() == 'es' ? 'Chocolate artesanal elaborado desde el grano hasta la barra, rescatando los cacaos finos de aroma de las selvas del Perú.' : 'Bean-to-bar artisanal chocolate, rescuing the fine aroma cocoas of the Peruvian rainforests.' }}
                    </p>
                    <div class="pt-4">
                        <!-- Complaints Book Badge / Libro de Reclamaciones -->
                        <a href="{{ route('claim-book') }}"
                            class="inline-flex items-center gap-3 border border-outline-variant/20 hover:border-primary/50 px-4 py-2 hover:bg-[#1a1a1a] transition-all duration-300">
                            <span class="material-symbols-outlined text-primary text-xl">menu_book</span>
                            <div class="text-left">
                                <span
                                    class="block text-[8px] font-label-caps tracking-[0.2em] text-outline">{{ app()->getLocale() == 'es' ? 'LIBRO DE' : 'COMPLAINTS' }}</span>
                                <span
                                    class="block text-[10px] font-bold text-on-surface tracking-wide uppercase">{{ app()->getLocale() == 'es' ? 'RECLAMACIONES' : 'BOOK' }}</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Contact coordinates -->
                <div class="md:col-span-4 space-y-4">
                    <h4 class="font-label-caps text-xs tracking-[0.2em] text-primary font-bold uppercase">
                        {{ __('messages.contact.hacienda') }}</h4>
                    <ul class="space-y-3 text-xs text-on-surface-variant">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                            <span>{{ $company->address }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-sm">phone</span>
                            <span>{{ $company->phone }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-sm">mail</span>
                            <span>{{ $company->email }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Navigation Quick Links -->
                <div class="md:col-span-4 space-y-4">
                    <h4 class="font-label-caps text-xs tracking-[0.2em] text-primary font-bold uppercase">
                        {{ app()->getLocale() == 'es' ? 'ENLACES' : 'QUICK LINKS' }}</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <a href="{{ route('home') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">Home</a>
                        <a href="{{ route('origin') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">{{ __('messages.nav.origin') }}</a>
                        <a href="{{ route('collections') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">{{ __('messages.nav.collections') }}</a>
                        <a href="{{ route('about') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">{{ __('messages.nav.story') }}</a>
                        <a href="{{ route('contact') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">{{ __('messages.nav.contact') }}</a>
                        <a href="{{ route('policies') }}"
                            class="text-on-surface-variant hover:text-primary transition-colors">{{ __('messages.footer.policies') }}</a>
                    </div>
                    <!-- Social Links -->
                    <div class="flex gap-4 pt-4 border-t border-outline-variant/5 items-center">
                        @if ($company->facebook_url)
                            <a href="{{ $company->facebook_url }}" target="_blank"
                                class="text-on-surface-variant hover:text-primary transition-colors" title="Facebook">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M9 8H7v3h2v9h3v-9h3.6l.4-3H12V6c0-.9.2-1.2 1-1.2H15V2h-3c-3 0-4 1.5-4 3.8V8z" />
                                </svg>
                            </a>
                        @endif
                        @if ($company->instagram_url)
                            <a href="{{ $company->instagram_url }}" target="_blank"
                                class="text-on-surface-variant hover:text-primary transition-colors"
                                title="Instagram">
                                <svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                                </svg>
                            </a>
                        @endif
                        @if ($company->tiktok_url)
                            <a href="{{ $company->tiktok_url }}" target="_blank"
                                class="text-on-surface-variant hover:text-primary transition-colors" title="TikTok">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12.53.02C13.84 0 15 1.4 15 2.8v3.5c1.4-.8 2.8-1.3 4.2-1.3v3.2c-2.4 0-4.2 1.4-4.2 3.8v4.5c0 3.3-2.6 6-5.8 6-3.3 0-6-2.7-6-6 0-3 2.2-5.5 5.1-5.9v3.3c-1.3.4-2.2 1.6-2.2 2.6 0 1.5 1.2 2.7 2.7 2.7 1.5 0 2.7-1.2 2.7-2.7V0h3.13z" />
                                </svg>
                            </a>
                        @endif
                        @if ($company->youtube_url)
                            <a href="{{ $company->youtube_url }}" target="_blank"
                                class="text-on-surface-variant hover:text-primary transition-colors" title="YouTube">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M23.5 6.2c-.3-1.1-1.1-2-2.2-2.3C19.3 3.5 12 3.5 12 3.5s-7.3 0-9.3.4C1.6 4.2.8 5.1.5 6.2.1 8.2.1 12 .1 12s0 3.8.4 5.8c.3 1.1 1.1 2 2.2 2.3 2 1 9.3 1 9.3 1s7.3 0 9.3-1c1.1-.3 1.9-1.2 2.2-2.3.4-2 .4-5.8.4-5.8s0-3.8-.4-5.8zM9.5 15.5V8.5l6.5 3.5-6.5 3.5z" />
                                </svg>
                            </a>
                        @endif
                        @if ($company->whatsapp_phone)
                            <a href="https://wa.me/{{ $company->whatsapp_phone }}" target="_blank"
                                class="text-on-surface-variant hover:text-primary transition-colors" title="WhatsApp">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12.012 2C6.48 2 2 6.48 2 12.01c0 1.91.5 3.71 1.47 5.29L2.03 22l4.89-1.28c1.51.82 3.23 1.29 5.09 1.29 5.53 0 10.01-4.48 10.01-10.01S17.54 2 12.012 2zm5.79 13.9c-.25.7-1.42 1.3-1.95 1.38-.49.07-1.12.1-3.23-.77-2.7-1.11-4.43-3.87-4.57-4.05-.14-.19-1.1-1.46-1.1-2.79 0-1.33.69-1.99.94-2.26.25-.27.55-.34.73-.34.18 0 .37 0 .53.01.17.01.4.01.62.53.23.55.78 1.9.85 2.05.07.15.12.33.02.53-.1.2-.15.33-.3.51-.15.18-.32.4-.46.54-.15.15-.31.32-.13.62.18.3.8 1.31 1.71 2.12.91.81 1.68 1.06 1.98 1.21.3.15.48.13.66-.08.18-.21.78-.91.99-1.22.21-.31.42-.26.71-.15.29.11 1.86.88 2.18 1.04.32.16.53.24.61.38.08.14.08.82-.17 1.52z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Footer Bottom -->
            <div class="flex flex-col md:flex-row justify-between items-center pt-8 text-[11px] text-outline">
                <p>&copy; {{ date('Y') }} {{ $company->name }}. {{ __('messages.footer.rights') }}</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="{{ route('policies') }}"
                        class="hover:text-primary transition-colors">{{ __('messages.footer.policies') }}</a>
                    <a href="{{ route('claim-book') }}"
                        class="hover:text-primary transition-colors">{{ __('messages.nav.claim_book') }}</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Global Toast Notification Box -->
    <div id="global-toast"
        class="fixed bottom-8 right-8 z-50 transform translate-y-12 opacity-0 pointer-events-none transition-all duration-500 bg-[#161616] border border-primary/30 text-primary px-6 py-4 text-xs font-bold font-body shadow-2xl flex items-center gap-3">
        <span class="material-symbols-outlined text-sm text-leaf-green" id="global-toast-icon">check_circle</span>
        <span id="global-toast-message"></span>
    </div>

    <script>
        function showGlobalToast(message, isSuccess = true) {
            const toast = document.getElementById('global-toast');
            const toastMsg = document.getElementById('global-toast-message');
            const toastIcon = document.getElementById('global-toast-icon');
            if (!toast || !toastMsg || !toastIcon) return;

            toastMsg.innerText = message;
            if (isSuccess) {
                toastIcon.innerText = 'check';
                toastIcon.className = 'material-symbols-outlined text-sm text-leaf-green';
                toast.className =
                    'fixed bottom-8 right-8 z-50 transition-all duration-500 bg-[#161616] border border-leaf-green/30 text-leaf-green px-6 py-4 text-xs font-bold font-body shadow-2xl flex items-center gap-3';
            } else {
                toastIcon.innerText = 'error';
                toastIcon.className = 'material-symbols-outlined text-sm text-error';
                toast.className =
                    'fixed bottom-8 right-8 z-50 transition-all duration-500 bg-[#161616] border border-error/30 text-error px-6 py-4 text-xs font-bold font-body shadow-2xl flex items-center gap-3';
            }

            toast.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');

            setTimeout(() => {
                toast.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
            }, 3500);
        }

        function addToCartFromGrid(variantId) {
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_variant_id: variantId,
                        quantity: 1
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('cart-badge-count');
                        if (badge) {
                            badge.innerText = data.cartCount;
                            badge.classList.remove('hidden');
                        }
                        showGlobalToast(
                            '{{ app()->getLocale() == 'es' ? '¡Producto agregado al carrito!' : 'Product added to cart!' }}',
                            true);
                    } else {
                        showGlobalToast(data.message || 'Error', false);
                    }
                })
                .catch(err => {
                    showGlobalToast(
                        '{{ app()->getLocale() == 'es' ? 'Error al agregar al carrito.' : 'Error adding to cart.' }}',
                        false);
                });
        }

        // Lock body scroll immediately on parse to prevent early user scrolling
        document.body.style.overflow = 'hidden';

        document.addEventListener('DOMContentLoaded', () => {
            // Proteger íconos de Material Symbols de la traducción del navegador
            document.querySelectorAll('.material-symbols-outlined, .material-icons').forEach(el => {
                el.setAttribute('translate', 'no');
                el.classList.add('notranslate');
            });

            // Cinematic preloader animation reveal controller
            const preloader = document.getElementById('preloader');
            const colorLogo = document.getElementById('preloader-logo-color');
            const displacement = document.getElementById('displacement-map');

            if (preloader && colorLogo && displacement) {
                let startTime = null;
                const duration = 2800; // 2.8 seconds color reveal duration

                function animateReveal(timestamp) {
                    if (!startTime) startTime = timestamp;
                    const elapsed = timestamp - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // 1. Expand reveal radius from 0% to 160% starting from bottom center (50% 100%)
                    const radius = progress * 160;
                    colorLogo.style.clipPath = `circle(${radius}% at 50% 100%)`;

                    // 2. Damped wave displacement scale (maximum peak of 20, drops smoothly to 0 at completion)
                    const displacementScale = (1 - progress) * Math.sin(progress * Math.PI) * 20;
                    displacement.setAttribute('scale', displacementScale);

                    if (progress < 1) {
                        requestAnimationFrame(animateReveal);
                    } else {
                        // Reveal completed: add subtle luxury glow drop shadow
                        colorLogo.style.filter = 'drop-shadow(0 0 25px rgba(234, 188, 184, 0.65))';

                        // Wait 300ms, then fade out the black preloader screen overlay
                        setTimeout(() => {
                            preloader.style.opacity = '0';
                            preloader.style.pointerEvents = 'none';

                            // Re-enable scrolling after the preloader opacity fade transition finishes (800ms)
                            setTimeout(() => {
                                preloader.style.display = 'none';
                                document.body.style.overflow = '';
                            }, 800);
                        }, 300);
                    }
                }

                // Start reveal after a short delay for smooth paint rendering
                setTimeout(() => {
                    requestAnimationFrame(animateReveal);
                }, 150);
            } else {
                // Fallback unlock if elements are missing
                document.body.style.overflow = '';
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
