<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class ActorNameState extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Enter your name");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
        if (is_object($cache_record) && !empty($argument)) {
            $cache_record->name = $argument;
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
            info("Ghana Card ". json_encode($cache_record));
        }

        $this->decision->custom(function ($user_input) {
            return empty($user_input);
        }, RegistrationInCompleteState::class)
        ->custom(function ($user_input) {
            return !empty($user_input);
        }, RegistrationCompleteState::class);
    }
}
