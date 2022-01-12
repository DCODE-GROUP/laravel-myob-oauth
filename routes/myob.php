<?php

use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobAuthController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobCallbackController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\UpdateTenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', MyobController::class)->name('index');
Route::get('/auth', MyobAuthController::class)->name('auth');
Route::get('/callback', MyobCallbackController::class)->name('callback');
Route::post('/tenant/{id}', UpdateTenantController::class)->name('tenant.update');
