<?php


namespace app\controller;


use think\Response;

/**
 * Class Base
 * @package app\controller
 */
abstract class Base
{
    /**
     * @param $data
     * @param string $msg
     * @param int $code
     * @param string $type
     * @return Response
     */
    protected function create($data, string $msg = '', int $code = 200, string $type = 'json'):Response{
        $result = [
            //状态码
            'code'  =>  $code,
            //消息
            'msg'   =>  $msg,
            //数据
            'data'  =>  $data
        ];

        return Response::create($result, $type);
    }


    /**
     * @param $name
     * @param $arguments
     * @return Response
     */
    public function __call($name, $arguments){
        return $this->create([],'资源不存在',404);
    }
}