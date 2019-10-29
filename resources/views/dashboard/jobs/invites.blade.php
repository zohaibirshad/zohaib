@extends('layouts.dashboard_master')
@section('title', 'Job Invites')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-gavel"></i> Job Invites</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @forelse ($invites as $invite)
                    <li>
                            <!-- Job Listing -->
                            <div class="job-listing width-adjustment">
    
                                <!-- Job Listing Details -->
                                <div class="job-listing-details">
    
                                    <!-- Details -->
                                    <div class="job-listing-description">
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $invite->job->slug) }}">{{ $invite->job->name }}</a></h3>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- <!-- Task Details -->
                            <ul class="dashboard-task-info">
                                <li><strong>$40</strong><span>Hourly Rate</span></li>
                                <li><strong>2 Days</strong><span>Delivery Time</span></li>
                            </ul> --}}
    
                            <!-- Buttons -->
                            <div class="buttons-to-right always-visible">
                                <a href="#" class="button primary ripple-effect ico" title="Accept Invite" data-tippy-placement="top"><i class="icon-feather-check-circle"></i></a>
                                <a href="#" class="button red ripple-effect ico" title="Reject Invite" data-tippy-placement="top"><i class="icon-line-awesome-close"></i></a>
                            </div>
                        </li> 
                    @empty
                    <p class="text-center text-muted py-3">YOU HAVE NO INVITE</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection