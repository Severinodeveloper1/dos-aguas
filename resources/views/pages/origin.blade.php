@extends('layouts.app')

@section('title', __('messages.nav.origin') . ' | Dos Aguas')

@section('styles')
<style>
    /* Custom AOS-like scroll reveal animations */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">
        
        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.nav.origin') }}</span>
        </nav>

        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center space-y-8 mb-20 reveal">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ app()->getLocale() == 'es' ? 'NUESTRO VIAJE' : 'OUR JOURNEY' }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-bold leading-tight">
                {{ app()->getLocale() == 'es' ? 'Historia y Orígenes' : 'History and Origins' }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <!-- Timeline Section (Above the Process) -->
        @if($timelineEvents->isNotEmpty())
            <section class="mb-32">
                <div class="max-w-4xl mx-auto relative border-l border-outline-variant/20 pl-8 space-y-12 py-4">
                    @foreach($timelineEvents as $event)
                        <div class="relative reveal">
                            <!-- Diamond Bullet Indicator -->
                            <div class="absolute -left-[38px] top-1.5 w-4 h-4 bg-background border border-primary rotate-45 flex items-center justify-center">
                                <div class="w-1.5 h-1.5 bg-primary"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                                <div class="md:col-span-3 text-secondary font-headline text-2xl font-bold tracking-tight">
                                    {{ $event->year }}
                                </div>
                                <div class="md:col-span-9 space-y-3">
                                    <h4 class="font-headline text-lg font-bold text-on-surface">
                                        {{ app()->getLocale() == 'en' && $event->title_en ? $event->title_en : $event->title }}
                                    </h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">
                                        {{ app()->getLocale() == 'en' && $event->description_en ? $event->description_en : $event->description }}
                                    </p>
                                    @if($event->image_path)
                                        <div class="pt-3 max-w-sm aspect-[16/9] overflow-hidden border border-outline-variant/10">
                                            <img src="{{ str_starts_with($event->image_path, 'http') ? $event->image_path : asset('storage/' . $event->image_path) }}" 
                                                 alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-102"/>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="max-w-3xl mx-auto text-center space-y-8 mb-20 reveal">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ app()->getLocale() == 'es' ? 'EL MÉTODO' : 'THE METHOD' }}
            </span>
            <h2 class="font-headline text-3xl font-bold uppercase tracking-wider">
                {{ app()->getLocale() == 'es' ? 'Del Grano a la Barra' : 'From Bean to Bar' }}
            </h2>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <!-- Visual Craft Steps -->
        <section class="space-y-24 mb-32">
            
            <!-- Step 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center reveal">
                <div class="lg:col-span-6 space-y-4">
                    <span class="font-headline text-primary text-4xl font-bold">01</span>
                    <h3 class="font-headline text-2xl font-bold">{{ app()->getLocale() == 'es' ? 'Selección Rigurosa de Mazorcas' : 'Rigorous Pod Selection' }}</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        {{ app()->getLocale() == 'es' ? 'Solo cosechamos mazorcas en su punto óptimo de maduración. Cada grano de cacao nativo fino de aroma es clasificado manualmente para garantizar la homogeneidad y pureza del lote.' : 'We only harvest pods at their optimal ripeness. Each grain of fine aroma native cocoa is manually sorted to guarantee batch homogeneity and purity.' }}
                    </p>
                </div>
                <div class="lg:col-span-6 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                    <img class="w-full h-full object-cover hover:scale-102 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4" alt="Cacao pod harvest"/>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center reveal">
                <div class="lg:col-span-6 lg:order-last space-y-4">
                    <span class="font-headline text-primary text-4xl font-bold">02</span>
                    <h3 class="font-headline text-2xl font-bold">{{ app()->getLocale() == 'es' ? 'Fermentación en Cajones de Madera' : 'Fermentation in Wooden Boxes' }}</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        {{ app()->getLocale() == 'es' ? 'El cacao reposa en cajones de madera de laurel durante 5 a 6 días. Este paso crítico es donde los precursores del sabor y aroma a frutos secos y flores se desarrollan de forma natural.' : 'The cocoa rests in laurel wood boxes for 5 to 6 days. This critical step is where the precursors of flavor and aroma of nuts and flowers develop naturally.' }}
                    </p>
                </div>
                <div class="lg:col-span-6 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                    <img class="w-full h-full object-cover hover:scale-102 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GRdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac" alt="Cacao fermentation"/>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center reveal">
                <div class="lg:col-span-6 space-y-4">
                    <span class="font-headline text-primary text-4xl font-bold">03</span>
                    <h3 class="font-headline text-2xl font-bold">{{ app()->getLocale() == 'es' ? 'Secado Solar' : 'Solar Drying' }}</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        {{ app()->getLocale() == 'es' ? 'Los granos húmedos se extienden en camas solares elevadas bajo sombra parcial, permitiendo un secado lento y homogéneo para reducir la acidez volátil sin dañar las grasas naturales.' : 'The wet beans are spread on elevated solar beds under partial shade, allowing slow and homogeneous drying to reduce volatile acidity without damaging natural fats.' }}
                    </p>
                </div>
                <div class="lg:col-span-6 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                    <img class="w-full h-full object-cover hover:scale-102 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4" alt="Cacao drying"/>
                </div>
            </div>

        </section>

        <!-- Blog/Journal Posts Section -->
        @if($posts->isNotEmpty())
            <section class="border-t border-outline-variant/10 pt-20 reveal">
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                    <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                        {{ __('messages.home.journal_title') }}
                    </span>
                    <h2 class="font-headline text-3xl font-bold uppercase tracking-wider">
                        {{ app()->getLocale() == 'es' ? 'Crónicas del Cacao' : 'Cacao Chronicles' }}
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="flex flex-col bg-[#161616] border border-outline-variant/10 hover:border-primary/30 transition-all duration-300 reveal">
                            <div class="aspect-[16/10] overflow-hidden bg-surface-container">
                                @if($post->image_path)
                                    <img src="{{ str_starts_with($post->image_path, 'http') ? $post->image_path : asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-102 transition-transform duration-500"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-outline/20">
                                        <span class="material-symbols-outlined text-4xl">article</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div class="space-y-3">
                                    <span class="text-[10px] text-outline font-label-caps font-bold">
                                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : '' }}
                                    </span>
                                    <h4 class="font-headline text-lg font-bold text-on-surface line-clamp-2">{{ $post->title }}</h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

@endsection

@section('scripts')
<script>
    // Scroll Reveal implementation (custom lightweight AOS)
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
    
    // Initial run on page load
    document.addEventListener("DOMContentLoaded", () => {
        revealOnScroll();
    });
</script>
@endsection
