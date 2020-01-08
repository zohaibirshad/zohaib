@extends('layouts.dashboard_master')
@section('title', 'Subscription')

@section('content')
	<div class="row">

		<div class="col-xl-12">
			@auth
			<!-- Billing Cycle  -->
			<div class="margin-bottom-70 flex flex-row">
			@isset($my_plan->pivot)
				<div class="mx-3 px-4 py-3 bg-orange-500 shadow-lg text-white"><h3 class="text-white ">Current Plan: {{ $my_plan->title ?? '' }} - Bids {{ $my_plan->quantity ?? '' }}</h3></div>
				<div class="mx-3 px-4 py-3 bg-orange-500 shadow-lg text-white"><h3 class="text-white">Bids Used: {{ $my_plan->pivot->count ?? '' }}</h3></div>
				<div class="mx-3"><a href="../cancel/subscription"><button class=" bg-light-blue-500 shadow text-white px-16 text-lg py-3 font-bold rounded-full">CANCEL</button></a></div>
			</div>
			@endisset
			@empty($my_plan->pivot)
			<p>You are not subscribe to any plans yet.. <a href="../pricing">Subscibe now</a>
			@endempty
			@endauth

			


		</div>

	</div>


<div class="margin-top-80"></div>


@endsection