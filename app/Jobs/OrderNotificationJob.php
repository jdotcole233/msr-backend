<?php

namespace App\Jobs;

use App\Models\tblOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class OrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private tblOrder $order, private string $orderStatus)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = "";

        $phoneNumber = $this->order->actor->contactPhone;

        $message .= "Hi " . $this->order->actor->name;
        $message .= "\nYour " . strtolower($this->order->transactionType) . " request for " . json_decode($this->order->orderDetails)->commodityName;
        $message .= "\nhas been ". strtolower($this->orderStatus);
        
        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $phoneNumber,
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS Actor Job - Warehouse Information request " . json_encode($request->body()));
    }
}
