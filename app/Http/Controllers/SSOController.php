<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Config;
Use App\Models\User;
use Carbon\Carbon;

class SSOController extends Controller
{   
    public function ssoUser()
    {
        if($this->hasValidToken()){

        } else{
            $redirectUri = Config::get('custom.redirect_uri');        
            $clientId = Config::get('custom.client_id');        
            $authorizationUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';

            $input = [
                'client_id' => $clientId,
                'redirect_uri' => $redirectUri,
                'response_type' => 'code',
                'response_mode' => 'query',
                'scope' => 'https://graph.microsoft.com/.default',
                'state' => '12345',
            ];

            return redirect()->to($authorizationUrl . '?' . http_build_query($input));
        }        
    }

    public function handleSSOCallback(Request $request)
    {        
        $tokenDetails = $this->getUserToken($request->code);
        
        $userDetails = $this->getUserDetails();
        if(!empty($userDetails)){            
            $this->email = $userDetails->userPrincipalName;
            $this->name = $userDetails->displayName;
        }
        $this->insertTokenForUsers();
    }
    
    public function getUserToken($code)
    {
        $clientSecret = Config::get('custom.client_secret');
        $clientId = Config::get('custom.client_id');
        $redirectUri = Config::get('custom.redirect_uri');

        $input = [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default offline_access',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];
        
        $responseDetails = $this->getResponseData($input);
    
        return $responseDetails;
    }

    public function getResponseData($input)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/common/oauth2/v2.0/token', [            
            'form_params' => $input,
        ]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
        
        if ($statusCode == 200 && isset($responseBody) && !is_null($responseBody)) {            
            $this->accessToken = $responseBody['access_token'];
            $this->refreshToken = $responseBody['refresh_token'];
            $this->expiresAt = $responseBody['expires_in'];

            return $responseBody;
        } else {
            return ['error' => 'Failed to obtain access token'];
        }
    }

    public function getAccessTokenBasedOnRefreshToken()
    {
        $clientSecret = Config::get('custom.client_secret');
        $clientId = Config::get('custom.client_id');
        $redirectUri = Config::get('custom.redirect_uri');

        $input = [
            'grant_type' => 'refresh_token',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default offline_access',
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $redirectUri,
        ];

        $responseDetails = $this->getResponseData($input);
        
        return $responseDetails;
    }

    public function insertTokenForUsers()
    {                    
        $userName = User::where('email', $this->email)->pluck('name')->first();
        $name = empty($userName) ? $this->name : 'User';                

        $createUpdateUser = User::updateOrCreate(
            [
                'email'   => $this->email,
            ],
            [
                'access_token'   => $this->accessToken,
                'refresh_token' => $this->refreshToken,
                'name' => $this->name,
                'expires_at' => Carbon::now()->addSeconds($this->expiresAt)->format('Y-m-d H:i:s'),
            ],
        );            
    }

    public function getUserDetails()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);
                        
            return json_decode($response->getBody());
        } catch (\Exception $e) {
            return view('microsoft.error', ['error' => $e->getMessage()]);
        }
    }

    public function hasValidToken()
    {
        $expiryTime = User::where('email', $this->email)->pluck('expires_at')->first();
        $currentTime = Carbon::now()->format('Y-m-d H:i:s');
        
        if($currentTime > $expiryTime){
            $this->getAccessTokenBasedOnRefreshToken();
        }
        return true;
    }
}