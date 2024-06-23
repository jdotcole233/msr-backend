<?php

namespace App\Http\Ussd\States\Buyorder;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class PackageSizeBuyOrderState extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Select Package size (tonnes)");
    }

    protected function afterRendering(string $argument): void
    { 
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record))
        {
            $cache_record->package_size = $argument; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        
        $this->decision->numeric(CommodityQuantityBuyOrderState::class)
        ->any(Error::class);


    }
}
