<?php
// 应用公共文件
use \Firebase\JWT\JWT;



//生成验签
function signToken($uid){
    $key='!@#$%*&'; //jwt的签发密钥，验证token的时候需要用到
    $time = time(); //签发时间
    $expire = $time + 14400; //过期时间
    $token = array(
        'user_id' => $uid,
        'iss' => config('iss'),
        'aud' => config('aud'),
        'iat' => $time,
        'nbf' => $time,
        'exp' => $expire
    );
    //  print_r($token);
    $jwt = JWT::encode($token, $key, 'HS256');  //根据参数生成了 token
    return $jwt;
}


//验证token
function checkToken($token){
    $key='!@#$%*&';
    $status=array('code'=>400);
    try {
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        $decoded = JWT::decode($token, $key, array('HS256')); //HS256方式，这里要和签发的时候对应
        $arr['data'] = (array)$decoded;
        $res['code']=200;
        $res['data']=$arr['data'];
        return $res;

    } catch(\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
        $status['msg']='签名不正确';
        return $status;
    }catch(\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
        $status['msg']='token失效';
        return $status;
    }catch(\Firebase\JWT\ExpiredException $e) { // token过期
        $status['msg']='token失效';
        return $status;
    }catch(Exception $e) { //其他错误
        $status['msg']='未知错误';
        return $status;
    }
}