<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;      // 追記
use App\Http\Controllers\MicropostsController; // 追記
use App\Http\Controllers\UserFollowController;  // 追記
use App\Http\Controllers\FavoritesController;  // 追記

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

// Route::get('/', function () {
//     return view('welcome');
// });
// 
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [MicropostsController::class, 'index']);

Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {                                            // 追記
    Route::group(['prefix' => 'users/{id}'], function () {                                          // 追記
        Route::post('follow', [UserFollowController::class, 'store'])->name('user.follow');         // 追記
        Route::delete('unfollow', [UserFollowController::class, 'destroy'])->name('user.unfollow'); // 追記
        Route::get('followings', [UsersController::class, 'followings'])->name('users.followings'); // 追記
        Route::get('followers', [UsersController::class, 'followers'])->name('users.followers');    // 追記
        Route::get('favorites', [UsersController::class, 'favorites'])->name('users.favorites');            // 追加
    });                                                                                             // 追記

    Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);              // 追記
    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]); // 追記
    
    Route::group(['prefix' => 'microposts/{id}'], function () {                                             // 追加
        Route::post('favorites', [FavoritesController::class, 'store'])->name('favorites.favorite');            // 追加
        Route::delete('unfavorite', [FavoritesController::class, 'destroy'])->name('favorites.unfavorite');     // 追加
    });                                                                                                     // 追加
});                                                                                               // 追記