<?php

namespace TETFund\BIMSOnboarding\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

class RemoveRecordFromBIMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bIMSRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BIMSRecord $bIMSRecord)
    {
        $this->bIMSRecord = $bIMSRecord;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Awaiting API BIMS endpoint for bim record remove
         */
        
        return;

    }
}
