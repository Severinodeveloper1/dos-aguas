@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title . ' | Dos Aguas')

@if ($post->meta_description)
@section('meta_description', $post->meta_description)
@endif

@section('styles')
<style>
    .blog-content {
        font-size: 1rem;
        line-height: 1.85;
        color: var(--color-on-surface-variant, #ccc);
    }
    .blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4 {
        font-family: var(--font-headline, serif);
        color: var(--color-on-surface, #f4f4f4);
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.3;
    }
    .blog-content h2 { font-size: 1.65rem; }
    .blog-content h3 { font-size: 1.35rem; }
    .blog-content p  { margin-bottom: 1.5rem; }
    .blog-content ul, .blog-content ol {
        margin: 1.2rem 0 1.5rem 1.5rem;
        list-style: disc;
    }
    .blog-content ol { list-style: decimal; }
    .blog-content li { margin-bottom: 0.5rem; }
    .blog-content a {
        color: var(--color-primary, #eabcb8);
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .blog-content blockquote {
        border-left: 3px solid var(--color-primary, #eabcb8);
        padding: 1rem 1.5rem;
        margin: 2rem 0;
        background: rgba(255,255,255,0.03);
        font-style: italic;
    }
    .blog-content img {
        width: 100%;
        height: auto;
        margin: 2rem 0;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .blog-content strong { color: var(--color-on-surface, #f4f4f4); }
    .blog-content code {
        background: rgba(255,255,255,0.05);
        padding: 0.15em 0.4em;
        border-radius: 3px;
        font-size: 0.85em;
    }
    .related-card-img {
        transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
    }
    .related-card:hover .related-card-img {
        transform: scale(1.06);
    }
</style>
@endsection

@section('content')

    {{-- Full-Width Hero Cover --}}
    <section class="relative h-[55vh] min-h-[360px] w-full overflow-hidden flex items-end bg-[#0a0a0a]">
        @if ($post->image_path)
            <img src="{{ str_starts_with($post->image_path, 'http') ? $post->image_path : asset('storage/' . $post->image_path) }}"
                 alt="{{ $post->title }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-50" />
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent z-10"></div>
        <div class="relative z-20 w-full max-w-container-max mx-auto px-margin-edge pb-14 space-y-5">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors duration-300">
                    {{ app()->getLocale() == 'es' ? 'INICIO' : 'HOME' }}
                </a>
                <span class="text-[8px] opacity-40">/</span>
                <a href="{{ route('blog') }}" class="hover:text-primary transition-colors duration-300">
                    BLOG
                </a>
                <span class="text-[8px] opacity-40">/</span>
                <span class="text-on-surface font-bold truncate max-w-xs">{{ Str::upper(Str::limit($post->title, 40)) }}</span>
            </nav>
            {{-- Meta row --}}
            <div class="flex flex-wrap items-center gap-4">
                @if ($post->published_at)
                    <span class="font-label-caps text-[10px] text-primary tracking-[0.25em] font-bold uppercase">
                        {{ $post->published_at->format('d M Y') }}
                    </span>
                @endif
                @if ($post->author)
                    <span class="font-label-caps text-[10px] text-outline tracking-wider uppercase">
                        {{ app()->getLocale() == 'es' ? 'Por' : 'By' }} {{ $post->author->name }}
                    </span>
                @endif
            </div>
            {{-- Title --}}
            <h1 class="font-headline text-4xl md:text-6xl font-bold text-on-surface leading-[1.1] max-w-4xl">
                {{ $post->title }}
            </h1>
            @if ($post->excerpt)
                <p class="font-body text-on-surface-variant text-base leading-relaxed max-w-2xl">
                    {{ $post->excerpt }}
                </p>
            @endif
        </div>
    </section>

    {{-- Article Body + Sidebar --}}
    <div class="max-w-container-max mx-auto px-margin-edge py-16 grid grid-cols-1 lg:grid-cols-12 gap-16">

        {{-- Main Article Content --}}
        <article class="lg:col-span-8 min-w-0">
            {{-- Decorative top rule --}}
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-outline-variant/20"></div>
                <span class="material-symbols-outlined text-primary text-lg">eco</span>
                <div class="h-px flex-1 bg-outline-variant/20"></div>
            </div>

            {{-- Rich text content --}}
            <div class="blog-content">
                {!! $post->content !!}
            </div>

            {{-- Bottom share / navigation --}}
            <div class="mt-16 pt-8 border-t border-outline-variant/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <a href="{{ route('blog') }}"
                   class="inline-flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-on-surface-variant hover:text-primary transition-colors duration-300 uppercase font-bold">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    {{ app()->getLocale() == 'es' ? 'VOLVER AL BLOG' : 'BACK TO BLOG' }}
                </a>
                {{-- Social share icons --}}
                <div class="flex items-center gap-4">
                    <span class="font-label-caps text-[9px] text-outline tracking-wider uppercase">
                        {{ app()->getLocale() == 'es' ? 'COMPARTIR' : 'SHARE' }}
                    </span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                       target="_blank" rel="noopener"
                       class="text-on-surface-variant hover:text-primary transition-colors duration-300"
                       aria-label="Compartir en Facebook">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                       target="_blank" rel="noopener"
                       class="text-on-surface-variant hover:text-primary transition-colors duration-300"
                       aria-label="Compartir en X/Twitter">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                       target="_blank" rel="noopener"
                       class="text-on-surface-variant hover:text-primary transition-colors duration-300"
                       aria-label="Compartir por WhatsApp">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-8">
            {{-- About the blog --}}
            <div class="bg-surface-container border border-outline-variant/10 p-8 space-y-4">
                <span class="font-label-caps text-[9px] text-primary tracking-[0.3em] uppercase font-bold block">
                    {{ app()->getLocale() == 'es' ? 'SOBRE EL BLOG' : 'ABOUT THE BLOG' }}
                </span>
                <h3 class="font-headline text-lg font-bold text-on-surface leading-snug">Desde la Semilla</h3>
                <p class="text-on-surface-variant font-body text-xs leading-relaxed">
                    {{ app()->getLocale() == 'es'
                        ? 'Contenido editorial sobre el mundo del cacao fino de aroma, los procesos bean to bar y el origen peruano.'
                        : (app()->getLocale() == 'de'
                            ? 'Redaktionelle Inhalte über die Welt des Edelkakaos, Bean-to-Bar-Prozesse und den peruanischen Ursprung.'
                            : 'Editorial content about the world of fine flavour cacao, bean to bar processes and Peruvian origin.') }}
                </p>
                <a href="{{ route('blog') }}"
                   class="inline-flex items-center gap-2 font-label-caps text-[9px] tracking-widest text-primary hover:gap-4 transition-all duration-300 uppercase font-bold">
                    {{ app()->getLocale() == 'es' ? 'VER TODOS LOS ARTÍCULOS' : 'ALL ARTICLES' }}
                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                </a>
            </div>

            {{-- Related Posts --}}
            @if ($relatedPosts->isNotEmpty())
                <div class="space-y-4">
                    <span class="font-label-caps text-[9px] text-primary tracking-[0.3em] uppercase font-bold block">
                        {{ app()->getLocale() == 'es' ? 'TAMBIÉN TE PUEDE INTERESAR' : 'YOU MAY ALSO LIKE' }}
                    </span>
                    @foreach ($relatedPosts as $related)
                        @php
                            $relImg = $related->image_path
                                ? (str_starts_with($related->image_path, 'http') ? $related->image_path : asset('storage/' . $related->image_path))
                                : null;
                        @endphp
                        <a href="{{ route('blog.detail', $related->slug) }}"
                           class="related-card group flex gap-4 bg-surface-container border border-outline-variant/10 overflow-hidden hover:border-primary/30 transition-colors duration-400">
                            {{-- Thumbnail --}}
                            <div class="relative w-24 h-24 flex-shrink-0 overflow-hidden bg-surface-container-highest">
                                @if ($relImg)
                                    <img src="{{ $relImg }}" alt="{{ $related->title }}"
                                         class="related-card-img w-full h-full object-cover opacity-70" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-2xl text-outline">article</span>
                                    </div>
                                @endif
                            </div>
                            {{-- Text --}}
                            <div class="py-4 pr-4 flex flex-col justify-center gap-1.5 min-w-0">
                                @if ($related->published_at)
                                    <span class="font-label-caps text-[8px] text-primary tracking-widest font-bold">
                                        {{ $related->published_at->format('d M Y') }}
                                    </span>
                                @endif
                                <h4 class="font-headline text-sm font-bold text-on-surface leading-snug line-clamp-2 group-hover:text-primary transition-colors duration-300">
                                    {{ $related->title }}
                                </h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- CTA --}}
            <div class="bg-primary/5 border border-primary/20 p-8 space-y-4 text-center">
                <span class="material-symbols-outlined text-3xl text-primary block">eco</span>
                <h4 class="font-headline text-base font-bold text-on-surface">
                    {{ app()->getLocale() == 'es' ? 'Descubre nuestra colección' : 'Explore our collection' }}
                </h4>
                <p class="text-on-surface-variant font-body text-xs leading-relaxed">
                    {{ app()->getLocale() == 'es'
                        ? 'Chocolates bean to bar elaborados con cacao fino de origen peruano.'
                        : 'Bean to bar chocolates crafted from fine flavour Peruvian cacao.' }}
                </p>
                <a href="{{ route('collections') }}"
                   class="inline-block px-6 py-3 bg-primary text-on-primary font-label-caps text-[10px] tracking-widest hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-bold">
                    {{ app()->getLocale() == 'es' ? 'VER COLECCIÓN' : 'VIEW COLLECTION' }}
                </a>
            </div>
        </aside>
    </div>

@endsection
