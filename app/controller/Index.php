<?php
namespace app\controller;

use app\BaseController;
use app\model\File;
use app\Request;
use think\facade\Db;
use think\Model\File as FileModel;

class Index extends Base
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function upload($id){
        // 获取表单上传文件 例如上传了001.jpg
        $data = \app\model\User::where('stu_id',$id)->find();
        $file = request()->file('image');
        // 上传到本地服务器
        $savename = \think\facade\Filesystem::disk('public')->putFile('/',$file);
        $filename = explode("\\",$savename);
        $data = ['uid'=>$data['uid'],'url_name'=>$savename,'file_name'=>$filename[1]];
        $file = new File();
        $file->save($data);
        echo $savename;
        echo $filename[1];
    }

    public function download()
    {
        // download是系统封装的一个助手函数
//        return download('storage\topic\20201204\12ecd809f819c069b6be82cc1722c174.jpg', 'my.jpg')->force(false);
        $data = File::select();
        return json($data);
    }
}
