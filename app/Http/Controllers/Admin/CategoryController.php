<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    public function index()
    {
        $categorys = (new Category)->tree();
        //$categorys = Category::tree();
        return view('admin.category.index')->with('data', $categorys);
    }

    public function changeOrder()
    {
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        if($cate->update()) {
            $data = [
                'status' => 0,
                'msg' => '分类排序更新成功',
            ];
        } else {
            $data = [
                'status' => 1,
                'msg' => '分类排序更新失败',
            ];
        }
        return $data;
    }

    public function create()
    {
        $pids = Category::where('cate_pid', 0)->get();
        return view('admin.category.create', compact('pids'));
    }

    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'cate_name'=>'required',
        ];
        $message = [
            'cate_name.required' => '分类名称不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->passes()) {
            $re = Category::create($input);
            if($re) {
                return redirect('admin/category');
            } else {
                return back()->with('errors', '数据填充失败，请稍后重试');
            }
        } else {
            return back()->withErrors($validator);
        }
    }

    public function edit($cate_id)
    {
        $pids = Category::where('cate_pid', 0)->get();
        $field = Category::find($cate_id);
        return view('admin.category.edit', compact('field', 'pids'));
    }

    public function update($cate_id)
    {
        $input = Input::except('_token', '_method', 'admin/category/'.$cate_id);
        $re = Category::where('cate_id', $cate_id)->update($input);
        if($re) {
            return redirect('admin/category');
        } else {
            return back()->with('errors', '分类信息修改失败，请稍后重试');
        }
    }

    public function destroy($cate_id)
    {
        $re = Category::where('cate_id', $cate_id)->delete();
        if($re) {
            Category::where('cate_pid', $cate_id)->update(['cate_pid' => 0]);
            $data = [
                'status' => 0,
                'msg' => '分类删除成功'
            ];
        } else {
            $data = [
                'status' => 1,
                'msg' => '分类删除失败'
            ];
        }
        return $data;
    }

    public function show()
    {

    }
}
