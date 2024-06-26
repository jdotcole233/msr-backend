<?php

namespace App\Http\Ussd\States;

use App\Models\tblActor;
use App\Models\tblWarehouse;
use Illuminate\Http\Request;
use Sparors\Ussd\Machine;
use Sparors\Ussd\State;

class Welcome extends State
{

    // private $transactionTypes = ['Storage', 'Sell Offer', 'Buy Order', 'Withdraw', 'Warehouse Info', 'Other Regions'];
    // protected $actor

    public $regions = [
        "Ashanti",
        "Brong Ahafo",
        "Bono East",
        "Central",
        "Eastern",
        "Greater Accra"
    ];


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
                ->lineBreak()
                ->line("Select option")
                ->lineBreak()
                ->listing($this->regions, ". ")
                ->lineBreak(2)
                ->line("n. Next Page");
                // ->line("0. Main menu");
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

            // if (in_array($argument, [1, 2, 3, 4])) {
            //     $cache_record->transactionType = $this->transactionTypes[intval($argument) - 1];
            //     $cache_record = json_encode($cache_record);
            //     $this->record->set($this->record->sessionId, $cache_record);
            // }

            if (is_object($cache_record) && (intval($argument) >= 1 && intval($argument) <= 6)) {
                $cache_record->region = $this->regions[intval($argument) - 1];
                $cache_record->phoneNumer = $this->record->phoneNumber;
                $cache_record = json_encode($cache_record);
                $this->record->set($this->record->sessionId, $cache_record);
            }


            // $this->decision->between(1, 7, Warehouse::class)
            $this->decision->between(1, 7, RegionTransactionType::class)
                ->equal('n', RegionTrnsactionTwo::class)
                // ->equal(5, WarehouseInformationState::class)
                // ->equal(6, RegionTransactionType::class)
                ->any(Error::class);
        }
    }
}