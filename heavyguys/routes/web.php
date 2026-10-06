<?php

use App\Http\Controllers\Admin\AdminGames;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/games', [\App\Http\Controllers\Games::class, 'index'])->name('games');
Route::get('/games/{id}', [\App\Http\Controllers\Games::class, 'show'])->name('game.show');
Route::get('/games/{id}/download', [\App\Http\Controllers\Games::class, 'download'])->middleware('auth')->name('game.download');

Route::get('/blog', [App\Http\Controllers\Blog::class, 'index'])->name('blog');
Route::get('/blog/{id}', [App\Http\Controllers\Blog::class, 'show'])->name('blog.show');
Route::delete('/blog/{id}', [App\Http\Controllers\Blog::class, 'delete'])->middleware('auth')->name('blog.delete');

Route::post('/blog/{id}/reaction', [App\Http\Controllers\Reaction::class, 'store'])->name('reaction.store');
Route::delete('/reaction/{id}', [App\Http\Controllers\Reaction::class, 'destroy'])->middleware('auth')->name('reaction.delete');

Route::get('/about', function () {return view('about');})->name('about');

Route::get('/contact', function () {return view('contact');})->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactMessage::class, '__invoke'])->name('contact.store');

Route::get('/post', [App\Http\Controllers\Post::class, 'create'])->name('post.create');
Route::post('/post', [App\Http\Controllers\Post::class, 'store'])->name('post.store');

Route::get('/shopping-cart', [App\Http\Controllers\ShoppingCart::class, 'index'])->name('shopping-cart');
Route::post('/cart/add/{gameId}', [App\Http\Controllers\ShoppingCart::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{gameId}', [App\Http\Controllers\ShoppingCart::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update/{gameId}', [App\Http\Controllers\ShoppingCart::class, 'updateQuantity'])->name('cart.update');
Route::post('/checkout', [\App\Http\Controllers\ShoppingCart::class, 'checkout'])->middleware('auth')->name('checkout');

Route::get('/register', function () {return view('auth.register');})->name('register');
Route::post('/register', [App\Http\Controllers\Auth\Register::class, '__invoke'])->middleware('guest')->name('register.store');

Route::get('/login', function () {return view('auth.login');})->name('login');
Route::post('/login', [App\Http\Controllers\Auth\Login::class, '__invoke'])->middleware('guest')->name('login.store');

Route::post('/logout', [App\Http\Controllers\Auth\Logout::class, '__invoke'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.panel'); })->name('dashboard');
    Route::resource('games', \App\Http\Controllers\Admin\AdminGames::class);
});

