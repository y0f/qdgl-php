<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Classes extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function task(){
        return $this->hasMany(Task::class, 'uid', 'uid')->with(['user','course','term','classes']);
    }
}
