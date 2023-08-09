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
    protected $signature = 'bims:push-record-to-bims {delay_min=0.2} {no_bims_record_per_bnf=5}';

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
        $delay_min =  $this->argument('delay_min');
        $no_bims_record_per_bnf =  $this->argument('no_bims_record_per_bnf');
        if(BIMSRecord::where('user_status', '<>', 'bims-active')->get()->count() <=0){
            $this->info("No unpushed record");
            return 1;
        }

        $beneficiaries_bims_sync = Beneficiary::where('bims_tetfund_id', '<>', null)->get();
        $beneficiaries_bims_unsync = Beneficiary::select(
            'id',
            'short_name', 
            'full_name', 
            'official_email', 
            'official_website',
            'type', 
            'geo_zone',
            'bims_tetfund_id as bims_institution_id'
        )->where('bims_tetfund_id', null)->orderBy('short_name')->get();
        
        $file_path = public_path()."/beneficiaries_bims_unsync.csv";

        /**
         * write to file all list of beneficiaries unsync with bims 
         * else delete file if no beneficiaries unsync with bims 
         */
        if($beneficiaries_bims_unsync->count() >= 0 )
        {
            file_put_contents($file_path, "Sn; id; short_name; full_name; official_email; official_website; type; goe_zone; bims_institution_id".PHP_EOL);
            foreach($beneficiaries_bims_unsync as $key => $bnf_bim_unsyc){
                $sn = $key+1;
                file_put_contents($file_path, $sn.";".implode(';',$bnf_bim_unsyc->toArray()).PHP_EOL, FILE_APPEND );
            }
            $this->warn("{$beneficiaries_bims_unsync->count()} beneficiaries  (bims unsyncf) ");

        }else {
            if(file_exists($file_path)){
                // Delete the file
                unlink($file_path);
            } 
        }
        // $this->line("Pushing records of {$beneficiaries_bims_sync->count()} sync beneficiaries to bims");
        // $this->line('');
        foreach($beneficiaries_bims_sync as $beneficiary)
        {            
            $bims_records = $beneficiary->bimsRecords()
            ->where('user_status', '<>', 'bims-active')->inRandomOrder()->take($no_bims_record_per_bnf)->get();
            if($bims_records->count()==0){
                // $this->warn("No unpushed records for {$beneficiary->short_name}");
                continue;
            }

            if($bims_records->count()==0){
                // $this->warn("No unpushed records for {$beneficiary->short_name}");
                continue;
            }

            $counter = 1;
            $this->line("Pushing {$beneficiary->short_name} {$bims_records->count()} records to BIMS");
            foreach ($bims_records as $key => $bim_record) {
                // $this->warn("Pushing {$bim_record->email_imported}  {$counter} of {$bims_records->count()} {$beneficiary->short_name} record to BIMS");
                PushRecordToBIMS::dispatchNow($bim_record);

                if(is_null(session('push record to bims error'))){
                    // $this->info("Pushed {$bim_record->email_imported} of {$beneficiary->short_name} records to BIMS inst-{$beneficiary->bims_tetfund_id}");
                    $microseconds = $delay_min * 60 * 1000000;
                    usleep($microseconds); // delay push to avert too many request on bims
                    
                    if($counter >= $bims_records->count()){
                        break;
                    }
                }else{
                    // $this->error("Failed: {$bim_record->email_imported} index {$counter} of {$bims_records->count()} {$beneficiary->short_name} with bims-id {$beneficiary->bims_tetfund_id} record");
                    usleep(1000000); //  1 seconds delay 
                }
                $counter++;
            }

            if(!is_null(session('push record to bims error'))){
                session()->forget('push record to bims error');
                continue;
            }

        }

        return 1;
    }
}
