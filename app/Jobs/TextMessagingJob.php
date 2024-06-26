<?php

namespace App\Jobs;

use App\Models\tblOrder;
use App\Models\tblWarehouse;
use App\Utility\MsrUSSDRequest;
use App\Utility\MsrUtility;
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
        info("Messaging ". json_encode($msrUSSDRequest->getWarehouseName()));
        info("Messaging 1 ". json_encode($msrUSSDRequest->getHarvestType()));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Store in database
        $warehouse = tblWarehouse::with(['operators'])->where('registeredName', $this->msrUSSDRequest->getWarehouseName())
            ->first();

        info("warehouse ". json_encode($warehouse));

        tblOrder::create([
            'fkWarehouseIDNo' => $warehouse->warehouseIDNo,
            'fkActorID' => $this->msrUSSDRequest->getActorID(),
            'contactPhone' => $this->msrUSSDRequest->getPhoneNumber(),
            'isBuyOrder' => strcmp('Buy Order', $this->msrUSSDRequest->getTransactionType()) == 0,
            'transactionType' => strtoupper($this->msrUSSDRequest->getTransactionType()),
            'orderDetails' => json_encode([
                "duration" => $this->msrUSSDRequest->getDuration() ?? "",
                "quantity" => $this->msrUSSDRequest->getQuantity() ?? "",
                "package_size" => $this->msrUSSDRequest->getPackageSize() ?? "",
                "commodityName" => $this->msrUSSDRequest->getCommodityName() ?? "",
                "grnID" => $this->msrUSSDRequest->getGRNID() ?? "",
                "unit_price" => $this->msrUSSDRequest->getUnitPrice() ?? "",
                'harvest_type' => $this->msrUSSDRequest->getHarvestType() ?? ""
            ]),
            'isComplete' => MsrUtility::$UNCOMPLETED,
        ]);

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

        $this->sendSMS($this->msrUSSDRequest->getPhoneNumber(), $message);
        $this->operatorMessage($warehouse);

    }

    private function outputMessage($message): string
    {
        $message .= "\nCommodity: " . $this->msrUSSDRequest->getCommodityName();
        $message .= "\nQuantity: " . $this->msrUSSDRequest->getQuantity();
        $message .= "\nSize : " . $this->msrUSSDRequest->getPackageSize();
        return $message;
    }

    private function operatorMessage($warehouse)
    {
        $operators = $warehouse->operators ?? [];
        foreach ($operators as $operator) {
            if ($operator->contactPhone) {
                $message = "Hi " . $operator->operatorName . "\n";
                $message .= $this->msrUSSDRequest->getTransactionType() . " requests received";
                $this->sendSMS($operator->contactPhone, $message);
            }
        }

    }

    private function sendSMS($numbers, $message)
    {
        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $numbers,
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS " . json_encode($request->body()));
    }
}
