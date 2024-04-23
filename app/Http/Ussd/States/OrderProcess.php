<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class OrderProcess extends State
{
    protected function beforeRendering(): void
    {
        //->custom(function ($user_input) use ($cache_record) {
        //     $transactionType = $cache_record['transactionType'];
        //     return strcmp($transactionType, 'Withdrawal') != 0;
        // }, CommodityQuantity::class)
        // ->custom(function ($user_input) use ($cache_record) {
        //     $transactionType = $cache_record['transactionType'];
        //     return strcmp($transactionType, 'Withdrawal') == 0;
        // }, GRN::class);
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
