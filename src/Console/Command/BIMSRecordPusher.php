<?php

namespace TETFund\BIMSOnboarding\Console\Command;

use Illuminate\Console\Command;
use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Jobs\PushRecordToBIMS ;
use App\Models\Beneficiary;

class BIMSRecordPusher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bims:push-record-to-bims';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push record to bims';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $unpushed_bims_records  = BIMSRecord::where('user_status', '<>', 'bims-active')
                        ->where('is_verified', true)->get()->count();

        if($unpushed_bims_records <=0){
            $this->info("No unpushed verified record");
            return 1;
        }

        $beneficiaries = Beneficiary::all();

        $this->line("Pushing records of {$beneficiaries->count()} beneficiaries  to bims");

        foreach($beneficiaries as $beneficiary)
        {
            $bims_records = $beneficiary->bimsRecords()
            ->where('user_status', '<>', 'bims-active')
            ->where('is_verified', true)->get();

           
            if($bims_records->count() <=0);
            continue;

            $this->line("Pushing {$beneficiary->short_name} {$bims_records->count()} records to BIMS");
            $counter = 1;
            foreach ($bims_records as $bim_record) {
                
                $this->warn("Pushing {$bim_record->email_verified}, {$counter} of {$bims_records->count()} record to BIMS");
                PushRecordToBIMS::dispatchNow($bim_record);
                $this->info("Pushed {$bim_record->email_verified} to BIMS");

                if($counter==100)
                break;
                $counter++;
            }

            $this->ifno("Pushed {$beneficiary->short_name}{$bims_records->count()} records to BIMS");

            $counter=1;

        }
            
        $this->info("Pushed {$beneficiaries->count()} beneficiaries records to bims");
        return 1;
    }
}
