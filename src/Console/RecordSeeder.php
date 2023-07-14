<?php

namespace TETFund\BIMSOnboarding\Console;

use PDF;
use File;
use Flash;
use Session;
use Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

use Illuminate\Console\Command;

use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Hasob\FoundationCore\Models\Organization;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class RecordSeeder extends Command
{
    protected $signature = 'bims:bi-record-seeder {org_id} {max_per_bi} {record_type}';
    protected $description = 'Seeds all BI BIMS records with sample test data';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
    
        $org_id = $this->argument('org_id');
        $max_per_bi = $this->argument('max_per_bi');
        $record_type = $this->argument('record_type');

        $all_beneficiaries = Beneficiary::all();


        foreach($all_beneficiaries as $beneficiary){
            $beneficiary_id = $beneficiary->id;
            $beneficiary_count = BIMSRecord::where('beneficiary_id', $beneficiary_id)->count();


            if ($org_id != null && Organization::find($beneficiary->organization_id) != null){
                $org_id = $beneficiary->organization_id;
            }
    
            if ($org_id == null){
                echo "Organization not found.\n";
                next;
            }


            echo "Running BIMS seeder for {$beneficiary->full_name} with {$beneficiary_count} BIMS records already. \n";
            if ($beneficiary_count > 100){
                echo "Skipping {$beneficiary->full_name} \n";
            }

            $num_records_to_add = $max_per_bi + rand(2,26);

            try{

                for($rec_counter=0;$rec_counter<$num_records_to_add;$rec_counter++){    
                    $bims_record =  BIMSRecord::create([
                        'organization_id'=>$org_id,
                        'beneficiary_id'=>$beneficiary->id,
                        'first_name_imported'=>"Seed",
                        'middle_name_imported'=>"M",
                        'last_name_imported'=>"Samples",
                        'phone_imported'=>"0708312311{$rec_counter}",
                        'email_imported'=>"{$record_type}-{$rec_counter}@seed-record.com",
                        'staff_number_imported' => "ST{$rec_counter}",
                        'matric_number_imported' => "STU{$rec_counter}",
                        'user_status' => 'new-import',
                        'user_type' => $record_type,
                    ]);
                    //$created_records_counter++;
                    //echo "{$created_records_counter} Record added for {$last_name}, {$first_name} - {$email} \n";
                }

                echo "Seeder Records added for {$beneficiary->full_name} => {$num_records_to_add} \n";

            } catch (\Throwable $th) {
                \Log::error($th);
            }

            echo "Done Uploading BIMS records \n";
        }

        return 1;
    }

}
