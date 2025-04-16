<?php

use App\Http\Controllers\WorkaLoveAuthController;

Route::post('/workaloev-auth', [WorkaLoveAuthController::class, 'authenticate']);