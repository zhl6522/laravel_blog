<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    public function index()
    {
        $user_name = session('user')->user_name;
        return view('admin.index', compact('user_name'));
    }

    public function info()
    {
        return view('admin.info');
    }

    public function quit()
    {
        session(['user' => null]);
        return redirect('admin/login');
    }

    public function pass()
    {
        return view('admin.pass');
    }
    
    public function repass()
    {
        if($input = Input::all()) {
            $rules = [
                'password'=>'required|between:6,20|confirmed',
            ];
            $message = [
                'password.required' => '新密码不能为空',
                'password.between' => '新密码必须在6-20位之间',
                'password.confirmed' => '新密码与确认密码不一致',
            ];
            $validator = Validator::make($input, $rules, $message);
            if(!$validator->passes()) {
//                dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
            $user = User::first();
            $_password = Crypt::decrypt($user->user_password);
            if($input['password_o'] == $_password) {
                $user->user_password = Crypt::encrypt($input['password']);
                //$user->save();
                $user->update();
                return back()->with('errors', '密码修改成功');
            } else {
//                $arr = [
//                    '0' => '原密码错误'
//                ];
//                return back()->withErrors($arr);
                return back()->with('errors', '原密码错误');
            }
        }
    }
}
