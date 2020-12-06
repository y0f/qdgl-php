<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名'=>'require|max:50',
        'stu_id|学号'=>'require|max:30',
        'wx_id|微信ID'=>'max:50',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
//        'username.require'=>'用户名不能为空~',
//        'username.max'=>'用户名超过50个字符~',
//        'password.require'=>'密码不能为空~',
//        'password.max'=>'密码不能超过50个字符~',
//        'stu_id.require'=>'学号不能为空~',
//        'stu_id.max'=>'学号不能超过30个字符',
//        'wx_id'=>'微信OPENID不能超过50个字符'
    ];

    protected $scene = [
    ];
}
