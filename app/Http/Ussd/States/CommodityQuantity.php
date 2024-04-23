<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class CommodityQuantity extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter Quantity");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get(""));
        $cache_record["quantity"] = $argument; 
        $cache_record = json_encode($cache_record);
        $this->record->set("", $cache_record);
        $this->decision->numeric(PackageSize::class);
    }
}
