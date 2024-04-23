<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Warehouse extends State
{
    private $warehouses = ["North East (Nalerigu)", "Upper East (Tilli)", "Northern (Gbambu)"];
    protected function beforeRendering(): void
    {
        $this->menu->text("Select Warehouse")
        ->lineBreak()
        ->listing($this->warehouses)
        ->lineBreak()
        ->line("0. Main menu");
    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));
    
        info(json_encode($cache_record));
    
        if (strcmp($argument, "0") != 0 && in_array($argument, [1, 2, 3])) {
            // Access object properties using -> operator
            $cache_record->warehouseName = $this->warehouses[intval($argument) - 1]; 
            $cache_record_temp = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record_temp);
        }
    
        info(json_encode($cache_record));
        // Ensure $cache_record is an object before accessing its properties
        if (is_object($cache_record)) {
            info(json_encode($cache_record->transactionType));
            info(json_encode(strcmp("Withdrawal", $cache_record->transactionType) == 0));
        }
    
        $this->decision->equal('0', Welcome::class)
            ->in([1, 2, 3], OrderProcess::class)
            ->custom(function ($input) use ($cache_record) {
                info("input ". json_encode($input));
                return is_object($cache_record) && strcmp("Withdrawal", $cache_record->transactionType) == 0;
            }, GRN::class)
            ->any(Error::class);
    }
    
}
