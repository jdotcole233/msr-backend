<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Warehouse extends State
{
    private $warehouses = [];
    protected $action = self::INPUT;
    
    protected function beforeRendering(): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record)) {
            $this->warehouses = collect($cache_record->warehouses)->pluck('registeredName')->all();
        }

        if (count($this->warehouses) <= 0) {
            $this->action = 'prompt';
            $this->menu->text("No warehouses found in your region.")
                ->line("\nTry again later.");
        } else {
            $this->menu->text("Select Warehouse")
                ->lineBreak(2)
                ->listing($this->warehouses ?? [])
                ->lineBreak(2)
                ->line("0. Main menu");
        }

    }

    protected function afterRendering(string $argument): void
    {
        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record)) {
            $warehouses = collect($cache_record->warehouses)->pluck('registeredName')->all();
            $range = \range(1, count($warehouses));
        }

        info("range ". json_encode($range));

        if (in_array($argument, $range) && is_object($cache_record)) {
            $cache_record->warehouseName = $warehouses[intval($argument) - 1];
            $cache_record_temp = json_encode($cache_record);
            $this->record->set($this->record->sessionId, $cache_record_temp);
        }

        $this->decision->in($range, Commodity::class)
            ->any(Error::class);
    }

}
