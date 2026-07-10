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

    <!-- 1. Full Screen Hero Header Section with IMAGEN_SENDERO.webp Background (Parallax Effect) -->
    <section class="relative h-screen w-full flex items-center justify-center overflow-hidden bg-black">
        
        <!-- Background Image with Parallax fixed attachment -->
        <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('img/IMAGEN_SENDERO.webp') }}'); background-attachment: fixed;"></div>
        
        <!-- Elegant Dark Overlay -->
        <div class="absolute inset-0 bg-black/60 z-0"></div>

        <!-- Breadcrumbs (absolute positioned at the top left) -->
        <nav class="absolute top-8 left-margin-edge z-10 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline-variant/60">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.nav.origin') }}</span>
        </nav>

        <!-- Centered Titles -->
        <div class="relative z-10 text-center max-w-4xl mx-auto px-margin-edge space-y-6">
            <span class="font-label-caps text-xs text-primary tracking-[0.4em] uppercase block font-bold">
                {{ __('messages.about.journey') }}
            </span>
            <h1 class="font-headline text-5xl md:text-7xl font-bold leading-tight uppercase text-on-surface">
                {{ __('messages.origin.title') }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>
    </section>

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">

        <!-- 2. Stacking Deck of Cards Timeline Section (with blur/fade scroll animation) -->
        @if($timelineEvents->isNotEmpty())
            <section class="py-20 mb-32 relative">
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-20 reveal">
                    <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                        {{ __('messages.origin.timeline') }}
                    </span>
                    <h2 class="font-headline text-3xl font-bold uppercase tracking-wider">
                        {{ __('messages.origin.milestones') }}
                    </h2>
                </div>                <div class="relative space-y-[15vh]">
                    @foreach($timelineEvents as $index => $event)
                        @php
                            $bgImg = $event->image_path 
                                ? (str_starts_with($event->image_path, 'http') ? $event->image_path : asset('storage/' . $event->image_path)) 
                                : 'https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4';
                        @endphp
                        
                        <!-- Sticky Card Stacking/Passing Layout -->
                        <div class="timeline-card sticky top-[12vh] h-[75vh] w-full max-w-5xl mx-auto flex flex-col justify-end p-8 md:p-16 border border-outline-variant/15 overflow-hidden shadow-2xl transition-all duration-300 ease-out"
                             style="z-index: {{ $index + 10 }}; margin-bottom: 5vh; background-color: #131313;">
                            
                            <!-- Card background image -->
                            <div class="timeline-card-bg absolute inset-0 z-0 bg-cover bg-center transition-all duration-300 ease-out" 
                                 style="background-image: url('{{ $bgImg }}')"></div>
                            
                            <!-- Card dark overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent z-0"></div>

                            <!-- Card content -->
                            <div class="relative z-10 max-w-2xl text-left space-y-4">
                                <span class="font-headline text-5xl md:text-7xl font-bold text-primary block leading-none">{{ $event->year }}</span>
                                <h3 class="font-headline text-2xl md:text-3xl font-bold text-on-surface">
                                    {{ app()->getLocale() == 'es' ? $event->title : ($event->title_en ?? $event->title) }}
                                </h3>
                                <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-body">
                                    {{ app()->getLocale() == 'es' ? $event->description : ($event->description_en ?? $event->description) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- 3. Horizontal Scroll Section for DEL GRANO A LA BARRA Proceso -->
    <section id="horizontal-scroll-container" class="relative h-[350vh] bg-black">
        
        <!-- Sticky full screen viewport wrapper -->
        <div class="sticky top-0 h-screen overflow-hidden flex items-center">
            
            <!-- Horizontal slider track -->
            <div id="horizontal-scroll-track" class="flex w-[400vw] h-full items-stretch">
                
                <!-- Slide 1: Section Title Intro with background image Cacao_abierto.jpeg -->
                <div class="w-screen h-full flex flex-col justify-center items-center text-center relative overflow-hidden bg-black flex-shrink-0">
                    
                    <!-- Background Image -->
                    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat opacity-50" 
                         style="background-image: url('{{ asset('img/Cacao_abierto.jpeg') }}')"></div>
                    
                    <!-- Dark overlay -->
                    <div class="absolute inset-0 bg-black/50 z-0"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                            {{ __('messages.origin.method') }}
                        </span>
                        <h2 class="font-headline text-4xl md:text-6xl font-bold uppercase tracking-wider text-on-surface">
                            {{ __('messages.origin.bean_to_bar') }}
                        </h2>
                        <div class="w-16 h-px bg-primary mx-auto"></div>
                        <p class="text-[10px] text-outline font-body max-w-sm uppercase tracking-widest pt-4">
                            {{ __('messages.origin.scroll_process') }} &darr;
                        </p>
                    </div>
                </div>

                <!-- Slide 2: Step 1 Selection -->
                <div class="w-screen h-full flex items-center px-12 md:px-24 bg-surface-container-lowest border-r border-outline-variant/10 flex-shrink-0">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 w-full max-w-6xl mx-auto items-center">
                        <div class="lg:col-span-5 space-y-4 text-left">
                            <span class="font-headline text-primary text-5xl font-bold block leading-none">01</span>
                            <h3 class="font-headline text-2xl md:text-3xl font-bold text-on-surface">{{ app()->getLocale() == 'es' ? 'Selección Rigurosa de Mazorcas' : 'Rigorous Pod Selection' }}</h3>
                            <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-body">
                                {{ app()->getLocale() == 'es' ? 'Solo cosechamos mazorcas en su punto óptimo de maduración. Cada grano de cacao nativo fino de aroma es clasificado manualmente para garantizar la homogeneidad y pureza del lote.' : 'We only harvest pods at their optimal ripeness. Each grain of fine aroma native cocoa is manually sorted to guarantee batch homogeneity and purity.' }}
                            </p>
                        </div>
                        <div class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4" alt="Cacao pod harvest"/>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: Step 2 Fermentation -->
                <div class="w-screen h-full flex items-center px-12 md:px-24 bg-surface-container-lowest border-r border-outline-variant/10 flex-shrink-0">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 w-full max-w-6xl mx-auto items-center">
                        <div class="lg:col-span-5 space-y-4 text-left">
                            <span class="font-headline text-primary text-5xl font-bold block leading-none">02</span>
                            <h3 class="font-headline text-2xl md:text-3xl font-bold text-on-surface">{{ app()->getLocale() == 'es' ? 'Fermentación en Madera' : 'Fermentation in Wooden Boxes' }}</h3>
                            <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-body">
                                {{ app()->getLocale() == 'es' ? 'El cacao reposa en cajones de madera de laurel durante 5 a 6 días. Este paso crítico es donde los precursores del sabor y aroma a frutos secos y flores se desarrollan de forma natural.' : 'The cocoa rests in laurel wood boxes for 5 to 6 days. This critical step is where the precursors of flavor and aroma of nuts and flowers develop naturally.' }}
                            </p>
                        </div>
                        <div class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GFdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac" alt="Cacao fermentation"/>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: Step 3 Drying -->
                <div class="w-screen h-full flex items-center px-12 md:px-24 bg-surface-container-lowest flex-shrink-0">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 w-full max-w-6xl mx-auto items-center">
                        <div class="lg:col-span-5 space-y-4 text-left">
                            <span class="font-headline text-primary text-5xl font-bold block leading-none">03</span>
                            <h3 class="font-headline text-2xl md:text-3xl font-bold text-on-surface">{{ app()->getLocale() == 'es' ? 'Secado Solar' : 'Solar Drying' }}</h3>
                            <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-body">
                                {{ app()->getLocale() == 'es' ? 'Los granos húmedos se extienden en camas solares elevadas bajo sombra parcial, permitiendo un secado lento y homogéneo para reducir la acidez volátil sin dañar las grasas naturales.' : 'The wet beans are spread on elevated solar beds under partial shade, allowing slow and homogeneous drying to reduce volatile acidity without damaging natural fats.' }}
                            </p>
                        </div>
                        <div class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-surface-container border border-outline-variant/5">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4" alt="Cacao drying"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Bottom Margin Container -->
    <main class="max-w-container-max mx-auto px-margin-edge py-10 font-body">

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
    // Custom scroll reveal triggers
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

    // Scroll-driven horizontal process translation
    function handleHorizontalScroll() {
        const container = document.getElementById('horizontal-scroll-container');
        const track = document.getElementById('horizontal-scroll-track');
        if (!container || !track) return;

        const containerTop = container.offsetTop;
        const containerHeight = container.offsetHeight;
        const windowHeight = window.innerHeight;
        const scrollTop = window.scrollY;

        // Calculate progress inside horizontal scroll wrapper (from 0 to 1)
        const scrollRange = containerHeight - windowHeight;
        if (scrollRange <= 0) return;

        let progress = (scrollTop - containerTop) / scrollRange;
        progress = Math.max(0, Math.min(1, progress));

        // Since there are 4 screens of 100vw, translate track by up to -300vw
        const translateVal = progress * 300; 
        track.style.transform = `translateX(-${translateVal}vw)`;
    }

    // Timeline card scroll-driven effects (blur background & scale down past cards)
    function updateTimelineEffects() {
        const cards = document.querySelectorAll('.timeline-card');
        const windowHeight = window.innerHeight;

        cards.forEach((card, index) => {
            const bg = card.querySelector('.timeline-card-bg');
            if (!bg) return;
            
            // Check if there is a next card that will cover this card
            const nextCard = cards[index + 1];
            if (nextCard) {
                const nextRect = nextCard.getBoundingClientRect();
                const nextTop = nextRect.top;
                
                // Sticky top of cards is 12vh (12% of viewport height)
                const stickyTopLimit = windowHeight * 0.12;
                
                // If Card 2 top starts coming up (nextTop < windowHeight)
                if (nextTop < windowHeight) {
                    const distance = windowHeight - stickyTopLimit;
                    let progress = (windowHeight - nextTop) / distance;
                    progress = Math.max(0, Math.min(1, progress));
                    
                    const blurVal = progress * 12; // up to 12px blur
                    const scaleVal = 1 - (progress * 0.05); // scale down to 0.95
                    const opacityVal = 1 - (progress * 0.4); // fade to 0.6 opacity
                    
                    bg.style.filter = `blur(${blurVal}px)`;
                    card.style.transform = `scale(${scaleVal})`;
                    card.style.opacity = `${opacityVal}`;
                } else {
                    bg.style.filter = 'blur(0px)';
                    card.style.transform = 'scale(1)';
                    card.style.opacity = '1';
                }
            } else {
                // Last card stays active and unblurred
                bg.style.filter = 'blur(0px)';
                card.style.transform = 'scale(1)';
                card.style.opacity = '1';
            }
        });
    }

    window.addEventListener("scroll", () => {
        revealOnScroll();
        handleHorizontalScroll();
        updateTimelineEffects();
    });
    
    window.addEventListener("resize", () => {
        handleHorizontalScroll();
        updateTimelineEffects();
    });

    document.addEventListener("DOMContentLoaded", () => {
        revealOnScroll();
        handleHorizontalScroll();
        updateTimelineEffects();
    });
</script>
@endsection
