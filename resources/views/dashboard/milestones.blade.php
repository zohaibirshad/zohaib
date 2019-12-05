@extends('layouts.dashboard_master')
@section('title', 'Milestones')
@section('subtitle')
Milestones for <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
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
                <h3><i class="icon-material-outline-access-time"></i> {{ $completion }}% Completed</h3>
                @endif
            </div>
            {{-- approved, completed, paid --}}
            {{-- approved, completed, paid --}}
    
            @if(sizeof($milestones) > 0)
            <div class="content pb-1">
                
                <ul class="timeline">
                    @forelse ($milestones as $milestone)
                    <li class="event {{ $milestone->status == 'done' ? 'done' : 'notdone'}}">
                        <h3> <a href="#small-dialog-2"  class="popup-with-zoom-anim milestoneDetails" data-milestone="{{ $milestone }}">
                            {{ $milestone->heading }}</a>
                            @if($milestone->status == 'done') 
                            <i class="icon-material-outline-check-circle text-success"></i>
                            
                            @if($milestone->is_paid)
                            <span class="badge badge-success float-right">Paid</span>
                            @else
                            @role('hirer')
                            <span class="float-right">
                                <a href="#small-dialog-1"  class="popup-with-zoom-anim button btn-xs pay" data-milestone="{{ $milestone }}">
                                    Pay
                                </a>
                            </span>
                            @endrole
                            @endif
                            
                            @endif

                            @if($milestone->status == 'not done') 
                            @role('freelancer')
                            <span class="float-right">
                                <a href="#small-dialog-3"  class="popup-with-zoom-anim button btn-xs completedBtn" data-milestone="{{ $milestone }}">
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
					<h3 id="releasePayment">Release Payment to David for Milestone?</h3>
					<div class="bid-acceptance margin-top-15" id="paymentCost">
						$600
					</div>

				</div>

                <!-- Button -->
                <form action="" method="post" id="releasePaymentForm">
                    @csrf
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="releasePaymentForm">Yes, Release <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-2" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Milestone Details</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
                    <h3 id="milestoneHeading">DD</h3>
                    <div class="bid-acceptance margin-top-15">
                        <p id="milestoneCost"></p>
					</div>
					<div class="margin-top-15">
						<p id="milestoneActivity"></p>
					</div>

				</div>

				
			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-3" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Confirm Completion</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Are you sure you want to mark this as completed?</h3>

				</div>

                <!-- Button -->
                <form action="" method="post" id="completionForm">
                    @csrf
                    <input type="hidden" name="status" value="done">
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="completionForm">Yes <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->
@endsection

@push('custom-scripts')
    <script>
        $(document).ready(function(){
            $('.milestoneDetails').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);

                $('#milestoneHeading').text(milestone.heading);
                $('#milestoneActivity').text(milestone.activity);
                $('#milestoneCost').text('$'+ThousandSeparator(milestone.cost));
            });

            $('.pay').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);

                $('#releasePayment').text('Release Payment to '+ milestone.profile.name + ' for Milestone?');
                $('#paymentCost').text('$'+ThousandSeparator(milestone.cost));
                $('#releasePaymentForm').attr('action', 'release_payment/'+milestone.uuid);
            });

            $('.completedBtn').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);
                $('#completionForm').attr('action', 'update_milestone/'+milestone.uuid);
            });

            function ThousandSeparator(nStr) {
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
            }
        });
    </script>
@endpush