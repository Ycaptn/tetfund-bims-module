<?php

namespace TETFund\BIMSOnboarding\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class BIMSOnboardingEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        \TETFund\BIMSOnboarding\Events\BIMSRecordCreated::class => [
            \TETFund\BIMSOnboarding\Listeners\BIMSRecordCreatedListener::class,
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}