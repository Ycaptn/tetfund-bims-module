

<!-- <div class="card mt-0">
    <div class="card-footer mt-0">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="inlineRadioOptions" id="inlineRadio1" value="option1">
            <label class="form-check-label" for="inlineRadio1">Check all</label>
        </div>
        <div class="form-check form-check-inline">
            <a href="#" class="text-info btn btn-sm">
                <i class="text-info bx bx-export"></i> Remove
            </a>
        </div>
        <div class="form-check form-check-inline">
            <a href="#" class="text-danger btn btn-sm">
                <i class="text-danger bx bxs-trash-alt"></i> Delete
            </a>
        </div>
    </div>
</div> -->
<div class="modal fade" id="mdl-bIMSRecord-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-bIMSRecord-modal-title" class="modal-title">B I M S Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-bIMSRecord-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-bIMSRecord-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-b_i_m_s_records">You are currently offline</span></div>

                            <div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-bIMSRecord-primary-id" value="0" />
                            <div id="div-show-txt-bIMSRecord-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('tetfund-bims-module::pages.bims_records.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-bIMSRecord-primary-id">
                                <div class="row">
                                    @include('tetfund-bims-module::pages.bims_records.fields')
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-bIMSRecord-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-bIMSRecord-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-b_i_m_s_records').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-bIMSRecord-modal", function(e) {
        $('#div-bIMSRecord-modal-error').hide();
        $('#mdl-bIMSRecord-modal').modal('show');
        $('#frm-bIMSRecord-modal').trigger("reset");
        $('#txt-bIMSRecord-primary-id').val(0);

        $('#div-show-txt-bIMSRecord-primary-id').hide();
        $('#div-edit-txt-bIMSRecord-primary-id').show();

        $("#spinner-b_i_m_s_records").hide();
        $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-bIMSRecord-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-b_i_m_s_records').fadeIn(300);
            return;
        }else{
            $('.offline-b_i_m_s_records').fadeOut(300);
        }

        $('#div-bIMSRecord-modal-error').hide();
        $('#mdl-bIMSRecord-modal').modal('show');
        $('#frm-bIMSRecord-modal').trigger("reset");

        $("#spinner-b_i_m_s_records").show();
        $("#div-save-mdl-bIMSRecord-modal").attr('disabled', true);

        $('#div-show-txt-bIMSRecord-primary-id').show();
        $('#div-edit-txt-bIMSRecord-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('bims-onboarding-api.bims_records.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-bIMSRecord-primary-id').val(response.data.id);
            	$('#spn_bIMSRecord_first_name_verified').html(response.data.first_name_verified);
            $('#spn_bIMSRecord_middle_name_verified').html(response.data.middle_name_verified);
            $('#spn_bIMSRecord_last_name_verified').html(response.data.last_name_verified);
            $('#spn_bIMSRecord_name_title_verified').html(response.data.name_title_verified);
            $('#spn_bIMSRecord_name_suffix_verified').html(response.data.name_suffix_verified);
            $('#spn_bIMSRecord_matric_number_verified').html(response.data.matric_number_verified);
            $('#spn_bIMSRecord_staff_number_verified').html(response.data.staff_number_verified);
            $('#spn_bIMSRecord_email_verified').html(response.data.email_verified);
            $('#spn_bIMSRecord_phone_verified').html(response.data.phone_verified);
            $('#spn_bIMSRecord_phone_network_verified').html(response.data.phone_network_verified);
            $('#spn_bIMSRecord_bvn_verified').html(response.data.bvn_verified);
            $('#spn_bIMSRecord_nin_verified').html(response.data.nin_verified);
            $('#spn_bIMSRecord_dob_verified').html(response.data.dob_verified);
            $('#spn_bIMSRecord_gender_verified').html(response.data.gender_verified);
            $('#spn_bIMSRecord_first_name_imported').html(response.data.first_name_imported);
            $('#spn_bIMSRecord_middle_name_imported').html(response.data.middle_name_imported);
            $('#spn_bIMSRecord_last_name_imported').html(response.data.last_name_imported);
            $('#spn_bIMSRecord_name_title_imported').html(response.data.name_title_imported);
            $('#spn_bIMSRecord_name_suffix_imported').html(response.data.name_suffix_imported);
            $('#spn_bIMSRecord_matric_number_imported').html(response.data.matric_number_imported);
            $('#spn_bIMSRecord_staff_number_imported').html(response.data.staff_number_imported);
            $('#spn_bIMSRecord_email_imported').html(response.data.email_imported);
            $('#spn_bIMSRecord_phone_imported').html(response.data.phone_imported);
            $('#spn_bIMSRecord_phone_network_imported').html(response.data.phone_network_imported);
            $('#spn_bIMSRecord_bvn_imported').html(response.data.bvn_imported);
            $('#spn_bIMSRecord_nin_imported').html(response.data.nin_imported);
            $('#spn_bIMSRecord_dob_imported').html(response.data.dob_imported);
            $('#spn_bIMSRecord_gender_imported').html(response.data.gender_imported);
            $('#spn_bIMSRecord_user_status').html(response.data.user_status);
            $('#spn_bIMSRecord_user_type').html(response.data.user_type);
            $('#spn_bIMSRecord_admin_entered_record_issues').html(response.data.admin_entered_record_issues);
            $('#spn_bIMSRecord_admin_entered_record_notes').html(response.data.admin_entered_record_notes);


            $("#spinner-b_i_m_s_records").hide();
            $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-bIMSRecord-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-bIMSRecord-modal-error').hide();
        $('#mdl-bIMSRecord-modal').modal('show');
        $('#frm-bIMSRecord-modal').trigger("reset");

        $("#spinner-b_i_m_s_records").show();
        $("#div-save-mdl-bIMSRecord-modal").attr('disabled', true);

        $('#div-show-txt-bIMSRecord-primary-id').hide();
        $('#div-edit-txt-bIMSRecord-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('bims-onboarding-api.bims_records.show','') }}/"+itemId).done(function( response ) { 
            
            if (typeof formartFormEditables !="undefined" && formartFormEditables instanceof Function)
            formartFormEditables(response.data);
    
			$('#txt-bIMSRecord-primary-id').val(response.data.id);
            $('#beneficiary_id').val(response.data.beneficiary_id);
            $('#first_name_verified').val(response.data.first_name_verified);
            $('#middle_name_verified').val(response.data.middle_name_verified);
            $('#last_name_verified').val(response.data.last_name_verified);
            $('#name_title_verified').val(response.data.name_title_verified);
            $('#name_suffix_verified').val(response.data.name_suffix_verified);
            $('#matric_number_verified').val(response.data.matric_number_verified);
            $('#staff_number_verified').val(response.data.staff_number_verified);
            $('#email_verified').val(response.data.email_verified);
            $('#phone_verified').val(response.data.phone_verified);
            $('#phone_network_verified').val(response.data.phone_network_verified);
            $('#bvn_verified').val(response.data.bvn_verified);
            $('#nin_verified').val(response.data.nin_verified);
            $('#dob_verified').val(response.data.dob_verified);
            $('#gender_verified').val(response.data.gender_verified);
            $('#first_name_imported').val(response.data.first_name_imported);
            $('#middle_name_imported').val(response.data.middle_name_imported);
            $('#last_name_imported').val(response.data.last_name_imported);
            $('#name_title_imported').val(response.data.name_title_imported);
            $('#name_suffix_imported').val(response.data.name_suffix_imported);
            $('#matric_number_imported').val(response.data.matric_number_imported);
            $('#staff_number_imported').val(response.data.staff_number_imported);
            $('#email_imported').val(response.data.email_imported);
            $('#phone_imported').val(response.data.phone_imported);
            $('#phone_network_imported').val(response.data.phone_network_imported);
            $('#bvn_imported').val(response.data.bvn_imported);
            $('#nin_imported').val(response.data.nin_imported);
            $('#dob_imported').val(response.data.dob_imported);
            $('#gender_imported').val(response.data.gender_imported);
            $('#user_status').val(response.data.user_status);
            $('#user_type').val(response.data.user_type);
            $('#admin_entered_record_issues').val(response.data.admin_entered_record_issues);
            $('#admin_entered_record_notes').val(response.data.admin_entered_record_notes);
            $("#spinner-b_i_m_s_records").hide();
            $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);

            
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-bIMSRecord-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-b_i_m_s_records').fadeIn(300);
            return;
        }else{
            $('.offline-b_i_m_s_records').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this BIMSRecord?",
                text: "You will not be able to recover this BIMSRecord if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    swal({
                        title: '<div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting BIMSRecord.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('bims-onboarding-api.bims_records.destroy','') }}/"+itemId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'DELETE');
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Deleted",
                                        text: "BIMSRecord deleted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        },
                    });
                }
        });
    });

    //Save details
    $('#btn-save-mdl-bIMSRecord-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-b_i_m_s_records').fadeIn(300);
            return;
        }else{
            $('.offline-b_i_m_s_records').fadeOut(300);
        }

        $("#spinner-b_i_m_s_records").show();
        $("#div-save-mdl-bIMSRecord-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('bims-onboarding-api.bims_records.store') }}";
        let primaryId = $('#txt-bIMSRecord-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('bims-onboarding-api.bims_records.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        if ($('#beneficiary_id').length){	formData.append('beneficiary_id',$('#beneficiary_id').val());	}
        if ($('#first_name_verified').length){	formData.append('first_name_verified',$('#first_name_verified').val());	}
        if ($('#middle_name_verified').length){	formData.append('middle_name_verified',$('#middle_name_verified').val());	}
        if ($('#last_name_verified').length){	formData.append('last_name_verified',$('#last_name_verified').val());	}
        if ($('#name_title_verified').length){	formData.append('name_title_verified',$('#name_title_verified').val());	}
        if ($('#name_suffix_verified').length){	formData.append('name_suffix_verified',$('#name_suffix_verified').val());	}
        if ($('#matric_number_verified').length){	formData.append('matric_number_verified',$('#matric_number_verified').val());	}
        if ($('#staff_number_verified').length){	formData.append('staff_number_verified',$('#staff_number_verified').val());	}
        if ($('#email_verified').length){	formData.append('email_verified',$('#email_verified').val());	}
        if ($('#phone_verified').length){	formData.append('phone_verified',$('#phone_verified').val());	}
        if ($('#phone_network_verified').length){	formData.append('phone_network_verified',$('#phone_network_verified').val());	}
        if ($('#bvn_verified').length){	formData.append('bvn_verified',$('#bvn_verified').val());	}
        if ($('#nin_verified').length){	formData.append('nin_verified',$('#nin_verified').val());	}
        if ($('#dob_verified').length){	formData.append('dob_verified',$('#dob_verified').val());	}
        if ($('#gender_verified').length){	formData.append('gender_verified',$('#gender_verified').val());	}
        if ($('#first_name_imported').length){	formData.append('first_name_imported',$('#first_name_imported').val());	}
        if ($('#middle_name_imported').length){	formData.append('middle_name_imported',$('#middle_name_imported').val());	}
        if ($('#last_name_imported').length){	formData.append('last_name_imported',$('#last_name_imported').val());	}
        if ($('#name_title_imported').length){	formData.append('name_title_imported',$('#name_title_imported').val());	}
        if ($('#name_suffix_imported').length){	formData.append('name_suffix_imported',$('#name_suffix_imported').val());	}
        if ($('#matric_number_imported').length){	formData.append('matric_number_imported',$('#matric_number_imported').val());	}
        if ($('#staff_number_imported').length){	formData.append('staff_number_imported',$('#staff_number_imported').val());	}
        if ($('#email_imported').length){	formData.append('email_imported',$('#email_imported').val());	}
        if ($('#phone_imported').length){	formData.append('phone_imported',$('#phone_imported').val());	}
        if ($('#phone_network_imported').length){	formData.append('phone_network_imported',$('#phone_network_imported').val());	}
        if ($('#bvn_imported').length){	formData.append('bvn_imported',$('#bvn_imported').val());	}
        if ($('#nin_imported').length){	formData.append('nin_imported',$('#nin_imported').val());	}
        if ($('#dob_imported').length){	formData.append('dob_imported',$('#dob_imported').val());	}
        if ($('#gender_imported').length){	formData.append('gender_imported',$('#gender_imported').val());	}
        if ($('#user_status').length){	formData.append('user_status',$('#user_status').val());	}
        if ($('#user_type').length){	formData.append('user_type',$('#user_type').val());	}
        if ($('#admin_entered_record_issues').length){	formData.append('admin_entered_record_issues',$('#admin_entered_record_issues').val());	}
        if ($('#admin_entered_record_notes').length){	formData.append('admin_entered_record_notes',$('#admin_entered_record_notes').val());	}
        {{-- 
        swal({
            title: '<div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving BIMSRecord',
            showConfirmButton: false,
            allowOutsideClick: false,
            html: true
        })
        --}}
        
        $.ajax({
            url:endPointUrl,
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(result){
                if(result.errors){
					$('#div-bIMSRecord-modal-error').html('');
					$('#div-bIMSRecord-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-bIMSRecord-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-bIMSRecord-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-bIMSRecord-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "BIMSRecord saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-b_i_m_s_records").hide();
                $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-b_i_m_s_records").hide();
                $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);

            }
        });
    });

    // change user type 
    $('#user_type').on('change', function() {
        if(this.value == 'student'){
            $('#div-matric_number_imported').show();            
            $('#div-staff_number_imported').hide();
        }else {
            $('#div-matric_number_imported').hide();            
            $('#div-staff_number_imported').show();
        }
    });

    // push to bim action
    $(document).on('click', ".btn-push-to-bim-mdl-bIMSRecord-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-b_i_m_s_records').fadeIn(300);
            return;
        }else{
            $('.offline-b_i_m_s_records').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');

        swal({
                title: "You are about to push this BIMSRecord to BIM",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    swal({
                        title: '<div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Pushing BIMSRecord to BIMS.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('bims-onboarding-api.bims_records.push_to_bims','') }}";
                    let index = endPointUrl.indexOf('//', endPointUrl.indexOf('://') + 3);

                    // modified url to include the item id
                    endPointUrl = endPointUrl.substring(0, index+1) + itemId + endPointUrl.substring(index+1);

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'PUT');
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Pushed",
                                        text: "BIMSRecord pushed to BIMS successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        }, error: function(data){
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try again.", "error");

                            $("#spinner-b_i_m_s_records").hide();
                            $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);

                        }
                    });
                }
        });
    });

     //remove from bim action
    $(document).on('click', ".btn-remove-from-bim-mdl-bIMSRecord-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-b_i_m_s_records').fadeIn(300);
            return;
        }else{
            $('.offline-b_i_m_s_records').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');

        swal({
                title: "Are you sure you want to remove this BIMSRecord from BIM?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    swal({
                        title: '<div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Removing BIMSRecord from BIMS.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('bims-onboarding-api.bims_records.remove_from_bims','') }}";
                    let index = endPointUrl.indexOf('//', endPointUrl.indexOf('://') + 3);

                    // modified url to include the item id
                    endPointUrl = endPointUrl.substring(0, index+1) + itemId + endPointUrl.substring(index+1);

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'PUT');
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Removed",
                                        text: "BIMSRecord removed BIMS successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        }, error: function(data){
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try again.", "error");

                            $("#spinner-b_i_m_s_records").hide();
                            $("#div-save-mdl-bIMSRecord-modal").attr('disabled', false);

                        }
                    });
                }
        });
    });
    $('#mdl-bIMSRecord-modal').on('hidden.bs.modal', function () {
         $('.input-field-verification-status').remove();
    })

});
</script>
@endpush
