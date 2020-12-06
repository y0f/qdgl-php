<?php
declare (strict_types = 1);

namespace app\controller;

use think\exception\ValidateException;
use think\Request;
use app\model\Record as RecordModel;
use app\validate\Record as RecordValidate;

class Record extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = RecordModel::with(['user','task'])->paginate(config('app.page_size'));
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
            validate(RecordValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }

        $isSign = RecordModel::where('uid',$data['uid'])->where('task_id',$data['task_id'])->find();
        if(empty($isSign)) {
            $record = new RecordModel();
            $record->save($data);

            if (empty($record->id)) {
                return $this->create([], '签到失败~请联系管理员', 400);
            } else {
                return $this->create($record, '签到成功~', 200);
            }
        }else{
            return $this->create([], '您签到过了~', 400);
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
        $data = RecordModel::with('user')->where('task_id',$id)->select();
        if($data->isEmpty()){
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
            validate(RecordValidate::class)->check($data);
        }catch (ValidateException $exception){
            return $this->create([],$exception->getError(),400);
        }
        unset($data['create_time']);
        unset($data['update_time']);
        unset($data['delete_time']);
        $record = RecordModel::find($id);

        if(!empty($record)){
            $record->allowField(['class_name'])->save($data);
            return $this->create($record,'修改信息成功~',200);
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
            $data = RecordModel::find($id)->delete();
            return $this->create($data,'删除成功~',200);
        }catch(\Error $e){
            return $this->create([],'错误或无法删除~',400);
        }
    }
}
