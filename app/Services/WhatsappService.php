<?php
namespace App\Services;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;


// app/Services/WhatsappService.php
class WhatsappService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL');
    }

    public function sendMessage($number, $message)
    {

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($this->apiUrl, [
                'json' => [
                    'number' => $number,
                    'message' => $message
                ]
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
            return false;
        }
    }

   
}
