<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;


class CommodityQuantity extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Specify Quantity")
        ->lineBreak(2)
        ->listing(['50Kg', '100Kg']);
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record->quantity = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->any(CommodityDuration::class) //PlaceOrder
            ->any(Error::class);
    }
}
