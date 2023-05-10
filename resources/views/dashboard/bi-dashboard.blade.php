@extends('layouts.app')

@section('title_postfix')
BIMS Identities Onboarding
@stop

@section('page_title')
BIMS Identities Onboarding
@stop

@section('page_title_suffix')
{{ $beneficiary->full_name }}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop


@section('content')

    <div class="card border-0 border-success border-start border-4">
        <div class="card-body">
            BIMS (<b>Beneficiary Identity Management Service</b>) is an identity management system utilized by TETFund to manage and administer various services offered to the staff and students of beneficiary institutions. 
            You can use the <b>BIMS Onboarding</b> functionality to manage which members of your institution can access services rendered by TETFund to your institution such as free mobile internet data, and access to research and journal subscriptions, etc.
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6 col-xl-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-body" style="position: relative;">
                    <span class="small float-end"><a href="{{route('bims-onboarding.BIMSRecords.index')}}"><i class="bx bx-cog"></i>Manage Users</a></span>
                    <h5 class="card-title">
                        BIMS Statistics 
                    </h5>

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

                    <div class="mt-2">
                        <div class="progress-wrapper mb-3">
                            <p class="mb-1">Academic Staff <span class="float-end fw-bold">{{number_format($academic_staff_count,0)}}</span></p>
                            <div class="progress rounded-0" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{$academic_staff_pct}}%;"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <p class="mb-1">Non Academic Staff <span class="float-end fw-bold">{{number_format($non_academic_staff_count,0)}}</span></p>
                            <div class="progress rounded-0" style="height: 8px;">
                                <div class="progress-bar bg-pink" role="progressbar" style="width: {{$non_academic_staff_pct}}%;"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <p class="mb-1">Students <span class="float-end fw-bold">{{number_format($student_count,0)}}</span></p>
                            <div class="progress rounded-0" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$student_pct}}%;"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <p class="mb-1">Others <span class="float-end fw-bold">{{number_format($others_count,0)}}</span></p>
                            <div class="progress rounded-0" style="height: 8px;">
                                <div class="progress-bar bg-pink" role="progressbar" style="width: {{$others_pct}}%;"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <p class="mb-1">Verified Records <span class="float-end fw-bold">{{number_format($verified_count,0)}}%</span></p>
                            <div class="progress rounded-0" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$verified_pct}}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-body" style="position: relative;">

                    <div class="col text-center my-3">  
                        <div class="btn-group">
                            <a href="{{route('bims-onboarding.BIMSRecords.index')}}" class="btn btn-sm btn-primary bg-olive mx-1" title="Search">
                                <i class="bx bx-search"></i> Search
                            </a>        
                            <a href="{{route('bims-onboarding.bi-import')}}" class="btn btn-sm btn-primary bg-olive mx-1" title="Onboard">
                                <i class="bx bx-import"></i> Onboard
                            </a>
                            <a href="#" class="btn btn-sm btn-primary bg-olive mx-1" title="Remove">
                                <i class="bx bx-export"></i> Remove
                            </a>
                        </div>
                    </div>

                    <hr/>
                    
                    <span class="small text-danger">
                        You need to keep your institution <b>BIMS data actively up to date</b> as students enter into or graduate and leave your institution, likewise staff of your institution. You can remove old staff or students using this functionality. Keeping users no longer affliated with your institution will allow them consume TETFund services meant for only <b>active members</b> of your institution.
                    </span>

                </div>
            </div>
        </div>
    </div>
@stop


@push('page_scripts')
@endpush