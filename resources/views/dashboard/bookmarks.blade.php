@extends('layouts.dashboard_master')
@section('title', 'Bookmarks')

@section('content')

<!-- Row -->
<div class="row">

	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box margin-top-0">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-material-outline-business-center"></i> Bookmarked Jobs</h3>
			</div>

			<div class="content">
				<ul class="dashboard-box-list">
                </ul>
			</div>
		</div>
	</div>

	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-material-outline-face"></i> Bookmarked Freelancers</h3>
			</div>

			<div class="content">
				<ul class="dashboard-box-list">
					<li>
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								<!-- Avatar -->
								<div class="freelancer-avatar">
									<div class="verified-badge"></div>
									<a href="#"><img src="{{ asset('assets/images/user-avatar-big-02.jpg') }}" alt=""></a>
								</div>
								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="#">David Peterson <img class="flag" src="{{ asset('assets/images/flags/de.svg') }}" alt="" title="Germany" data-tippy-placement="top"></a></h4>
									<span>iOS Expert + Node Dev</span>
									<!-- Rating -->
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.2"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Buttons -->
						<div class="buttons-to-right">
							<a href="#" class="button red ripple-effect ico" title="Remove" data-tippy-placement="left"><i class="icon-feather-trash-2"></i></a>
						</div>
					</li>
					<li>
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								
								<!-- Avatar -->
								<div class="freelancer-avatar">
									<a href="#"><img src="{{ asset('assets/images/user-avatar-big-02.jpg') }}" alt=""></a>
								</div>
								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="#">Marcin Kowalski <img class="flag" src="{{ asset('assets/images/flags/pl.svg') }}" alt="" title="Poland" data-tippy-placement="top"></a></h4>
									<span>Front-End Developer</span>
									<!-- Rating -->
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.7"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- Buttons -->
						<div class="buttons-to-right">
							<a href="#" class="button red ripple-effect ico" title="Remove" data-tippy-placement="left"><i class="icon-feather-trash-2"></i></a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

</div>
<!-- Row / End -->


@endsection