<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Error extends State
{

    protected $action = self::PROMPT;
    
    protected function beforeRendering(): void
    {
        $this->menu->text("MSR Error")
        ->lineBreak()
        ->line("Selected option not availble..");
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
