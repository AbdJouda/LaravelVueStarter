<?php

use App\Events\V1\RolesUpdatedEvent;
use Illuminate\Support\Facades\Route;

Route::view('/{any?}', 'welcome')
    ->where('any', '.*');


