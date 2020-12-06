<?php
declare (strict_types = 1);

namespace app\controller;

use think\db\Where;
use think\exception\ValidateException;
use think\facade\Validate;
use think\Log;
use think\Request;
use app\model\User as UserModel;
use app\validate\User as UserValidate;

class User extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     * @throws \think\db\exception\DbException
     */
    public function index(Request $request)
    {
        $data = $request->param();
        $user = new UserModel();
        $data = $user->withoutField('password')->with('classes')->paginate($data['limit']);
        if($data->isEmpty()){
            return $this->create([],'未找到数据~',204);
        }else{
            return $this->create($data,'数据查询成功~',200);
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //获取提交的注册参数，并验证
        $data = $request->param();
        try{
            validate(UserValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }

        //生成uid
        $data['uid'] = uniqid();
        $data['password'] = sha1($data['password']);
        $user = new UserModel();
        $user->save($data);

        if(empty($user->id)){
            return $this->create([],'创建失败~请联系管理员',400);
        } else{
            return $this->create($user,'创建成功~',200);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param $id
     * @return \think\Response
     */
    public function read($id)
    {
//        $data = UserModel::withoutField('password')->withSearch(['username', 'stu_id'],[
//           'username' => $id,
//           'stu_id' => $id
//        ])->select();
//        if($data->isEmpty()) {
//            return $this->create([], '未找到该用户数据~', 204);
//        }
//        else{
//            return $this->create($data,'数据查询成功~',200);
//        }
        $data = UserModel::withoutField('password')->where('stu_id',$id)->with('classes')->find();
        if(empty($data)){
            $data = UserModel::withoutField('password')->where('username',$id)->with('classes')->find();
            if(empty($data)) {
                return $this->create([], '未找到该用户数据~', 204);
            }
            else{
                return $this->create($data,'数据查询成功~',200);
            }
        }else{
            return $this->create($data,'数据查询成功~',200);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //检查更新数据
        $data = $request->param();
        try{
            validate(UserValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }
        unset($data['create_time']);
        unset($data['update_time']);
        unset($data['delete_time']);
        if(!empty($data['password'])){
            $data['password'] = sha1($data['password']);
            //获取数据库里的数据
            $user = UserModel::where('uid',$id)->find();
            if(!empty($user) && $user['uid'] === $id){
                $user->allowField(['username', 'password','stu_id', 'class_id', 'wx_id'])->save($data);
                return $this->create($user,'修改用户成功~',200);
            }else{
                return $this->create([],'修改用户失败~',400);
            }
        } else {
            $user = UserModel::where('uid',$id)->find();
            if(!empty($user) && $user['uid'] === $id){
                $user->allowField(['username', 'stu_id', 'class_id', 'wx_id'])->save($data);
                return $this->create($user,'修改用户成功~',200);
            }else{
                return $this->create([],'修改用户失败~',400);
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        try{
            $data = UserModel::where('uid',$id)->find()->delete();
            return $this->create($data,'删除成功~',200);
        }catch(\Error $e){
            return $this->create([],'错误或无法删除~',400);
        }
    }

    public function task($id){
        try{
            $data = UserModel::where('stu_id',$id)->find()->task()->select();
            return $this->create($data,'数据请求成功',200);
        }catch(\Error $e){
            return $this->create([],'无数据',400);
        }
    }

    public function login(Request $request){
        $data = $request->param();

        //验证用户密码
        $result = Validate::rule([
            'stu_id' => 'unique:user,stu_id^password'
        ])->check([
            'stu_id' => $data['username'],
            'password' => sha1($data['password'])
        ]);

        //发送登录成功内容，token
        if(!$result){
            session('admin',$data['username']);
            $data = UserModel::withoutField('password')->where('stu_id',$data['username'])->with('classes')->find();
            $data['token'] = signToken($data['uid']);
            return $this->create($data,'登录成功',200);
        }else{
            return $this->create([],'用户名或密码错误',400);
        }
    }

    public function check(Request $request){
        $token = $request->header('token');
        $res = checkToken($token);
        return $res;
    }
}
