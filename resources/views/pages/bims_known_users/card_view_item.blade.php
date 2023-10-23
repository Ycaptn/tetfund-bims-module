
<div class="col-lg-12">
    <div class="card border-radius-0 border border-1 border-default m-0 p-1">
        <div class="row p-2">
            <div class="col-md-1 text-left">
                @if(empty($data_item->photo))
                    <span class="fa fa-user fa-3x"> </span>
                @else
                    <img src="{{ $data_item->photo }}" class="rounded-circle col-sm-12" width="100%" alt="No Pics...">
                @endif
            </div>
            <div class="col-md-10 text-left">
                <b>NAME: &nbsp; </b>
                <span>
                    {{ $data_item->first_name??''}}
                    {{ $data_item->last_name??'' }}                    
                </span><br>

                <b>EMAIL: &nbsp;</b>
                <span>{{ $data_item->email??'' }}</span><br>

                <b>INSTITUTION: &nbsp;</b>
                <i>
                    @if(!empty($data_item->institution_meta_data))
                        {{ json_decode($data_item->institution_meta_data)->name??'N/A' }}
                    @else
                        N/A
                    @endif
                </i><br>
            </div>

            <div class="col-md-1">    
                <a data-toggle="tooltip" 
                    title="Preview" 
                    data-val='{{$data_item->id}}' 
                    class="btn btn-sm btn-primary btn-preview-mdl-bims_known_user-modal me-1 d-print-none" href="#">
                    <i class="fa fa-eye small"></i>
                </a>

                <a data-toggle="tooltip" 
                    title="Delete" 
                    data-val='{{$data_item->id}}' 
                    class="btn btn-sm btn-danger btn-delete-mdl-bims_known_user me-1 d-print-none small" href="#">
                    <i class="bx bxs-trash-alt"></i>
                </a>
            </div>          
        </div>
    </div>
</div>


