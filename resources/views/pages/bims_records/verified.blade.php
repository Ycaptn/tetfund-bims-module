@extends('layouts.frontend')

@section('content')

    <div class="flex justify-center">      
        <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-3/4 z-10 px-8 pt-6 pb-8 mb-4" >
            <h3 class="mb-5 text-3xl font-semibold leading-tight md:text-5xl text-center" style="color:green;">
                B I M S Record verification      
            </h3>
            
            @if (session('success'))
                <p class="mb-5 text-2xl text-center font-semibold leading-tight ">
                    {{session('success')}}
                </p>
            
            @endif
            @if (session('info'))
                <p class="mb-5 text-2xl font-semibold leading-tight ">
                    {{session('success')}}
                </p>
            
            @endif
           
            
            <ul class="flex flex-wrap justify-center mt-5">
                <li><a class="mx-3 main-btn gradient-green-btn" href="http://www.tetfund.gov.ng" target="_blank">TETFund Website</a></li>
                <li><a class="mx-3 main-btn gradient-green-btn" href="{{ route('login') }}">Login</a></li>
            </ul>

            <p class="px-5 mb-5 pt-6 text-sm" style="color:green;">
                For <b>National Research Fund (NRF)</b> submissions, please use the <a href="https://nrf.tetfund.gov.ng" style="color:blue;" target="_blank">NRF Portal</a> to process your submissions.
            </p>
        </div>
    </div>

@stop