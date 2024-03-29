


<div class="modal fade" id="mdl-bulk-upload-bIMSRecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 id="lbl-bIMSRecord-modal-title-bku" class="modal-title">Bulk Onboarding</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-bIMSRecord-modal-error-bku" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-bIMSRecord-modal-bku" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-lg-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline">You are currently offline</span></div>
                            <div id="spinner-mdl-bulk-upload-bIMSRecord-modal" class="">
                                <div class="loader" id="loader-1"></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-11 ma-10">

                                    <div id="div-value-key" class="form-group">
                                        <div class="col-sm-12">
                                            You may select a comma separated value (csv) file containing data that can be uploaded. The format of the <span style="font-weight:bold;">expected CSV file</span> is indicated below, only properly formatted will be successfully uploaded. 
                                        </div>
                                    </div>

                                    <div id="div-value" class="form-group">
                                        <div class="col-sm-12">
                                            <input id="txt_BIMSRecordType" name="txt_BIMSRecordType" value="0" type="hidden" />
                                            {!! Form::file('mdl-bulk-upload-bIMSRecord-modal-file', ['class' => 'form-control', 'id'=>'mdl-bulk-upload-bIMSRecord-modal-file']) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div id="div-upload-mdl-bIMSRecord-modal-bku" class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-upload-mdl-bIMSRecord-modal" value="add">Upload</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline').hide();

    //Show Modal for Bulk Upload
    $(document).on('click', ".btn-mdl-bulk-upload-bIMSRecord-modal", function(e) {
        $('#div-bIMSRecord-modal-error-bku').hide();
        $('#mdl-bulk-upload-bIMSRecord-modal').modal('show');
        $('#frm-bIMSRecord-modal-bku').trigger("reset");

        $('#txt_BIMSRecordType').val($(this).attr('data-val-rtype'));

        $("#spinner-mdl-bulk-upload-bIMSRecord-modal").hide();
        $("#btn-upload-mdl-bIMSRecord-modal").attr('disabled', false);
    });

    //Save details
    $('#btn-upload-mdl-bIMSRecord-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline').fadeIn(300);
            return;
        }else{
            $('.offline').fadeOut(300);
        }

        $("#spinner-mdl-bulk-upload-bIMSRecord-modal").show();
        $("#btn-upload-mdl-bIMSRecord-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{route('bims-onboarding.bi-import-processing')}}";
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());        
        formData.append('_method', actionType);
        formData.append('user_type', $('#txt_BIMSRecordType').val());
        formData.append('file', $('#mdl-bulk-upload-bIMSRecord-modal-file')[0].files[0]);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif

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
                    $('#div-bIMSRecord-modal-error-bku').html('');
                    $("#spinner-mdl-bulk-upload-bIMSRecord-modal").show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-bIMSRecord-modal-error-bku').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-bIMSRecord-modal-error-bku').hide();
                    window.setTimeout( function(){

                        $('#div-bIMSRecord-modal-error-bku').hide();
                        swal({
                                title: "Saved",
                                text: result.message, //"Bulk Onboarding completed successfully",
                                type: "success",
                                showCancelButton: false,
                                closeOnConfirm: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },function(){
                                //location.reload(true);
                                location.href = "{{route('bims-onboarding.BIMSRecords.index')}}";
                        });

                    },20);
                }

                $("#spinner-mdl-bulk-upload-bIMSRecord-modal").hide();
                $("#btn-upload-mdl-bIMSRecord-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-mdl-bulk-upload-bIMSRecord-modal").hide();
                $("#btn-upload-mdl-bIMSRecord-modal").attr('disabled', false);
            }
        });
    });

});
</script>
@endpush
