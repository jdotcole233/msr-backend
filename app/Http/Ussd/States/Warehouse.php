<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Warehouse extends State
{
    private $warehouses = ["North East (Nalerigu)", "Upper East (Tilli)", "Northern (Gbambu)"];
    protected function beforeRendering(): void
    {
        $this->menu->text("Select Warehouse")
            ->lineBreak()
            ->listing($this->warehouses)
            ->lineBreak()
            ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        info(json_encode($cache_record));

        if (in_array($argument, [1, 2, 3]) && is_object($cache_record)) {
            // Access object properties using -> operator
            $cache_record->warehouseName = $this->warehouses[intval($argument) - 1];
            $cache_record_temp = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record_temp);
            info("Warehouse 22 ");

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
