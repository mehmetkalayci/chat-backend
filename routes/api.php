<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ChatbotController;
use App\Http\Middleware\JwtMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('chatbot')->group(function () {

    // Chatbot ayarlarının yüklenmesi
    Route::get('/{chatbotId}/settings', [ChatbotController::class, 'getSettings']);


    // Kullanıcı ilk kullanıcı mı değil mi ayırt edilerek eski mesajların yüklenmesi
    Route::post('load-messages', [ChatbotController::class, 'loadMessages']);

    // Kullanıcıların soru sorması
    Route::post('ask', [ChatbotController::class, 'askQuestion']);

    // Eski mesajların silinmesi
    Route::delete('delete-history', [ChatbotController::class, 'deleteHistory']);

    // Ödeme yapılması
    Route::post('payment', [ChatbotController::class, 'makePayment']);
});
