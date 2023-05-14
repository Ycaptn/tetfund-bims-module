@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
B I M S Record 
@stop

@section('page_title')
B I M S Record 
@stop

@section('page_title_suffix')
Data Verification
@stop


@section('page_title_buttons')
@auth
<a class="btn btn-sm btn-primary btn-new-mdl-bIMSRecord-modal"  href="{{ route('bims-onboarding.BIMSRecords.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to B I M S Record List
</a>
@endauth

@guest
    <a href="{{route('login')}}" class="btn btn-sm btn-primary btn-new-mdl-bIMSRecord-modal">
        <i class="bx bx-book-add mr-1"></i> Login
    </a>
@endguest
@stop


@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
        {{-- Summary Row --}}
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-header">
            <h5>{{_('B I M S Record one time data verification')}}<h5>
        </div>
        <form id="bims_record_data_verfication_form" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="card-body">
            <div id="div-bIMSRecord-modal-error" class="alert alert-danger" role="alert" hidden></div>

            
                <div class="col-lg-12 pe-2">                    
                    <div class="offline-flag"><span class="offline-b_i_m_s_records">You are currently offline</span></div>

                    <div id="spinner-b_i_m_s_records" class="spinner-border text-primary" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <input type="hidden" id="txt-bIMSRecord-primary-id" value="0" />

                    <div id="div-edit-txt-bIMSRecord-primary-id">
                        <div class="row">
                            @include('tetfund-bims-module::pages.bims_records.fields')
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" id="div-confirm-mdl-bIMSRecord">
                <button type="button" class="btn btn-primary" id="btn-confirm-mdl-bIMSRecord" value="confirm">Confirm</button>
            </div>
        </form>

    </div>
       


@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            This is the help message.
            This is the help message.
            This is the help message.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
<script>
    $(document).ready(function() {

        let bIMSRecord = {{ Illuminate\Support\Js::from($bIMSRecord? $bIMSRecord->toArray() : []) }}

        $('.offline-b_i_m_s_records').hide()
        $('#div-bIMSRecord-modal-error').hide();
        $("#div-confirm-mdl-bIMSRecord-modal").attr('disabled', true);

        $("#spinner-b_i_m_s_records").show();

        if (typeof formartFormEditables !="undefined" && formartFormEditables instanceof Function){
            formartFormEditables(bIMSRecord);
        }            
        $('#txt-bIMSRecord-primary-id').val(bIMSRecord.id);
        $('#beneficiary_id').val(bIMSRecord.beneficiary_id);
        $('#first_name_verified').val(bIMSRecord.first_name_verified);
        $('#middle_name_verified').val(bIMSRecord.middle_name_verified);
        $('#last_name_verified').val(bIMSRecord.last_name_verified);
        $('#name_title_verified').val(bIMSRecord.name_title_verified);
        $('#name_suffix_verified').val(bIMSRecord.name_suffix_verified);
        $('#matric_number_verified').val(bIMSRecord.matric_number_verified);
        $('#staff_number_verified').val(bIMSRecord.staff_number_verified);
        $('#email_verified').val(bIMSRecord.email_verified);
        $('#phone_verified').val(bIMSRecord.phone_verified);
        $('#phone_network_verified').val(bIMSRecord.phone_network_verified);
        $('#bvn_verified').val(bIMSRecord.bvn_verified);
        $('#nin_verified').val(bIMSRecord.nin_verified);
        $('#dob_verified').val(bIMSRecord.dob_verified);
        $('#gender_verified').val(bIMSRecord.gender_verified);
        $('#first_name_imported').val(bIMSRecord.first_name_imported);
        $('#middle_name_imported').val(bIMSRecord.middle_name_imported);
        $('#last_name_imported').val(bIMSRecord.last_name_imported);
        $('#name_title_imported').val(bIMSRecord.name_title_imported);
        $('#name_suffix_imported').val(bIMSRecord.name_suffix_imported);
        $('#matric_number_imported').val(bIMSRecord.matric_number_imported);
        $('#staff_number_imported').val(bIMSRecord.staff_number_imported);
        $('#email_imported').val(bIMSRecord.email_imported);
        $('#phone_imported').val(bIMSRecord.phone_imported);
        $('#phone_network_imported').val(bIMSRecord.phone_network_imported);
        $('#bvn_imported').val(bIMSRecord.bvn_imported);
        $('#nin_imported').val(bIMSRecord.nin_imported);
        $('#dob_imported').val(bIMSRecord.dob_imported);
        $('#gender_imported').val(bIMSRecord.gender_imported);
        $('#user_status').val(bIMSRecord.user_status);
        $('#user_type').val(bIMSRecord.user_type);
        $('#admin_entered_record_issues').val(bIMSRecord.admin_entered_record_issues);
        $('#admin_entered_record_notes').val(bIMSRecord.admin_entered_record_notes);
        $("#spinner-b_i_m_s_records").hide();

        //Save details
        $('#btn-confirm-mdl-bIMSRecord').click(function(e) {
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
            let formData = new FormData(bims_record_data_verfication_form);

            let actionType = "PUT";
            let endPointUrl = "{{route('bims-onboarding-api.bims_records.confirm',$bIMSRecord->id) }}";
            formData.append('_method', actionType);

            console.log(endPointUrl);
            @if (isset($organization) && $organization!=null)
                formData.append('organization_id', '{{$organization->id}}');
            @endif

            $.ajax({
                url:endPointUrl,
                type: "post",
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

                            swal({
                                    title: "Saved",
                                    text: "BIMSRecord data verified successfully",
                                    type: "success"
                                },function(){
                                    location.reload(true);
                            });

                        },20);
                    }
                    $("#spinner-b_i_m_s_records").hide();
                    
                }, error: function(data){
                    console.log(data);
                    swal("Error", "Oops an error occurred. Please try again.", "error");

                    $("#spinner-b_i_m_s_records").hide();

                }
            });
        });

    })
</script>
@endpush