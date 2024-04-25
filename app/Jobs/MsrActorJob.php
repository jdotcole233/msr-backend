<?php

namespace App\Jobs;

use App\Models\tblActor;
use App\Utility\MsrActor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MsrActorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private MsrActor $msrActor)
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
        $name = $this->msrActor->getName() ?? "";
        $message = "Your account on MSR has been created successfully.";
        $message .= "\nYou now have access to all services";

        $actor = tblActor::create([
            'name' => $name,
            'contactPhone' => $this->msrActor->getPhoneNumber(),
            'isMale' => strcmp($this->msrActor->getGender(), 'Male') === 0,
            'region' => $this->msrActor->getRegion()
        ]);

        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $this->msrActor->getPhoneNumber(),
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS Actor Job " . json_encode($request->body()));
    }
}
