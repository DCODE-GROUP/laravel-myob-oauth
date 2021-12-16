<?php

use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobAuthController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobCallbackController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\MyobController;
use Dcodegroup\LaravelMyobOauth\Http\Controllers\SwitchMyobTenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', MyobController::class)->name('index');
Route::get('/auth', MyobAuthController::class)->name('auth');
Route::get('/callback', MyobCallbackController::class)->name('callback');
Route::post('/tenants/{tenantId}/', SwitchMyobTenantController::class)->name('tenant.update');
