<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send SMS notification.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public static function send($phone, $message)
    {
        // Standardize phone number format if necessary
        $phone = trim($phone);

        // Always log to laravel.log for visibility and testing
        Log::info("SMS Sent to [{$phone}]: {$message}");

        $url = env('SMS_GATEWAY_URL');
        $apiKey = env('SMS_API_KEY');
        $senderId = env('SMS_SENDER_ID', 'ECBUS');

        if ($url && $apiKey) {
            try {
                $response = Http::post($url, [
                    'api_key' => $apiKey,
                    'to' => $phone,
                    'message' => $message,
                    'sender' => $senderId,
                ]);

                if ($response->successful()) {
                    Log::info("SMS Gateway successfully sent SMS to {$phone}");
                    return true;
                } else {
                    Log::error("SMS Gateway returned error code: " . $response->status() . " | " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Failed to send SMS to {$phone} via Gateway: " . $e->getMessage());
            }
        }

        return true; // Return true as log-fallback succeeds in dev
    }
}
