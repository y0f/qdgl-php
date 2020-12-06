<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Course extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
