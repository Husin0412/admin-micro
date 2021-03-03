@if(Session::has('response'))
<div class="toast mt-2 mr-2 @if(Session::get('response') === 'success') bg-success @elseif(Session::get('response') === 'error') bg-danger @endif"
    data-delay="6000" style="position: fixed; top: 14px; right: 14px; z-index:9999;">
    <div class="toast-header">
        <i class="mdi mdi-checkbox-marked-circle-outline"></i>
        <strong class="ml-2 mr-auto">{{ strtoupper( (string) Session::get('response')) }}</strong>
        <small></small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        {{ Session::get('message') }}
    </div>
</div>
@endif