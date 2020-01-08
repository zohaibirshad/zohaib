<?php
namespace App\Exceptions;

use Exception;

class CouldNotSubtractMoney extends Exception
{
    public static function notEnoughFunds(int $amount): self
    {
        return new static("Could not withdraw amount {$amount} because you can not go below 0.");
        
    }
}