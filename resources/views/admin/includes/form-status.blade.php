@if (session('message'))
  <div class="alert alert-solid-success alert-bold" role="alert">
      <div class="alert-text">{{ session('message') }}</div>
      <div class="alert-close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="la la-close"></i></span>
          </button>
      </div>
  </div>
@endif

@if(session()->has('status'))
    @if(session()->get('status') == 'wrong')
        <div class="alert alert-danger status-box alert-dismissable fade show" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
            {{ session('message') }}
        </div>
    @endif
@endif

@if (session('success'))
  <div class="alert alert-success fade show" role="alert">
      <div class="alert-icon"><i class="flaticon2-information"></i></div>
      <div class="alert-text">{{ session('success') }}</div>
      <div class="alert-close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="la la-close"></i></span>
          </button>
      </div>
  </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-warning"></i></div>
        <div class="alert-text">{{ session('error') }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif



