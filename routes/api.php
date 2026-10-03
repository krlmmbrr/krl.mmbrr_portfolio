<?php

use App\Http\Controllers\PortfolioChatController;
use Illuminate\Support\Facades\Route;

Route::post('/portfolio-chat', [PortfolioChatController::class, 'store'])->middleware('throttle:portfolio-chat');
