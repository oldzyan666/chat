<?php


namespace app\index\controller;


use think\Cache;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function index2()
    {
        return $this->fetch();
    }
    public function test()
    {
        $cache= new Cache();
        $data=$cache->hSet('name','12345',1);
      /*  $redis=new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $data=$redis->hSet('hash', 'key1', 'val11');*/
        /*$data = 1!==1;
        $temp[]=$data;*/
        var_dump( $data );
       /* $dir=('static/');
        if (@$handle= opendir($dir)){
            //var_dump($handle);die;
            while (($file= readdir($handle))!==false){
                var_dump($file);die;
                $data=file_get_contents($dir.$file);
                var_dump($data);die;
            }
        }*/
       /* $log = 'curr.log';
        $log1 = 'curr1.log' ;
        touch($log);
        chmod($log,0777);
        touch($log1);*/
    }
}