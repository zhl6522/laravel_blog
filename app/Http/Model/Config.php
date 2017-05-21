<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table='config';
    protected $primaryKey='conf_id';
    public $timestamps=false;
    protected $guarded = ['admin/config'];//排除不能填充的字段，与$fillable(可以填充的字段)相反


}
