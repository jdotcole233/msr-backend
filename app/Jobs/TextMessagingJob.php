<?php

namespace App\Jobs;

use App\Utility\MsrUSSDRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TextMessagingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private MsrUSSDRequest $msrUSSDRequest)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Store in database

        // Send SMS
        $message = "Details of your " . $this->msrUSSDRequest->getTransactionType() . " request:";

        if (strcmp($this->msrUSSDRequest->getTransactionType(), "Storage") == 0) {
            $message = $this->outputMessage($message);
            $message .= "\nDuration: " . $this->msrUSSDRequest->getDuration();
        } else if (strcmp($this->msrUSSDRequest->getTransactionType(), "Withdrawal") == 0) {
            $message .= "\nGRN ID: " . $this->msrUSSDRequest->getGRNID();
        } else {
            $message = $this->outputMessage($message);
            $message .= "\nUnit price (GHS): " . $this->msrUSSDRequest->getUnitPrice();
        }

        $message .= "\nAn operator from " . $this->msrUSSDRequest->getWarehouseName() . " will contact you soon";
        $message .= "\nThank you";

        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $this->msrUSSDRequest->getPhoneNumber(),
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS " . json_encode($request->body()));
    }

    private function outputMessage ($message) : string
    {
        $message .= "\nCommoidity: " . $this->msrUSSDRequest->getCommodityName();
        $message .= "\nQuantity: " . $this->msrUSSDRequest->getQuantity();
        $message .= "\nSize : " . $this->msrUSSDRequest->getPackageSize();
        return $message;
    }
}
