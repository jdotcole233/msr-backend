<?php

namespace App\Http\Ussd\States\Storage;

use App\Http\Ussd\States\PlaceOrder;
use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class HarvestTypeState extends State
{
    public $harvest_type = ['Mechanical threshing', 'Manual threshing'];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select harvest type")
        ->lineBreak(2)
        ->listing($this->harvest_type);
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record->harvest_type = $this->harvest_type[intval($argument) - 1];
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->numeric(PlaceOrder::class)
            ->any(Error::class);
    }
}
