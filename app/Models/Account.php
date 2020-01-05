<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Events\MoneyAdded;
use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Events\MoneySubtracted;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasNotes, Uuid;

    protected $guarded = [];

    public static function createWithAttributes(array $attributes): Account
    {
    
        /*
         * The account will be created inside this event using the generated uuid.
         */
        event(new AccountCreated($attributes));

        /*
         * The uuid will be used the retrieve the created account.
         */
        return static::uuid($attributes['uuid']);
    }

    public function addMoney(int $amount)
    {
        event(new MoneyAdded($this->uuid, $amount));
    }

    public function subtractMoney(int $amount)
    {
        event(new MoneySubtracted($this->uuid, $amount));
    }

    public function remove()
    {
        event(new AccountDeleted($this->uuid));
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Account
    {
        return static::where('uuid', $uuid)->first();
    }
}
