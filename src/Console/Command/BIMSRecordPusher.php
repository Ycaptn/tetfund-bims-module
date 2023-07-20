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
        if(BIMSRecord::where('user_status', '<>', 'bims-active')->get()->count() <=0){
            $this->info("No unpushed record");
            return 1;
        }

        $beneficiaries = Beneficiary::all();

        $this->line("Pushing records by {$beneficiaries->count()} beneficiaries  to bims");

        foreach($beneficiaries as $beneficiary)
        {            
            
            $bims_records = $beneficiary->bimsRecords()
            ->where('user_status', '<>', 'bims-active')->get();
            if($bims_records->count()==0)
            continue;

            $this->line("Pushing {$beneficiary->short_name} {$bims_records->count()} records to BIMS");
            $counter = 1;
            foreach ($bims_records as $bim_record) {
                
                $this->warn("Pushing {$bim_record->email_imported}, {$counter} of {$bims_records->count()} {$beneficiary->short_name} record to BIMS");
                PushRecordToBIMS::dispatchNow($bim_record);
                
                $this->info("Pushed {$bim_record->email_imported} to BIMS");

                if($counter==50){
                    $this->line('');
                    $this->info("Pushed {$counter} {$beneficiary->short_name} records to BIMS");
                    break;

                }
                $counter++;
            }

        }

        return 1;
    }
}
