<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

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

        info("Final ". json_encode($cache_record));

        $this->decision->numeric(PlaceOrder::class)
            ->any(Error::class);
    }
}
