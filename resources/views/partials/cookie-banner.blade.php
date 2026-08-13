<div x-data="{
        showBanner: false,
        init() {
            if (!localStorage.getItem('dosaguas_cookie_consent')) {
                setTimeout(() => { this.showBanner = true; }, 1200);
            }
        },
        acceptCookies() {
            localStorage.setItem('dosaguas_cookie_consent', 'accepted');
            this.showBanner = false;
        }
    }"
    x-show="showBanner"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    x-cloak
    class="fixed bottom-6 right-6 left-6 md:left-auto md:max-w-md z-50 bg-[#161616]/95 backdrop-blur-md border border-outline-variant/20 p-6 shadow-2xl space-y-4 rounded-sm text-xs font-body text-on-surface">

    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-primary text-xl flex-shrink-0 mt-0.5">cookie</span>
        <div class="space-y-1.5 flex-grow">
            <h4 class="font-headline font-bold text-sm tracking-wide text-on-surface">
                {{ app()->getLocale() == 'es' ? 'Uso de Cookies' : (app()->getLocale() == 'de' ? 'Verwendung von Cookies' : 'Cookie Usage') }}
            </h4>
            <p class="text-on-surface-variant text-[11px] leading-relaxed">
                {{ app()->getLocale() == 'es'
                    ? 'Utilizamos cookies técnicas necesarias para recordar la bolsa de compras y tu preferencia de idioma. Al continuar navegando, aceptas su uso.'
                    : (app()->getLocale() == 'de'
                        ? 'Wir verwenden notwendige technische Cookies, um Ihren Warenkorb und Ihre Sprachpräferenz zu speichern. Durch die Weiternutzung stimmen Sie dem zu.'
                        : 'We use essential technical cookies to remember your shopping cart and language preference. By continuing to browse, you agree to their use.') }}
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between gap-4 pt-2 border-t border-outline-variant/10">
        <a href="{{ route('policies') }}"
           class="font-label-caps text-[9px] tracking-widest text-outline hover:text-primary transition-colors duration-300 uppercase font-bold">
            {{ app()->getLocale() == 'es' ? 'Más Información' : (app()->getLocale() == 'de' ? 'Mehr erfahren' : 'Learn More') }}
        </a>
        <button @click="acceptCookies()"
                type="button"
                class="bg-primary text-on-primary px-5 py-2 font-label-caps text-[10px] tracking-widest uppercase font-bold hover:bg-secondary hover:text-on-secondary transition-all duration-300">
            {{ app()->getLocale() == 'es' ? 'Entendido / Aceptar' : (app()->getLocale() == 'de' ? 'Akzeptieren' : 'Accept All') }}
        </button>
    </div>
</div>
