<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Record extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function user()
    {
        return $this->hasOne(User::class, 'uid','uid');
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'id','task_id');
    }
}
