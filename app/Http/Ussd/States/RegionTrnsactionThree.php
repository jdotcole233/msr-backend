<?php

namespace App\Http\Ussd\States;

use App\Http\Ussd\States\Warehouse;
use App\Http\Ussd\States\Welcome;
use App\Models\tblWarehouse;
use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class RegionTrnsactionThree extends State
{
    public $regions = [
        "Oti",
        "Ahafo",
        "Bono",
        "Western North",
        "North East"
    ];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select your region")
        ->lineBreak(2)
        ->listing($this->regions)
        ->lineBreak(2)
        ->line("#. Go Back")
        ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        
        if (is_object($cache_record) && (intval($argument) >= 1 && intval($argument) <= 5)) {
            $cache_record->region = $this->regions[intval($argument) - 1];
            $cache_record->phoneNumer = $this->record->phoneNumber;
            $cache_record->warehouses = tblWarehouse::with(['commodities'])->where('region', $this->regions[intval($argument) - 1])->get();
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        $this->decision->between(1, 5, Warehouse::class)
            ->equal("0", Welcome::class)
            ->equal("#", RegionTrnsactionTwo::class)
            ->any(Error::class);
    }
}
