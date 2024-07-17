<?php

namespace App\Http\Ussd\States;

use App\Jobs\MsrActorJob;
use App\Utility\MsrActor;
use Sparors\Ussd\State;

class RegistrationCompleteState extends State
{
    protected $action = self::PROMPT;

    protected function beforeRendering(): void
    {
        $this->menu->text("Registration completed.");

        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record)) {
            $actor = new MsrActor(
                $cache_record->region,
                $cache_record->gender,
                $cache_record->name,
                $this->record->phoneNumber,
                $cache_record->ghana_card,
                $cache_record->age,
            );

            dispatch(new MsrActorJob($actor));
        }
        ;
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
