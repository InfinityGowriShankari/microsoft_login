<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DateTime;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Model\OnlineMeeting;
use Microsoft\Graph\GraphServiceRequest;
use Microsoft\Graph\GraphServiceClient;

class MicrosoftGraphController extends Controller
{
    public function getMe(Request $request)
    {        
        $accessToken = $request->session()->get('access_token');

        if (!$accessToken) {
            return redirect()->route('microsoft.login');
        }

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            $data = json_decode($response->getBody());
// dd($data);
            
            return view('microsoft.graph', ['data' => $data]);

        } catch (\Exception $e) {
            return view('microsoft.error', ['error' => $e->getMessage()]);
        }
    }

    public function createEvent(Request $request) // Create meeting with static acess token
    {
        $accessToken = $request->session()->get('access_token');
        $userID = $request->session()->get('user_principal_name');

        $graph = new Graph();
        $graph->setAccessToken('eyJ0eXAiOiJKV1QiLCJub25jZSI6ImpEU3h0a2xheDFZZnI0YzVKOWdScXlFUk45UjJGeXZUMWVHcHFTdzNXYnMiLCJhbGciOiJSUzI1NiIsIng1dCI6IjlHbW55RlBraGMzaE91UjIybXZTdmduTG83WSIsImtpZCI6IjlHbW55RlBraGMzaE91UjIybXZTdmduTG83WSJ9.eyJhdWQiOiJodHRwczovL2dyYXBoLm1pY3Jvc29mdC5jb20iLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wNTQ2OWY1ZC01MTE0LTQxNDYtYmMxNy05ZDk2NDBiMWEyZjcvIiwiaWF0IjoxNjk5MDk0MzQ2LCJuYmYiOjE2OTkwOTQzNDYsImV4cCI6MTY5OTA5ODI0NiwiYWlvIjoiRTJGZ1lQQmxFZUhRbURDVjJmU3daTC9qOXRXK0FBPT0iLCJhcHBfZGlzcGxheW5hbWUiOiJJbnRlZ3JhdGlvbiBwdXJwb3NlIiwiYXBwaWQiOiJjM2E1ZWNjYy03ZGU4LTQ5ZDctYjNlNS1iYjZmOTZhMzEwZWYiLCJhcHBpZGFjciI6IjEiLCJpZHAiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wNTQ2OWY1ZC01MTE0LTQxNDYtYmMxNy05ZDk2NDBiMWEyZjcvIiwiaWR0eXAiOiJhcHAiLCJvaWQiOiJjNTZiYTBkZS1kNzYyLTRhMTEtOGFhYi1hOTg0YTdkMTQzYjYiLCJyaCI6IjAuQVNzQVhaOUdCUlJSUmtHOEY1MldRTEdpOXdNQUFBQUFBQUFBd0FBQUFBQUFBQURDQUFBLiIsInJvbGVzIjpbIkFQSUNvbm5lY3RvcnMuUmVhZFdyaXRlLkFsbCIsIlVzZXIuUmVhZFdyaXRlLkFsbCIsIkNhbGVuZGFycy5SZWFkIiwiVXNlci5FbmFibGVEaXNhYmxlQWNjb3VudC5BbGwiLCJVc2VyLlJlYWQuQWxsIiwiQ2FsZW5kYXJzLlJlYWRCYXNpYy5BbGwiLCJBUElDb25uZWN0b3JzLlJlYWQuQWxsIiwiVXNlci5FeHBvcnQuQWxsIiwiQ2FsZW5kYXJzLlJlYWRXcml0ZSIsIlVzZXIuTWFuYWdlSWRlbnRpdGllcy5BbGwiXSwic3ViIjoiYzU2YmEwZGUtZDc2Mi00YTExLThhYWItYTk4NGE3ZDE0M2I2IiwidGVuYW50X3JlZ2lvbl9zY29wZSI6IkFTIiwidGlkIjoiMDU0NjlmNWQtNTExNC00MTQ2LWJjMTctOWQ5NjQwYjFhMmY3IiwidXRpIjoiOHZCM0lNblhZa0Mza2h2YnVJY1FBQSIsInZlciI6IjEuMCIsIndpZHMiOlsiMDk5N2ExZDAtMGQxZC00YWNiLWI0MDgtZDVjYTczMTIxZTkwIl0sInhtc190Y2R0IjoxNjk4NzQ4MTA5fQ.vkDIX8fdQtMUVV7wNhTYReNmrNnpy9ucGKii5qcyWmj1Vo2ZBAwgjcJ74E0URxvaMf8KbMya_E8dGiy4tACB2gWQoNCN3J_DdrURsuPav3BQtsnfb8Q0eYfD6Ou06j5RdNd0ZFxgMyGxXeY7DaS8M4rtDaeeHi6ZdLDEZGDg_lX-skrHb8Dk6s8ilsQwO0c09FitkuQ169uolYqabjEYPoJ7v7op_9GAjhQv0PlgZ920X9H5KZvCs5A92u8r0iXo3z9vuUq5GS60uzo7xtB9zJsPK8FAf4B56y9ZvQ29wRnnJeoF29easqJ6r3eEUfVZ1l9U7yK5dBUZP6QQSDrDyw');
        // dd($accessToken);
       
        $startDate = new DateTime('2023-11-05 13:25:20');
        $endDate = new DateTime('2023-11-05 14:25:20');
        
        $event = new Model\Event();
        $event->setSubject('New meeting');
        $event->setStart(new Model\DateTimeTimeZone([
            'dateTime' => $this->formatDateTimeT($startDate),
            'timeZone' => 'UTC',
        ]));
        $event->setEnd(new Model\DateTimeTimeZone([
            'dateTime' => $this->formatDateTimeT($endDate),
            'timeZone' => 'UTC',
        ]));

        $addresses = ['gowrishankari97@outlook.com'];
        $attendees = [];
        foreach($addresses as $address){
            $attendee = new Model\Recipient(["emailAddress" => ["address" => $address]]);
            $attendees []= $attendee;
        }        
        $event->setAttendees($attendees);

        $response = $graph->createRequest('POST', '/users/'.$userID.'/events')
                        ->attachBody($event)                        
                        ->setReturnType(Model\Event::class)
                        ->execute();

        
        $meetingId = $response->getId();
        dd($meetingId, $response);
    }
    
    public function formatDateTimeT($dateTime)
    {
        return $dateTime->format('Y-m-d\TH:i:s');
    }

    public function createEventsa(Request $request)
    {
        $accessToken = $request->session()->get('access_token');

        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        $graph->setApiVersion('v1.0');

        $graphServiceClient = new GraphServiceClient($graph);

        $requestBody = new OnlineMeeting();
        $requestBody->setStartDateTime(new \DateTime('2019-07-12T14:30:34.2444915-07:00'));
        $requestBody->setEndDateTime(new \DateTime('2019-07-12T15:00:34.2464912-07:00'));
        $requestBody->setSubject('User Test Meeting');

        try {
            $result = $graphServiceClient->me()->onlineMeetings()->post($requestBody)->request();

            $onlineMeeting = $result->setReturnType(OnlineMeeting::class)->execute();

            echo 'Online Meeting created successfully.' . PHP_EOL;
            echo 'Join URL: ' . $onlineMeeting->getJoinUrl() . PHP_EOL;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }
}