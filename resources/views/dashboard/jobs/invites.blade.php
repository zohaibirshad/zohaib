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
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $invite->job->slug) }}">{{ $invite->job->title }}</a></h3>
                                        @role('hirer')
                                            <div class="flex flex-row">
                                                <button class="bg-green-500 text-white p-1 px-4 rounded shadow-lg">{{ strtoupper($invite->status) }}</button>
                                            </div>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Task Details -->
                            <ul class="dashboard-task-info">
                            @foreach ($invite->getMedia('project_files') as $file)
                            <li>
                                <a href="storage/{{ $file->id }}/{{ $file->file_name }}" target="new" >
                                    <div class="attachment-box hover:bg-orange-500 hover:text-white cursor-pointer">
                                        <span class="text-capitalize hover:text-white ">{{ $file->name }} </span>
                                        <i class="text-uppercase hover:text-white">{{ $file->extension }}</i>
                                    </div> 
                                </a>
                            </li>
                            @endforeach
                                <li><strong>${{ $invite->job->min_budget ==  $invite->job->max_budget ? $invite->job->min_budget : $invite->job->min_budget. " - " .$invite->job->max_budget }}</strong><span>{{ $invite->job->budget_type == 'fixed' ? 'Fixed' : 'Hourly' }} rate</span></li>
                            </ul> 
    
                            <!-- Buttons -->
                            @role('freelancer')
                            <div class="buttons-to-right always-visible">
                                <a href="{{ route('jobs.show', $invite->job->slug) }}" class="button primary ripple-effect ico" title="Accept Invite" data-tippy-placement="top"><i class="icon-feather-check-circle"></i></a>
                                <a href="#" onclick="reject_invite('{{ $invite->uuid }}')" class="button red ripple-effect ico" title="Reject Invite" data-tippy-placement="top"><i class="icon-line-awesome-close"></i></a>
                            </div>
                            @endrole
                        </li> 
                    @empty
                    <p class="text-center text-muted py-3">
                        @role('hirer')
                        YOU HAVE SENT NO INVITE
                        @endrole
                        @role('freelancer')
                        YOU HAVE NO INVITE
                        @endrole
                    </p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
<script>

function reject_invite(uuid){
    var result = confirm('Do you want to Reject Invite');
    if(result){
        axios.get("{{ url('reject-invite') }}/" + uuid)
            .then(function(r){
               window.location.reload();
            })
    }
}
</script>
@endsection