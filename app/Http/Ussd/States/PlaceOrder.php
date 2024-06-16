<?php

namespace App\Http\Ussd\States;

use App\Jobs\TextMessagingJob;
use App\Utility\MsrUSSDRequest;
use Sparors\Ussd\State;

class PlaceOrder extends State
{

    protected $action = self::PROMPT;

    protected function beforeRendering(): void
    {
        $this->menu->line("You'll receive an sms with order details")
            ->line("Thank you");

        $cache_record = json_decode($this->record->get($this->record->sessionId));

        if (is_object($cache_record)) {
            $ussdRequest = new MsrUSSDRequest(
                $cache_record->actor_id,
                $cache_record->transactionType,
                $cache_record->warehouseName,
                $cache_record->commodityName ?? "",
                $cache_record->unit_price ?? "",
                $cache_record->duration ?? "",
                $cache_record->quantity ?? "",
                $cache_record->package_size ?? "",
                $cache_record->grnID ?? "",
                $this->record->phoneNumber,
                $this->record->harvest_type,
            );

            $textMessaging = new TextMessagingJob($ussdRequest);
            dispatch($textMessaging);
        }

    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
