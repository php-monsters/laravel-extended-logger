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
        $this->app->singleton(env('XLOG_TRACK_ID_KEY', 'xTrackId'), function ($app) {
            return substr(0, 10, sha1(uniqid('xTrackId')));
        });

        $this->app->singleton('XLog', function ($app) {
            return new \Tartan\Log\XLog($app);
        });
    }
}
