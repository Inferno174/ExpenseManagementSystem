<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::GET('/',function(){
    return redirect()->route('login');
});

Auth::routes();



Route::middleware(['islogin'])->group(function () {
    // Expenses
    Route::GET('home', [UserController::class, 'Index']);
    Route::POST('home', [UserController::class, 'Index']);
    Route::POST('add/expense/submit', [UserController::class, 'Store']);
    Route::GET('add/expense/view/{id}', [UserController::class, 'View']);
    Route::POST('check/expense',[UserController::class,'CheckRemaining'])->name('check.remaining');

    // Dashboard components
    Route::GET('bar-chart',[UserController::class,'BarChart'])->name('bar.chart');
    Route::GET('line-chart',[UserController::class,'LineChart'])->name('line.chart');

    // Expense Master
    Route::GET('expense-category/list', [CategoryController::class, 'Index']);
    Route::POST('expense-category/list', [CategoryController::class, 'Index']);
    Route::POST('expense-category/add/submit', [CategoryController::class, 'Store']);
    Route::GET('expense-category/edit/{id}', [CategoryController::class, 'Edit']);
    Route::GET('expense-category/edit/submit', [CategoryController::class, 'Update']);
    Route::GET('expense-category/view/{id}', [CategoryController::class, 'View']);
    Route::GET('expense-category/delete/{id}', [CategoryController::class, 'Delete']);
});
