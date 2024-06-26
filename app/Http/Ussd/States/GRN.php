<?php

namespace App\Http\Ussd\States;

use App\Models\tblGRN;
use Sparors\Ussd\State;

class GRN extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter GRN ID");
    }

    protected function afterRendering(string $argument): void
    {
        $grn_record = tblGRN::where('grnidno',  trim($argument))->first();

        if (!$grn_record)
        {
            $this->decision->any(GrnError::class);
        }

        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) 
        {
            $cache_record->grnID = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->custom( function () use ($argument) {
            return is_string($argument);
        },PlaceOrder::class);
        // ->any(Error::class);
    }
}
