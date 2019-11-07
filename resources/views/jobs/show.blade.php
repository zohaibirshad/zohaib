@extends('layouts.master')
@section('title', $job->title)

@section('content')

<!-- Titlebar
================================================== -->
<div class="single-page-header" data-background-image="{{ asset('assets/images/single-task.jpg') }}">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="single-page-header-inner">
					<div class="left-side">
                        <div class="header-image"><a href="#"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a></div>
						<div class="header-details">
							<h3>{{ $job->title }}</h3>
							<h5>About the Employer</h5>
							<ul>
								<li><a href="#"><i class="icon-feather-user"></i>{{$job->owner->name}}</a></li>
								<li><div class="star-rating" data-rating="5.0"></div></li>
								<li><img class="flag" src="{{ asset('assets/images/flags/'.$job->owner->country->code.'.svg') }}" alt=""> Ghana</li>
								
								@if($job->owner->verified == 1)
                                <li><div class="verified-badge-with-title">Verified</div></li>
                                @endif
							</ul>
						</div>
					</div>
					<div class="right-side">
						<div class="salary-box">
							<div class="salary-type">Project Budget</div>
							<div class="salary-amount">${{ $job->min_budget }}  - ${{ $job->max_budget }} </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Page Content
================================================== -->
<div class="container">
	<div class="row">

		
		
		<!-- Content -->
		<div class="col-xl-8 col-lg-8 content-right-offset">
			
			<!-- Description -->
			<div class="single-page-section">
				<h3 class="margin-bottom-25">Project Description</h3>
				<div>
					{!! $job->description !!}
				</div>
			</div>

			{{-- <!-- Atachments -->
			<div class="single-page-section">
				<h3>Attachments</h3>
				<div class="attachments-container">
					<a href="#" class="attachment-box ripple-effect"><span>Project Brief</span><i>PDF</i></a>
				</div>
			</div> --}}

			<!-- Skills -->
			<div class="single-page-section">
				<h3>Skills Required</h3>
				<div class="task-tags">
					@foreach ($job->skills as $skill)
					<span>{{ $skill->title }}</span>
					@endforeach
				</div>
			</div>
			<div class="clearfix"></div>
			
			<!-- Freelancers Bidding -->
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-group"></i> Current Bids</h3>
				</div>
				<ul class="boxed-list-ul">
					<li>
						<div class="bid">
							<!-- Avatar -->
							<div class="bids-avatar">
								<div class="freelancer-avatar">
									<div class="verified-badge"></div>
									<a href="#"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
								</div>
							</div>
							
							<!-- Content -->
							<div class="bids-content">
								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="#">Tom Smith <img class="flag" src="{{ asset('assets/images/flags/gb.svg') }}" alt="" title="United Kingdom" data-tippy-placement="top"></a></h4>
									<div class="star-rating" data-rating="4.9"></div>
								</div>
							</div>
							
							<!-- Bid -->
							<div class="bids-bid">
								<div class="bid-rate">
									<div class="rate">$4,400</div>
									<span>in 7 days</span>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="bid">
							<!-- Avatar -->
							<div class="bids-avatar">
								<div class="freelancer-avatar">
									<a href="#"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></a>
								</div>
							</div>
							
							<!-- Content -->
							<div class="bids-content">
								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="#">Marcin Kowalski <img class="flag" src="{{ asset('assets/images/flags/pl.svg') }}" alt="" title="Poland" data-tippy-placement="top"></a></h4>
									<span class="not-rated">Minimum of 3 votes required</span>

								</div>
							</div>
							
							<!-- Bid -->
							<div class="bids-bid">
								<div class="bid-rate">
									<div class="rate">$3,800</div>
									<span>In 20 days</span>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>

		</div>
		

		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">

				{{-- <div class="countdown green margin-bottom-35">6 days, 23 hours left</div> --}}

				@role('freelancer')
				<div class="sidebar-widget">
					<div class="bidding-widget">
						<div class="bidding-headline"><h3>Bid on this job!</h3></div>
						<div class="bidding-inner">

							<!-- Headline -->
							<span class="bidding-detail">Set your <strong>minimal rate</strong></span>

							<!-- Price Slider -->
							<div class="bidding-value">$<span id="biddingVal"></span></div>
							<input class="bidding-slider" type="text" value="" data-slider-handle="custom" data-slider-currency="$" data-slider-min="{{ $job->max_budget / 2 }}" data-slider-max="{{ $job->max_budget }}" data-slider-value="auto" data-slider-step="50" data-slider-tooltip="hide" />
							
							<!-- Headline -->
							<span class="bidding-detail margin-top-30">Set your <strong>delivery time</strong></span>

							<!-- Fields -->
							<div class="bidding-fields">
								<div class="bidding-field">
									<!-- Quantity Buttons -->
									<div class="qtyButtons">
										<div class="qtyDec"></div>
										<input type="text" name="qtyInput" value="1">
										<div class="qtyInc"></div>
									</div>
								</div>
								<div class="bidding-field">
									<select class="selectpicker default">
										<option selected>Days</option>
										<option>Hours</option>
									</select>
								</div>
							</div>

							<!-- Button -->
							<button id="snackbar-place-bid" class="button ripple-effect move-on-hover full-width margin-top-30"><span>Place a Bid</span></button>

						</div>
						{{-- <div class="bidding-signup">Don't have an account? <a href="#sign-in-dialog" class="register-tab sign-in popup-with-zoom-anim">Sign Up</a></div> --}}
					</div>
				</div>
				@endrole

				<!-- Sidebar Widget -->
				<div class="sidebar-widget">
					<h3>Bookmark or Share</h3>
					<button class="bookmark-button margin-bottom-25 {{ $isBookmakedByUser == 1 ? 'bookmarked' : '' }}" onclick="bookmark({{ $job->id }})">
						<span class="bookmark-icon"></span>
						<span class="bookmark-text">Bookmark</span>
						<span class="bookmarked-text">Bookmarked</span>
					</button>

					<!-- Copy URL -->
					<div class="copy-url">
						<input id="copy-url" type="text" value="" class="with-border">
						<button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
					</div>

					<!-- Share Buttons -->
					<div class="share-buttons margin-top-25">
						<div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
						<div class="share-buttons-content">
							<span>Interesting? <strong>Share It!</strong></span>
							<ul class="share-buttons-icons">
								<li><a href="#" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
								<li><a href="#" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
								<li><a href="#" data-button-color="#dd4b39" title="Share on Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus-g"></i></a></li>
								<li><a href="#" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
							</ul>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>

<script>
	function bookmark(id){
		axios.post('bookmarks-toggle-api', {
			job_id: id,
		})
        .then(response => {
            Snackbar.show({
				text: response.data.message,
				pos: 'bottom-center',
				showAction: false,
				actionText: "Dismiss",
				duration: 3000,
				textColor: '#fff',
				backgroundColor: '#383838'
			}); 
        });
	}
</script>
@endsection