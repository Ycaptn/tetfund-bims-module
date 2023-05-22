<?php

namespace TETFund\BIMSOnboarding\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class BIMSOnboardingEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        \TETFund\BIMSOnboarding\Events\BIMSRecordCreated::class => [
            \TETFund\BIMSOnboarding\Listeners\BIMSRecordCreatedListener::class,
        ],

        \TETFund\BIMSOnboarding\Events\BIMSRecordUpdated::class => [
            \TETFund\BIMSOnboarding\Listeners\BIMSRecordUpdatedListener::class,
        ],

        \TETFund\BIMSOnboarding\Events\BIMSRecordDeleted::class => [
            \TETFund\BIMSOnboarding\Listeners\BIMSRecordDeletedListener::class,
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}