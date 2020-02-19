@extends('layouts.dashboard_master')
@section('title', 'Withdraw Funds')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-6">
    <form action="add-funds" method="POST">
        @csrf
        <div class="dashboard-box margin-top-0">
    
            <div class="content px-4 py-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="flex flex-row justify-between mb-6">
                            <h3>
                                <i class="icon-line-awesome-money"></i> 
                                <img src="{{ asset('assets/images/stripe-logo.png') }}" height="30px">
                            </h3>
                            <h3>Balance: ${{ $account->balance }}</h3>
                        </div>
                        
                            <div class="submit-field">
                                <h5>Payment Method</h5>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <select name="method" class="selectpicker with-border" id="payment_method">
                                            @if(!empty($profile->paypal))
                                                <option value="paypal" >Paypal - free</option>
                                            @endif
                                            @if(!empty($profile->momo) & !empty($profile->momo_network) & !empty($profile->momo_country))
                                                <option value="momo">Mobile money - 3.0%</option>
                                            @endif
                                            @if(!empty($profile->bank_name) & !empty($profile->bank_no) & !empty($profile->bank_account_name))
                                                <option value="bank" >International wire</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-field">
                                <h5>Amount</h5>
                                <div class="row">
                                    <div class="col-xl-12">
                                
                                        <div class="input-with-icon-left no-border">
                                            <i class="icon-feather-dollar-sign"></i>
                                            <input type="amount" value="{{ old('amount') }}" name="amount" id="amount" type="number" class="input-text" value="0" required>
                                            <input type="hidden" name="withdrawal" value="withdrawal"/>
                                            <input type="hidden" name="type" value="withdrawal"/>
                                        </div> 
                                        <small class="text-muted"><span class="float-right">Additional fee may be applied</span></small>  
                                
                                    </div>
                                </div>
                            </div>
                     
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-12"> 
                        <p>Total<span id="total" class="float-right"></span></p>
                    </div>
                    <div class="col-12">
                        <button class="button ripple-effect">
                            <i class="icon-material-outline-check-circle mr-1"></i>
                            <span id="amount-value">Confirm & Add $0</span>
                        </button>
                    </div> 
                </div>
            </div>

        </div>
      </form>
    </div>
    <div class="col-xl-6">
        <h3>Withdrawal fees</h3>
        <ul>
        <li>Minimum withdrawal of $30</li>
        <li>Paypal – free</li>
        <li>Skrill – free</li>
        <li>International wire - $25</li>
        <li>Mobile money – 3.0% of total amount</li>
        <li> Debit card – 3.0% of total amount</li>
        </ul>
        <h3>Taxes</h3>
        <p>Applies depending on country or region</p>
    </div>
</div>

<script>

amount.addEventListener('input',updateValue)

function updateValue(e){
     var total = document.getElementById('total');
     total.value = Number(Number(e.target.value));
     console.log();
     
     
     var amountValue = document.getElementById('amount-value')
     amountValue.innerHTML = "Confirm & Add $" + total.value;
     console.log(amountValue.innerHTML);
}

</script>
@endsection