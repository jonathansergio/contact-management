<?php

use App\Http\Controllers\Api\ContactApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('contacts', ContactApiController::class);
