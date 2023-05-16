@extends('layouts.app')

@section('app_css')
    {!! $cdv_bims_records->render_css() !!}
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
<a href="{{route('bims-onboarding.bi-import')}}" class="btn btn-sm btn-primary bg-olive mx-1" title="Search">
    <i class="bx bx-import"></i> Onboard BIMS Records
</a>
@stop

@section('content')

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-3 row-cols-xxl-3">
     
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
                        <h4 class="mb-0 text-pink">{{number_format($student_count,0)}}</h4>
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
                        <h4 class="mb-0 text-orange">{{number_format($others_count,0)}}</h4>
                    </div>
                    <div class="ms-auto widget-icon bg-orange text-white">
                        <i class="bi bi-box-arrow-in-down-right text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    
    <div class="card mt-0 border-top border-0 border-4 border-primary">
        <div class="card-body">
            <p>To remove records on BIMS, check on the record and click remove</p>
            {{ $cdv_bims_records->render() }}

        </div>
    </div>

    @include('tetfund-bims-module::pages.bims_records.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            BIMS Onboarding Records are identity records of staff and students <b>actively</b> enrolled or affliated with your Institution.
            These identities will enable users access various services provided by TETFund. It is important to <b>ensure these records are up to date</b>.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_bims_records->render_js() !!}
@endpush