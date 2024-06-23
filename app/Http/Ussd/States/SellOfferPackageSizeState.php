<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;


class SellOfferPackageSizeState extends State
{
    public $package_size = ['50Kg', '100Kg'];

    protected function beforeRendering(): void
    {
        $this->menu->text("Enter Package size")
        ->lineBreak(2)
        ->listing($this->package_size);
    }

    protected function afterRendering(string $argument): void
    { 
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record))
        {
            $cache_record->package_size = $this->package_size[intval($argument) - 1]; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        
        $this->decision->between(1, 2, CommodityQuantitySellOfferState::class)
        ->any(Error::class);


    }
}
