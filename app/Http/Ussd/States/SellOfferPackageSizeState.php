<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;


class SellOfferPackageSizeState extends State
{
    public $package_size = ['50Kg', '100Kg'];

    protected function beforeRendering(): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record))
        {
            $this->package_size = $cache_record->sizes; 
        }

        $this->menu->text("Select Package size")
        ->lineBreak(2)
        ->listing($this->package_size);
    }

    protected function afterRendering(string $argument): void
    { 
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        $range = \range(1, count($cache_record->sizes));

        if (is_object($cache_record))
        {
            $cache_record->package_size = $cache_record->sizes[intval($argument) - 1]; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        
        // $range = \range(1, count($this->package_size));
        $this->decision->in($range, CommodityQuantitySellOfferState::class)
        ->any(Error::class);


    }
}
