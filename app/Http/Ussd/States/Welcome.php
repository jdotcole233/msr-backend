<?php

namespace App\Http\Ussd\States;

use App\Models\tblActor;
use App\Models\tblWarehouse;
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
        $warehouses = tblWarehouse::with(['commodities'])->where('region', $actor->region)->get();

        $message = "Welcome to MSR";
        $this->record->set($this->record->sessionId, json_encode([
            'actor_id' => $actor->id,
            'warehouses' => $warehouses
        ]));

        if (!empty($actor)) {
            $message = "Welcome " . $actor->name ?? "to MSR";
        }


        if (is_null($actor)) {
            $cache_record = json_decode($this->record->get($this->record->sessionId));
            $cache_record['init'] = 'START';
            $cache_record = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record);

            $this->menu->text($message)
                ->lineBreak(2)
                ->line("1. Register");
        } else {
            $this->menu->text($message)
                ->lineBreak(2)
                ->line("Select option")
                ->listing($this->transactionTypes, ". ");
        }
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (
            is_object($cache_record) && property_exists($cache_record, 'init')
            && strcmp($cache_record->init, "START") === 0
        ) {
            $this->decision->equal("1", RegionState::class)
                ->any(Error::class);
        } else {

            if (in_array($argument, [1, 2, 3, 4])) {
                info("setting... ");
                $cache_record->transactionType = $this->transactionTypes[intval($argument) - 1];
                $cache_record = json_encode($cache_record);
                $this->record->set($this->record->sessionId, $cache_record);
                info("set... ");
            }

            $this->decision->between(1, 4, Warehouse::class)
                // ->equal('4', Warehouse::class)
                ->any(Error::class);
        }
    }
}