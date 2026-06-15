<?php

namespace App\Providers;

use App\Events\PostSaved;
use App\Listeners\PostSavedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostSaved::class => [
            PostSavedListener::class,
        ],
    ];
}
