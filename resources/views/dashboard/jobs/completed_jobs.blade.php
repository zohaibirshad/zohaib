@extends('layouts.dashboard_master')
@section('title', 'Completed Jobs')

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
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a> </h3>
    
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
                                <li><strong>{{ $job->milestones_count }}</strong><span>Milestones</span></li>
                                <li><strong>${{ number_format($job->min_budget) }} - ${{ number_format($job->max_budget) }}</strong><span class="text-capitalize">{{ $job->budget_type }} Rate</span></li>
                            </ul>
    
                            <!-- Buttons -->
                            @role('hirer')
                            <div class="buttons-to-right always-visible">
                                <a href="{{ route('milestones', $job->slug) }}" class="button ripple-effect"><i class="icon-line-awesome-list-alt"></i> View Milestones <span class="button-info">{{ $job->milestones_count }}</span></a>
                            </div>
                            @endrole
                        </li>  
                    @empty
                    <p class="text-center text-muted py-3">YOU HAVE NO COMPLETED JOBS</p> 
                    @endforelse
                    </li>

                    
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection