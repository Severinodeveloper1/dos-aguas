<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublicSubmissionController;
use Illuminate\Support\Facades\Route;

// Language Switcher
Route::get('/locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'es'])) {
        session()->put('locale', $lang);
    }
    return redirect()->back();
})->name('locale.switch');

// Public Pages
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/origen', [PublicController::class, 'origin'])->name('origin');
Route::get('/colecciones', [PublicController::class, 'collections'])->name('collections');
Route::get('/productos/{slug}', [PublicController::class, 'productDetail'])->name('product.detail');
Route::get('/nuestra-historia', [PublicController::class, 'about'])->name('about');
Route::get('/politicas', [PublicController::class, 'policies'])->name('policies');

Route::get('/contacto', [PublicController::class, 'contact'])->name('contact');
Route::post('/contacto', [PublicSubmissionController::class, 'submitContact'])
    ->middleware('throttle:3,1');

Route::get('/libro-de-reclamaciones', [PublicController::class, 'claimBook'])->name('claim-book');
Route::post('/libro-de-reclamaciones', [PublicSubmissionController::class, 'submitClaim'])
    ->middleware('throttle:5,1');

// Cart Routes
Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/agregar', [CartController::class, 'add'])->name('add');
    Route::post('/actualizar', [CartController::class, 'update'])->name('update');
    Route::post('/eliminar', [CartController::class, 'remove'])->name('remove');
});

// Checkout Routes
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/envio', [CheckoutController::class, 'shippingForm'])->name('shipping');
    Route::post('/envio', [CheckoutController::class, 'saveShipping'])->name('shipping.save');
    Route::get('/pago', [CheckoutController::class, 'paymentForm'])->name('payment');
    Route::post('/procesar', [CheckoutController::class, 'processOrder'])->name('process');
    Route::get('/confirmacion/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('confirmation');
});
