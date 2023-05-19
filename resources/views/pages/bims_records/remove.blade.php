@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
BIMS Onboarding
@stop

@section('page_title')
BIMS Onboarding Records
@stop

@section('page_title_suffix')
{{ $beneficiary->full_name }}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('bims-onboarding.bi-dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to BIMS Onboarding Dashboard
</a>
@stop

@section('page_title_buttons')
{{-- <a id="btn-new-mdl-bIMSRecord-modal" class="btn btn-sm btn-primary btn-new-mdl-bIMSRecord-modal">
    <i class="bx bx-book-add me-1"></i>New BIMS Record
</a> --}}
<a href="{{route('bims-onboarding.BIMSRecords.index')}}" class="btn btn-sm btn-primary bg-olive mx-1" title="Search">
    <i class="bx bx-search"></i> Search BIMS Records
</a>
@stop

@section('content')

    @php
        $total_count = \BIMSOnboarding::get_bims_records_count();
        $academic_staff_count = \BIMSOnboarding::get_bims_records_count('academic');
        $non_academic_staff_count = \BIMSOnboarding::get_bims_records_count('non-academic');
        $student_count = \BIMSOnboarding::get_bims_records_count('student');
        $others_count = \BIMSOnboarding::get_bims_records_count('other');
        $verified_count = \BIMSOnboarding::get_bims_records_count(null,null,1);

        $academic_staff_pct = ($total_count>0) ? 100*($academic_staff_count/$total_count) : 0;
        $non_academic_staff_pct = ($total_count>0) ? 100*($non_academic_staff_count/$total_count) : 0;
        $student_pct = ($total_count>0) ? 100*($student_count/$total_count) : 0;
        $others_pct = ($total_count>0) ? 100*($others_count/$total_count) : 0;
        $verified_pct = ($total_count>0) ? 100*($verified_count/$total_count) : 0;
    @endphp


    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-3 row-cols-xxl-3">
        
        <div class="col">
            <div class="card radius-5 border-0 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1 fw-bold">Total Staff</p>
                            <h4 class="mb-0 text-success">{{number_format(($academic_staff_count+$non_academic_staff_count),0)}}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-success text-white">
                            <i class="bi bi-layout-wtf text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-5 border-0 border-start border-pink border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1 fw-bold">Total Students</p>
                            <h4 class="mb-0 text-pink">{{number_format(($student_count),0)}}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-pink text-white">
                            <i class="bi bi-box-arrow-up-right text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-5 border-0 border-start border-orange border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1 fw-bold">Others</p>
                            <h4 class="mb-0 text-orange">{{number_format(($others_count),0)}}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-orange text-white">
                            <i class="bi bi-box-arrow-in-down-right text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
        
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
        
            <p>
                To remove records from BIMS, enter a list of emails, staff code, matric numbers or phone numbers pertaining to the records you wish to remove
            </p>
            <div class="col  my-3">  
                
                <form  method="POST" action="{{route('bims-onboarding.bi-remove-processing')}}" class="form-inline" id="bulk-remove-form" >
                    @csrf
                    <input type="text" name="organization_id" id="organization_id" hidden value="{{$organization->id}}">
                    <input type="text" name="beneficiary_id" id="beneficiary_id" hidden value="{{$beneficiary->id}}">
                    <div class="form-group">
                        <!-- <label for="exampleFormControlTextarea1" class="text-success rounded border p-2"></label> -->
                        <textarea class="form-control" name="bIMSRecordList" id="bIMSRecordTextArea" rows="6" required></textarea>
                    </div>
                    <!-- <a id="btn-mdl-bulk-remove-by-upload-bIMSRecord-modal" class="p-2 mt-2 btn btn-sm btn-primary btn-mdl-bulk-upload-bIMSRecord-modal" tabindex="-1" data-val-rtype="academic" role="dialog" aria-modal="true" aria-hidden="true">
                        <i class="bx bx-upload"></i> Upload CSV Records
                    </a> -->
                    <button type="button" class="btn btn-danger mt-2 btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal" id="btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal">Remove</button>
                </form>
            </div>

            @include('tetfund-bims-module::pages.bims_records.bulk-remove-modal')
        

        </div>
    </div>
    @include('tetfund-bims-module::pages.bims_records.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body" >
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            BIMS Onboarding Records are identity records of staff and students <b>actively</b> enrolled or affliated with your Institution.
            These identities will enable users access various services provided by TETFund. It is important to <b>ensure these records are up to date</b>.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
<script>
  document.getElementById("bIMSRecordTextArea").addEventListener("focus", function() {
    bIMSRecordTextAreaPlaceHolder = "staff59@email.com 07012345671 student32@email.com 07012329658 STF\\111\\112 STU\\121\\122\n";
    bIMSRecordTextAreaPlaceHolder += "staff22@email.com 07042345991 STF\\144\\442 student93@email.com 08042329658  STU\\99\\122\n";
    this.setAttribute("placeholder", bIMSRecordTextAreaPlaceHolder);
  });

  document.getElementById("bIMSRecordTextArea").addEventListener("blur", function() {
    this.setAttribute("placeholder", "");
    if(this.value.length> 0)
        $('#btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal').prop('disabled', false);
    else 
        $('#btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal').prop('disabled', true);
  });

  $('#bIMSRecordTextArea').keyup(function(){
        if(this.value.length> 0)
            $('#btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal').prop('disabled', false);
        else 
            $('#btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal').prop('disabled', true);
  })
  
  $('#btn-mdl-bulk-remove-by-textarea-bIMSRecord-modal').prop('disabled', true);

</script>
@endpush