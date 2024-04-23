<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class GRN extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter GRN ID");
    }

    protected function afterRendering(string $argument): void
    {
        $this->decision->numeric(PlaceOrder::class);
    }
}
