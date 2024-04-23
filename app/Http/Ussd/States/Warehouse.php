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
        ->line("Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get(""));
        // $cache_record["commodityName"] = $this->commodities[intval($argument) - 1]; 

        $this->decision->equal('0', Welcome::class)
        ->in([1,2, 3], OrderProcess::class);
    }
}
