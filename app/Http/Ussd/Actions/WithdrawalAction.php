<?php

namespace App\Http\Ussd\Actions;

use App\Http\Ussd\States\Error;
use App\Http\Ussd\States\GRN;
use Sparors\Ussd\Action;

class WithdrawalAction extends Action
{
    public function run(): string
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record) && strcmp("Withdrawal", $cache_record->transactionType) == 0)
        {
            return GRN::class;
        }
        return Error::class; // The state after this
    }
}
