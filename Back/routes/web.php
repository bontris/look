<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\MyWalletController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\ActiveBidsController;
use App\Http\Controllers\AllSavedController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FlorgotPasswordController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\MyCollectionController;
use App\Http\Controllers\MarketPlaceDetailsController;
use App\Http\Controllers\ProductUploadController;

use App\Http\Controllers\DashController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\CardController;

use App\Http\Controllers\FirmController;

use App\Http\Controllers\HandController;

use App\Http\Controllers\LeadController;

use App\Http\Controllers\TermController;

use App\Http\Controllers\MakeController;

use App\Http\Controllers\PastController;

use App\Http\Controllers\SignController;

use App\Http\Controllers\TaskController;

use App\Http\Controllers\LoadController;

use App\Http\Controllers\FileController;

use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/',[DashboardController::class,'index']);

Route::get('/load-login',[AdminLoginController::class,'index']);
Route::post('/admin-login',[AdminLoginController::class,'login']);

Route::get('/load-register',[AdminRegisterController::class,'index']);
Route::post('/register',[AdminRegisterController::class,'register']);

Route::get('/history',[HistoryController::class,'index']);

Route::get('/my-wallet',[MyWalletController::class,'index']);

Route::get('/sell',[SellController::class,'index']);

Route::get('/market-place',[MarketPlaceController::class,'index']);

Route::get('/active-bids',[ActiveBidsController::class,'index']);

Route::get('/all-saved',[AllSavedController::class,'index']);

Route::get('/my-profile',[profileController::class,'index']);

Route::get('/setting',[SettingController::class,'index']);

Route::get('/notification',[NotificationController::class,'index']);

Route::get('/message',[MessageController::class,'index']);

Route::get('/forgot-password',[FlorgotPasswordController::class,'index']);
Route::post('/find-password',[FlorgotPasswordController::class,'findPassword']);

Route::get('/verify',[VerifyController::class,'index']);
Route::post('/verification',[VerifyController::class,'verification']);

Route::get('/my-collection',[MyCollectionController::class,'index']);

Route::get('/market-place-details',[MarketPlaceDetailsController::class,'index']);

Route::get('/upload-product',[ProductUploadController::class,'index']);

Route::post('/change-password',[SettingController::class,'changePassword']);

Route::match(['get', 'post'], '/dash/{task?}', [DashController::class, 'main'])
	 ->middleware('auth')
      ->where('task', 'home|load|dump')
	 ->name('dash');

Route::match(['get', 'post'], '/sign', [UserController::class, 'sign'])
     ->name('sign');

Route::post('/ping', [UserController::class, 'ping'])
     ->name('ping');

Route::post('/exit', [UserController::class, 'exit'])
     ->name('exit');

Route::match(['get', 'post'], '/lost', [UserController::class, 'lost'])
     ->name('lost');

Route::match(['get', 'post'], '/lock/{hash}', [UserController::class, 'lock'])
     ->where('hash', '\w{32}')
     ->name('lock');

Route::match(['get', 'post'], '/pass/{hash}/{pass}', [UserController::class, 'pass'])
     ->where('hash', '[\w]{32}')
     ->where('pass', '[\w]{32}')
     ->name('pass');

Route::match(['get', 'post'], '/users/{task?}/{item?}', [UserController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('users');

Route::match(['get', 'post'], '/firms/{task?}/{item?}', [FirmController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|team|data|make|save|date|dump|icon|drop|lock')
     ->where('item', '\w{32}|\d+')
     ->name('firms');

Route::match(['get', 'post'], '/terms/{task?}/{item?}', [TermController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'find|load|pull|bulk|make|save|icon|drop|lock')
     ->where('item', '\w{32}|(\d{1,16})')
     ->name('terms');

Route::match(['get', 'post'], '/makes/{task?}/{item?}/{type?}/{part?}', [MakeController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'find|load|pull|date|seek|bulk|make|save|icon|drop|lock')
     ->where('item', '\w{32}|(\d{1,16})')
     ->where('part', '\w{32}|(\d{1,16})')
     ->where('type', 'note')
     ->name('makes');

Route::match(['get', 'post'], '/pasts/{type}/{task?}/{item?}', [PastController::class, 'main'])
     ->middleware('auth')
     ->where('type', '1|2|3')
     ->where('task', 'load|post|drop|lock')
     ->where('item', '\w{32}|\d+')
     ->name('pasts');

Route::match(['get', 'post'], '/signs/{type}/{task?}/{item?}', [SignController::class, 'main'])
     ->middleware('auth')
     ->where('type', '1')
     ->where('task', 'load|post|drop|lock')
     ->where('item', '\w{32}|\d+')
     ->name('signs');

Route::match(['get', 'post'], '/cards/{task?}/{item?}', [CardController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|snap|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('cards');

Route::match(['get', 'post'], '/hands/{task?}/{item?}', [HandController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|icon|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('hands');

Route::match(['get', 'post'], '/leads/{task?}/{item?}', [LeadController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|icon|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('leads');

Route::match(['get', 'post'], '/tasks/{task?}/{item?}', [TaskController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'find|load|pull|make|save|post|drop|lock')
     ->where('item', '[0-9]+')
     ->name('tasks');

Route::match(['get', 'post'], '/loads/{task?}/{item?}', [LoadController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('loads');

Route::match(['get', 'post'], '/files/{task?}/{item?}', [FileController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|open|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w\-\_]{16,64}')
     ->name('files');

Route::match(['get', 'post'], '/documentos/{task?}/{item?}', [FileController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w\-\_]{16,64}')
     ->name('files');

Route::match(['get', 'post'], '/tests/{task?}/{item?}', [TestController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w\-\_]{16,64}')
     ->name('tests');

Route::match(['get', 'post'], '/diagnostico', [TestController::class, 'form'])
     ->middleware('auth')
     ->name('test');

Route::get('/', function (Request $request) {
    if (Auth::guest()) {
        return redirect()->route('sign');
    } else {
        return redirect()->route('dash');
    }
})->name('home');