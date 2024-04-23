<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class PlaceOrder extends State
{

    protected $action = self::PROMPT;
    
    protected function beforeRendering(): void
    {
        $this->menu->line("You'll receive an sms with order details")
        ->line("Thank you");
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
