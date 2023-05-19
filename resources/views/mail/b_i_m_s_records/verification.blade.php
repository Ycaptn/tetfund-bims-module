@component('mail::message')

@component('mail::panel')

B I M S Record Verification Request

@endcomponent

Dear {{$bIMSRecord->first_name_imported}}, 
<br/> Your details have been sent to TETFUND for onboarding to the BIMS Platform
<br/>Please kindly follow this link to verify your records. 
<br>
<a href="{{$url}}" target="_blank"><small>{{ $url}}</small></a>
<br/>
<br/>
Thank you.<br/>
TETFUND Beneficiary Portal
@endcomponent
