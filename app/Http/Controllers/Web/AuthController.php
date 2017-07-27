<?php

namespace App\Http\Controllers\Web;

use App\Model\User;
use Request;
use Illuminate\Http\Request as Re;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signup()
    {
        $username  = Request::get('username');
        $password  = Request::get('password');
        $email = Request::get('email');

        if (!(isset($username) && isset($password) && isset($email)))
            return ['status' => 0, 'msg' => '用户名、密码、邮箱都不能为空!'];

        if ($this->where($username, 'name')->orWhere($email, 'email')->exists())
            return ['status' => 0, 'msg' => '用户名或邮箱已存在!'];

        $user = new User();
        $user->name = $username;
        $user->password = encrypt($password);





    }

    public function signin()
    {

    }

    public function signout()
    {

    }
}
