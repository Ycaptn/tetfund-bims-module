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
<a id="btn-new-mdl-bIMSRecord-modal" class="btn btn-sm btn-primary btn-new-mdl-bIMSRecord-modal">
    <i class="bx bx-book-add me-1"></i>New BIMS Record
</a>
<a href="{{route('bims-onboarding.BIMSRecords.index')}}" class="btn btn-sm btn-primary bg-olive mx-1" title="Search">
    <i class="bx bx-search"></i> Search BIMS Records
</a>
@stop

@section('content')

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-3 row-cols-xxl-3">
        
        <div class="col">
            <div class="card radius-5 border-0 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1 fw-bold">Total Staff</p>
                            <h4 class="mb-0 text-success">15666</h4>
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
                            <h4 class="mb-0 text-pink">54666</h4>
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
                            <h4 class="mb-0 text-orange">15000</h4>
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
                To onboard records on BIMS, you can use an CSV file using MS Excel is a specified format. This is the fastest way to import your records.Download sample CSV file, open it in MS Excel add your records, then upload it here.
                <a href="{{ asset('bims-record.csv') }}" target="_blank">Download Sample CSV File</a>
            </p>

            <hr/>

            <div class="col text-center my-3">  
                <div class="btn-group">
                    <a id="btn-mdl-bulk-upload-bIMSRecord-modal" class="p-2 mx-1 btn btn-sm btn-primary btn-mdl-bulk-upload-bIMSRecord-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
                        <i class="bx bx-upload"></i> Upload Academic Staff
                    </a>

                    <a id="btn-mdl-bulk-upload-bIMSRecord-modal" class="p-2 mx-1 btn btn-sm btn-primary btn-mdl-bulk-upload-bIMSRecord-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
                        <i class="bx bx-upload"></i> Upload Non Academic Staff
                    </a>

                    <a id="btn-mdl-bulk-upload-bIMSRecord-modal" class="p-2 mx-1 btn btn-sm btn-primary btn-mdl-bulk-upload-bIMSRecord-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
                        <i class="bx bx-upload"></i> Upload Students
                    </a>
                </div>
            </div>

            <hr/>
            <br/>
            <br/>

            <div class="text-center">
                <img src="{{ asset('imgs/bims-csv-format.png') }}" style="max-width:500px;" />
            </div>

            @include('tetfund-bims-module::pages.bims_records.bulk-upload-modal')
        

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
@endpush