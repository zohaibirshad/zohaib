<?php

namespace App\Events;

use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;

class MoMoPayOutEvent
{
    use SerializesModels;

    public $profile;

    public $request;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, Transaction $transaction, Array $request)
    {
        $this->transaction = $transaction;
        $this->profile = $profile;
        $this->request = $request;
        
    }
}
