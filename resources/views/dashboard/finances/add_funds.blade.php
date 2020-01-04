@extends('layouts.dashboard_master')
@section('title', 'Add Funds')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-6">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3>
                    <i class="icon-line-awesome-money"></i> 
                    <img src="{{ asset('assets/images/stripe-logo.png') }}" height="30px">
                </h3>
            </div>
    
            <div class="content px-4 py-4">
                <div class="col-md-12">
                    <div class="card-label">
                        <input id="card-holder-name" name="nameOnCard" value="{{ Auth::user()->name }}" required type="text" placeholder="Cardholder Name">
                    </div>
                </div>

                <!-- Stripe Elements Placeholder -->
                <div class="mt-5 col-md-12 text-2xl" id="card-element"></div>

                <div class="row pt-5">
                    <div class="col-xl-12">
                        <div class="submit-field">
                            <div class="row">
                                <div class="col-xl-4">
                                    <select class="selectpicker with-border" id="currency">
                                        <option>USD</option>
                                    </select>
                                </div>
                                <div class="col-xl-8">
                                    <input id="amount" type="number" class="with-border" value="50" required>
                                    <small class="text-muted">Processing fee <span class="float-right">$0.99</span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-12"> 
                        <p>Total <span id="total" class="float-right"></span></p>
                    </div>
                    <div class="col-12">
                        <button  class="my-5 mx-1 button bg-blue-500 ripple-effect" id="card-button" data-secret="{{ $intent->client_secret }}">
                            <i class="icon-material-outline-check-circle mr-1"></i>
                            Confirm & Add $50.99
                        </button>
                    </div> 
                </div>
            </div>

        </div>
    </div>
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

    const amount = document.getElementById('amount');

    amount.addEventListener('input',updateValue)

    function updateValue(e){
         var total = document.getElementById('total');
         total.value = e.target.value + 0.99;
         console.log(total.value);
         
    }

    cardButton.addEventListener('click', async (e) => {

        const amount = document.getElementById('amount');

        function addMoney(method){
            axios.post('add-funds',{
                    method: method,
                    amount: amount,
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
                addMoney(setupIntent.payment_method); 
            }).catch(function(e){
                console.log(e);
                alert('Pls, try again')
                $('#billing').show()
                
            })
        }
    });

  
</script>
@endpush
