<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class RegionState extends State
{
    public $regions = [
        "Ahafo Region",
        "Ashanti",
        "Bono East",
        "Bono",
        "Central",
        "Eastern",
        "Greater Accra",
        "North East",
        "Northern",
        "Oti",
        "Savannah",
        "Upper East",
        "Upper West",
        "Volta",
        "South Dayi",
        "South Tongu",
        "Western North",
        "Western"
    ];
    protected function beforeRendering(): void
    {
        $this->menu->text("Select your region")
            ->lineBreak(2)
            ->paginateListing($this->regions, 1, 4)
            ->lineBreak()
            ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        
        if (is_object($cache_record)) {
            $cache_record['region'] = $this->regions[intval($argument) - 1];
            $cache_record['phoneNumer'] = $this->record->phoneNumber;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        $this->decision->between(1, 16, GenderState::class)
            ->equal("0", Welcome::class)
            ->any(Error::class);
    }
}
