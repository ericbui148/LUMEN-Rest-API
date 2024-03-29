<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $key = explode(' ',$request->header('Authorization'));
                $validAuthorization = (!empty($key[0]) && strtolower($key[0]) == 'bearer') && !empty($key[1]);
                if($validAuthorization) {
                    $user = User::where('access_token', $key[1])->first();
                    if(!empty($user)){
                        $request->request->add(['userid' => $user->id]);
                    }
                    return $user;
                }

            }
        });
    }
}
