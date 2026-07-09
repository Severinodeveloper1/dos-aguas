@extends('layouts.app')

@section('title', __('messages.nav.story') . ' | Dos Aguas')

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
                {{ app()->getLocale() == 'es' ? 'NUESTRO VIAJE' : 'OUR JOURNEY' }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-bold leading-tight">
                {{ app()->getLocale() == 'es' ? 'La Esencia de Dos Aguas' : 'The Essence of Dos Aguas' }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <!-- History narrative section -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-32">
            <div class="lg:col-span-6 space-y-6">
                <h2 class="font-headline text-2xl font-bold">{{ app()->getLocale() == 'es' ? 'Herencia y Territorio' : 'Heritage & Territory' }}</h2>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    {{ $company->about_history }}
                </p>
                <div class="border-t border-outline-variant/10 pt-6 grid grid-cols-3 gap-6 text-center">
                    <div>
                        <span class="block font-headline text-2xl text-primary font-bold">100%</span>
                        <span class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ app()->getLocale() == 'es' ? 'TRAZABLE' : 'TRACEABLE' }}</span>
                    </div>
                    <div>
                        <span class="block font-headline text-2xl text-primary font-bold">{{ app()->getLocale() == 'es' ? 'Origen' : 'Estate' }}</span>
                        <span class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ app()->getLocale() == 'es' ? 'SÓLIDO' : 'GROWN' }}</span>
                    </div>
                    <div>
                        <span class="block font-headline text-2xl text-primary font-bold">{{ app()->getLocale() == 'es' ? 'Hecho' : 'Hand' }}</span>
                        <span class="font-label-caps text-[9px] tracking-wider text-outline font-bold">{{ app()->getLocale() == 'es' ? 'A MANO' : 'CRAFTED' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-6 aspect-[4/3] overflow-hidden bg-surface-container border border-outline-variant/5">
                <img alt="Our cacao farms" class="w-full h-full object-cover hover:scale-102 transition-transform duration-700" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GFdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac"/>
            </div>
        </section>

        <!-- Mission / Vision / Values grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-32 border-t border-b border-outline-variant/10 py-16">
            <!-- Mission -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">spa</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">{{ app()->getLocale() == 'es' ? 'Misión' : 'Mission' }}</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    {{ $company->about_mission }}
                </p>
            </div>
            
            <!-- Vision -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">visibility</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">{{ app()->getLocale() == 'es' ? 'Visión' : 'Vision' }}</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    {{ $company->about_vision }}
                </p>
            </div>

            <!-- Values -->
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-3xl">favorite</span>
                <h3 class="font-headline text-xl font-bold uppercase tracking-wide">{{ app()->getLocale() == 'es' ? 'Valores' : 'Values' }}</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    {{ $company->about_values }}
                </p>
            </div>
        </section>

        <!-- Gallery photos grid -->
        @if(is_array($company->gallery_photos) && count($company->gallery_photos) > 0)
            <section class="space-y-8">
                <h3 class="font-headline text-2xl font-bold text-center mb-12">{{ app()->getLocale() == 'es' ? 'Imágenes del Estudio' : 'Studio Images' }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($company->gallery_photos as $photo)
                        <div class="aspect-square bg-surface-container overflow-hidden border border-outline-variant/5">
                            <img src="{{ str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo) }}" alt="Studio gallery" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"/>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

@endsection
