<?php

namespace App\Jobs;

use App\Models\tblOrder;
use App\Utility\MsrUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class QualityAssessmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private tblOrder $order;
    private $completed;
    /**
     * Create a new job instance.
     *
     * @param tblOrder $order
     * @param $completed
     * @return void
     */
    public function __construct(tblOrder $order, $completed)
    {
        $this->order = $order;
        $this->completed = $completed;
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
        $message .= "\nhas  {$this->completed}";
        $message .= " quality assessment.";
        
        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $phoneNumber,
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS Actor Job - Warehouse Information request " . json_encode($request->body()));
    }
}
