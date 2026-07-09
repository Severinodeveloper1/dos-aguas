@extends('layouts.app')

@section('title', __('messages.footer.policies') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body">
        
        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.footer.policies') }}</span>
        </nav>

        <div class="max-w-3xl mx-auto text-center space-y-8 mb-20">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ app()->getLocale() == 'es' ? 'LEGAL Y CONDICIONES' : 'LEGAL & TERMS' }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-bold leading-tight">
                {{ __('messages.footer.policies') }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <!-- Policies container -->
        <div class="max-w-4xl mx-auto space-y-16">
            @if($policies->isNotEmpty())
                @foreach($policies as $policy)
                    <section class="space-y-6">
                        <h2 class="font-headline text-2xl font-bold border-b border-outline-variant/15 pb-3 text-on-surface">
                            {{ $policy->title }}
                        </h2>
                        <div class="text-xs text-on-surface-variant font-body leading-relaxed space-y-4">
                            {!! nl2br(e($policy->content)) !!}
                        </div>
                    </section>
                @endforeach
            @else
                <div class="text-center py-20 bg-[#161616] border border-outline-variant/10 max-w-xl mx-auto p-12">
                    <span class="material-symbols-outlined text-outline text-4xl mb-4">gavel</span>
                    <p class="text-on-surface-variant text-sm font-body">
                        {{ app()->getLocale() == 'es' ? 'No se han registrado políticas aún.' : 'No policies registered yet.' }}
                    </p>
                </div>
            @endif
        </div>

    </main>

@endsection
