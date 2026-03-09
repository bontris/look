<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

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

use App\Http\Controllers\ChatController;

use App\Http\Controllers\RingController;

use App\Http\Controllers\KindController;

use App\Http\Controllers\SaleController;

use App\Http\Controllers\HookController;

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

/** BASE ROUTES */


Route::match(['get', 'post'], '/dash/{task?}', [DashController::class, 'main'])
	 ->middleware('auth')
      ->where('task', 'home|load|dump')
	 ->name('dash');

Route::match(['get', 'post'], '/bell', [UserController::class, 'bell'])
	 ->middleware('auth')
	 ->name('bell');

Route::match(['get', 'post'], '/sign', [UserController::class, 'sign'])
     ->name('sign');

Route::post('/ping', [UserController::class, 'ping'])
     ->name('ping');

Route::post('/data', [UserController::class, 'data'])
     ->name('data');

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

Route::match(['get', 'post'], '/chat/{task?}/{item?}', [ChatController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('chat');

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
     ->where('task', 'find|load|pull|date|seek|bulk|make|risk|save|icon|drop|lock')
     ->where('item', '\w{32}|(\d{1,16})')
     ->where('part', '\w{32}|(\d{1,16})')
     ->where('type', 'note')
     ->name('makes');

Route::match(['get', 'post'], '/kinds/{task?}/{item?}', [KindController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'find|load|pull|make|save|drop|lock')
     ->where('item', '\w{32}|(\d{1,16})')
     ->name('kinds');

Route::match(['get', 'post'], '/sales/{task?}/{item?}', [SaleController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'find|load|pull|make|save|drop|lock')
     ->where('item', '\w{32}|(\d{1,16})')
     ->name('sales');

Route::match(['get', 'post'], '/pasts/{type}/{task?}/{item?}', [PastController::class, 'main'])
     ->middleware('auth')
     ->where('type', '1|2|3')
     ->where('task', 'load|post|drop|lock|dump')
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

Route::match(['get', 'post'], '/rings/{task?}/{item?}', [RingController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|drop|lock')
     ->where('item', '[\w]{32}')
     ->name('rings');

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
     ->where('task', 'load|pull|make|dump|face|pass|mail|wait|drop|lock')
     ->where('item', '[0-9]+')
     ->name('loads');

Route::match(['get', 'post'], '/files/{task?}/{item?}', [FileController::class, 'main'])
     ->where('task', 'load|open|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w\-\_]{16,64}')
     ->name('files');

Route::get('/snaps/{item}/{size?}', [FileController::class, 'snap'])
     ->where('size', 'thumb|small')
     ->where('item', '\w+')
     ->name('snaps');

Route::match(['get', 'post'], '/tests/{task?}/{item?}', [TestController::class, 'main'])
     ->middleware('auth')
     ->where('task', 'load|pull|make|save|face|pass|mail|wait|drop|lock')
     ->where('item', '[\w\-\_]{16,64}')
     ->name('tests');

Route::post('/hook/{firm}/{type}', 'HookController@main')
     ->where('firm', '\w+')
     ->where('type', '\w+')
     ->name('hook');

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