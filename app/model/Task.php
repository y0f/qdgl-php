<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Task extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function user()
    {
        return $this->hasOne(User::class, 'uid','uid');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id','course_id');
    }

    public function term()
    {
        return $this->hasOne(Term::class, 'id','term_id');
    }

    public function classes()
    {
        return $this->hasOne(Classes::class, 'id','class_id');
    }
}
