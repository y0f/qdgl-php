<?php
declare (strict_types = 1);

namespace app\controller;

use think\exception\ValidateException;
use think\Request;
use app\model\Task as TaskModel;
use app\validate\Task as TaskValidate;

class Task extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = TaskModel::with(['user','course','term','classes'])->paginate(config('app.page_size'));
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
            validate(TaskValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }

        $task = new TaskModel();
        $task->save($data);

        if(empty($task->id)){
            return $this->create([],'创建失败~请联系管理员',400);
        } else{
            return $this->create($task,'创建成功~',200);
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
        $data = TaskModel::find($id);
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
        try{
            validate(TaskValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }
        unset($data['create_time']);
        unset($data['update_time']);
        unset($data['delete_time']);
        $task = TaskModel::find($id);

        if(!empty($task)){
            $task->allowField(['course_id','term_id','class_id','uid'])->save($data);
            return $this->create($task,'修改信息成功~',200);
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
            $data = TaskModel::find($id)->delete();
            return $this->create($data,'删除成功~',200);
        }catch(\Error $e){
            return $this->create([],'错误或无法删除~',400);
        }
    }
}
