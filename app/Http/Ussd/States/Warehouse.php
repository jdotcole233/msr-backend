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


        if (strcmp($argument, "0") != 0 && in_array($argument, [1, 2, 3]))
        {
            $cache_record->warehouseName = $this->warehouses[intval($argument) - 1]; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }


        $this->decision->equal('0', Welcome::class)
        ->in([1,2, 3], OrderProcess::class)
        ->custom(function () use ($cache_record) {
            return strcmp("Withdrawal", $cache_record->transactionType) == 0;
        }, GRN::class)
        ->any(Error::class);
    }
}
