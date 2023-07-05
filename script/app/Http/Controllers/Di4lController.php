<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Domain;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Di4lController extends Controller
{
    public function httpClient($url, $query){
        try{
            $client = new Client();
            $res = $client->request('GET', $url, ['query' => $query]);

            return json_decode($res->getBody());
        }catch (BadResponseException $exception){
            return false;
        }
    }

    public function verifyLogin(Request $request){
//        $secret_key = '1TI6WqikBjK62KQTngVrcA2AitsHhWWCp36YBtTuYmFE';
//        if($secret_key != $request->secret){
//            return ['success' => 0];
//        }

        $user = User::select('users.name', 'users.email', 'users.email_verified', 'domains.domain as subdomain', 'requestdomains.domain', 'users.password')
            ->where('users.di4l_token', $request->token)
            ->leftJoin('domains', 'users.domain_id', '=', 'domains.id')
            ->leftJoin('requestdomains', 'users.domain_id', '=', 'requestdomains.domain_id')
            ->first()->makeVisible(['password']);

        if(!$user->domain){
            $user->domain = $user->subdomain;
        }

        if(!$user){
            return ['success' => 0];
        }

       return ['success' => 1, 'user' => $user];
    }
}
