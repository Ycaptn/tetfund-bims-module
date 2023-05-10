@extends('layouts.app')

@php
$hide_right_panel = true;
@endphp

@section('app_css')
@stop

@section('title_postfix')
All B I M S Record Report
@stop

@section('page_title')
B I M S Record Report
@stop

@section('page_title_suffix')
All B I M S Record Report
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

    <div class="card border-top border-0 border-4 border-primary">
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