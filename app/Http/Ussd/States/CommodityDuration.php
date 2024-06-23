<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class CommodityDuration extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Specify Storage duration (in Months)");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record->duration = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->numeric(HarvestTypeState::class)
            ->any(Error::class);
    }
}
