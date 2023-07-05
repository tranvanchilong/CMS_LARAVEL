<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Category;
use Auth;

class Di4lController extends Controller
{
    public function analytics(){
        $token = md5(microtime(true).mt_Rand());
        User::where('id', Auth::id())->update(['di4l_token' => $token]);

        return redirect('http://analytics.di4l.vn/login-di4l?secret=1TI6WqikBjK62KQTngVrcA2AitsHhWWCp36YBtTuYmFE&token=' . $token);
    }
}
