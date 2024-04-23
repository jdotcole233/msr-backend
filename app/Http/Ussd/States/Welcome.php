<?php

namespace App\Http\Ussd\States;

use App\Models\tblActor;
use Illuminate\Http\Request;
use Sparors\Ussd\Machine;
use Sparors\Ussd\State;

class Welcome extends State
{
    
    private $transactionTypes = ['Storage', 'Offtake', 'Buy Order', 'Withdrawal'];
    // protected $actor


    protected function beforeRendering(): void
    {
        // Check if actor exists
        $actor = tblActor::where('contactPhone', $this->record->phoneNumber)->first();
        $message = "Welcome to MSR";
        
        $this->record->set($this->record->sessionId, json_encode([]));

        if (!empty($actor))
        {
            $message .= "Welcome ". $actor->name;
        }

        $this->menu->text($message)
            ->lineBreak(2)
            ->line("Select option")
            ->listing($this->transactionTypes, ".", " ");
    }

    protected function afterRendering(string $argument): void
    {

        if (in_array($argument, [1, 2, 3, 4]))
        {
            $cache_record = json_decode($this->record->get($this->record->sessionId));
            $cache_record['transactionType'] = $this->transactionTypes[intval($argument) - 1]; 
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);
        }
        
        
        $this->decision->between(1, 3, Commodity::class)
            ->equal('4', Warehouse::class)
            ->any(Error::class);
    }
}
