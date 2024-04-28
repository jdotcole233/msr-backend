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
                ->pluck('commodityName')
                ->all();
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
            $commodities = collect($cache_record->warehouses)->pluck('commodityName')->all();
            $range = \range(1, count($commodities));
        }


        if (is_object($cache_record) && strcmp($argument, "0") != 0 && in_array($argument, $range)) {
            $cache_record->commodityName = $commodities[intval($argument) - 1];
            $cache_record_temp = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record_temp);
        }

        $this->decision->custom(function () use ($cache_record) {
            return is_object($cache_record) && strcmp("Withdrawal", $cache_record->transactionType) === 0;
        }, GRN::class)
            ->custom(function () use ($cache_record) {
                return is_object($cache_record) && (strcmp("Offtake", $cache_record->transactionType) === 0 || strcmp("Buy Order", $cache_record->transactionType) === 0);
            }, CommodityUnitPrice::class)
            ->custom(function () use ($cache_record) {
                return is_object($cache_record) && strcmp("Storage", $cache_record->transactionType) === 0;
            }, CommodityDuration::class)
            ->equal('0', Welcome::class)
            ->any(Error::class);
    }
}
