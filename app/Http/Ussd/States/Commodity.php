<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Commodity extends State
{
    private $commodities = ['Maize', 'Soy Bean', 'Shea', 'Cowpea', 'Millet', 'Sorghum'];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select Commodity")
        ->lineBreak()
        ->listing($this->commodities)
        ->lineBreak()
        ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        if (strcmp($argument, "0") != 0 && in_array($argument, [1, 2, 3, 4, 5, 6]))
        {
            $cache_record = json_decode($this->record->get($this->record->sessionId));
            $cache_record['commodityName'] = $this->commodities[intval($argument) - 1]; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->equal('0', Welcome::class)
            ->between(1, 6, Warehouse::class)
            ->any(Error::class);
    }
}
