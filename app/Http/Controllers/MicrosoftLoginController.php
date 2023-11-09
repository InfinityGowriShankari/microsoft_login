<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Socialize;
use Illuminate\Support\Facades\Log;
use App\Services\MicroSoft\MicroSoftService;
use App\Models\User;
use Auth;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Support\Facades\Http;

class MicrosoftLoginController extends Controller
{    
     /**
     * @var Object
     * */
    protected $microSoftService;

    /**
     * @return void*/
    public function __construct(MicroSoftService $microSoftService)
    {
        $this->microSoftService = $microSoftService;        
    }


    public function redirectToMicrosoft()
    {
        config(['services.graph.redirect' => env('MICROSOFT_OUTLOOK_REDIRECT_URI')]);
        return Socialize::driver('graph')->scopes(['offline_access'])->redirect();
    }

    public function handleMicrosoftCallback()
    {
        try{
            config(['services.graph.redirect' => env('MICROSOFT_OUTLOOK_REDIRECT_URI')]);            
            $user = Socialize::driver('graph')->user();
            dd($user);
            $existingUser = User::where('email', $user->email)->first();
            
            if (!$existingUser) {                
                $newUser = User::create([  
                    'name' => 'Employee',              
                    'email' => $user->email,
                    'access_token' => $user->token,
                    'refresh_token' => $user->refreshToken,
                ]);
            }

            session()->put('access_token', $user->token);
            session()->put('refresh_token', $user->refreshToken);
            session()->put('user_id', $user->id);
            session()->put('user_principal_name', $user->userPrincipalName);
            // return view('microsoft.create_onedrive_file');

            return redirect()->route('microsoft.listOneDrive'); /* list OneDrive */

            // return view('microsoft.create_note_book'); /* create notebook */

            // return redirect()->route('microsoft.onenote'); /* displaying onenote*/

            // $this->createMeetingForUser($user);

            // return view('microsoft.calendar_schedule_meeting'); /* schedule meeting*/

            // return redirect()->route('microsoft.me'); /* get logged in user details API*/

            // return redirect('https://outlook.office.com'); /* Outlook redirection */

        } catch (Exception $e) {
            Log::error('SSO Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login with Microsoft failed. Please try again.');
        }
    }

    public function redirectToMicrosoftTeams()
    {           
        config(['services.graph.redirect' => env('MICROSOFT_TEAMS_REDIRECT_URI')]);
        return Socialize::driver('graph')->redirect();
    }

    public function handleMicrosoftTeamsCallback()
    {
        try{
            config(['services.graph.redirect' => env('MICROSOFT_TEAMS_REDIRECT_URI')]);
            $user = Socialize::driver('graph')->user();
            $existingUser = User::where('email', $user->email)->first();

            return redirect('https://teams.microsoft.com');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Login with Microsoft failed. Please try again.');
        }
    }

    public function createMeetingForUser($user)
    {
        $user = User::where('email', $user->email)->first();

        if (!$user) {
            return response('User not found', 404);
        }

        if ($this->isAccessTokenExpired($user->access_token)) {
            $refreshedTokens = $this->refreshAccessToken($user->refresh_token);

            if (isset($refreshedTokens['access_token'])) {
                $user->access_token = $refreshedTokens['access_token'];

                if (isset($refreshedTokens['refresh_token'])) {
                    $user->refresh_token = $refreshedTokens['refresh_token'];
                }

                $user->save();
            } else {
                return response('Error refreshing token', 500);
            }
        }
        return view('microsoft.calendar_schedule_meeting');        
    }

    private function isAccessTokenExpired($accessToken)
    {
        return true;
    }

    private function refreshAccessToken($refreshToken)
    {
        $clientId = env('MICROSOFT_CLIENT_ID');
        $clientSecret = env('MICROSOFT_CLIENT_SECRET');
        $tenantId = env('MICROSOFT_TENANT_ID');

        $tokenEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/token";

        $requestData = [
            'client_id' => $clientId,
            'scope' => 'https://graph.microsoft.com/.default offline_access',
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_secret' => $clientSecret,
        ];

        $response = Http::asForm()->post($tokenEndpoint, $requestData);

        if ($response->failed()) {
            Log::error('Refresh token request failed: ' . $response->status());
            Log::error('Response content: ' . $response->body());
            return ['error' => 'Error refreshing token'];
        }

        return $response->json();
    }  
}
