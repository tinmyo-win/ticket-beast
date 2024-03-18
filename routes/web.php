<?php

use App\Http\Controllers\Backstage\ConcertMessagesController;
use App\Http\Controllers\Backstage\ConcertOrderExportController;
use App\Http\Controllers\Backstage\ConcertsController as BackstageConcertsController;
use App\Http\Controllers\Backstage\PublishedConcertOrdersController;
use App\Http\Controllers\Backstage\PublishedConcertsController;
use App\Http\Controllers\Backstage\StripeConnectController;
use App\Http\Controllers\ConcertOrderController;
use App\Http\Controllers\ConcertsController;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\ForceStripeAccount;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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


Route::group(['middleware' => 'auth', 'prefix' => 'backstage'], function () {

    Route::group(['middleware' => ForceStripeAccount::class], function() {
        Route::get('concerts/new', [BackstageConcertsController::class, 'create'])->name('backstage.concerts.new');
        Route::get('concerts/{id}/edit', [BackstageConcertsController::class, 'edit'])->name('backstage.concerts.edit');
        Route::get('concerts', [BackstageConcertsController::class, 'index'])->name('backstage.concerts.index');
        Route::post('concerts', [BackstageConcertsController::class, 'store'])->name('backstage.concerts.store');
        Route::patch('/concerts/{id}', [BackstageConcertsController::class, 'update'])->name('backstage.concerts.update');
        Route::post('published-concerts', [PublishedConcertsController::class, 'store'])->name('backstage.concerts.publish');

        Route::get('/published-concerts/{id}/orders', [PublishedConcertOrdersController::class, 'index'])->name('backstage.published-concert-orders.index');

        Route::get('/concerts/{id}/messages/new', [ConcertMessagesController::class, 'create'])->name('backstage.concert-messages.new');
        Route::post('/concerts/{id}/messages', [ConcertMessagesController::class, 'store'])->name('backstage.concert-messages.store');
    });

    Route::get('/stripe-connect/connect', [StripeConnectController::class, 'connect'])->name('backstage.stripe-connect.connect');
    Route::get('/stripe-connect/authorize', [StripeConnectController::class, 'authorizeRedirect'])->name('backstage.stripe-connect.authorize');
    Route::get('/stripe-connect/redirect', [StripeConnectController::class, 'redirect'])->name('backstage.stripe-connect.redirect');
});
// Route::get('/backstage/concerts/{concert}', [ConcertsController::class, 'show'])->name('concerts.show');

Route::get('/concerts/{concert}', [ConcertsController::class, 'show'])->name('concerts.show');
Route::post('concerts/{concert}/orders', [ConcertOrderController::class, 'store']);
Route::get('orders/{confirmationNumber}', [OrderController::class, 'show']);

Route::get('invitations/{code}', [InvitationsController::class, 'show'])->name('invitations.show');

Route::get('/', function () {
    // return 'Laravel';
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// require __DIR__.'/auth.php';

Route::get('/tailwind/typo', function () {
    return Inertia::render('TailwindTypoTest');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register/{invitation_code}', [RegisterController::class, 'register'])->name('promoters.register');

Route::get('backstage/concerts/{id}/orders/download', [ConcertOrderExportController::class, 'index'])->name('backstage.concert-orders.download');
