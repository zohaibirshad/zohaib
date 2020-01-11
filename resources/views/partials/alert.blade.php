@if (session('success'))
<div class="notification success closeable">
        <p class="text-center" ><i class="icon-line-awesome-check-circle" style="font-size: 20px;"></i> {{ session('success') }}</p>
        <a class="close" href="#"></a>
    </div>
@endif

@if (session('error'))
<div class="notification error closeable">
        <p class="text-center" ><i class="icon-line-awesome-exclamation-circle" style="font-size: 20px;"></i> {{ session('error') }}</p>
        <a class="close" href="#"></a>
    </div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="notification error closeable">
            <p class="text-center">
                <i class="icon-line-awesome-exclamation-circle" style="font-size: 20px;"></i> {{ $error }}
            </p>
            <a class="close" href="#"></a>
        </div>
@endforeach

@endif

@auth
@if(auth()->user()->review != 'successful')
<div class="notification error closeable">
        <p class="text-center" ><i class="icon-line-awesome-exclamation-circle" style="font-size: 20px;"></i>Please upload supporting documents to be fully verified to bid for jobs. <a href="../verify-profile"> Verify Now!</a></p>
        <a class="close" href="#"></a>
    </div>
@endif
@endauth
