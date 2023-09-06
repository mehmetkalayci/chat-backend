<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return null;
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->prefix('chatbot')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::get('/create', [ChatbotController::class, 'create'])->name('chatbot.create');
    Route::post('/store', [ChatbotController::class, 'store'])->name('chatbot.store');
    Route::get('/{chatbot}', [ChatbotController::class, 'edit'])->name('chatbot.edit');
    Route::get('/{chatbot}/questions', [ChatbotController::class, 'questions'])->name('chatbot.questions');
    Route::put('/{chatbot}/questions', [ChatbotController::class, 'questionsUpdate'])->name('chatbot.questionsUpdate');
    Route::delete('/{chatbot}/questions', [ChatbotController::class, 'deleteQuestion'])->name('chatbot.deleteQuestion');
    Route::put('/{chatbot}', [ChatbotController::class, 'update'])->name('chatbot.update');
    Route::delete('/{chatbot}/delete', [ChatbotController::class, 'destroy'])->name('chatbot.delete');
});

Route::get('chatbot/{chatbot}/config.json', [ChatbotController::class, 'json'])->name('chatbot.json');


require __DIR__.'/auth.php';