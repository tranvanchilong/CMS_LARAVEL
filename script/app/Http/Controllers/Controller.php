<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function verifyRegister($data){
        $secret_key = '1TI6WqikBjK62KQTngVrcA2AitsHhWWCp36YBtTuYmFE';
        if($secret_key != $data['secret']){
            return false;
        }

        try{
            $client = new Client();
            $res = $client->request('GET', 'https://my.di4l.vn/verify-login', ['query' => ['token' => $data['token']]]);

            $data = json_decode($res->getBody());
            if(!$data->success){
                return false;
            }
            return true;

        }catch (BadResponseException $exception){
            return false;
        }
    }

    public function verifyDi4l($data){
      $secret_key = '1TI6WqikBjK62KQTngVrcA2AitsHhWWCp36YBtTuYmFE';
      if($secret_key != $data['secret']){
        return false;
      }

      try{
          $client = new Client();
          $res = $client->request('GET', 'https://my.di4l.vn/verify-login', ['query' => ['token' => $data['token']]]);

          $data = json_decode($res->getBody());
          if(!$data->success){
            return false;
          }

          $user = User::where('email', $data->user->email)->first();
          if(!$user){
              return false;
          }
          if($data->user->verify_email_at && $user->email_verified == 0){
              $user->email_verified_at = $data->user->verify_email_at;
              $user->email_verified = 1;
              $user->save();
          }
          return $user;

      }catch (BadResponseException $exception){
        return false;
      }
    }

    public function verifyLoginDomain(Request $request){
      if(!$request->token){
          return ['success' => 0];
      }
      $user = User::where('token_login', $request->token)->first();
      if(!$user){
          return ['success' => 0];
      }else{
          User::where('token_login', $request->token)->update(['token_login' => null]);
          return ['success' => 1];
      }
    }

    public function verifyDi4lSellAdmin($data){
      $secret_key = '1TI6WqikBjK62KQTngVrcA2AitsHhWWCp36YBtTuYmFE';
      if($secret_key != $data['secret']){
        return false;
      }

      try{
          $client = new Client();
          $res = $client->request('GET', env('APP_URL') . '/verify-login-domain', ['query' => ['token' => $data['token']]]);

          $data = json_decode($res->getBody());
          if(!$data->success){
            return false;
          }
          return true;

      }catch (BadResponseException $exception){
        return false;
      }
    }
}
