<?php

namespace App\Http\Ussd\States;

use App\Http\Ussd\States\Regions\RegionTransactionType;
use App\Http\Ussd\States\Regions\RegionTrnsaction;
use App\Http\Ussd\States\Registration\RegionState;
use App\Models\tblActor;
use App\Models\tblWarehouse;
use Illuminate\Http\Request;
use Sparors\Ussd\Machine;
use Sparors\Ussd\State;

class Welcome extends State
{

    private $transactionTypes = ['Storage', 'Sell Offer', 'Buy Order', 'Withdraw', 'Warehouse Info', 'Other Regions'];
    // protected $actor


    protected function beforeRendering(): void
    {
        // Check if actor exists
        $actor = tblActor::where('contactPhone', $this->record->phoneNumber)->first();
        $message = "Welcome to MSR";

        if (!empty($actor)) {
            $message = "Welcome " . $actor->name ?? "to MSR";
            $warehouses = tblWarehouse::with(['commodities'])->where('region', $actor->region)->get();
            $this->record->set($this->record->sessionId, json_encode([
                'actor_id' => $actor->id,
                'warehouses' => $warehouses
            ]));
        }
        else
        {
            $this->record->set($this->record->sessionId, json_encode([]));
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
                $cache_record->transactionType = $this->transactionTypes[intval($argument) - 1];
                $cache_record = json_encode($cache_record);
                $this->record->set($this->record->sessionId, $cache_record);
            }

            $this->decision->between(1, 4, Warehouse::class)
                ->equal(5, WarehouseInformationState::class)
                ->equal(6, RegionTransactionType::class)
                ->any(Error::class);
        }
    }
}