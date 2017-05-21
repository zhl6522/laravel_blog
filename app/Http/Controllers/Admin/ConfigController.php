<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends CommonController
{
    public function index()
    {
        $data = Config::orderBy('conf_order', 'desc')->get();
        foreach($data as $k => $v) {
            switch($v->field_type) {
                case 'input':
                    $data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea class="lg" name="conf_content[]">'.$v->conf_content.'</textarea>';
                    break;
                default:
                    $arr = explode(',', $v->field_value);
                    $str = '';
                    foreach($arr as $key => $value) {
                        $r = explode('|', $value);
                        $c = $v->conf_content == $r[0]?' checked ':'';
                        $str .='<input type="radio" name="conf_content[]" value="'.$r[0].'" '.$c.'>'.$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.config.index', compact('data'));
    }

    public function changeContent()
    {
        $input = Input::all();
        foreach ($input['conf_id'] as $k => $v) {
            Config::where('conf_id', $v)->update(['conf_content' => $input['conf_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors', '配置项更新成功');
    }
    
    public function putFile()
    {
        //echo \Illuminate\Support\Facades\Config::get('web.web_title');//读取新加的web.php网站配置文件的内容
        $config = Config::pluck('conf_content', 'conf_name')->all();
        $str = '<?php return '.var_export($config, true).';';
        $path = base_path().'/config/web.php';
        file_put_contents($path, $str);
    }

    public function changeOrder()
    {
        $input = Input::all();
        $conf = Config::find($input['conf_id']);
        $conf->conf_order = $input['conf_order'];
        if($conf->update()) {
            $data = [
                'status' => 0,
                'msg' => '配置项排序更新成功',
            ];
        } else {
            $data = [
                'status' => 1,
                'msg' => '配置项排序更新失败',
            ];
        }
        return $data;
    }

    public function create()
    {
        return view('admin.config.create');
    }

    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'conf_title'=>'required',
            'conf_name'=>'required',
        ];
        $message = [
            'conf_title.required' => '配置项标题不能为空',
            'conf_name.required' => '配置项名称不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->passes()) {
            $re = config::create($input);
            if($re) {
                return redirect('admin/config');
            } else {
                return back()->with('errors', '数据填充失败，请稍后重试');
            }
        } else {
            return back()->withErrors($validator);
        }
    }

    public function edit($conf_id)
    {
        $data = Config::find($conf_id);
        return view('admin.config.edit', compact('data'));
    }

    public function update($conf_id)
    {
        $input = Input::except('_token', '_method', 'admin/config/'.$conf_id);
        $re = config::where('conf_id', $conf_id)->update($input);
        if($re) {
            $this->putFile();
            return redirect('admin/config');
        } else {
            return back()->with('errors', '配置项信息修改失败，请稍后重试');
        }
    }

    public function destroy($conf_id)
    {
        $re = config::where('conf_id', $conf_id)->delete();
        if($re) {
            $this->putFile();
            $data = [
                'status' => 0,
                'msg' => '配置项删除成功'
            ];
        } else {
            $data = [
                'status' => 1,
                'msg' => '配置项删除失败'
            ];
        }
        return $data;
    }

    public function show()
    {

    }
}
