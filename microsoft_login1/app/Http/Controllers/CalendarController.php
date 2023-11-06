<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CalendarController extends Controller
{
    //

    public function calendarList()
    {
        $accessToken = $this->getAccessToken();

        $client = new Client();
         
        // $accessToken = 'EwCIA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAUzH4Y081MKfzwCFW0ZRhW31LK//bDZKSWsplWWX3Q0SwcfvWdDgBLnUPbCVkFS8DuB4ebKF2I052iCj6gqOvG7UlE2pKxrqe/dKa66pv8b55YMUeF6ktmaKWafd0PqQ/93ngUre7P9ipzkKxn8zcrZylTylW6aF1PwkuKtbScLJjS5t4PRaX/yVYne0wHTs7QvUAvX5SZB8QYDFQ8fA1q/qGy7M5RLQ7uLIVIGONwYPK6yQ7g6nW7N+DBIVkzzXE1VZxI33OFS9XkaTgdF7L4oJyikhrNCEF6eReixQrj/3Yl1y4gteQuxfG5kcYuSrKOIVpslcChWYHcep01VXkogDZgAACDFN0P6OgnU1WALf1U7eTezDWCS5Y0Kt9U8HMBipUlLa07gOpeZUg3SqIwd3TXCz5npjmRaTy1QA/IblmYc++WxWC3IqzXXIBN9WQP16vkdK6N9dMaaU4sZQKUOS147HtMyGLwy6keqpVjiMx79n5zVcO3YCXTLqXMR/xZw+xtrsnwUcPV1fwNxg5QBrpPDXtR/lpNKF9TKrRdIeMuD9ak6oH+DPGGv2C34h/YqE/0uXVJadIqR2jGHuhjFqY/4zqaStzkYdWOYrbsPLQ1jOm7rAMbRm05Pjt/s3bVWcbij64PXU9GOiA9MEycMCG0uJKeYwQZNCop2mPREt0bNUcZPllVFJaBhZtd9+TIewnKvoiEvFP4nccMaccyOC209Bvm0oE3bs7FaBrdL7OaeemSddINpKKj8rSd0GPsv6dKx7gJijUXnvRx+32IXk9H1GI3Lbq8L9gqcbEUCxLN3dm/3uyvJQKPUVN1fjXcuKJmM4TNE4fGn0RHSPKU7JrMVLCPSF4PvMqvmmqp/acR7GVYKwy80wDnQZClw40mldS3OSUnRh78/Hwx+nPMPWQZg7IhzRgaCCL3hiadz+yVKQDlXydEoOpW5W1YMtbe2/wAhj64PvbCug/BKhjb60rEOFGMxoJc5W6H6dMJVfSdRms8iRhIuqZBwSzIJ8Fiz6+q1n57wp4zgO7kAKkDWPAszoYyt4/Hr/Um3ayUz2Bvw923tNRyG+g6xP2u2vCWhqmxPVzuK62RzcQczuxy64kwYF3KktyZBH6cQa0HoMPFLsu6ipF6y2Py1exRGT1L1n8FWePsmjAg==';
        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me/drive/root/children', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,               
            ],
        ]);
        $data = json_decode($response->getBody());
        dd($data);
    }

    public function getAccessToken()
    {
        $tenantId = env('MICROSOFT_TENANT_ID');
        $clientSecret = env('MICROSOFT_CLIENT_SECRET');
        $clientId = env('MICROSOFT_CLIENT_ID');        

        $input = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'scope' =>"https://graph.microsoft.com/.default",
            'client_secret' => $clientSecret,
        ];
  
        $client = new Client();
        $response = $client->request('POST', "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token", ['form_params' => $input]);
     
        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        $this->accessToken = $responseBody['access_token'];
        return $responseBody;
    }
}