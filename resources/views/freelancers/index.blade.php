@extends('layouts.master')
@section('title', 'Browse Freelancers')

@section('content')

<!-- Spacer -->
<div class="margin-top-90"></div>
<!-- Spacer / End-->

<!-- Page Content
================================================== -->
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4">
			<div class="sidebar-container">
				
				<!-- Location -->
				<div class="sidebar-widget">
					<h3>Location</h3>
					<div class="input-with-icon">
						<div id="autocomplete-container">
							<input id="autocomplete-input" type="text" placeholder="Location">
						</div>
						<i class="icon-material-outline-location-on"></i>
					</div>
				</div>

				<!-- Category -->
				<div class="sidebar-widget">
					<h3>Category</h3>
					<select class="selectpicker default" multiple data-selected-text-format="count" data-size="7" title="All Categories" >
						<option>Admin Support</option>
						<option>Customer Service</option>
						<option>Data Analytics</option>
						<option>Design & Creative</option>
						<option>Legal</option>
						<option>Software Developing</option>
						<option>IT & Networking</option>
						<option>Writing</option>
						<option>Translation</option>
						<option>Sales & Marketing</option>
					</select>
				</div>

				<!-- Tags -->
				<div class="sidebar-widget">
					<h3>Skills</h3>

					<div class="tags-container">
						<div class="tag">
							<input type="checkbox" id="tag1"/>
							<label for="tag1">front-end dev</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag2"/>
							<label for="tag2">angular</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag3"/>
							<label for="tag3">react</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag4"/>
							<label for="tag4">vue js</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag5"/>
							<label for="tag5">web apps</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag6"/>
							<label for="tag6">design</label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag7"/>
							<label for="tag7">wordpress</label>
						</div>
					</div>
					<div class="clearfix"></div>

					<!-- More Skills -->
					<div class="keywords-container margin-top-20">
						<div class="keyword-input-container">
							<input type="text" class="keyword-input" placeholder="add more skills"/>
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list"><!-- keywords go here --></div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="clearfix"></div>

			</div>
		</div>
		<div class="col-xl-9 col-lg-8 content-left-offset">

			<h3 class="page-title">Search Results</h3>

			<div class="notify-box margin-top-15">
				<div class="switch-container">
					{{-- <label class="switch"><input type="checkbox"><span class="switch-button"></span><span class="switch-text">Turn on email alerts for this search</span></label> --}}
				</div>

				<div class="sort-by">
					<span>Sort by:</span>
					<select class="selectpicker hide-tick">
						<option>Relevance</option>
						<option>Rating</option>
						<option>Success %</option>
						<option>Newest</option>
						<option>Oldest</option>
					</select>
				</div>
			</div>
			
			<!-- Freelancers List Container -->
			<div class="freelancers-container freelancers-list-layout compact-list margin-top-35">
				
				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
						<div class="freelancer-overview-inner">
							
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
								<div class="verified-badge"></div>
								<a href="{{ route('freelancers.show', 1) }}"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="{{ route('freelancers.show', 1) }}">Tom Smith <img class="flag" src="{{ asset('assets/images/flags/gb.svg') }}" alt="" title="United Kingdom" data-tippy-placement="top"></a></h4>
								<span>UI/UX Designer</span>
								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="4.9"></div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> London</strong></li>
								<li>Rate <strong>$60 / hr</strong></li>
								<li>Job Success <strong>95%</strong></li>
							</ul>
						</div>
						<a href="{{ route('freelancers.show', 1) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
						<div class="freelancer-overview-inner">
							
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
								<div class="verified-badge"></div>
								<a href="{{ route('freelancers.show', 1) }}"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="{{ route('freelancers.show', 1) }}">David Peterson <img class="flag" src="{{ asset('assets/images/flags/de.svg') }}" alt="" title="Germany" data-tippy-placement="top"></a></h4>
								<span>iOS Expert + Node Dev</span>
								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="4.2"></div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> Berlin</strong></li>
								<li>Rate <strong>$40 / hr</strong></li>
								<li>Job Success <strong>88%</strong></li>
							</ul>
						</div>
						<a href="{{ route('freelancers.show', 1) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
						<div class="freelancer-overview-inner">
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
                                <a href="{{ route('freelancers.show', 1) }}"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="{{ route('freelancers.show', 1) }}">Marcin Kowalski <img class="flag" src="{{ asset('assets/images/flags/pl.svg') }}" alt="" title="Poland" data-tippy-placement="top"></a></h4>
								<span>Front-End Developer</span>
								<!-- Rating -->
								<span class="company-not-rated margin-bottom-5">Minimum of 3 votes required</span>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> Warsaw</strong></li>
								<li>Rate <strong>$50 / hr</strong></li>
								<li>Job Success <strong>100%</strong></li>
							</ul>
						</div>
						<a href="{{ route('freelancers.show', 1) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
								<div class="verified-badge"></div>
								<a href="{{ route('freelancers.show', 1) }}"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="{{ route('freelancers.show', 1) }}">Sindy Forest <img class="flag" src="{{ asset('assets/images/flags/au.svg') }}" alt="" title="Australia" data-tippy-placement="top"></a></h4>
								<span>Magento Certified Developer</span>
								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="5.0"></div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> Brisbane</strong></li>
								<li>Rate <strong>$70 / hr</strong></li>
								<li>Job Success <strong>100%</strong></li>
							</ul>
						</div>
						<a href="{{ route('freelancers.show', 1) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

				<!--Freelancer -->
				<div class="freelancer">

					<!-- Overview -->
					<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
							<!-- Bookmark Icon -->
							<span class="bookmark-icon"></span>
							
							<!-- Avatar -->
							<div class="freelancer-avatar">
                                <a href="{{ route('freelancers.show', 1) }}"><img src="{{ asset('assets/images/user-avatar-big-01.jpg') }}" alt=""></a>
							</div>

							<!-- Name -->
							<div class="freelancer-name">
								<h4><a href="{{ route('freelancers.show', 1) }}">Sebastiano Piccio <img class="flag" src="{{ asset('assets/images/flags/it.svg') }}" alt="" title="Italy" data-tippy-placement="top"></a></h4>
								<span>Laravel Dev</span>
								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="4.5"></div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Details -->
					<div class="freelancer-details">
						<div class="freelancer-details-list">
							<ul>
								<li>Location <strong><i class="icon-material-outline-location-on"></i> Milan</strong></li>
								<li>Rate <strong>$80 / hr</strong></li>
								<li>Job Success <strong>89%</strong></li>
							</ul>
						</div>
						<a href="{{ route('freelancers.show', 1) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
					</div>
				</div>
				<!-- Freelancer / End -->

	
			</div>
			<!-- Freelancers Container / End -->


			<!-- Pagination -->
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					<!-- Pagination -->
					<div class="pagination-container margin-top-40 margin-bottom-60">
						<nav class="pagination">
							<ul>
								<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
								<li><a href="#" class="ripple-effect">1</a></li>
								<li><a href="#" class="current-page ripple-effect">2</a></li>
								<li><a href="#" class="ripple-effect">3</a></li>
								<li><a href="#" class="ripple-effect">4</a></li>
								<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
			<!-- Pagination / End -->

		</div>
	</div>
</div>

@endsection