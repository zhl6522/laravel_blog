<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='category';
    protected $primaryKey='cate_id';
    public $timestamps=false;
    protected $guarded = ['admin/category'];//排除不能填充的字段，与$fillable(可以填充的字段)相反
    
    public function tree()
    {
        $categorys = $this->orderBy('cate_order', 'asc')->get();
        return $this->getTree($categorys, 'cate_name', 'cate_pid', 'cate_id');
    }

//    public function tree()
//    {
//        $categorys = Category::all();
//        return (new Category)->getTree($categorys, 'cate_name', 'cate_pid', 'cate_id');
//    }
    
    public function getTree($data, $field_name, $field_pid='pid', $field_id='id', $pid=0)
    {
        $arr = array();
        foreach ($data as $k => $v) {
            if($v->$field_pid == $pid) {
                $arr[] = $data[$k];
                foreach($data as $m => $n) {
                    if($n->$field_pid == $v->$field_id) {
                        $data[$m][$field_name] = '├─ '.$data[$m][$field_name];
                        $arr[] = $data[$m];
                    }
                }
            }
        }
        return $arr;
    }
}
