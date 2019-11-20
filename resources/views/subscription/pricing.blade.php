@extends('layouts.master')
@section('title', 'Pricing Plans')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')

<div class="container">
	<div class="row">

		<div class="col-xl-12">

			<!-- Billing Cycle  -->
			<!-- <div class="billing-cycle-radios margin-bottom-70">
				<div class="radio billed-monthly-radio">
					<input id="radio-5" name="radio-payment-type" type="radio" checked>
					<label for="radio-5"><span class="radio-label"></span> Billed Monthly</label>
				</div>

				<div class="radio billed-yearly-radio">
					<input id="radio-6" name="radio-payment-type" type="radio">
					<label for="radio-6"><span class="radio-label"></span> Billed Yearly <span class="small-label">Save 10%</span></label>
				</div>
			</div> -->

			<!-- Pricing Plans Container -->
			<div class="pricing-plans-container">

				@foreach($plans as $plan)
					<!-- Plan -->
					<div class="pricing-plan {{ $plan->recommended == 1 ? 'recommended' : '' }}">
						@if($plan->recommended == 1)
						<div class="recommended-badge">Recommended</div>
						@endif
						<h3>{{ $plan->title }}</h3>
						<p class="margin-top-10">{{ $plan->description }}</p>
						<div class="pricing-plan-label billed-monthly-label"><strong>${{ $plan->price }}</strong>/ monthly</div>
						<!-- <div class="pricing-plan-label billed-yearly-label"><strong>$529</strong>/ yearly</div> -->
						<div class="pricing-plan-features">
							<strong>Features of {{ $plan->title }}</strong>
							<ul>
								<li>{{ $plan->quantity }} Bids Each Month</li>
							</ul>
						</div>
						<a href="{{ route('checkout', $plan->id) }}" class="button full-width margin-top-20">Buy Now</a>
					</div>
				@endforeach

			</div>


		</div>

	</div>
</div>


<div class="margin-top-80"></div>
@endsection
