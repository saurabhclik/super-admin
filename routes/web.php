<?php

use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Middleware\AuthenticateAccess;
use Illuminate\Support\Facades\Route;
Route::get('/', [AuthenticateController::class, 'login'])->name('login');
Route::post('/login', [AuthenticateController::class, 'doLogin'])->name('do.login');
Route::get('/logout', [AuthenticateController::class, 'logout'])->name('logout');

Route::middleware([AuthenticateAccess::class])->group(function () 
{
    Route::get('/software/manage', [SoftwareController::class, 'index'])->name('software.manage');
    Route::post('/software/store', [SoftwareController::class, 'store'])->name('software.store');
    Route::post('/software/{id}', [SoftwareController::class, 'update'])->name('software.update');
    Route::delete('/software/{id}', [SoftwareController::class, 'destroy'])->name('software.destroy');
});
Route::get('/clear-all', function () 
{
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize');
    return 'All caches cleared and optimized!';
});