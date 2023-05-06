<?php

namespace Database\Factories;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Hasob\FoundationCore\Models\Organization;

class BIMSRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BIMSRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organization_id' => $this->faker->word,
        'beneficiary_id' => $this->faker->word,
        'first_name_verified' => $this->faker->word,
        'middle_name_verified' => $this->faker->word,
        'last_name_verified' => $this->faker->word,
        'name_title_verified' => $this->faker->word,
        'name_suffix_verified' => $this->faker->word,
        'matric_number_verified' => $this->faker->word,
        'staff_number_verified' => $this->faker->word,
        'email_verified' => $this->faker->word,
        'phone_verified' => $this->faker->word,
        'phone_network_verified' => $this->faker->word,
        'bvn_verified' => $this->faker->word,
        'nin_verified' => $this->faker->word,
        'dob_verified' => $this->faker->word,
        'gender_verified' => $this->faker->word,
        'first_name_imported' => $this->faker->word,
        'middle_name_imported' => $this->faker->word,
        'last_name_imported' => $this->faker->word,
        'name_title_imported' => $this->faker->word,
        'name_suffix_imported' => $this->faker->word,
        'matric_number_imported' => $this->faker->word,
        'staff_number_imported' => $this->faker->word,
        'email_imported' => $this->faker->word,
        'phone_imported' => $this->faker->word,
        'phone_network_imported' => $this->faker->word,
        'bvn_imported' => $this->faker->word,
        'nin_imported' => $this->faker->word,
        'dob_imported' => $this->faker->word,
        'gender_imported' => $this->faker->word,
        'user_status' => $this->faker->word,
        'user_type' => $this->faker->word,
        'display_ordinal' => $this->faker->randomDigitNotNull,
        'admin_entered_record_issues' => $this->faker->text,
        'admin_entered_record_notes' => $this->faker->text,
        'verification_meta_data' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
            'organization_id' => Organization::first()
        ];
    }
}
