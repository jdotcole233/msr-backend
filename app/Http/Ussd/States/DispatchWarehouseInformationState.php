<?php

namespace App\Http\Ussd\States;

use App\Jobs\WarehouseInformationJob;
use Sparors\Ussd\State;

class DispatchWarehouseInformationState extends State
{

    protected $action = self::PROMPT;
    
    protected function beforeRendering(): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        $this->menu->line("You'll receive an sms with information for " . $cache_record->warehouseName)
            ->line("Thank you");

        if (is_object($cache_record)) {
            dispatch(new WarehouseInformationJob($cache_record->warehouseName, $this->record->phoneNumber));
        }
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
