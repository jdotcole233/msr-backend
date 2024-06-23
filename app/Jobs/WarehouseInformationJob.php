<?php

namespace App\Jobs;

use App\Models\tblWarehouse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class WarehouseInformationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $warehouseName, private $phoneNumber)
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
        $warehouse = tblWarehouse::where('registeredName', $this->warehouseName)->first();
        $message = "Warehouse name: " . $warehouse->registeredName . "\n";
        $message .= "Region: " . $warehouse->region . "\n";
        $message .= "Region: " . $warehouse->townCity . "\n";
        $message .= "District: " . $warehouse->district . "\n";
        $message .= "Storage Capacity: " . $warehouse->storageCapacity . "\n";
        $message .= "Business Type: " . $warehouse->businessType . "\n";

        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $this->phoneNumber,
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS Actor Job - Warehouse Information request " . json_encode($request->body()));
    }
}
