<?php

namespace Tartan\Log;

use Illuminate\Support\ServiceProvider;

class XLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(env('XLOG_TRACK_ID_KEY', 'xTrackId'), function () {
            return substr(sha1(uniqid('xTrackId')), 0, 10);
        });
    }
}
