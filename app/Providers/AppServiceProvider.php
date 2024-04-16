<?php

namespace App\Providers;

use App\Jobs\SendConversationMessage;
use App\Jobs\SendMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Event::listen(
            [
                SendMessage::class,
                SendConversationMessage::class
            ]
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
