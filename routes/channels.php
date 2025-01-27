<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});
