<?php


namespace app\message\controller;


use GatewayClient\Gateway;
use think\cache\driver\Redis;
use think\Controller;
use think\Queue;
use think\Request;

class Index extends Controller
{
    protected $type=['text','img','lang'];
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index (Request $request)
    {
        $uid=$request->post('uid');
        $cid=$request->post('client_id');
        if (empty($uid) || empty($cid)){
            return json(['code'=>202,'msg'=>'error','data'=>'必要数据缺失']) ;
        }
        Gateway::bindUid($cid,$uid);
        $redis=new Redis();
        if ( $redis->set('socket'.$uid,$cid) ){
            return json(['code'=>200,'msg'=>'绑定成功']) ;
        };
    }
    public function chat(Request $request)
    {
        $tid = $request->post('tid');
        $uid = $request->post('uid');
        $data = $request->post('data');
        if (empty($uid) || empty($tid) || empty($data)) {
            return json(['code' => 202, 'msg' => 'error', 'data' => '必要数据缺失']);
        }
        $msg=$this->send($tid,$data);
        return json(['code'=>200,'mgs'=>$msg]);
    }
    public function send($uid,$data,$type=0)
    {
        if (Gateway::isUidOnline($uid)){
            Gateway::sendToUid($uid,json_encode(array('type'=> 'text', 'msg' => $data)) );
            return '发送成功';
        }
        return $this->actionWithHelloJob($uid,$data);
    }
    public function actionWithHelloJob($cid,$data)
    {

        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName = 'app\index\job\Hello';
        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName = "chat";
        // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        //   ( jobData 为对象时，需要在先在此处手动序列化，否则只存储其public属性的键值对)
        $jobData = ['tid' =>$cid, 'msg' =>$data];
        // 4.将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = Queue::push($jobHandlerClassName, $jobData, $jobQueueName);
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if ($isPushed !== false) {
            return '离线发送成功';
        } else {
            return '离线发送失败';
        }
    }

}