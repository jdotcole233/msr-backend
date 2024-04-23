<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class PackageSize extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter Package Size");
    }

    protected function afterRendering(string $argument): void
    { 
        $cache_record = json_decode($this->record->get(""));
        $cache_record["duration"] = $argument; 
        $cache_record = json_encode($cache_record);
        $this->record->set("", $cache_record);
        $this->decision->numeric(CommodityDuration::class);
    }
}
