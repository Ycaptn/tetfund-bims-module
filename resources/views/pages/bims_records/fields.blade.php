<!-- Hide all verified input field -->
{!! Form::text('beneficiary_id', null, ['id'=>'beneficiary_id', 'hidden'=>'hidden',]) !!}

<!-- First Name Verified Field -->
<div id="div-first_name_verified" class="form-group col-lg-4 mb-1">
    <label for="first_name_verified" class="col-lg-12 col-form-label">First Name Verified</label>
    <div class="col-lg-12">
        {!! Form::text('first_name_verified', null, ['id'=>'first_name_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Middle Name Verified Field -->
<div id="div-middle_name_verified" class="form-group col-lg-4 mb-1">
    <label for="middle_name_verified" class="col-lg-12 col-form-label">Middle Name Verified</label>
    <div class="col-lg-12">
        {!! Form::text('middle_name_verified', null, ['id'=>'middle_name_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Last Name Verified Field -->
<div id="div-last_name_verified" class="form-group col-lg-4 mb-1">
    <label for="last_name_verified" class="col-lg-12 col-form-label">Last Name Verified</label>
    <div class="col-lg-12">
        {!! Form::text('last_name_verified', null, ['id'=>'last_name_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Name Title Verified Field -->
<div id="div-name_title_verified" class="form-group col-lg-6 mb-1">
    <label for="name_title_verified" class="col-lg-12 col-form-label">Name Title Verified</label>
    <div class="col-lg-12">
        {!! Form::text('name_title_verified', null, ['id'=>'name_title_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Name Suffix Verified Field -->
<div id="div-name_suffix_verified" class="form-group col-lg-6 mb-1">
    <label for="name_suffix_verified" class="col-lg-12 col-form-label">Name Suffix Verified</label>
    <div class="col-lg-12">
        {!! Form::text('name_suffix_verified', null, ['id'=>'name_suffix_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Matric Number Verified Field -->
<div id="div-matric_number_verified" class="form-group col-lg-6 mb-1">
    <label for="matric_number_verified" class="col-lg-12 col-form-label">Matric Number Verified</label>
    <div class="col-lg-12">
        {!! Form::text('matric_number_verified', null, ['id'=>'matric_number_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Staff Number Verified Field -->
<div id="div-staff_number_verified" class="form-group col-lg-6 mb-1">
    <label for="staff_number_verified" class="col-lg-12 col-form-label">Staff Number Verified</label>
    <div class="col-lg-12">
        {!! Form::text('staff_number_verified', null, ['id'=>'staff_number_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Email Verified Field -->
<div id="div-email_verified" class="form-group col-lg-12 mb-1">
    <label for="email_verified" class="col-lg-12 col-form-label">Email Verified</label>
    <div class="col-lg-12">
        {!! Form::text('email_verified', null, ['id'=>'email_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Phone Verified Field -->
<div id="div-phone_verified" class="form-group col-lg-6 mb-1">
    <label for="phone_verified" class="col-lg-12 col-form-label">Phone Verified</label>
    <div class="col-lg-12">
        {!! Form::text('phone_verified', null, ['id'=>'phone_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Phone Network Verified Field -->
<div id="div-phone_network_verified" class="form-group col-lg-6 mb-1">
    <label for="phone_network_verified" class="col-lg-12 col-form-label">Phone Network Verified</label>
    <div class="col-lg-12">
        {!! Form::select('phone_network_verified',['9Mobile'=>'9Mobile','Airtel'=>'Airtel','GLO'=>'GLO','MTN'=>'MTN'], null, ['id'=>'phone_network_verified', 'class' => 'form-select','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Bvn Verified Field -->
<div id="div-bvn_verified" class="form-group col-lg-6 mb-1">
    <label for="bvn_verified" class="col-lg-12 col-form-label">Bvn Verified</label>
    <div class="col-lg-12">
        {!! Form::text('bvn_verified', null, ['id'=>'bvn_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Nin Verified Field -->
<div id="div-nin_verified" class="form-group col-lg-6 mb-1">
    <label for="nin_verified" class="col-lg-12 col-form-label">Nin Verified</label>
    <div class="col-lg-12">
        {!! Form::text('nin_verified', null, ['id'=>'nin_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Dob Verified Field -->
<div id="div-dob_verified" class="form-group col-lg-6 mb-1">
    <label for="dob_verified" class="col-lg-12 col-form-label">Dob Verified</label>
    <div class="col-lg-12">
        {!! Form::date('dob_verified', null, ['id'=>'dob_verified', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Gender Verified Field -->
<div id="div-gender_verified" class="form-group col-lg-6 mb-1">
    <label for="gender_verified" class="col-lg-12 col-form-label">Gender Verified</label>
    <div class="col-lg-12">
        {!! Form::select('gender_verified', ['male'=>'male','female'=>'female'], null, ['id'=>'gender_verified', 'class' => 'form-select','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Hide all verified input field end-->

<!-- First Name Imported Field -->
<div id="div-first_name_imported" class="form-group col-lg-4 mb-1">
    <label for="first_name_imported" class="col-lg-12 col-form-label">First Name Imported</label>
    <div class="col-lg-12">
        {!! Form::text('first_name_imported', null, ['id'=>'first_name_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Middle Name Imported Field -->
<div id="div-middle_name_imported" class="form-group col-lg-4 mb-1">
    <label for="middle_name_imported" class="col-lg-12 col-form-label">Middle Name Imported</label>
    <div class="col-lg-12">
        {!! Form::text('middle_name_imported', null, ['id'=>'middle_name_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Last Name Imported Field -->
<div id="div-last_name_imported" class="form-group col-lg-4 mb-1">
    <label for="last_name_imported" class="col-lg-12 col-form-label">Last Name Imported</label>
    <div class="col-lg-12">
        {!! Form::text('last_name_imported', null, ['id'=>'last_name_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Name Title Imported Field -->
<div id="div-name_title_imported" class="form-group col-lg-6 mb-1">
    <label for="name_title_imported" class="col-lg-12 col-form-label">Name Title Imported</label>
    <div class="col-lg-12">
        {!! Form::text('name_title_imported', null, ['id'=>'name_title_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Name Suffix Imported Field -->
<div id="div-name_suffix_imported" class="form-group col-lg-6 mb-1">
    <label for="name_suffix_imported" class="col-lg-12 col-form-label">Name Suffix Imported</label>
    <div class="col-lg-12">
        {!! Form::text('name_suffix_imported', null, ['id'=>'name_suffix_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Matric Number Imported Field -->
<div id="div-matric_number_imported" class="form-group col-lg-6 mb-1">
    <label for="matric_number_imported" class="col-lg-12 col-form-label">Matric Number Imported</label>
    <div class="col-lg-12">
        {!! Form::text('matric_number_imported', null, ['id'=>'matric_number_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Staff Number Imported Field -->
<div id="div-staff_number_imported" class="form-group col-lg-6 mb-1">
    <label for="staff_number_imported" class="col-lg-12 col-form-label">Staff Number Imported</label>
    <div class="col-lg-12">
        {!! Form::text('staff_number_imported', null, ['id'=>'staff_number_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Email Imported Field -->
<div id="div-email_imported" class="form-group col-lg-12 mb-1">
    <label for="email_imported" class="col-lg-12 col-form-label">Email Imported</label>
    <div class="col-lg-12">
        {!! Form::text('email_imported', null, ['id'=>'email_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Phone Imported Field -->
<div id="div-phone_imported" class="form-group col-lg-6 mb-1">
    <label for="phone_imported" class="col-lg-12 col-form-label">Phone Imported</label>
    <div class="col-lg-12">
        {!! Form::text('phone_imported', null, ['id'=>'phone_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Phone Network Imported Field -->
<div id="div-phone_network_imported" class="form-group col-lg-6 mb-1">
    <label for="phone_network_imported" class="col-lg-12 col-form-label">Phone Network Imported</label>
    <div class="col-lg-12">
        {!! Form::select('phone_network_imported',['9Mobile'=>'9Mobile','Airtel'=>'Airtel','GLO'=>'GLO','MTN'=>'MTN'], null, ['id'=>'phone_network_imported', 'class' => 'form-select','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Bvn Imported Field -->
<div id="div-bvn_imported" class="form-group col-lg-6 mb-1">
    <label for="bvn_imported" class="col-lg-12 col-form-label">Bvn Imported</label>
    <div class="col-lg-12">
        {!! Form::text('bvn_imported', null, ['id'=>'bvn_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Nin Imported Field -->
<div id="div-nin_imported" class="form-group col-lg-6 mb-1">
    <label for="nin_imported" class="col-lg-12 col-form-label">Nin Imported</label>
    <div class="col-lg-12">
        {!! Form::text('nin_imported', null, ['id'=>'nin_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Dob Imported Field -->
<div id="div-dob_imported" class="form-group col-lg-6 mb-1">
    <label for="dob_imported" class="col-lg-12 col-form-label">Dob Imported</label>
    <div class="col-lg-12">
        {!! Form::text('dob_imported', null, ['id'=>'dob_imported', 'class' => 'form-control','maxlength' => 300,]) !!}
    </div>
</div>

<!-- Gender Imported Field -->
<div id="div-gender_imported" class="form-group col-lg-6 mb-1">
    <label for="gender_imported" class="col-lg-12 col-form-label">Gender Imported</label>
    <div class="col-lg-12">
        {!! Form::select('gender_imported', ['male'=>'male','female'=>'female'], null, ['id'=>'gender_imported', 'class' => 'form-select','maxlength' => 300,]) !!}
    </div>
</div>

<!-- User Status Field -->
<div id="div-user_status" class="form-group col-lg-6 mb-1">
    <label for="user_status" class="col-lg-12 col-form-label">User Status Imported</label>
    <div class="col-lg-12">
        {!! Form::text('user_status', null, ['id'=>'user_status', 'class' => 'form-control','maxlength' => 100]) !!}
    </div>
</div>

<!-- User Type Field -->
<div id="div-user_type" class="form-group col-lg-6 mb-1">
    <label for="user_type" class="col-lg-12 col-form-label">User Type Imported</label>
    <div class="col-lg-12">
        {!! Form::select('user_type', ['student'=>'student','academic'=>'academic','non-academic'=>'non-academic', 'other'=>'other'], null, ['id'=>'user_type', 'class' => 'form-select','maxlength' => 100]) !!}
    </div>
</div>

<!-- Admin Entered Record Issues Field -->
<div id="div-admin_entered_record_issues" class="form-group col-lg-12 mb-1">
    <label for="admin_entered_record_issues" class="col-lg-12 col-form-label">Admin Entered Record Issues Imported</label>
    <div class="col-lg-12">
        {!! Form::textarea('admin_entered_record_issues', null, ['id'=>'admin_entered_record_issues', 'class' => 'form-control','maxlength' => 2000, 'rows'=>'3']) !!}
    </div>
</div>

<!-- Admin Entered Record Notes Field -->
<div id="div-admin_entered_record_notes" class="form-group col-lg-12 mb-1">
    <label for="admin_entered_record_notes" class="col-lg-12 col-form-label">Admin Entered Record Notes Imported</label>
    <div class="col-lg-12">
        {!! Form::textarea('admin_entered_record_notes', null, ['id'=>'admin_entered_record_notes', 'class' => 'form-control','maxlength' => 2000, 'rows'=>'3']) !!}
    </div>
</div>
@php
  $bim_record =  \TETFund\BIMSOnboarding\Models\BIMSRecord::first();
@endphp
<script>
//     form_input_divs = document.getElementsByClassName(');

//    /* this is an example for new snippet extension make by me xD */
//     for (const form_input_div of form_input_divs) 
//         form_input_div.style.visibility = "hidden";
        
    function formartFormEditables(bims_record=null){
        if(bims_record==null)
        return
        verified = '_verified'
        imported = '_imported'
        editable_props ={{ Illuminate\Support\Js::from($bim_record->updatableInputs()) }}
        
        let indicateVerificationStatus = function(property, imported="_imported", verifed="_verified" ){
            if(property.endsWith(imported)){
                prop = property.substr(0, property.length - imported.length)
                prop_verified = prop.concat(verified);
                if(typeof bims_record[property] != 'undefined' && typeof bims_record[prop_verified] != 'undefined'){
                    $("#div-".concat(property) ).append("<span class='text-warning'>unverified</span>")
                }else{
                    $("#div-".concat(property) ).append("<span class='text-success'>verified</span>")
                }
            }
        };
            

        for(const property in bims_record ){
            // disabled prop if not editable
            input_field = "#".concat(property);
            div_parent_input = "#div-".concat(property);

            if(!editable_props.includes(property)){

                if(input_field.length){
                    $(input_field).attr('readonly', 'readonly');
                    $(input_field).attr('disabled', 'disabled');
                }
                if(div_parent_input.length){
                    $(div_parent_input).hide();
                    $(div_parent_input).remove();
               }
            }
            indicateVerificationStatus(property);


              
           
        }
    }
</script>