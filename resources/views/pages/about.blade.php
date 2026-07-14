@extends('layouts.app')

@section('title', __('messages.nav.story') . ' | Dos Aguas')

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
    </style>
@endsection

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">

        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.nav.story') }}</span>
        </nav>

        <div class="max-w-3xl mx-auto text-center space-y-8 mb-20">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ __('messages.about.journey') }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-bold leading-tight">
                {{ __('messages.about.essence') }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <!-- History narrative section -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-32">
            <div class="lg:col-span-6 space-y-6">
                <h2 class="font-headline text-2xl font-bold">{{ __('messages.about.heritage') }}</h2>
                <div class="text-xs text-on-surface-variant leading-relaxed space-y-3">
                    {!! $company->about_history !!}
                </div>
                <div class="border-t border-outline-variant/10 pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                    <div>
                        <span class="block font-headline text-2xl text-primary font-bold">100%</span>
                        <span
                            class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ __('messages.about.trazable') }}</span>
                    </div>
                    <div>
                        <span
                            class="block font-headline text-2xl text-primary font-bold">{{ __('messages.about.origen') }}</span>
                        <span
                            class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ __('messages.about.solido') }}</span>
                    </div>
                    <div>
                        <span
                            class="block font-headline text-2xl text-primary font-bold">{{ __('messages.about.hecho') }}</span>
                        <span
                            class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ __('messages.about.a_mano') }}</span>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-6 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                <img alt="Our cacao farms"
                    class="w-full h-full object-cover hover:scale-102 transition-transform duration-700"
                    src="{{ asset('img/NUESTRA PRODUCCION.jpeg') }}" />
            </div>
        </section>

        <!-- Mission / Vision / Values grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-32 border-t border-b border-outline-variant/10 py-16">
            <!-- Mission -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">spa</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">
                    {{ app()->getLocale() == 'es' ? 'Misión' : 'Mission' }}</h3>
                <div class="text-xs text-on-surface-variant leading-relaxed">
                    {!! $company->about_mission !!}
                </div>
            </div>

            <!-- Vision -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">visibility</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">
                    {{ app()->getLocale() == 'es' ? 'Visión' : 'Vision' }}</h3>
                <div class="text-xs text-on-surface-variant leading-relaxed">
                    {!! $company->about_vision !!}
                </div>
            </div>

            <!-- Values -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">favorite</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">
                    {{ app()->getLocale() == 'es' ? 'Valores' : 'Values' }}</h3>
                <div class="text-xs text-on-surface-variant leading-relaxed">
                    {!! $company->about_values !!}
                </div>
            </div>
        </section>

        <!-- Gallery photos section -->
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

        <section class="border-t border-outline-variant/10 pt-20" x-data="{ activeImage: null }">
            <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                    {{ __('messages.about.gallery_title') }}
                </span>
                <h2 class="font-headline text-3xl font-bold uppercase tracking-wider">
                    {{ __('messages.about.gallery_subtitle') }}
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
                <div
                    class="relative bg-[#161616] border border-outline-variant/20 max-w-4xl w-full p-4 flex flex-col gap-4">
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

    </main>

@endsection
