<?php


namespace app\index\job;

use GatewayClient\Gateway;
use think\cache\driver\Redis;
use think\queue\Job;


class Hello {

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){

        $isJobDone = $this->doHelloJob($data);
      if ($isJobDone) {
              //如果任务执行成功， 记得删除任务
            $job->delete();

        }/*else{
           if ($job->attempts() > 3) {
               $job->delete();
           }
        }*/
    }

    /**
      * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
    */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){
            return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function doHelloJob($data) {
        if (Gateway::isUidOnline($data['tid'])){
            Gateway::sendToUid($data['tid'],json_encode(array('type'=> 'text', 'msg' => $data['msg'])) );
            return true;
        }
        return false;
    }
}
