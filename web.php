<?php

use App\Http\Controllers\DepositController;

Route::post('/pay', [DepositController::class, 'store']);
