@extends('layouts.app')

@section('title_postfix')
BIMS Admin Dashboard
@stop

@section('page_title')
BIMS
@stop

@section('page_title_suffix')
Admin Dashboard
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
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

<div class="row">
    <div class="col-12">
        <div class="card border-0 border-success border-top border-2 radius-10 w-100">
        </div>
    </div>
</div>

@stop


@push('page_scripts')
@endpush