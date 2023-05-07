<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBimsRecordTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bims_record', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('beneficiary_id')->references('id')->on('tf_bi_portal_beneficiaries');
            $table->string('first_name_verified')->nullable();
            $table->string('middle_name_verified')->nullable();
            $table->string('last_name_verified')->nullable();
            $table->string('name_title_verified')->nullable();
            $table->string('name_suffix_verified')->nullable();
            $table->string('matric_number_verified')->nullable();
            $table->string('staff_number_verified')->nullable();
            $table->string('email_verified')->nullable();
            $table->string('phone_verified')->nullable();
            $table->string('phone_network_verified')->nullable();
            $table->string('bvn_verified')->nullable();
            $table->string('nin_verified')->nullable();
            $table->string('dob_verified')->nullable();
            $table->string('gender_verified')->nullable();
            $table->string('first_name_imported')->nullable();
            $table->string('middle_name_imported')->nullable();
            $table->string('last_name_imported')->nullable();
            $table->string('name_title_imported')->nullable();
            $table->string('name_suffix_imported')->nullable();
            $table->string('matric_number_imported')->nullable();
            $table->string('staff_number_imported')->nullable();
            $table->string('email_imported')->nullable();
            $table->string('phone_imported')->nullable();
            $table->string('phone_network_imported')->nullable();
            $table->string('bvn_imported')->nullable();
            $table->string('nin_imported')->nullable();
            $table->string('dob_imported')->nullable();
            $table->string('gender_imported')->nullable();
            $table->string('user_status');
            $table->string('user_type')->nullable();
            $table->integer('display_ordinal')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->text('admin_entered_record_issues', 2000)->nullable();
            $table->text('admin_entered_record_notes', 2000)->nullable();
            $table->text('verification_meta_data', 2000)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tf_bims_record');
    }
}
