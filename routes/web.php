<?php

use App\Http\Controllers\VisitorRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
    Route::get('/', [VisitorRegistrationController::class, 'phone'])->name('phone');
    Route::post('/cek-hp', [VisitorRegistrationController::class, 'checkPhone'])->name('check-phone');

    Route::get('/daftar-baru', [VisitorRegistrationController::class, 'newVisitorForm'])->name('new-visitor');
    Route::post('/daftar-baru', [VisitorRegistrationController::class, 'storeNewVisitor'])->name('store-new-visitor');

    Route::get('/konfirmasi', [VisitorRegistrationController::class, 'confirmForm'])->name('confirm');
    Route::post('/konfirmasi', [VisitorRegistrationController::class, 'storeConfirm'])->name('store-confirm');

    Route::get('/video', [VisitorRegistrationController::class, 'video'])->name('video');
    Route::post('/submit', [VisitorRegistrationController::class, 'submit'])->name('submit');

    Route::get('/checkout', [VisitorRegistrationController::class, 'checkoutForm'])->name('checkout-confirm');
    Route::post('/checkout', [VisitorRegistrationController::class, 'storeCheckout'])->name('store-checkout');

    Route::get('/feedback', [VisitorRegistrationController::class, 'feedbackForm'])->name('feedback');
    Route::post('/feedback', [VisitorRegistrationController::class, 'storeFeedback'])->name('store-feedback');
    Route::post('/feedback/lewati', [VisitorRegistrationController::class, 'skipFeedback'])->name('skip-feedback');

    Route::get('/terima-kasih', [VisitorRegistrationController::class, 'thanks'])->name('thanks');
    Route::get('/status', [VisitorRegistrationController::class, 'status'])->name('status');
});