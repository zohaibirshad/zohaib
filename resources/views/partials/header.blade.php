

	<!-- Header -->
	<div id="header">
		<div class="container">
			
			<!-- Left Side Content -->
			<div class="left-side">
				
				<!-- Logo -->
				<div id="logo">
					<a href="/"><img src="{{ asset('assets/images/logo.png') }}" alt=""></a>
				</div>

				<!-- Main Navigation -->
				<nav id="navigation">
					<ul id="responsive">
						@guest
						<li>
							<a href="{{ route('how-it-works') }}" class="{{ (request()->is('how-it-works')) ? 'current' : '' }}">
							How it works
							</a>
						</li>
						<li>
							<a href="{{ route('pricing') }}" class="{{ (request()->is('pricing')) ? 'current' : '' }}">
							Pricing
							</a>
						</li>						
						@endguest
						<li>
							<a href="{{ route('blog.index') }}" class="{{ (request()->is('blog')) ? 'current' : '' }}">
							Blog
							</a>
						</li>
						@auth
						<li>
							<a href="{{ route('freelancers.index') }}" class="{{ (request()->is('browse-freelancers')) ? 'current' : '' }}">
								Browse Freelancers
							</a>
						</li>
						<li>
							<a href="{{ route('jobs.index') }}" class="{{ (request()->is('browse-jobs')) ? 'current' : '' }}">
								Browse Jobs
							</a>
						</li>
						@endauth
						<li class="d-sm-block d-md-none">
							<a href="{{ route('post-job') }}" class="{{ (request()->is('post-job')) ? 'current' : '' }}">
								Post a job
							</a>
						</li>
						<li class="d-sm-block d-md-none">
							<a href="/login" class="{{ (request()->is('login')) ? 'current' : '' }}">
								Log In
							</a>
						</li>
						<li class="d-sm-block d-md-none">
							<a href="/register" class="{{ (request()->is('register')) ? 'current' : '' }}">
								Register
							</a>
						</li>
						<li class="d-none d-sm-none d-md-block">
							<a href="{{ route('post-job') }}" class="button button-sliding-icon ripple-effect" tabindex="0">
								<span class="text-white">Post a Job</span> 
								<i class="icon-material-outline-add-circle-outline text-white"></i>
							</a>
						</li>

					</ul>
				</nav>
				<div class="clearfix"></div>
				<!-- Main Navigation / End -->
				
			</div>
			<!-- Left Side Content / End -->


			<!-- Right Side Content / End -->
			<div class="right-side">

				@guest
				<div class="header-widget hide-on-mobile">

					<div class="header-notifications">
						<!-- Trigger -->
						<div class="header-notifications-trigger">
							<a href="/login" class="text-uppercase">Login</a>
						</div>
					</div>

					<div class="header-notifications">
						<!-- Trigger -->
						<div class="header-notifications-trigger">
							<a href="/register" class="text-uppercase">Register</a>
						</div>
					</div>

				</div>
				@endguest


				@auth
				<!-- User Menu -->
				<div class="header-widget">

					<!-- Messages -->
					<div class="header-notifications user-menu">
						<div class="header-notifications-trigger">
							<a href="#"><div class="user-avatar status-online">
								@if (sizeof(Auth::user()->profile->getMedia('profile')) == 0)
									<img src="{{ asset('assets/images/user-avatar-small-01.jpg') }}" alt=""></div>
								@else
									<img src="{{ Auth::user()->profile->getFirstMediaUrl('profile', 'thumb') }}" alt=""/> </div>
								@endif								
							</a>
						</div>

						<!-- Dropdown -->
						<div class="header-notifications-dropdown">

							<!-- User Status -->
							<div class="user-status">

								<!-- User Name / Avatar -->
								<div class="user-details">
									<div class="user-avatar status-online">
										<img src="{{ Auth::user()->profile->getFirstMediaUrl('profile', 'thumb') ?? asset('assets/images/user-avatar-small-01.jpg')  }}" alt=""/>
									</div>
									<div class="user-name">
										{{ Auth::user()->name }} <span class="text-capitalize">{{ Auth::user()->profile->type }}</span>
									</div>
								</div>
								
								<!-- User Status Switcher -->
								<div class="status-switch">
									<label class="user-online {{ Auth::user()->profile->type == 'freelancer' ? 'current-status' : '' }}">Freelancer</label>
									<label class="user-invisible {{ Auth::user()->profile->type == 'hirer' ? 'current-status' : '' }}">Hirer</label>
									<!-- Status Indicator -->
									<span class="status-indicator" aria-hidden="true"></span>
								</div>

								<form id="update_role_form" action="/update_role" method="POST" style="display: none;">
									@csrf
									<input type="hidden" name="account_type" id="update_role_account_type">
                                </form>
						</div>
						
						<ul class="user-menu-small-nav">
							<li><a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
							<li><a href="{{ route('settings') }}"><i class="icon-material-outline-settings"></i> Settings</a></li>
							<li>
								<a href="{{ route('logout') }}"  onclick="event.preventDefault();
								document.getElementById('logout-form').submit();">
									<i class="icon-material-outline-power-settings-new"></i> Logout
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            	    @csrf
                                </form>
							</li>
						</ul>

						</div>
					</div>

				</div>
				<!-- User Menu / End -->
				@endauth

				<!-- Mobile Navigation Button -->
				<span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>

			</div>
			<!-- Right Side Content / End -->

		</div>
	</div>
	<!-- Header / End -->