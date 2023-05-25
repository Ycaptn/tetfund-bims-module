@extends('layouts.frontend')

@section('content')
<div>
        <div class="flex justify-center" role="alert">
            @if ($errors->any())
                    <div class="text-center bg-red-100 border border-red-400 text-red-700 px-4 pb-3 w-full md:w-3/4 m-4 rounded relative">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <h4 class="block"><i class="icon fa fa-warning"></i> Errors!</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="mb-2">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
            
                @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
            
                @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
            
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block" style="margin:15px;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
        </div>

        <div class="flex justify-center" style="margin-top: 15px;" >      
            <div class=" bg-white shadow-md border border-blue-300 rounded-md w-full md:w-2/4 z-10 px-8 pt-6 pb-8 mb-4" >                
            <h3 class="mb-0 text-3xl font-semibold leading-tight md:text-5xl text-center" style="color:green;">
                BIMS Record Verification
            </h3>
                <p class="pt-2 mb-2 text-sm" style="color:green; font-size:80%">
                   Please <b>verify</b> and <b>confirm</b> your data on BIM Record 
                </p>
                <!-- form to be toggled -->
                <div class=""> 
                    <form method="post" action="{{ route('bims-onboarding.BIMSRecords.confirm', $bIMSRecord->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="organization_id" id="organization_id" hidden value="{{$bIMSRecord->organization_id}}">
                            <input type="text" name="beneficiary_id" id="beneficiary_id" hidden value="{{$bIMSRecord->beneficiary_id}}">
                            <input type="text" name="user_type" id="user_type" hidden value="{{$bIMSRecord->user_type}}">
                            <div 
                                style="display: grid; grid-template-columns: repeat( auto-fit, minmax(180px, 1fr) ); column-gap: 10px; "
                                class="" 
                            >
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="first_name_imported">
                                        {{ __('First Name') }}
                                    </label>
                                    <input 
                                            type="text" 
                                            id="first_name" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('first_name_imported') is-invalid @enderror" 
                                            name="first_name_imported" 
                                            value= "{{ old('first_name_imported')?? $bIMSRecord->first_name_imported }}" 
                                            required 
                                            autocomplete="first_name_imported" 
                                            autofocus 
                                            placeholder="enter your first name"
                                        >
                                    @error('first_name_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="middle_name_imported">
                                        {{ __('Middle Name') }}
                                    </label>
                                    <input 
                                            type="text" 
                                            id="middle_name" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('middle_name_imported') is-invalid @enderror" 
                                            name="middle_name_imported" 
                                            value= "{{ old('middle_name_imported')?? $bIMSRecord->middle_name_imported }}" 
                                            autocomplete="middle_name_imported" 
                                            autofocus 
                                            placeholder="enter your middle name"
                                        >
                                    @error('middle_name_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="last_name_imported">
                                        {{ __('Last Name') }}
                                    </label>
                                    <input 
                                            type="text" 
                                            id="last_name" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('last_name_imported') is-invalid @enderror" 
                                            name="last_name_imported" 
                                            value= "{{ old('last_name_imported')?? $bIMSRecord->last_name_imported }}" 
                                            required
                                            autocomplete="last_name_imported" 
                                            autofocus 
                                            placeholder="enter your last name"
                                        >
                                    @error('last_name_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div 
                                style="display: grid; grid-template-columns: repeat( auto-fit, minmax(250px, 1fr) ); column-gap: 10px; "
                                class="" 
                            >
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="{{$bIMSRecord->user_type=='student'? 'matric_number_imported' : 'staff_number_imported'}}">
                                        {{ $bIMSRecord->user_type=="student"? __('Matric Number') : __('Staff Number') }}
                                    </label>
                                    <input 
                                            type="text" 
                                            id="{{$bIMSRecord->user_type=='student'? 'matric_number_imported' : 'staff_number_imported'}}" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error($bIMSRecord->user_type=='student'? 'matric_number_imported' : 'staff_number_imported') is-invalid @enderror" 
                                            name="{{$bIMSRecord->user_type=='student'? 'matric_number_imported' : 'staff_number_imported'}}" 
                                            value= "{{ $bIMSRecord->user_type=='student'? old('matric_number_imported')?? $bIMSRecord->matric_number_imported  : old('staff_number_imported')?? $bIMSRecord->staff_number_imported }}" 
                                            required 
                                            autocomplete="{{$bIMSRecord->user_type=='student'? 'matric_number_imported' : 'staff_number_imported'}}" 
                                            placeholder= "{{$bIMSRecord->user_type=='student'? 'enter your matric number' : 'enter your staff number'}}"
                                            autofocus 
                                        >
                                    @if($bIMSRecord->user_type=='student')
                                        @error('matric_number_imported')
                                        <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                        @enderror
                                    @else 
                                        @error('staff_number_imported')
                                        <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                        @enderror
                                    @endif
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="email_imported">
                                        {{ __('E-Mail Address') }}
                                    </label>
                                    <input 
                                            type="email" 
                                            id="email" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('email_imported') is-invalid @enderror" 
                                            name="email_imported" 
                                            value= "{{ old('email_imported')?? $bIMSRecord->email_imported }}" 
                                            required 
                                            autocomplete="email" 
                                            autofocus 
                                            placeholder="example@mail.com"
                                        >
                                    @error('email_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>  
                            
                            <div 
                                style="display: grid; grid-template-columns: repeat( auto-fit, minmax(250px, 1fr) ); column-gap: 10px; "
                                class="" 
                            >

                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="dob_imported">
                                        {{ __('Date of Birth') }}
                                    </label>
                                    <input 
                                            type="date" 
                                            id="dob" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('dob_imported') is-invalid @enderror" 
                                            name="dob_imported" 
                                            value= "{{ old('dob_imported')?? $bIMSRecord->dob_imported }}" 
                                            required 
                                            autocomplete="dob" 
                                            autofocus 
                                            placeholder="yyyy-mm-dd"
                                        >
                                    @error('dob_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="gender_imported">
                                        {{ __('Gender') }}
                                    </label>
                                    <select name="gender_imported" id="gender_imported" required     
                                        class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('gender_imported') is-invalid @enderror" 
                                    >
                                        <option value="">Select gender</option>
                                        <option value="{{(old('gender_imported')=='M' || $bIMSRecord->dob_imported=='M')? 'M':'M' }}" {{(old('gender_imported')=='M' || $bIMSRecord->gender_imported=='M')? 'Selected':'' }}>
                                            {{(old('gender_imported')=='M' || $bIMSRecord->gender_imported=='M')? 'Male':'Male' }}
                                        </option>
                                        <option value="{{(old('gender_imported')=='F' || $bIMSRecord->gender_imported=='F')? 'F':'F' }}" {{(old('gender_imported')=='F' || $bIMSRecord->gender_imported=='F')? 'Selected':'' }}>
                                            {{(old('gender_imported')=='F' || $bIMSRecord->gender_imported=='F')? 'Female':'Female' }}
                                        </option>
                                    </select>   
                                    @error('gender_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div 
                                style="display: grid; grid-template-columns: repeat( auto-fit, minmax(250px, 1fr) ); column-gap: 10px; "
                                class="" >
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="phone_imported">
                                        {{ __('Phone') }}
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="phone_imported" 
                                        class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('phone_imported') is-invalid @enderror" 
                                        name="phone_imported" 
                                        value= "{{ old('phone_imported')?? $bIMSRecord->phone_imported }}" 
                                        required 
                                        autocomplete="phone_imported" 
                                        autofocus 
                                        placeholder="080xxxxxxxx"
                                    >
                                    @error('phone_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="phone_network_imported">
                                        {{ __('Phone Network') }}
                                    </label>
                                    <select name="phone_network_imported" id="phone_network_imported"     
                                        class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('phone_network_imported') is-invalid @enderror" 
                                    >
                                        <option value="">Select phone network</option>
                                        <option value="{{(old('phone_network_imported')=='9Mobile' || $bIMSRecord->phone_network_imported=='9Mobile')? '9Mobile':'9Mobile' }}" {{(old('phone_network_imported')=='9Mobile' || $bIMSRecord->phone_network_imported=='9Mobile')? 'Selected':'' }}>
                                            {{(old('phone_network_imported')=='9Mobile' || $bIMSRecord->phone_network_imported=='9Mobile')? '9Mobile':'9Mobile' }}
                                        </option>
                                        <option value="{{(old('phone_network_imported')=='Airtel' || $bIMSRecord->phone_network_imported=='Airtel')? 'Airtel':'Airtel' }}" {{(old('phone_network_imported')=='Airtel' || $bIMSRecord->phone_network_imported=='Airtel')? 'Selected':'' }}>
                                            {{(old('phone_network_imported')=='Airtel' || $bIMSRecord->phone_network_imported=='Airtel')? 'Airtel':'Airtel' }}
                                        </option>
                                        <option value="{{(old('phone_network_imported')=='GLO' || $bIMSRecord->phone_network_imported=='GLO')? 'GLO':'GLO' }}" {{(old('phone_network_imported')=='GLO' || $bIMSRecord->phone_network_imported=='GLO')? 'Selected':'' }}>
                                            {{(old('phone_network_imported')=='GLO' || $bIMSRecord->phone_network_imported=='GLO')? 'GLO':'GLO' }}
                                        </option>
                                        <option value="{{(old('phone_network_imported')=='MTN' || $bIMSRecord->phone_network_imported=='MTN')? 'MTN':'MTN' }}" {{(old('phone_network_imported')=='MTN' || $bIMSRecord->phone_network_imported=='MTN')? 'Selected':'' }}>
                                            {{(old('phone_network_imported')=='MTN' || $bIMSRecord->phone_network_imported=='MTN')? 'MTN':'MTN' }}
                                        </option>
                                    </select>   
                                    @error('phone_network_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                          
                            <div 
                                style="display: grid; grid-template-columns: repeat( auto-fit, minmax(250px, 1fr) ); column-gap: 10px; "
                                class="" 
                            >
                                
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="username">
                                        {{ __('NIN') }}
                                    </label>
                                    <input 
                                            type="number" 
                                            id="nin" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('nin_imported') is-invalid @enderror" 
                                            name="nin_imported" 
                                            value= "{{ old('nin_imported')?? $bIMSRecord->nin_imported }}" 
                                            required 
                                            autocomplete="nin_imported" 
                                            autofocus 
                                        >
                                    @error('nin_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                {{--
                                <div class="mb-4">
                                    <label class="block text-gray-500 text-sm font-bold p-2 mb-2 " for="bvn_imported">
                                        {{ __('BVN') }}
                                    </label>
                                    <input 
                                            type="number" 
                                            id="bvn" 
                                            class="shadow appearance-none border rounded py-2 px-2 w-full md:w-full text-gray-700 @error('bvn_imported') is-invalid @enderror" 
                                            name="bvn_imported" 
                                            value= "{{ old('bvn_imported')?? $bIMSRecord->bvn_imported }}" 
                                            autocomplete="bvn_imported" 
                                            autofocus 
                                            placeholder="BVN"
                                        >
                                    @error('bvn_imported')
                                    <p class="text-sm text-red-600 dark:text-red-500" style="color:red">{{$message}}</p>
                                    @enderror
                                </div>
                                --}}
                            </div>
                            
                        <div class=" pt-3 md:w-2/4">
                            <button class="bg-green hover:bg-limegreen-500 text-white font-bold py-2 px-5 rounded" type="submit">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
                <!-- end of login for to be toggled -->
        </div>
    <div>
@stop

@push('page_scripts')
<script>
        document.body.style.backgroundColor="#C2E3F4";
</script>
@endpush
