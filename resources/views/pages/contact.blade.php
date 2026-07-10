@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' | Dos Aguas')

@section('styles')
<style>
    .map-container iframe {
        width: 100% !important;
        height: 100% !important;
        border: 0 !important;
    }
</style>
@endsection

@section('content')

    <!-- 1. Hero Parallax Header Section with Custom Background Image -->
    <section class="relative h-[50vh] w-full overflow-hidden flex items-center justify-center bg-black">
        
        <!-- Background Image with Parallax fixed attachment -->
        <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('img/imagenes_constatenos.jpeg') }}'); background-attachment: fixed;"></div>
        
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/60 z-0"></div>
        
        <!-- Breadcrumbs (absolute positioned at top left) -->
        <nav class="absolute top-8 left-margin-edge z-10 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline-variant/60">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.nav.contact') }}</span>
        </nav>

        <!-- Centered Titles -->
        <div class="relative z-10 text-center max-w-4xl mx-auto px-margin-edge space-y-4">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ __('messages.contact.near') }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-bold uppercase tracking-wider text-on-surface">
                {{ __('messages.contact.talk_cacao') }}
            </h1>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>
    </section>

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body"
          x-data="{
              name: '',
              email: '',
              phone: '',
              subject: '',
              message: '',
              errors: {},
              successMessage: '',
              loading: false,

              submitContact() {
                  this.loading = true;
                  this.errors = {};
                  this.successMessage = '';

                  fetch('{{ route('contact') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                          name: this.name,
                          email: this.email,
                          phone: this.phone,
                          subject: this.subject,
                          message: this.message
                      })
                  })
                  .then(res => {
                      return res.json().then(data => ({ status: res.status, body: data }));
                  })
                  .then(res => {
                      this.loading = false;
                      if (res.status === 201) {
                          this.successMessage = res.body.message;
                          // Reset form
                          this.name = '';
                          this.email = '';
                          this.phone = '';
                          this.subject = '';
                          this.message = '';
                      } else if (res.status === 422) {
                          this.errors = res.body.errors || {};
                      } else {
                          alert('Error al enviar el mensaje. Por favor intente más tarde.');
                      }
                  })
                  .catch(err => {
                      this.loading = false;
                      alert('Error de red. Verifique su conexión.');
                  });
              }
          }">

        <!-- Contact Grid -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start mb-24">
            
            <!-- Left Form Column -->
            <div class="lg:col-span-7 bg-[#161616] border border-outline-variant/10 p-8 md:p-12 space-y-8">
                <h2 class="font-headline text-2xl font-bold uppercase tracking-wider">{{ __('messages.contact.title') }}</h2>
                
                <!-- Success Alert -->
                <div x-show="successMessage" x-transition class="p-4 bg-leaf-green/10 border border-leaf-green/30 text-leaf-green text-xs font-bold font-body">
                    <span x-text="successMessage"></span>
                </div>

                <form @submit.prevent="submitContact()" class="space-y-6 text-xs font-body">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.contact.name') }} *</label>
                            <input type="text" x-model="name" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.name">
                                <span class="text-error text-[10px]" x-text="errors.name[0]"></span>
                            </template>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.contact.email') }} *</label>
                            <input type="email" x-model="email" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.email">
                                <span class="text-error text-[10px]" x-text="errors.email[0]"></span>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.contact.phone') }}</label>
                            <input type="text" x-model="phone"
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.phone">
                                <span class="text-error text-[10px]" x-text="errors.phone[0]"></span>
                            </template>
                        </div>
                        
                        <!-- Subject -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.contact.subject') }} *</label>
                            <input type="text" x-model="subject" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.subject">
                                <span class="text-error text-[10px]" x-text="errors.subject[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.contact.message') }} *</label>
                        <textarea x-model="message" required rows="5"
                                  class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none"></textarea>
                        <template x-if="errors.message">
                            <span class="text-error text-[10px]" x-text="errors.message[0]"></span>
                        </template>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="loading"
                                class="bg-primary text-on-primary px-12 py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary disabled:bg-surface-container-high transition-all duration-300 font-bold flex items-center gap-2">
                            <span x-show="loading" class="w-3.5 h-3.5 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                            <span x-text="loading ? '...' : '{{ __('messages.contact.send') }}'"></span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Info Column -->
            <div class="lg:col-span-5 space-y-12 leading-relaxed text-xs">
                
                <div class="space-y-4">
                    <h3 class="font-headline text-2xl font-bold">{{ __('messages.contact.hacienda') }}</h3>
                    <p class="text-on-surface-variant font-body">
                        {{ __('messages.contact.hacienda_desc') }}
                    </p>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                        <div>
                            <p class="font-label-caps text-[10px] text-primary font-bold uppercase">{{ __('messages.footer.address') }}</p>
                            <p class="font-bold text-on-surface pt-1">{{ $company->address }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-primary text-xl">call</span>
                        <div>
                            <p class="font-label-caps text-[10px] text-primary font-bold uppercase">{{ __('messages.footer.phone') }}</p>
                            <p class="font-bold text-on-surface pt-1">{{ $company->phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-primary text-xl">alternate_email</span>
                        <div>
                            <p class="font-label-caps text-[10px] text-primary font-bold uppercase">{{ __('messages.footer.email') }}</p>
                            <p class="font-bold text-on-surface pt-1">{{ $company->email }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <!-- Map and Collection Centers Section (Vertical Flow) -->
        <section class="mt-32 border-t border-outline-variant/10 pt-20 space-y-20">
            
            <!-- Map Location (Full width/centered) -->
            <div class="max-w-4xl mx-auto space-y-6 text-center">
                <div class="space-y-2">
                    <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                        {{ __('messages.contact.map_location') }}
                    </span>
                    <h2 class="font-headline text-3xl font-bold uppercase tracking-wider text-center">
                        {{ app()->getLocale() == 'en' ? 'Dos Aguas Estate' : (app()->getLocale() == 'de' ? 'Hacienda Dos Aguas' : 'Hacienda Dos Aguas') }}
                    </h2>
                </div>
                
                @if($company->maps_iframe)
                    <div class="map-container w-full aspect-video border border-outline-variant/10 bg-[#161616] overflow-hidden">
                        {!! $company->maps_iframe !!}
                    </div>
                @else
                    <!-- Fallback map -->
                    <div class="w-full aspect-video border border-outline-variant/10 bg-[#161616] flex items-center justify-center text-outline-variant/30">
                        <span class="material-symbols-outlined text-4xl">map</span>
                    </div>
                @endif
            </div>

            <!-- Collection/Harvest Centers (Centros de Acopio - Centered below map) -->
            <div class="space-y-12">
                <div class="max-w-3xl mx-auto text-center space-y-4">
                    <span class="font-label-caps text-xs text-primary tracking-[0.2em] uppercase block font-bold">
                        {{ __('messages.contact.supply_network') }}
                    </span>
                    <h2 class="font-headline text-3xl font-bold uppercase tracking-wider text-center">
                        {{ __('messages.contact.collection_centers') }}
                    </h2>
                    <p class="text-xs text-on-surface-variant font-body max-w-lg mx-auto text-center">
                        {{ app()->getLocale() == 'en' 
                            ? 'Our reception sites for native fine aroma cacao directly from local farmers.' 
                            : (app()->getLocale() == 'de' 
                                ? 'Unsere Annahmestellen für edlen nativen Aromakakao direkt von den Landwirten der Region.' 
                                : 'Nuestras sedes de recepción de cacao nativo fino de aroma directamente de los agricultores de la zona.') }}
                    </p>
                </div>
                
                <!-- Centered grid/flex layout for cards -->
                <div class="flex flex-wrap justify-center gap-8 max-w-5xl mx-auto font-body">
                    <!-- Center 1 -->
                    <div class="p-6 bg-[#161616] border border-outline-variant/10 space-y-4 w-full md:w-[calc(50%-1rem)] max-w-sm text-left">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-2xl">warehouse</span>
                            <h4 class="font-headline text-base font-bold text-on-surface">Acopio Central Satipo</h4>
                        </div>
                        <div class="space-y-2 text-xs text-on-surface-variant">
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ __('messages.footer.address') }}:</span> Jr. Francisco Irazola 450, Satipo, Junín</p>
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ app()->getLocale() == 'en' ? 'Contact' : (app()->getLocale() == 'de' ? 'Ansprechpartner' : 'Contacto') }}:</span> Ing. Carlos Mendoza (+51 987 654 321)</p>
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ app()->getLocale() == 'en' ? 'Hours' : (app()->getLocale() == 'de' ? 'Öffnungszeiten' : 'Horario') }}:</span> {{ app()->getLocale() == 'en' ? 'Mon - Sat' : (app()->getLocale() == 'de' ? 'Mo - Sa' : 'Lun - Sáb') }}: 7:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                    
                    <!-- Center 2 -->
                    <div class="p-6 bg-[#161616] border border-outline-variant/10 space-y-4 w-full md:w-[calc(50%-1rem)] max-w-sm text-left">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-2xl">warehouse</span>
                            <h4 class="font-headline text-base font-bold text-on-surface">Acopio Pichanaki</h4>
                        </div>
                        <div class="space-y-2 text-xs text-on-surface-variant">
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ __('messages.footer.address') }}:</span> Av. Marginal Sur Km 1.5, Pichanaki, Junín</p>
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ app()->getLocale() == 'en' ? 'Contact' : (app()->getLocale() == 'de' ? 'Ansprechpartner' : 'Contacto') }}:</span> Sra. Elena Torres (+51 987 111 222)</p>
                            <p class="flex gap-2"><span class="font-semibold text-on-surface">{{ app()->getLocale() == 'en' ? 'Hours' : (app()->getLocale() == 'de' ? 'Öffnungszeiten' : 'Horario') }}:</span> {{ app()->getLocale() == 'en' ? 'Mon - Sat' : (app()->getLocale() == 'de' ? 'Mo - Sa' : 'Lun - Sáb') }}: 8:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

@endsection
