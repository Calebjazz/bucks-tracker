<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ExpenseFlow — frontend-only routes
|--------------------------------------------------------------------------
|
| Every route uses Route::view(), which renders a Blade view directly
| without a controller. When you build the backend, swap each one for a
| controller, e.g. Route::get('/transactions', [TransactionController::class, 'index']).
|
*/

Route::view('/', 'welcome')->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/transactions', 'transactions.index')->name('transactions.index');
Route::view('/income', 'transactions.index', ['type' => 'income'])->name('income');
Route::view('/expenses', 'transactions.index', ['type' => 'expense'])->name('expenses');
Route::view('/categories', 'categories.index')->name('categories.index');
Route::view('/budgets', 'budgets.index')->name('budgets.index');
Route::view('/reports', 'reports.index')->name('reports.index');
Route::view('/settings', 'settings.index')->name('settings');
