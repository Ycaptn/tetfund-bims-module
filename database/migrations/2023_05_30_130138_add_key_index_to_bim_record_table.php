<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyIndexToBimRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tf_bims_record', function (Blueprint $table) {
            //
        });

        //Any record stored in the table needs to be keyed on the model_name, id, and key values for speed and optimization.
        // DB::update("ALTER TABLE `tf_bims_record` ADD INDEX `tf_bims_record_import_key` (`matric_number_imported`, `phone_imported`, `email_imported`, `staff_number_imported`);");
        // DB::update("ALTER TABLE `tf_bims_record` ADD INDEX `tf_bims_record_verified_key` (`staff_number_verified`, `matric_number_verified`, `email_verified`, `phone_verified`);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tf_bims_record', function (Blueprint $table) {
            //
        });
    }
}
