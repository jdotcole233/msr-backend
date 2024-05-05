<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class OperatorOnboardedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public string $numbers;
     public string $name;
    public string $password;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $numbers, string $name, string $password)
    {
        $this->numbers = $numbers;
        $this->name  = $name;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = "Welcome " . $this->name . "\n";
        $message .= "Your account on msr platform has been setup successfully\n";
        $message .= "This is your first time password\n";
        $message .= "Password: " . $this->password . "\n";
        $message .= "Login here: http://usaidmsractivityghana.org/". 
        $message .= "\n. Please change your password when you first log in";

        $this->sendSMS($this->numbers, $message);
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
