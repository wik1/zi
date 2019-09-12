<?php namespace App\Providers;

use App\Services\UsersService;
use App\User;
use App\Auth\CustomUserProvider;
use Illuminate\Support\ServiceProvider;

class CustomAuthProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('custom',function()
        {
            return new CustomUserProvider(new User, new UsersService());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

}