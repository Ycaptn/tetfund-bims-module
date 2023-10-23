@extends('layouts.app')

@section('app_css')
    {!! $cdv_bims_known_users->render_css() !!}
@stop

@section('title_postfix')
BIMS Known Users
@stop

@section('page_title')
BIMS Known Users
@stop

@section('page_title_suffix')
BIMS Known Users
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
    @if(session()->has('bims_token'))
        <a href="#" class="btn btn-sm btn-primary bg-olive mx-1 btn-synchronize-bims-know-users" title="Click to synchonize BIMS Known Users">
            <i class="bx bx-import"></i> Synchonize BIMS known users
        </a>
    @else
        <button class="btn btn-sm btn-primary bg-olive mx-1" disabled='disabled' title="Login with BIMS to synchonize BIMS Known Users.">
            <i class="bx bx-import"></i> Login with BIMS to synchonize known users.
        </button>
    @endif
@stop

@section('content')
    
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            {{ $cdv_bims_known_users->render() }}
        </div>
    </div>
    @include('tetfund-bims-module::pages.bims_known_users.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small" style="text-align: justify;">
            You may preview and synchronize BIMS Known Users from this window</b>.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_bims_known_users->render_js() !!}
@endpush