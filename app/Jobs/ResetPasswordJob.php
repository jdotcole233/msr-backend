<?php

namespace App\Jobs;

use App\Models\tblOperator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ResetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private tblOperator $operator, private string $password)
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
        $message = "Your password with MSR has been updated\n";
        $message .= "New Password: {$this->password}\n";
        $message .= "Change this password after login\n";

        $request = Http::post(config('app.sms_endpoint'), [
            'key' => config('app.sms_key'),
            'msisdn' => $this->operator->contactPhone,
            'message' => $message,
            'sender_id' => config('app.sms_userid')
        ]);

        info("SMS Actor Job " . json_encode($request->body()));
    }
}
