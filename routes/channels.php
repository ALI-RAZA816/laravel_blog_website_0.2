<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-messages', fn ($user) => $user->role === 'admin');
