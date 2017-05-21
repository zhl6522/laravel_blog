<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

include_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
//        session(['user' => null]);
//        dd($_SERVER);
        return view('admin.login');
    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    public function store()
    {
        if ($input = Input::all()) {
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!= $_code) {
                return back()->with('msg', '验证码错误！');
            }
            $user = User::first();
            if($user->user_name != $input['user_name'] || Crypt::decrypt($user->user_password) != $input['user_password']) {
                return back()->with('msg', '用户名或密码错误！');
            }
            session(['user' => $user]);
//            dd(session('user'));
            return redirect('admin/index');
        }
    }

    public function crypt() {
        //字符加密长度<250
        $str = '123456';
        echo Crypt::encrypt($str);
        echo '<br/>';
        $str_p = 'eyJpdiI6Im1qRnRNY1BTYmlvRG1qUHV2U2E3ZHc9PSIsInZhbHVlIjoiVHd0b3ZLWlNYRjNoMFwvWk9kcjYrQ2c9PSIsIm1hYyI6IjQwMDM1YTUyYWY3NTMxYmE1NWJlYWQ1NWI5YjNhN2Y2Nzg2N2JjYWZhZGUyMzBlNjQ0YThiOWU1NTVkZWY3NzEifQ==';
        echo Crypt::decrypt($str_p);
    }

}
