<div class="modal fade" id="mdl-bimsKnownUser-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-bimsKnownUser-modal-title" class="modal-title">Known BIMS User Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-bimsKnownUser-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-bimsKnownUser-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-bims_known_user">You are currently offline</span></div>

                            <div id="spinner-bims_known_user" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-bimsKnownUser-primary-id" value="0" />
                            <div id="div-show-txt-bimsKnownUser-primary-id">
                                <div class="col-sm-12">
                                    <div class="row">
                                    @include('tetfund-bims-module::pages.bims_known_users.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-bimsKnownUser-primary-id">
                                <div class="row">
                                    @include('tetfund-bims-module::pages.bims_known_users.fields')
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

            <div class="modal-footer" id="div-save-mdl-bimsKnownUser-modal">
                {{-- <button type="button" class="btn btn-primary" id="btn-save-mdl-bimsKnownUser-modal" value="add">Save</button> --}}
            </div>

        </div>
    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-bims_known_user').hide();


    //Show Modal for View
    $(document).on('click', ".btn-preview-mdl-bims_known_user-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-bims_known_user').fadeIn(300);
            return;
        }else{
            $('.offline-bims_known_user').fadeOut(300);
        }

        $('#div-bimsKnownUser-modal-error').hide();
        $('#mdl-bimsKnownUser-modal').modal('show');
        $('#frm-bimsKnownUser-modal').trigger("reset");

        $("#spinner-bims_known_user").show();
        $("#btn-save-mdl-bimsKnownUser-modal").attr('disabled', true);

        $('#div-show-txt-bimsKnownUser-primary-id').show();
        $('#div-edit-txt-bimsKnownUser-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('bims-onboarding-api.bims_known_users.show','') }}/"+itemId).done(function( response ) {
            
            $('#txt-bimsKnownUser-primary-id').val(response.data.id);
            $('#spn_bIMSKnownUser_first_name').text(response.data.first_name);
            $('#spn_bIMSKnownUser_middle_name').text(response.data.middle_name);
            $('#spn_bIMSKnownUser_last_name').text(response.data.last_name);
            $('#spn_bIMSKnownUser_email').text(response.data.email);
            $('#spn_bIMSKnownUser_gender').text(response.data.gender ?? 'N/A');
            $('#spn_bIMSKnownUser_dob').text(response.data.dob ?? 'N/A');

            let institution_obj = JSON.parse(response.data.institution_meta_data);
            $('#spn_bIMSKnownUser_institution').text((institution_obj!=null && institution_obj.name.length>0) ? institution_obj.name : 'N/A');


            $("#spinner-bims_known_user").hide();
            $("#btn-save-mdl-bimsKnownUser-modal").attr('disabled', false);
        });
    });



    //Synchronize BIMS users action
    $(document).on('click', ".btn-synchronize-bims-know-users", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-bims_known_user').fadeIn(300);
            return;
        }else{
            $('.offline-bims_known_user').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
            title: "Are you sure you want to Synchronize  users from BIMS?",
            text: "This action will retrieve, save and display BIMS users for this application.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, sync",
            cancelButtonText: "No, don't sync",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {

                swal({
                    title: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Synchronizing...  </span> </div> <br><br> Please wait...',
                    text: 'Retrieving users from BIMS server.',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html: true
                }) 
                                    
                let endPointUrl = "{{ route('bims-onboarding-api.bims_known_users.sync_bims_users') }}";

                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('_method', 'GET');
                
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
                            console.log();
                            swal({
                                title: "Synchronized",
                                text: result.data.count_bims_users + ' ' + result.message,
                                type: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },function(){
                                location.reload(true);
                            });
                        }
                    },error: function(response) {
                        console.log(response);
                        swal("Error", "Oops an error occurred. Please try again.", "error");
                    }
                });
            }
        });
    });


    //delete bims known user record action
    $(document).on('click', ".btn-delete-mdl-bims_known_user", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-bims_known_user').fadeIn(300);
            return;
        }else{
            $('.offline-bims_known_user').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
            title: "Are you sure you want to delete the known BIMS user record?",
            text: "This action is irreversible once completed successfully.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete",
            cancelButtonText: "No, don't delete",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {

                swal({
                    title: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Deleting...  </span> </div> <br><br> Please wait...',
                    text: 'Known BIMS user record was deleted successfully.',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html: true
                }) 
                                    
                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('_method', 'DELETE');
                
                $.ajax({
                    url: "{{ route('bims-onboarding-api.bims_known_users.destroy', '') }}/" + itemId,
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
                                text: "Known BIMS user record deleted successfully",
                                type: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },function(){
                                location.reload(true);
                            });
                        }
                    },error: function(response) {
                        console.log(response);
                        swal("Error", "Oops an error occurred. Please try again.", "error");
                    }
                });
            }
        });
    });

});
</script>
@endpush
