<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FonnteHelper
{
    public static function send($target, $message)
    {
        $token = env('FONNTE_API_KEY');

        // Kirim pesan
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message
        ]);

        return $response->json();
    }
}