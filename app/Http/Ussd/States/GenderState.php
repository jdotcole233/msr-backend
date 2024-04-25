<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class GenderState extends State
{

    public $gender = ['Male', 'Female'];

    protected function beforeRendering(): void
    {
        $this->menu->text("Select your Gender")
        ->lineBreak(2)
        ->listing($this->gender);
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $cache_record['gender'] = $this->gender[intval($argument) - 1];
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }

        $this->decision->in([1, 2], ActorNameState::class)
        ->any(Error::class);
    }
}
