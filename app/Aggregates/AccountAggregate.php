<?php

namespace App\Aggregates;

use App\Models\Job;
use App\Models\Account;
use App\Events\MoneyAdded;
use App\Events\AccountCreated;
use App\Events\MoneySubtracted;
use Spatie\EventSourcing\AggregateRoot;
use App\Exceptions\CouldNotSubtractMoney;

final class AccountAggregate extends AggregateRoot
{
    /** @var int */
    private $balance = 0;

    /** @var int */
    private $accountLimit = 0;

    /** @var int */
    private $accountLimitHitCount = 0;

    // we need to add this method to count the amount of this the limit was hit
    public function applyAccountLimitHit()
    {
        $this->accountLimitHitCount++;
    }

    public function applyMoneyAdded(MoneyAdded $event)
    {
        $this->balance += $event->amount;
    }

    public function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount;
    }
    public function createAccount(string $name, string $user_id)
    {
        $this->recordThat(new AccountCreated(['name' => $name, 'user_id' => $user_id]));
    }

    public function addMoney(int $amount)
    {
        $this->recordThat(new MoneyAdded($amount));
    }


    public function subtractMoney(int $amount)
    {
        if (!$this->hasSufficientFundsToSubtractAmount($amount)) {
            
            throw CouldNotSubtractMoney::notEnoughFunds($amount);
        }

        $this->recordThat(new MoneySubtracted($amount));
    }

    private function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        $account = Account::where('uuid', $this->uuid)->first();

        $jobs = Job::where('status', 'assigned')->where('user_id', $account->user_id)->with(['bids'=> function($q){
             $q->where('status', 'accepted');
        }, 'milestones' => function($q){
            $q->where('status', 'paid');
        }])->get();

        $bids = 0;
        foreach ($jobs as $job) {
            $bid_sum = $job->bids()->where('status', 'accepted')->first()->rate;

            $bids =  $bids + $bid_sum;
        }



        $milestones = [];
        foreach ($jobs as $job) {
            $milestone_sum = $job->milestones()->where('status', 'paid')->get()->map(function($item, $values){
                return [
                    'rate' => $item->sum('cost'),
                ];
            });

            // $milestones =  $milestones + $milestone_sum;
            array_push($milestones, $milestone_sum);
        }
      
         dd($bids, $milestones);
        //  dd($ongoing_job_amount[0]->sum('bids'));

        // $this->accountLimit = $ongoing_job_amount->

        return $this->balance - $amount >= $this->accountLimit;
    }
}
