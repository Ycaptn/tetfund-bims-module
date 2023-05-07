
<div class="col-lg-12">
    <div class="card border-radius-0 border border-1 border-default m-0 p-1">
        <div class="row g-0">
            <div class="col-md-2 text-start align-middle p-1">
                <a data-toggle="tooltip" 
                    title="Edit" 
                    data-val='{{$data_item->id}}' 
                    class="btn-edit-mdl-bIMSRecord-modal d-print-none" href="#">
                    <i class="bx bxs-edit"></i>
                </a>
                <a data-toggle="tooltip" 
                    title="Delete" 
                    data-val='{{$data_item->id}}' 
                    class="btn-delete-mdl-bIMSRecord-modal me-1 d-print-none" href="#">
                    <i class="text-danger bx bxs-trash-alt"></i>
                </a>            
                {{ \Carbon\Carbon::parse($data_item->updated_at)->format('j F Y') }}
            </div>
            <div class="col-md-2 align-middle p-1 text-center">
                <span class="text-success small fw-bold"> @if(empty($data_item->user_type)) UNKNOWN @else {{ strtoupper($data_item->user_type) }} @endif</span> <br/>
                @if ($data_item->is_verified == true)
                    <span class="text-success small" style="font-szie:70%">Confirmed</span>
                @else
                    <span class="text-warning small" style="font-szie:70%">Unverified</span>
                @endif
            </div>   
            <div class="col-md-3 align-middle p-1">
                @php
                    $detail_page_url = route('bims-onboarding.BIMSRecords.show', $data_item->id);
                @endphp
                <p class="card-text">
                    @if ($data_item->is_verified == false)
                    <a href='{{$detail_page_url}}'>{{ $data_item->email_imported }} <br/> {{$data_item->phone_imported}}</a>
                    @else
                    <a href='{{$detail_page_url}}'>{{ $data_item->email_verified }} <br/> {{$data_item->phone_verified}} - <b>{{$data_item->phone_network_verified}}</b></a>
                    @endif
                </p>
            </div>
            <div class="col-md-3 align-middle p-1">
                @if ($data_item->is_verified == false)
                    {{$data_item->name_title_imported}} <b>{{$data_item->last_name_imported}}</b> <br/>{{$data_item->first_name_imported}} {{$data_item->middle_name_imported}} {{$data_item->name_suffix_imported}}
                @else
                    {{$data_item->name_title_verified}} <b>{{$data_item->last_name_verified}}</b> <br/>{{$data_item->first_name_verified}} {{$data_item->middle_name_verified}} {{$data_item->name_suffix_verified}}
                @endif
            </div>
            <div class="col-md-2 align-middle  text-center p-1">
                @if (!empty($data_item->matric_number_imported))
                    {{$data_item->matric_number_imported}}
                @elseif (!empty($data_item->staff_number_imported))
                    {{$data_item->staff_number_imported}}
                @endif
                <br/><span class="text-primary small">{{ $data_item->user_status }}</span>
            </div>   
            
        </div>
    </div>
</div>


