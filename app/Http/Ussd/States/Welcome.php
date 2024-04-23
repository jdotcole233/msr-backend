<?php

namespace App\Http\Ussd\States;

use App\Models\tblActor;
use Sparors\Ussd\State;

class Welcome extends State
{
    
    private $transactionTypes = ['Storage', 'Offtake', 'Buy Order', 'Withdrawal'];

    protected function beforeRendering(): void
    {
        // Check if actor exists
        $actor = tblActor::where('contactPhone', "")->first();
        $message = "Welcome to MSR";
        
        if (!empty($actor))
        {
            $message .= "Welcome ". $actor->name;
        }

        $this->menu->text($message)
            ->lineBreak()
            ->line("Select option")
            ->listing($this->transactionTypes);
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get(""));
        $cache_record["transactionType"] = $this->transactionTypes[intval($argument) - 1]; 
        $cache_record = json_encode($cache_record);
        $this->record->set("", $cache_record);
        
        $this->decision->between(1, 3, Commodity::class)
            ->equal('4', Warehouse::class)
            ->any(Error::class);
    }
}
