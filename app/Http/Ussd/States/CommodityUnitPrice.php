<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class CommodityUnitPrice extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter Unit Price (GHS)");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record->unit_price = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);

        }

        // is_object($cache_record) && (strcmp("Sell Offer", $cache_record->transactionType) === 0

        $this->decision
        // ->custom(function () use ($cache_record) {
        //     return is_object($cache_record) && (strcmp("Sell Offer", $cache_record->transactionType) === 0);
        // }, SellOfferPackageSizeState::class)
        //     ->custom(function () use ($cache_record) {
        //         return is_object($cache_record) && (strcmp("Storage", $cache_record->transactionType) === 0);
        //     }, PackageSize::class)
            ->numeric(PackageSize::class)
            ->any(Error::class);
    }
}
