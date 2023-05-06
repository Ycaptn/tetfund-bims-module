<?php

namespace TETFund\BIMSOnboarding\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class BIMSOnboardingEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        // OrganizationCreatedEvent::class => [
        //     OrganizationCreatedListener::class,
        // ]
    ];

    public function boot()
    {
        parent::boot();
    }
}