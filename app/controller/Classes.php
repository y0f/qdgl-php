<?php
declare (strict_types = 1);

namespace app\controller;

use think\exception\ValidateException;
use think\Request;
use app\model\Classes as ClassesModel;
use app\validate\Classes as ClassesValidate;

class Classes extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = ClassesModel::select();
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
            validate(ClassesValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }

        $classes = new ClassesModel();
        $classes->save($data);

        if(empty($classes->id)){
            return $this->create([],'创建失败~请联系管理员',400);
        } else{
            return $this->create($classes,'创建成功~',200);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = ClassesModel::where('class_name', $id)->find();
        if(empty($data)){
            return $this->create([],'未找到数据~',204);
        }else{
            return $this->create($data,'数据查询成功~',200);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, int $id)
    {
        //检查更新数据
        $data = $request->param();
        unset($data['create_time']);
        unset($data['update_time']);
        unset($data['delete_time']);
        try{
            validate(ClassesValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }

        $classes = ClassesModel::find($id);

        if(!empty($classes) && $classes['id'] === $id){
            $classes->allowField(['class_name'])->save($data);
            return $this->create($classes,'修改信息成功~',200);
        }else{
            return $this->create([],'修改信息失败~',400);
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete(int $id)
    {
        try{
            $data = ClassesModel::find($id)->delete();
            return $this->create($data,'删除成功~',200);
        }catch(\Error $e){
            return $this->create([],'错误或无法删除~',400);
        }
    }

    public function task($id){
        try{
            $data = ClassesModel::find($id)->task()->select();
            return $this->create($data,'数据请求成功',200);
        }catch(\Error $e){
            return $this->create([],'无数据',400);
        }
    }
}
