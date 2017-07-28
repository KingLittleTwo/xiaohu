<?php

namespace App\Http\Controllers\Web;

use App\Model\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = new User();
        $data = self::getRequest($request);

        if (!(isset($data['username']) && isset($data['password']) && isset($data['email'])))
            return ['status' => 0, 'msg' => '用户名、密码、邮箱都不能为空!'];

        if ($user->where('name', $data['username'])->orWhere('email', $data['email'])->exists())
            return ['status' => 0, 'msg' => '用户名或邮箱已存在!'];

        $user->name  = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        if ($user->save())
            return [
                'status'  => 1,
                'msg'     => '注册成功!',
                'user_id' => $user->id
            ];
        else
            return [
                'status' => 0,
                'msg'    => '注册失败'
            ];
    }

    public function signin(Request $request)
    {
        $user = new User();
        $data = self::getRequest($request);

        if (empty($data['username']) || empty($data['password']))
            return ['status' => 0, 'msg' => '请输入用户名或密码!'];

        $userinfo = $user->where('name', $data['username'])
                         ->orWhere('email', $data['email'])
                         ->first();

        if (empty($userinfo))
            return ['status' => 0, 'msg' => '此用户不存在!'];

        if (!Hash::check($data['password'], $userinfo['password']))
            return ['status' => 0, 'msg' => '密码错误!'];

        session(['userinfo' => [
            'username' => $userinfo->name,
            'user_id'   => $userinfo->id
        ]]);

        return ['status' => 1, 'msg' => '登录成功!'];
    }

    public function signout(Request $request)
    {
        $request->session()->flush();

        if (!$request->session()->has('userinfo'))
            return ['status' => 1, 'msg' => '登出成功!'];
        return ['status' => 0, 'msg' => '登出失败!'];
    }

    public static function check($request)
    {
        return $request->session()->get('userinfo') ? true : false;
    }

    private static function getRequest($request)
    {
        return [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'email'    => $request->input('email')
        ];
    }
}
