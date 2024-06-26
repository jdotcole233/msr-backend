<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class GrnError extends State
{
    protected $action = self::PROMPT;
    
    protected function beforeRendering(): void
    {
        $this->menu->text("GRN Record not found...");
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
