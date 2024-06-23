<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class RegionTransactionType extends State
{
    private $transactionTypes = ['Storage', 'Sell Offer', 'Buy Order', 'Withdraw'];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select transaction type")
            ->lineBreak(2)
            ->line("Select option")
            ->listing($this->transactionTypes, ". ");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (in_array($argument, [1, 2, 3, 4])) {
            $cache_record->transactionType = $this->transactionTypes[intval($argument) - 1];
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->between(1, 4, RegionTrnsaction::class)
            ->any(Error::class);
    }
}
