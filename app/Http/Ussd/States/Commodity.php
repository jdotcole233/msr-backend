<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;


class Commodity extends State
{
    private $commodities = [];
    protected $action = self::INPUT;

    protected function beforeRendering(): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record)) {
            $warehouseName = $cache_record->warehouseName;
            $this->commodities = collect(collect($cache_record->warehouses)
                ->where('registeredName', $warehouseName)
                ->first()
                ->commodities)
                ->unique('commodityName')
                // ->map(function ($commodity) {
                //     return $commodity->commodityName;
                // })
                ->pluck('commodityName')
                ->all();
            info("commodities ". json_encode($this->commodities));
        }

        if (count($this->commodities) <= 0) {
            $this->action = 'prompt';
            $this->menu->text("No Commodities found")
                ->line("\nTry again later.");
        } else {
            $this->menu->text("Select Commodity")
                ->lineBreak()
                ->listing($this->commodities ?? [])
                ->lineBreak()
                ->line("0. Main menu");
        }

    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record)) {
            // $commodities = collect($cache_record->warehouses)->pluck('commodityName')->all();
            $commodity = collect(collect($cache_record->warehouses)
            ->where('registeredName', $cache_record->warehouseName)
            ->first()
            ->commodities);
            
            $this->commodities = $commodity->unique('commodityName')->pluck('commodityName')
                ->all();
            $range = \range(1, count($this->commodities));

            $packaging_sizes = $commodity->where('commodityName', $argument)->pluck('packingSize');
        }


        if (is_object($cache_record) && strcmp($argument, "0") != 0 && in_array($argument, $range)) {
            $cache_record->commodityName = $this->commodities[intval($argument) - 1];
            $cache_record->sizes = $packaging_sizes;
            $cache_record_temp = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record_temp);
        }

        info("commodity cache " . json_encode($cache_record));
        info("commodity argument " . json_encode($argument));


        $this->decision->custom(function () use ($cache_record) {
            return is_object($cache_record) && strcmp("Withdraw", $cache_record->transactionType) === 0;
        }, GRN::class)
            ->custom(function () use ($cache_record) {
                return is_object($cache_record) && strcmp("Sell Offer", $cache_record->transactionType) === 0;
            }, SellOfferPackageSizeState::class)
            ->custom(function () use ($cache_record) {
                return is_object($cache_record) && strcmp("Buy Order", $cache_record->transactionType) === 0;
            }, PackageSizeBuyOrderState::class)
            ->custom(function () use ($cache_record) {
                return is_object($cache_record) && strcmp("Storage", $cache_record->transactionType) === 0;
            }, PackageSize::class)
            ->equal('0', Welcome::class)
            ->any(Error::class);
    }
}
