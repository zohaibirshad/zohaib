@extends('layouts.dashboard_master')
@section('title', 'New Jobs')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-assignment"></i> Jobs List</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                   @forelse ($jobs as $job)
                   <li>
                    <!-- Job Listing -->
                    <div class="job-listing width-adjustment">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">

                            <!-- Details -->
                            <div class="job-listing-description">
                                <h3 class="job-listing-title"><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->name }}</a></h3>

                                <!-- Job Listing Footer -->
                                {{-- <div class="job-listing-footer">
                                    <ul>
                                        <li><i class="icon-material-outline-access-time"></i> 23 hours left</li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Task Details -->
                    <ul class="dashboard-task-info">
                        <li><strong>
                            ${{ number_format($job->job_budget->from) }} 
                            @if($job->job_budget->from != $job->job_budget->to) 
                            - ${{ number_format($job->job_budget->to) }} 
                            @endif
                        </strong><span class="text-capitalize">
                            {{ $job->job_budget->type }} Rate</span></li>
                    </ul>

                    <!-- Buttons -->
                    <div class="buttons-to-right always-visible">
                        <a href="{{ route('bidders', $job->slug) }}" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> Manage Bidders <span class="button-info">{{ $job->bids_count }}</span></a>
                        {{-- <a href="#" class="button gray ripple-effect ico" title="Edit" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                        <a href="#" class="button gray ripple-effect ico" title="Remove" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a> --}}
                    </div>
                </li>
                   @empty
                   <p class="text-center text-muted py-3">YOU HAVE NO NEW JOBS</p>
                   @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection