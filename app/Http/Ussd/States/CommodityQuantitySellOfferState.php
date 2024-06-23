<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class CommodityQuantitySellOfferState extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter Number of Bags");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record->quantity = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->integer(CommodityUnitPriceSellOfferState::class) //PlaceOrder
            ->any(Error::class);
    }
}
