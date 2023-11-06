<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;

class NoteBookController extends Controller
{
    public function listOneNotePages(Request $request)
    {
        $client = new Client();

        $accessToken = $request->session()->get('access_token');
        // $accessToken = 'EwCAA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAbRPQAIEadwVbFLQAw21ti5pJzrM/TAuUok5Xm5WaufWvQgKgpzorig/Z2k0jZs2OPD3GqtY6FEGEYF0o/03kDuJ7v1NXTC87Z2UmroTjQkejH0t9KAuV2bkiic0i9j1ESmSsBwumB48NNYk25/GJziJiiIu1WTZnm9VJNsaa/DrbTstfLiIwwdDw0bGgLlJgUkvZRrkQYxhq3Oqxmu2QhCPKPa1apHYtCp8cn5VfEa/v+ztuVuPbLh30In6TpXNCDxiuw+Q2JP9Rz23jXQv/rVb13NcsBUlR92e3jwE0D9mIVSIx1ZE/j3e9mxm4k5q0Yc1k1B829qgcyNJZEG4UpQDZgAACPpXe/4oNl7HUAK3WLmzQaPcTf34uWJqeGsxJi3fvBPK//4Z/xAaxVJclZqQlZA/pk4t/K1fM6W5cAsYeno7PtHduBg1NN+6/tEcHD4A21ATNWXuTowUQGxoqLqowKIU8N298cW9c85wKZCp05dEw0tvDN36LF15QutK7BzjGPNuGp0F/5jeLxbCSrN5wv/USWuUMTaVYKicapjYKua2p5wwVqD9GTJpq3VuwyVSdQ7O9kuTuP0yINoUnPptV8iBWmzoKdufRjy8QFR+59UTdJnSGY8tmqm9xMwgkwPAHBZeIg7wQ8nOmY0gMI+k+bbpQ5WMgIgnEIR6++gpW/atC1J4CcQ9FcMOBRMXJ3kU2BxSQ6VI02DbJ+oK/2mYZwJr2Hj+K7zSvGU6ssa9FlPB5x/DLJQK2Bv5gi8Qdp28J+lgUYhVHRAjSpZ7rBqZExs/G78NRz30PCAfpwTNZlQOS5Z0bFwvisLluEUTOpgWRMF3s1YHovujSgc5LExA6MJE4cU1uziY1i3mBgDfF17/iPiTr5/knjJkAWivkdPfMUSp5mskiv/clHRtOPGKzi/hySwh3xpUe/q2oDUYtdjJ07xcME57QJFsdtzmftqwg00qVpcYzMqBcpi7w33CPBJ96CpclxJRQs2ArqMgocddsgTOXZEcZxcEYOKAlyhTlXTBy28DDtzUa3uhZQaiI1om0XznmJVD7jok86b9FDLgQr6M0yiIt7iF9GryKvO2LiVsJX4XqpKLOVW6WtAIBl5ZpwsRkxrM8N42yQUVlTxXD4gsoszSrRcfMhq6nwI='; 

        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me/onenote/pages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ],
        ]);
        $data = json_decode($response->getBody());

        return view('onenote.pages', ['pages' => $data]);
    }

    private function getAccessToken($request)
    {        
        $accessToken =  $request->session()->get('access_token');
        $refreshToken =  $request->session()->get('refresh_token');
        $user = User::where('access_token', $accessToken)->first();                

        if (!$user) {            
            $tokenResponse = $this->requestNewAccessToken($refreshToken);
            if ($tokenResponse) {
                
                // $user->update(['access_token' => $tokenResponse['access_token']]);
                $accessToken = $tokenResponse['access_token'];
            } else {
                Log::error('Refresh token request failed: ' . $tokenResponse);                
            }
        }

        return $accessToken;
    }

    private function requestNewAccessToken($refreshToken)
    {
        $tenantId = env('MICROSOFT_TENANT_ID');

        $client = new Client();
        $response = $client->post("https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token", [
            'form_params' => [
                'client_id' => env('MICROSOFT_CLIENT_ID'),
                'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
                'redirect_uri' => 'http://localhost:8000/auth/microsoft/callback',
                'code' => $refreshToken,
                'grant_type' => 'authorization_code',
                'scope' => 'https://graph.microsoft.com/.default',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createNotebook()
    {
        // $accessToken = $this->getAccessTokenWorking(); 
        $accessToken = 'EwCAA8l6BAAUAOyDv0l6PcCVu89kmzvqZmkWABkAAWh6mBskR/FTC2SkYtK6jM0u6XYaOpJk8A9S84fTkWAjJ0ZreyoeBN8CwCFHZLAo2wJ7PnijtVbSxl9lfHHsUGj+DdaX/5+RyYy2ZJSwI8K3oONFspuPwfWEfp4u0i3j2+3rlWyidt6l1GIOsRlLN4nJSliNmTaL+QwApH8rMTToWZiAnwR3gYz6EJ7TvtrA2MqrpTcZSzPL5d9y0sHdPthtguDdIj3rOzdblQNS79gMuZyyR0i2NStXHxGv9GSdymmrNpwb7rNCldCDZMDhyfbJRP3D4jFaWL/eGZhq9iwwnc/QtWkuPoEZPBU4sYrO/4pho4AU9lNNZYWjxph9ZUIDZgAACOpiA/5H6En+UAKMN4YwZGY2hY6hkdqp4KvkE8nQptmNDA8KZPacxMC0PbgZbzz/w8Rangua2+uHhY0pOZF6lc6R0EsMFdF2XtBxP/245iS/9q21lJx25n4BjJBEsH23DBYkjJ6MKVqLFyg2i/jWfHIeGU3Lvow47wss8h3nOQR5VxMSNxsNq6bnLomhfXO9l/6H+9WUY9o9mDC8RuRUOcQYCxBGYzxNHZBnoRbFSqGdkfMnjUvXeykcdPXCbu8ZqEF0u+mHf+TVO3LTi79YwaZ+C3q7swEMs5GkhwPgotxZ1hsfbhiOSaG0y5d1kQq06bMNEyBQ4pZg9sTzprjVAywmTipSrDw42Q9ynILBzb6DQl+PWzRr9JZpBMH74GXBw7Q7D5eJIuEmlXKh3HnCFQwD2K2Dljf+dy68EQ/BvGM4yWNgeF/MzsmZqskzZTIlQ2sfRuokvU+YEKxxUzucFv13BPAaB+dfLlFs7ZQ32LRIkjqq3eosA+rWf1LABVx4t5GSFnSGiM1OdSBGZAl2uWWqmMQiQ0WujDIEC7xNruXpEbZ3QPRKzESDQrnHkz3wBj8ccmrJQUUIy521EfXxOZtOk9+SivD6uY2LiRcQHYXH8lD4npLm/ov0R+uM/BJpcHtCFpAEFgCZ+TZHzIos/okXED0wDRwwvNVjRs705RGVXwM2OlInH/NupBHhVf5onpWMcEUnSmkzsyrJ2t1/vO0P0obA+xPylSdsW1uqkFskMNwtjryTelYt6tjIfvRiiKU4qlY8IenCt4Pg9kFnR9QDgQHm7UQsHr6WnwI=';

        if (!$accessToken) {
            return redirect('/auth/microsoft');
        }

        $client = new Client();

        $response = $client->post('https://graph.microsoft.com/v1.0/me/onenote/notebooks', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'displayName' => 'My New Notebook',
            ],
        ]);

        if ($response->getStatusCode() == 201) {
            $data = json_decode($response->getBody(), true);
            $notebookId = $data['id'];

            return "Notebook created with ID: $notebookId";
        } else {
            return "Failed to create the notebook. HTTP Status Code: " . $response->getStatusCode();
        }
    }

    
    public function getAccessTokenWorking()
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

        $accessToken = $responseBody['access_token'];
        return $accessToken;
    }        
}
