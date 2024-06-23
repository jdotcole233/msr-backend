<?php

namespace App\Http\Ussd\States\Registration;

use Sparors\Ussd\State;
use App\Http\Ussd\States\Error;


class RegistrationInCompleteState extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("MSR Error: Name cannot be empty")
            ->lineBreak(2)
            ->text("0. Go Back");
    }

    protected function afterRendering(string $argument): void
    {
        $this->decision->equal("0", ActorNameState::class)
            ->any(Error::class);
    }
}
