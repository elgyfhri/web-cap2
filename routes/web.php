<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AmbassadorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/ambassador', [PublicController::class, 'ambassadors'])->name('ambassadors.index');
Route::get('/ambassador/cluster/{cluster}', [PublicController::class, 'cluster'])->name('ambassadors.cluster');
Route::get('/ambassador/qr', [PublicController::class, 'qrAll'])->name('ambassadors.qr');
Route::get('/ambassador/{ambassador:slug}/qr', [PublicController::class, 'qr'])->name('ambassadors.qr.single');
Route::get('/ambassador/{ambassador:slug}', [PublicController::class, 'ambassador'])->name('ambassadors.show');
Route::get('/artikel', [PublicController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{article:slug}', [PublicController::class, 'article'])->name('articles.show');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/ambassadors', [AdminController::class, 'ambassadors'])->name('ambassadors.index');
    Route::get('/ambassadors/create', [AmbassadorController::class, 'create'])->name('ambassadors.create');
    Route::post('/ambassadors', [AmbassadorController::class, 'store'])->name('ambassadors.store');
    Route::get('/ambassadors/{ambassador}/edit', [AmbassadorController::class, 'edit'])->name('ambassadors.edit');
    Route::put('/ambassadors/{ambassador}', [AmbassadorController::class, 'update'])->name('ambassadors.update');
     Route::delete('/ambassadors/{ambassador}', [AmbassadorController::class, 'destroy'])->name('ambassadors.destroy');
     Route::delete('/bulk-delete/{type}', [AdminController::class, 'bulkDelete'])->name('bulk-delete');
    Route::get('/articles', [AdminController::class, 'articles'])->name('articles.index');
    Route::post('/articles/upload-image', [ArticleController::class, 'uploadImage'])->name('articles.upload-image');
     Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::delete('/projects/{project}/images', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::resource('projects', ProjectController::class)->except(['show']);
     Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
     Route::get('/galleries', [AdminController::class, 'galleries'])->name('galleries.index');
     Route::delete('/bulk-delete/{type}', [AdminController::class, 'bulkDelete'])->name('bulk-delete');
     Route::get('/ambassadors/qr', [AmbassadorController::class, 'qrAll'])->name('ambassadors.qr');
     Route::get('/ambassadors/{ambassador}/qr', [AmbassadorController::class, 'qr'])->name('ambassadors.qr.single');
});
