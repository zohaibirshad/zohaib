@extends('layouts.dashboard_master')
@section('title', 'Settings')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-account-circle"></i> Basic Profile</h3>
            </div>

            <div class="content with-padding padding-bottom-0">

                <form method="post" action="/update_basic_info" enctype="multipart/form-data">
                    @csrf

                <div class="row">
                    <div class="col-auto">
                        <div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Avatar">
                           @if (sizeof($user->getMedia('profile')) == 0)
                            <img class="profile-pic" src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="" />
                           @else
                           <img class="profile-pic" src="{{ $user->getFirstMediaUrl('profile') }}" alt="" /> 
                           @endif
                            <div class="upload-button"></div>
                            <input class="file-upload" type="file" accept="image/*" name="picture"/>
                        </div>
                    </div>

                    <div class="col">
                        <div class="row">

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Full Name</h5>
                                    <div class="input-with-icon-left">
                                        <i class="icon-material-outline-account-circle"></i>
                                        <input type="text" class="with-border" value="{{ $user->name }}" name="name">
                                    </div>
                                </div>
                            </div>                            

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Email</h5>
                                    <div class="input-with-icon-left">
                                        <i class="icon-material-baseline-mail-outline"></i>
                                        <input type="text" class="with-border" value="{{ $user->email }}" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Country</h5>
                                    <select class="selectpicker with-border" data-size="7" title="Select Country" data-live-search="true" name="country_id" id="country">
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected="selected"' : '' }}>
                                            {{ $country->name }}
                                        </option> 
                                        @endforeach                                 
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <!-- Account Type -->
                                <div class="submit-field">
                                    <h5>Phone</h5>
                                    <div class="input-with-icon-left">
                                        <i class="intl_num">+0</i>
                                        <input type="text" class="with-border" value="{{ $user->phone }}" name="phone">
                                    </div>                                    
                                </div>
                            </div>

                            {{-- <div class="col-xl-6">
							 	<!-- Account Type -->
							 	<div class="submit-field">
							 		<h5>Account Type</h5>
							 		<div class="account-type">
							 			<div>
							 				<input type="radio" name="account_type" value="freelancer" id="freelancer-radio" class="account-type-radio" {{ $user->type == 'freelancer' ? 'checked="checked"' : '' }}/>
							 				<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
							 			</div>
							 			<div>
							 				<input type="radio" name="account_type" value="hirer" id="employer-radio" class="account-type-radio"  {{ $user->type == 'hirer' ? 'checked="checked"' : '' }}/>
							 				<label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Hirer</label>
							 			</div>
							 		</div>
							 	</div>
                             </div> --}}
                             
                             <div class="col-xl-12">
                                <hr>
                                <br>
                             </div>


                            <!-- Button -->
                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <button type="submit" class="button ripple-effect">Update Profile</button>
                                </div>
                            </div>      

                        </div>
                    </div>
                </div>

                </form>

            </div>
        </div>
    </div>

    @role('freelancer')
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-face"></i> Freelancer Profile</h3>
            </div>
            

            <div class="content">
                <form method="post" action="/update_freelancer_info" enctype="multipart/form-data">
                    @csrf
                    <ul class="fields-ul">
                    <li>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <div class="bidding-widget">
                                        <!-- Headline -->
                                        <span class="bidding-detail">Set your <strong>minimal hourly rate</strong></span>

                                        <!-- Slider -->
                                        <div class="bidding-value margin-bottom-10">$<span id="biddingVal"></span></div>
                                        <input class="bidding-slider" type="text" name="rate" value="{{ $user->rate }}" data-slider-handle="custom" data-slider-currency="$" data-slider-min="5" data-slider-max="150" data-slider-value="{{ $user->rate ?? 20 }}" data-slider-step="1" data-slider-tooltip="hide" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Skills <i class="help-icon" data-tippy-placement="right" title="Add up to 10 skills"></i></h5>

                                    <!-- Skills List -->
                                    {{-- <div class="keywords-container">
                                        <div class="keyword-input-container">
                                            <input type="text" class="keyword-input with-border" placeholder="e.g. Angular, Laravel"/>
                                            <button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
                                        </div>
                                        <div class="keywords-list">
                                            <span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">Angular</span></span>
                                            <span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">Vue JS</span></span>
                                            <span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">iOS</span></span>
                                            <span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">Android</span></span>
                                            <span class="keyword"><span class="keyword-remove"></span><span class="keyword-text">Laravel</span></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div> --}}
                                    <select class="selectpicker" data-size="7" data-live-search="true" name="skills[]" multiple>
                                        @foreach ($skills as $skill)
                                            <option value="{{ $skill->id }}"
                                                    {{ isset($user->skills) && in_array($skill->id, $user->skills()->pluck('id')->toArray()) ? 'selected' : '' }}
                                                >{{ $skill->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Attachments</h5>
                                    
                                    {{-- <!-- Attachments -->
                                    <div class="attachments-container margin-top-0 margin-bottom-0">
                                        @foreach ($user->getMedia('cv') as $file)
                                            <div class="attachment-box ripple-effect">
                                                <span class="text-capitalize">{{ $file->name }} </span>
                                                <i class="text-uppercase">{{ $file->extension }}</i>
                                                <button type="button" class="remove-attachment" data-tippy-placement="top" title="Remove"></button>
                                            </div> 
                                        @endforeach
                                    </div>
                                    <div class="clearfix"></div> --}}
                                    
                                    <!-- Upload Button -->
                                    <div class="uploadButton margin-top-0">
                                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload" name="documents[]" multiple/>
                                        <label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                                        <span class="uploadButton-file-name">Maximum file size: 2 MB</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Professional Headline</h5>
                                    <input type="text" class="with-border" value="{{ $user->headline }}" name="headline">
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <h5>Introduce Yourself</h5>
                                    <textarea cols="30" rows="5" class="with-border" name="description">{{ $user->description }}</textarea>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <button type="submit" class="button ripple-effect">Submit Changes</button>
                                </div>
                            </div> 

                        </div>
                    </li>
                </form>
            </ul>
            </div>
        </div>
    </div>
    @endrole

    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <form method="post" action="/update_password">
            @csrf
            <div id="test1" class="dashboard-box">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-lock"></i> Password & Security</h3>
                </div>

                <div class="content with-padding">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Current Password</h5>
                                <input type="password" name="current_password" class="with-border">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>New Password</h5>
                                <input type="password" name="password" class="with-border">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Repeat New Password</h5>
                                <input type="password" name="password_confirmation" class="with-border">
                            </div>
                        </div>

                        {{-- <div class="col-xl-12">
                            <div class="checkbox">
                                <input type="checkbox" id="two-step" checked>
                                <label for="two-step"><span class="checkbox-icon"></span> Enable Two-Step Verification via Email</label>
                            </div>
                        </div> --}}

                        <!-- Button -->
                        <div class="col-xl-12">
                            <button type="submit" class="button ripple-effect">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="my-6 col-xl-12">
    <div id="test1" class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-line-awesome-money"></i> Billing Information</h3>
        </div>
        <div class="flex flex-row justify-center items-center">
            <div id="spinner" style="display:none" class="spinner-border text-warning w-12 h-12 my-2"></div>
        </div>
        <div class="content with-padding col-xl-6" id="billing">
                <input placeholder="Card Holder Name" value="{{ $user->name }}" class="with-border" id="card-holder-name" type="text">
                <!-- Stripe Elements Placeholder -->
                <div class="mt-4" id="card-element"></div>

                <button  class="my-4 button ripple-effect" id="card-button" data-secret="{{ $intent->client_secret }}">
                    Update Payment Method
                </button>
        </div>
    </div>
    </div>
    
    {{-- <!-- Button -->
    <div class="col-xl-12">
        <a href="#" class="button ripple-effect big margin-top-30">Save Changes</a>
    </div> --}}
</div>
@endsection

@push('custom-scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    const stripe = Stripe('pk_test_5dfSYbt8bR3wfq8YcleK1YSE00CyBMueNa');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');

    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    

    cardButton.addEventListener('click', async (e) => {
        if(cardHolderName.value == ''){
            return alert('Enter Card Holder Name')
        }
        $('#spinner').show()
        $('#billing').hide()
        const { setupIntent, error } = await stripe.handleCardSetup(
            clientSecret, cardElement, {
                payment_method_data: {
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
            
            $('#spinner').hide()
            $('#billing').show()
            console.log(error);
            alert(error.message)
            
        } else {
            $('#spinner').hide();
            console.log(setupIntent);
            axios.post('billing/paymentmethod/update',{
                method: setupIntent.payment_method
            }).then(function(r){
                alert(r.data.message);
                console.log(r);
                $('#billing').show()
                
            }).catch(function(e){
                console.log(e);
                alert('Pls, try again')
                $('#billing').show()
                
            })
        }
    });
</script>
    <script>
        $(document).ready(function(){
            var userCountryCode = "{{ $user->country_id }}";
            if(userCountryCode != ""){
                setCountryCallCode({{ $user->country_id }});
            }
            $('#country').change(function(){
                var selected = $(this).find(":selected").val();

                setCountryCallCode(selected);
            });
        });

        function setCountryCallCode(selectedtCountryId){
            var countries = {!! json_encode($countries) !!};
            var selected = $.grep(countries, function(e){ return e.id == selectedtCountryId; })[0];
            $('.intl_num').text(selected.dial_code);
        }
    </script>
@endpush