<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    /**
     * @var mixed
     */
    private $uid;

    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function classes()
    {
        return $this->hasOne(Classes::class, 'id','class_id');
    }

    public function searchUserNameAttr($query, $value, $data){
        $query->where('username','like','%'.$value.'%');
    }

    public function searchStuIdAttr($query, $value, $data){
        $query->where('stu_id','like','%'.$value.'%');
    }

    public function task(){
        return $this->hasMany(Task::class, 'uid', 'uid');
    }
}
