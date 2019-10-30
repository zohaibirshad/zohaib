@extends('layouts.dashboard_master')
@section('title', 'Milestones')
@section('subtitle')
Milestones for <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->name }}</a>
@endsection

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">               
                @if(sizeof($milestones) == 0)
                <p class="text-center text-muted">NO MILESTONES FOR THIS JOB</p>
                @else
                <h3><i class="icon-material-outline-access-time"></i> 40% Completed</h3>
                @endif
            </div>
            {{-- approved, completed, paid --}}
            {{-- approved, completed, paid --}}
    
            @if(sizeof($milestones) > 0)
            <div class="content pb-1">
                
                <ul class="timeline">
                    @forelse ($milestones as $milestone)
                    <li class="event {{ $milestone->status == 'done' ? 'done' : 'notdone'}}">
                        <h3>UI Design
                            @if($milestone->status == 'done') 
                            <i class="icon-material-outline-check-circle text-success"></i>
                            @role('hirer')
                            <span class="float-right">
                                <a href="#small-dialog-1"  class="popup-with-zoom-anim button btn-xs">
                                    Modify
                                </a>
                            </span>
                            @endrole
                            @endif

                            @if($milestone->status == 'not done') 
                            @role('freelancer')
                            <span class="float-right">
                                <a href="#"  class="button btn-xs">
                                    <i class="icon-material-outline-check-circle"></i> Mark as Completed
                                </a>
                            </span>
                            @endrole
                            @endif
                        </h3>
                        <p>${{ $milestone->cost }}</p>
                    </li>
                    @empty
                    <p class="text-center text-muted py-3">NO MILESTONES FOR THIS JOB</p>
                    @endforelse
                </ul>
               
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Release Payment</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Release Payment to David for Milestone?</h3>
					<div class="bid-acceptance margin-top-15">
						$600
					</div>

				</div>

				<!-- Button -->
				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="terms">Yes, Release <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->
@endsection