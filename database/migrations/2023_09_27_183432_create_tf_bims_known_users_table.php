<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfBimsKnownUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_bims_known_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');

            if(Schema::hasTable('tf_bi_portal_beneficiaries')) {
                $table->foreignUuid('beneficiary_id')->nullable()->references('id')->on('tf_bi_portal_beneficiaries');
            } elseif (Schema::hasTable('tf_ajls_beneficiaries')) {
                $table->foreignUuid('beneficiary_id')->nullable()->references('id')->on('tf_ajls_beneficiaries');
            } else {
                $table->foreignUuid('beneficiary_id')->nullable();
            }

            $table->string('bims_db_row_id')->nullable();
            $table->string('bims_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('type')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('photo')->nullable();
            $table->text('institution_meta_data')->nullable();
            $table->boolean('is_verification_mail_sent')->default(false);
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
        Schema::drop('tf_bims_known_users');
    }
}
