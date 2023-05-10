@extends('layouts.app')

@php
$hide_right_panel = true;
@endphp

@section('app_css')
@stop

@section('title_postfix')
BIMS Onboarding Report
@stop

@section('page_title')
BIMS Onboarding Report
@stop

@section('page_title_suffix')
All Beneficiaries
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a> 
@stop

@section('app_css')
    @include('layouts.datatables_css')
@endsection

@section('content')

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 row-cols-xl-5 row-cols-xxl-5">
        
        @php
            $total_bi_count =  count(\BIMSOnboarding::get_beneficiaries_on_bims());
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
                            <p class="mb-1 fw-bold">Beneficiaries</p>
                            <h4 class="mb-0 text-success">{{number_format(($total_bi_count),0)}}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-success text-white">
                            <i class="bi bi-layout-wtf text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-5 border-0 border-start border-tiffany border-4">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="">
                    <p class="mb-1 fw-bold">Total Staff</p>
                    <h4 class="mb-0 text-tiffany">{{number_format(($academic_staff_count+$non_academic_staff_count),0)}}</h4></h4>
                  </div>
                  <div class="ms-auto widget-icon bg-tiffany text-white">
                    <i class="bi bi-bag-check-fill"></i>
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

        <div class="col">
            <div class="card radius-5 border-0 border-start border-danger border-4">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="">
                    <p class="mb-1 fw-bold">Verified</p>
                    <h4 class="mb-0 text-danger">{{number_format($verified_pct)}}%</h4>
                  </div>
                  <div class="ms-auto widget-icon bg-danger text-white">
                    <i class="bi bi-person-plus-fill"></i>
                  </div>
                </div>
              </div>
            </div>
        </div>

    </div>

    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body "> 
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped table-hover  nowrap'], true) !!}
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
@stop


@push('page_scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endpush