<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapParticipantRoutes();

        $this->mapLivewebinarRoutes();

        $this->mapStreamingRoutes();

        $this->mapWebRoutes();

        $this->mapStandManagerRoutes();

        $this->mapDeviceRoutes();

        $this->mapChatterRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapParticipantRoutes()
    {
        Route::prefix('participant-users')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/participant.php'));
    }

    protected function mapLivewebinarRoutes()
    {
        Route::prefix('livewebinar')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/livewebinar.php'));
    }

    protected function mapStreamingRoutes()
    {
        Route::prefix('streaming')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/streaming.php'));
    }

    protected function mapStandManagerRoutes()
    {
      Route::prefix('stands')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/stands.php'));
    }

    protected function mapDeviceRoutes()
    {
      Route::prefix('devices')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/devices.php'));
    }

    protected function mapChatterRoutes()
    {
        Route::prefix('chatter')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/chatter.php'));
    }
}
