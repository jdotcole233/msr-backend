<?php

namespace App\Http\Ussd\States\Registration;

use App\Http\Ussd\States\Welcome;
use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;

class RegionPageTwoState extends State
{

    public $regions = [
        "Northern",
        "Upper East",
        "Upper West",
        "Volta",
        "Western",
        "Savannah"
    ];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select your region")
        ->lineBreak(2)
        ->listing($this->regions)
        ->lineBreak(2)
        ->line("n. Next Page")
        ->line("#. Go Back")
        ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        
        if (is_object($cache_record) && intval($argument) >= 1 && intval($argument) <= 6) {
            $cache_record->region = $this->regions[intval($argument) - 1];
            $cache_record->phoneNumer = $this->record->phoneNumber;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        $this->decision->between(1, 6, GenderState::class)
            ->equal("0", Welcome::class)
            ->equal("#", RegionState::class)
            ->equal('n', RegionPageThreeState::class)
            ->any(Error::class);
    }
}
