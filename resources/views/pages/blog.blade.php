@extends('layouts.app')

@section('title', __("messages.nav.blog") . ' | Dos Aguas')

@section('styles')
<style>
    .post-card-img {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .post-card:hover .post-card-img {
        transform: scale(1.06);
    }
</style>
@endsection

@section('content')

    {{-- Hero Header --}}
    <section class="relative h-[38vh] min-h-[280px] flex items-end overflow-hidden bg-[#0a0a0a] border-b border-outline-variant/10">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-background/20 via-background/50 to-background z-10"></div>
        </div>
        <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-edge pb-14">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold mb-4">
                {{ __("messages.nav.blog") }}
            </span>
            <h1 class="font-headline text-5xl md:text-6xl font-bold text-on-surface leading-tight">
                Desde la Semilla
            </h1>
            <p class="mt-4 text-on-surface-variant font-body text-sm max-w-lg leading-relaxed">
                {{ app()->getLocale() == 'es'
                    ? 'Historias, procesos y conocimiento detrás del cacao de origen peruano.'
                    : (app()->getLocale() == 'de'
                        ? 'Geschichten, Prozesse und Wissen hinter dem peruanischen Ursprungskakao.'
                        : 'Stories, processes and knowledge behind single-origin Peruvian cacao.') }}
            </p>
        </div>
    </section>

    {{-- Blog Grid --}}
    <main class="max-w-container-max mx-auto px-margin-edge py-20">

        @if ($posts->isEmpty())
            <div class="text-center py-24 space-y-4">
                <span class="material-symbols-outlined text-5xl text-outline">article</span>
                <p class="text-on-surface-variant font-body text-sm">
                    {{ app()->getLocale() == 'es' ? 'No hay artículos publicados aún.' : 'No articles published yet.' }}
                </p>
            </div>
        @else

            {{-- Featured First Post --}}
            @php $featured = $posts->first(); @endphp
            <a href="{{ route('blog.detail', $featured->slug) }}"
               class="post-card group relative block w-full aspect-[16/7] overflow-hidden bg-surface-container border border-outline-variant/10 mb-14 reveal">
                @if ($featured->image_path)
                    <img src="{{ str_starts_with($featured->image_path, 'http') ? $featured->image_path : asset('storage/' . $featured->image_path) }}"
                         alt="{{ $featured->title }}"
                         class="post-card-img absolute inset-0 w-full h-full object-cover opacity-60" />
                @else
                    <div class="absolute inset-0 bg-surface-container-highest"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/50 to-transparent z-10"></div>
                <div class="absolute inset-0 z-20 flex flex-col justify-end p-10 md:p-14">
                    <span class="font-label-caps text-[9px] text-primary tracking-[0.3em] uppercase font-bold mb-3 block">
                        {{ app()->getLocale() == 'es' ? 'ARTÍCULO DESTACADO' : 'FEATURED ARTICLE' }}
                        @if ($featured->published_at)
                            &nbsp;·&nbsp; {{ $featured->published_at->format('d M Y') }}
                        @endif
                    </span>
                    <h2 class="font-headline text-3xl md:text-5xl font-bold text-on-surface leading-tight max-w-3xl mb-4 group-hover:text-primary transition-colors duration-300">
                        {{ $featured->title }}
                    </h2>
                    @if ($featured->excerpt)
                        <p class="text-on-surface-variant font-body text-sm leading-relaxed max-w-2xl line-clamp-2">
                            {{ $featured->excerpt }}
                        </p>
                    @endif
                    <span class="mt-6 inline-flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-primary uppercase font-bold group-hover:gap-4 transition-all duration-300">
                        {{ app()->getLocale() == 'es' ? 'LEER ARTÍCULO' : 'READ ARTICLE' }}
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </span>
                </div>
            </a>

            {{-- Rest of Posts Grid --}}
            @if ($posts->count() > 1)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts->skip(1) as $post)
                        @php
                            $imgUrl = $post->image_path
                                ? (str_starts_with($post->image_path, 'http') ? $post->image_path : asset('storage/' . $post->image_path))
                                : null;
                        @endphp
                        <a href="{{ route('blog.detail', $post->slug) }}"
                           class="post-card group relative flex flex-col bg-surface-container border border-outline-variant/10 overflow-hidden reveal hover:border-primary/30 transition-colors duration-500">

                            {{-- Cover Image --}}
                            <div class="relative h-56 overflow-hidden bg-surface-container-highest flex-shrink-0">
                                @if ($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $post->title }}"
                                         class="post-card-img w-full h-full object-cover opacity-70" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-outline">article</span>
                                    </div>
                                @endif
                                @if ($post->published_at)
                                    <div class="absolute top-4 left-4 bg-[#0a0a0a]/80 backdrop-blur-sm px-3 py-1.5 border border-outline-variant/20">
                                        <span class="font-label-caps text-[9px] text-primary tracking-widest font-bold">
                                            {{ $post->published_at->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-7 flex flex-col flex-1 gap-4">
                                <h3 class="font-headline text-lg font-bold text-on-surface leading-snug group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                @if ($post->excerpt)
                                    <p class="text-on-surface-variant font-body text-xs leading-relaxed line-clamp-3 flex-1">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mt-2 pt-4 border-t border-outline-variant/10">
                                    @if ($post->author)
                                        <span class="font-label-caps text-[9px] text-outline tracking-wider uppercase">
                                            {{ $post->author->name }}
                                        </span>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="material-symbols-outlined text-sm text-primary group-hover:translate-x-1 transition-transform duration-300">
                                        arrow_forward
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        @endif
    </main>

@endsection
