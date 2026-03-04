<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ExpenseController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $colocation = $user->colocations()
                       ->wherePivot('left_at', null)
                       ->first();
    return view('dashboard', compact('colocation'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
    Route::post('/profile/leave', [ProfileController::class, 'leaveColocation'])->name('profile.leave');
    Route::post('/profile/cancel', [ProfileController::class, 'cancelColocation'])->name('profile.cancel');
    Route::get('/colocation/create', [ColocationController::class, 'create'])->name('colocation.create');
    Route::post('/colocation', [ColocationController::class, 'store'])->name('colocation.store');
    // Invitation routes
Route::get('/invitation/create', [InvitationController::class, 'create'])->name('invitation.create');
Route::post('/invitation', [InvitationController::class, 'store'])->name('invitation.store');

// Expense routes
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

Route::get('/colocation', [ColocationController::class, 'index'])->name('colocation.index');
Route::post('/colocation/invite', [InvitationController::class, 'store'])->name('colocation.invite');
Route::delete('/colocation/{colocation}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
});
// Invitation routes
Route::post('/colocation/invite', [InvitationController::class, 'store'])->name('colocation.invite');
Route::get('/invitation/accept/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');
Route::get('/invitation/refuse/{token}', [InvitationController::class, 'refuse'])->name('invitation.refuse');

require __DIR__.'/auth.php';
