<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class RegionPageTwoState extends State
{

    public $regions = [
        "Ashanti",
        "Brong Ahafo",
        "Bono East",
        "Central",
        "Eastern",
        "Greater Accra",
        "Northern",
        "Upper East",
        "Upper West",
        "Volta",
        "Western",
        "Savannah",
        "Oti",
        "Ahafo",
        "Western North",
        "North East"
    ];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select your region")
        ->lineBreak(2)
        ->paginateListing($this->regions, 7, 12)
        ->lineBreak(2)
        ->line("9. Next Page")
        ->line("#. Go Back")
        ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        
        if (is_object($cache_record)) {
            $cache_record->region = $this->regions[intval($argument) - 1];
            $cache_record->phoneNumer = $this->record->phoneNumber;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        $this->decision->between(1, 16, GenderState::class)
            ->equal("0", Welcome::class)
            ->equal("#", RegionState::class)
            ->equal(9, RegionPageThreeState::class)
            ->any(Error::class);
    }
}
