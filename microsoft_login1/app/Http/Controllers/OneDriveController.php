<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OneDriveController extends Controller
{
    public function listOneDrive(Request $request)
    {
        $client = new Client();
         
        $accessToken = 'EwCIA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAUzH4Y081MKfzwCFW0ZRhW31LK//bDZKSWsplWWX3Q0SwcfvWdDgBLnUPbCVkFS8DuB4ebKF2I052iCj6gqOvG7UlE2pKxrqe/dKa66pv8b55YMUeF6ktmaKWafd0PqQ/93ngUre7P9ipzkKxn8zcrZylTylW6aF1PwkuKtbScLJjS5t4PRaX/yVYne0wHTs7QvUAvX5SZB8QYDFQ8fA1q/qGy7M5RLQ7uLIVIGONwYPK6yQ7g6nW7N+DBIVkzzXE1VZxI33OFS9XkaTgdF7L4oJyikhrNCEF6eReixQrj/3Yl1y4gteQuxfG5kcYuSrKOIVpslcChWYHcep01VXkogDZgAACDFN0P6OgnU1WALf1U7eTezDWCS5Y0Kt9U8HMBipUlLa07gOpeZUg3SqIwd3TXCz5npjmRaTy1QA/IblmYc++WxWC3IqzXXIBN9WQP16vkdK6N9dMaaU4sZQKUOS147HtMyGLwy6keqpVjiMx79n5zVcO3YCXTLqXMR/xZw+xtrsnwUcPV1fwNxg5QBrpPDXtR/lpNKF9TKrRdIeMuD9ak6oH+DPGGv2C34h/YqE/0uXVJadIqR2jGHuhjFqY/4zqaStzkYdWOYrbsPLQ1jOm7rAMbRm05Pjt/s3bVWcbij64PXU9GOiA9MEycMCG0uJKeYwQZNCop2mPREt0bNUcZPllVFJaBhZtd9+TIewnKvoiEvFP4nccMaccyOC209Bvm0oE3bs7FaBrdL7OaeemSddINpKKj8rSd0GPsv6dKx7gJijUXnvRx+32IXk9H1GI3Lbq8L9gqcbEUCxLN3dm/3uyvJQKPUVN1fjXcuKJmM4TNE4fGn0RHSPKU7JrMVLCPSF4PvMqvmmqp/acR7GVYKwy80wDnQZClw40mldS3OSUnRh78/Hwx+nPMPWQZg7IhzRgaCCL3hiadz+yVKQDlXydEoOpW5W1YMtbe2/wAhj64PvbCug/BKhjb60rEOFGMxoJc5W6H6dMJVfSdRms8iRhIuqZBwSzIJ8Fiz6+q1n57wp4zgO7kAKkDWPAszoYyt4/Hr/Um3ayUz2Bvw923tNRyG+g6xP2u2vCWhqmxPVzuK62RzcQczuxy64kwYF3KktyZBH6cQa0HoMPFLsu6ipF6y2Py1exRGT1L1n8FWePsmjAg==';

        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me/drive/root/children', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,               
            ],
        ]);
        $data = json_decode($response->getBody());
dd($data);
        // return view('onenote.pages', ['pages' => $data]);
    }

    public function createFile(Request $request)
    {
        $accessToken = 'EwCYA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAfspFZQ3LrioQXZx+l9AHTAl5pK9H9/2Le/CHUmxmKHSuzNPRWqNyVKYbCCDCv3AKLpBOodsvK30XLMhFzMmIzjN/dK9ARzGfkufj1gfVHxmUmlM+s/p9UUi6hbIhCMWhN8kJcbDzfKUgps4VAQJ5Hz1YB4XAgdcFFVazsKHp6sfmKxpYttR9l1jivvvxfGxXxFLVovTbnzFk8B9HhlU8tMtTRWwdGo9UClYA8vAsAcQYFJYWEkwRGKCHllmom9S9tc/MqLvWq/HqXNWjQku0iH8ipP9tM5683quyf02ZgyqIdQ4dRRFr2+Q87yvTb88Oxx+SsQ3esU6/eMViYWdB14DZgAACNyNyg5PExsKaAJ63eu8eWUixeeHwHk3X+f1kAZtcRsO4M+1c2BdPdFawptPp+ePgzFF7k/q3ZCSEZpjeZxKo/bzbSUoZrOvmnAiOrPMcLICF/8Z6gTxA/E+ojbyV7BZ0dLNHNMAKb4PEVcDGJ0zJwtrrEba8xmQcx77HWOWF1aW4GpyxlJtBoANVXBNIQBrra/+9hB13AH16BJ//Hjn/bKBpWtgTqU8Ww6lm53P2Gaz5uJaRr4EAlsZ7jftNXzQP5D8bbTyCNgsHkqsNARGjt8iGCeQn/J3IzhNCsTLnH9BSq/A3uVLoSlh+2wW7Fmznl9gvQyKpTffQ/o//kvPRTcjmQtN+cID05LC6ELNvBYLs/B+yvm0ccTUHNluD5gNhj+4BemvpxADGqypESl2AOH4gxBicY+c46YvYhTblb/0QL+7HDfT24dZpWMD0w9kO3HmeyEVctWO5XAQ5q9xu354HnJQDOQtZvPa6rrXGtlsGikrtlB38Ig+95AuWIKdPKoO2FcF0ElXIqsLuaPkrc4XAqI1iax/+Lhcd8+RQDbKGqY72VtaSebT95w9TdIgvfWBAuMUP+gAlEkjaFJsrXj2Bjjz/k8mkOPKHAxVogvhviztP/fLP++XgLwulD6qUzlK/cSguA/AuQH5G3X+NfiU9DFao/Au/DN5P+qElFb7fZXGpjOm8W7fmbml6DH4plPF8zcUT0zVXYrSME2nWQqXDNjoNa6SvQcvjelTMlyDkLbiuVuDIppXwU9Uq51xiYo2KQszZBrCuaeWvFlvvoOOB3XgXvSSOROIlILbmpg7yO8wAFEOpbMKy3iKZ841+GE+swI=';
        
        if (!$accessToken) {
            return redirect('/auth/microsoft');
        }
        
        $client = new Client();
        
        $fileName = 'sample_import_lead.xlsx';
        $fileContent = 'This is the content of the new file.';
        
        $driveItemPath = '/me/drive/root:/' . $fileName;
        
        $response = $client->put('https://graph.microsoft.com/v1.0' . $driveItemPath . ':/content', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'text/plain',
            ],
            'body' => $fileContent,
        ]);
        dd($response);
        if ($response->getStatusCode() == 200) {
            return "File '$fileName' created successfully.";
        } else {
            return "Failed to create the file. HTTP Status Code: " . $response->getStatusCode();
        }
    }
}
