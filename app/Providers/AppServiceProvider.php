<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\CompanyInfo;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Fetch company info or create a fallback object if none exists yet
            $company = CompanyInfo::first() ?? new CompanyInfo([
                'name' => 'Dos Aguas',
                'phone' => '+51 987 654 321',
                'whatsapp_phone' => '51987654321',
                'email' => 'info@dosaguas.pe',
                'address' => 'Sector Curimaná, Ucayali - Perú',
                'maps_iframe' => '',
                'facebook_url' => 'https://facebook.com',
                'instagram_url' => 'https://instagram.com',
                'tiktok_url' => 'https://tiktok.com',
                'youtube_url' => 'https://youtube.com',
                'about_history' => 'Dos Aguas nace en el corazón de Ucayali...',
                'about_mission' => 'Nuestra misión es ofrecer chocolate fino de aroma...',
                'about_vision' => 'Nuestra visión es ser referentes mundiales de chocolate de origen...',
                'about_values' => 'Calidad, Sostenibilidad, Tradición',
            ]);

            // Cart item count (sum of quantities of all variants in session cart)
            $cart = session()->get('cart', []);
            $cartCount = is_array($cart) ? array_sum($cart) : 0;

            // Active categories for the navbar
            $activeCategories = Category::where('is_active', true)
                ->orderBy('order')
                ->get();

            $view->with(compact('company', 'cartCount', 'activeCategories'));
        });
    }
}
