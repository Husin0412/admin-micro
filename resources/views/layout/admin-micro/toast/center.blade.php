@if(Session::has('response'))
<div class="row w-100">
    <div class="col-lg-4 mx-auto">
        <div class="toast toast-center @if(Session::get('response') === 'success') bg-success @else  @endif "
            role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000">
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
    </div>
</div>
@endif