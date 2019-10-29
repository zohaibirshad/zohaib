@extends('layouts.dashboard_master')
@section('title', 'My Active Bids')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-gavel"></i> Bids List</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @forelse ($bids as $bid)
                    <li>
                            <!-- Job Listing -->
                            <div class="job-listing width-adjustment">
    
                                <!-- Job Listing Details -->
                                <div class="job-listing-details">
    
                                    <!-- Details -->
                                    <div class="job-listing-description">
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $bid->job->slug) }}">{{ $bid->job->name }}</a></h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Task Details -->
                            <ul class="dashboard-task-info">
                                <li><strong>${{ $bid->rate }}</strong><span>{{ $bid->rate_type }}</span></li>
                                <li><strong>{{ $bid->delivery_time }} {{ $bid->delivery_type }}</strong><span>Delivery Time</span></li>
                            </ul>
    
                           
        
                            <!-- Buttons -->
                            <div class="buttons-to-right always-visible">
                                <a href="#small-dialog" class="popup-with-zoom-anim button dark ripple-effect ico" title="Edit Bid" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                                <a href="#" class="button red ripple-effect ico" title="Cancel Bid" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                            </div>
                        </li> 
                    @empty
                        <p class="text-center text-muted py-3">YOU HAVE NO BIDS CURRENTLY</p>
                    @endforelse

                </ul>
            </div>
        </div>
    </div>
</div>








<!-- Edit Bid Popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Edit Bid</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
						
					<!-- Bidding -->
					<div class="bidding-widget">
						<!-- Headline -->
						<span class="bidding-detail">Set your <strong>minimal hourly rate</strong></span>

						<!-- Price Slider -->
						<div class="bidding-value">$<span id="biddingVal"></span></div>
						<input class="bidding-slider" type="text" value="" data-slider-handle="custom" data-slider-currency="$" data-slider-min="10" data-slider-max="60" data-slider-value="40" data-slider-step="1" data-slider-tooltip="hide" />
						
						<!-- Headline -->
						<span class="bidding-detail margin-top-30">Set your <strong>delivery time</strong></span>

						<!-- Fields -->
						<div class="bidding-fields">
							<div class="bidding-field">
								<!-- Quantity Buttons -->
								<div class="qtyButtons with-border">
									<div class="qtyDec"></div>
									<input type="text" name="qtyInput" value="2">
									<div class="qtyInc"></div>
								</div>
							</div>
							<div class="bidding-field">
								<select class="selectpicker default with-border">
									<option selected>Days</option>
									<option>Hours</option>
								</select>
							</div>
						</div>
				</div>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect" type="submit">Save Changes <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Edit Bid Popup / End -->
@endsection