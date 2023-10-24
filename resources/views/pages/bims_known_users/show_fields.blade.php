<!-- First Name Field -->
<div id="div_bIMSKnownUser_first_name" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_first_name">
        @if (isset($bIMSKnownUser->first_name) && empty($bIMSKnownUser->first_name)==false)
            {!! $bIMSKnownUser->first_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Middle Name Field -->
<div id="div_bIMSKnownUser_middle_name" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_middle_name">
        @if (isset($bIMSKnownUser->middle_name) && empty($bIMSKnownUser->middle_name)==false)
            {!! $bIMSKnownUser->middle_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Last Name Field -->
<div id="div_bIMSKnownUser_last_name" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_last_name">
        @if (isset($bIMSKnownUser->last_name) && empty($bIMSKnownUser->last_name)==false)
            {!! $bIMSKnownUser->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Email Field -->
<div id="div_bIMSKnownUser_email" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('email', 'Email:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_email">
        @if (isset($bIMSKnownUser->email) && empty($bIMSKnownUser->email)==false)
            {!! $bIMSKnownUser->email !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_bIMSKnownUser_gender" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_gender">
        @if (isset($bIMSKnownUser->gender) && empty($bIMSKnownUser->gender)==false)
            {!! $bIMSKnownUser->gender !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>


<!-- dob Field -->
<div id="div_bIMSKnownUser_dob" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('dob', 'Date of Birth:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_dob">
        @if (isset($bIMSKnownUser->dob) && empty($bIMSKnownUser->dob)==false)
            {!! $bIMSKnownUser->dob !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution  Field -->
<div id="div_bIMSKnownUser_institution" class="col-sm-12 col-md-6 col-lg-4">
    <p>
        <b>{!! Form::label('institution', 'Institution:', ['class'=>'control-label']) !!} </b><br/>

        <span id="spn_bIMSKnownUser_institution">
        @if (isset($bIMSKnownUser->institution_meta_data) && empty($bIMSKnownUser->institution_meta_data)==false)
            {!! json_decode($bIMSKnownUser->institution_meta_data)->name ?? '' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>
