<?php

namespace App\Services\MicroSoft;

use Illuminate\Support\Facades\Config;

//model files

class MicroSoftService
{
   
    /**
     * @param $user
     * @return string
     * */
    public function checkValidUser($user): string
    {
        if (isset($user->token) && isset($user->user)) {
            if (!empty($user->token) && !empty($user->user)) {
                $userDetails = $user->user;
                if ($userDetails['email']) {
                    return $this->redirectToFronEnd(true, $user->token, $user->expiresIn, $userDetails);
                }
            }
        }
        return $this->redirectToFronEnd(false);
    }

    /**
     * @param $status
     * @param $token
     * @param $expiresIn
     * @param $userDetails
     * @return string
     * */
    private function redirectToFronEnd($status, $token = '', $expiresIn = '', $userDetails = [])
    {

        $result = ($status) ? [
            'status' => true,
            'email' => $userDetails['email'],
            'message' => 'Success',
            'type' => 'microsoft'
        ] :

        [
            'status' => false,
            'message' => 'Invalid User',
            'type' => 'microsoft'
        ];

        
        return 'http://127.0.0.1:8000/';
    }
}