聊天
===============
学习地址 同步　http://doc2.workerman.net/work-with-other-frameworks.html

学习地址 异步　https://github.com/coolseven/notes/blob/master/thinkphp-queue/README.md

消息队列使用的四种场景介绍 https://www.cnblogs.com/ruiati/p/6649868.html

## 启动

1.根目录/vender/getwaywork,启动　php start.php start,

2.根目录下打开终端，输入php think queue:listen --queue chat

启动这句命令可能会报错，得在输入一条，ln -s /usr/local/php/bin/php /usr/bin

网址　https://blog.csdn.net/weixin_42415136/article/details/80622095

需要配置一个本地的虚拟域名，比如：www.xy.com

## 同时在线

在谷歌浏览器打开，www.xy.com/index/index/index

火狐浏览器打开，www.xy.com/index/index/index2

刷新火狐浏览器，消息马上发送到谷歌浏览器。

## 原理

1.谷歌浏览器 uid = 1　火狐浏览器 uid = 2　作为两个客户端，分别有自己对应的uid,通过getwaywork在分别生成各自的client_id.

2.谷歌浏览器获取两个参数　uid client_id.将谷歌浏览器的uid与自己的client_id对应起来并存入缓存。sdata[$uid] = $client_id.

$redis->get('socket',sdata).

3.火狐浏览器获取两个参数　tid(发个对应客户端的id) uid msg.

4.$aid = $redis->get('socket')[$tid];  $aid为　发给对方的client_id.

5.Gateway::sendToClient($aid,json_encode(array('msg'=>$msg))); 发送过去

## 没有同时在线

在谷歌浏览器打开，www.xy.com/index/index/index

火狐浏览器打开，www.xy.com/index/index/index2

刷新火狐浏览器，消息马上发送到谷歌浏览器。



