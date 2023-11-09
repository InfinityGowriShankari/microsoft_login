<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OneDriveController extends Controller
{
    public function listOneDrive(Request $request)
    {
        $client = new Client();
         
        $accessToken = $request->session()->get('access_token');        
        // $accessToken = $this->getAccessToken();
        dd($accessToken);
        // $accessToken = 'EwCgA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAZH1iVJmhW0seXa3Bgbdk3+3+3BO1WICF1fmR3CuuaCn6GSmRd/14lplq5K5+rsXLeG0newbRcbityzeuuncV/vtJaMFZtIycMv0ZRiURagxnCnbpPKX9Nm+d/0OyQzPX/86kvnyYCcmPjQ0U3SX/SSogihGcLEXTUCvn5Pba3FHXl7E6f5AjbVzR9GiI5hhq2HRVDH1dyaOMFLu1I9OCHPQe1wHUDmtF87VfNobZ5JJ+x8T758myZiEQOcEwUEf8QD44WPAnaLsMmh/E/Eg9qyTDaixVraV0aYdvv/wfetkcgsI6X7FgBvd9XMXr0BVEGYyks3ppPFktVVDKXFo85UDZgAACGJi0A7HwGz1cAJYoSNWIzyVH3tD/Xn4iLyQ5Tbfc/dCUnmm/2oHQzFByd5u3hk8/ELsu4rEn1+I1ajkmCS1F/4qk2JmV15eYgktNFUN3hASoOtzbsKTYsl4gFaFzrGzLHoFOWDeeJf3GtN3ltbrb0HgS7+S+WrlS/qXkQgFN9/aSd2NDKgL1Uqf9JsOR5uvV39WcKClZqvIS4LU6Fd9F8WKYCLfzlOyDt0JqYpQJBMGCzwgKLWgxV1fQVg9krpuLJjO3uvOVLY8La+PW73LfdKNuQnOgJFALZ8EC1+HZ84mm5YiN0g01sv/n+MIUgfh/VPIkG8JUl8O3ydHr+Ezvo8FvOFsTbCR1OiFfBrO0GGq8gxwdQSJUFlsEujc1zlPHyjOiR/krG2mt8mEfctEM6Qtl0FwjY6BCJm0ZBS6Ahl6vy+PGPDNpibRgrCeZ3zFdJeVC/G1udle70T+PGPtpU+cOkltD2w7tScseoJeK9Wf2HsNqta7cC2Pb/rJaB2LHyTnqA7kcXSJFNAN0RAlCFC7+h74uCksYSx4nn2dBH5/qEdQd9mZXewM32IoYIHx6iCDj3zTThvDpN2B+ZJa3aYCBQUqZVeMXXlqML9w+pyORzX/FioKtPwMlKwKAOEDinXZM+Ee+jCOPCDB0gfElEMqaoVB0Nn8Qy8VuGbTnDffne6RcrhnKOPaVADb2Mgbsx6gjvGgDMutTiP2AaqnPBPcXTAng9DjTUzsxdeQeEd9MDMD+YFktdLYqDIy4PE5GyvrsjPgueROKLSw9rtzUqP9HWA/uDrh5OaDwlb+KYLbKbddrPOeoatVbkAKfFl1rYbTln7UqyuRYvm/Ag==';

        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me/drive/root/children', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,               
            ],
        ]);
        $data = json_decode($response->getBody());
dd($data);
        // return view('onenote.pages', ['pages' => $data]);
    }

    public function getAccessToken()
    {        
        $clientId = env('MICROSOFT_CLIENT_ID');        

        $input = [
            'response_type' => 'token',
            'client_id' => $clientId,
            'scope' => "onedrive.readwrite offline_access",
            'redirect_uri' => 'https://login.live.com/oauth20_desktop.srf',
        ];
  
        $client = new Client();
        $response = $client->request('GET', "https://login.live.com/oauth20_authorize.srf", ['form_params' => $input]);
     
        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
dd($responseBody);
        $this->accessToken = $responseBody['access_token'];
        return $responseBody;
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
