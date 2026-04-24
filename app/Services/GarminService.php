<?php

namespace App\Services;

use Alexoid\GarminConnect\GarminConnect;
use Alexoid\GarminConnect\TokenStore\LaravelCacheTokenStore;

class GarminService
{
    private GarminConnect $garmin;

    public function __construct()
    {
        $user = auth()->guard()->user();
        // INFO: This "env" props are just a temporary approach to test the Garmin API. The modal is needed on the Frontend Side to ask the user for his Garmin credentials, just wrote it here to don't forget ;p
        $this->garmin = new GarminConnect(
            email: env('GARMIN_EMAIL'),
            password: env('GARMIN_PASSWORD'),
            tokenStore: new LaravelCacheTokenStore(prefix: "garmin_tokens_{$user->id}_")
        );
        $this->garmin->login();
    }
}
