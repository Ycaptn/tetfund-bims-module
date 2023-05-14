@component('mail::message')

@component('mail::panel')

B I M S Record Verification Request

@endcomponent

Dear {{$bIMSRecord->first_name_imported}}, we kindly ask you to verify the data in your BIM record by following the link provided below.

@component('mail::button', ['url' => $url])
 Verify
@endcomponent

Thanks.<br/>

@endcomponent
