<?php

namespace WhetStone\Event;


/**
 * 这里是一个demo
 * 利用event注册功能，我们可以在事件产生时触发用户自定义回调
 * 这里就是一个简单的http router调用回调注册样例
 *
 * Class Hook
 * @package WhetStone\Event
 */
class Hook
{

    public function __construct()
    {

        //on worker start init some event
        //放在这里初始化框架 方便 reload时大部分功能重新加载
        //如果这里报异常，会产生大量日志
        $this->onWorkerStart();

        //Main 是主服务别名，下划线后是事件名，具体事件名可以在protocol下参考event的fire函数
        //公用事件请参考stone\server\event内注释
        $this->onMainServerRequest();

        //拦截掉所有Exception异常
        //到此步骤后都是worker和业务报错
        $this->onException();

    }

    public function onWorkerStart(){

        \WhetStone\Stone\Server\Event::register("worker_start", function ($param) {

            //load all config file on config folder
            \WhetStone\Stone\Config::loadAllConfig();

            //set router config to di
            //框架相关动态创建的数据支撑都扔到DI内管理
            //业务代码禁止注入到DI
            $routerConfig = \WhetStone\Stone\Config::getConfig("router");
            \WhetStone\Stone\Di::set("router", new \WhetStone\Stone\Router\Router($routerConfig));

        });

    }

    public function onMainServerRequest(){

        \WhetStone\Stone\Server\Event::register("Main_request", function ($param) {

            try{
                $context = \WhetStone\Stone\Context::getContext();

                //获取请求信息
                $request = $context->getRequest();
                $method = $request->getMethod();
                $uri = $request->getUri();

                /**
                 * 拿到已经初始化成功的router
                 * 根据router规则找到对应的控制器配置(handle)来自于conf/router.php
                 **/
                $router = \WhetStone\Stone\Di::get("router");

                //查找并执行router.php内的handle
                //返回结果只有两种情况，一种直接返回，一种是异常
                //返回格式由controller决定
                $result = $router->dispatch($context, $method, $uri);

                //获取response对象
                $response = $context->get("response");
                //返回结果
                $response->end($result);
            }catch (\Swoole\ExitException $e){
                //ignore exit exception
            }catch (\Throwable $e){
                $result = array(
                    "code" => $e->getCode(),
                    "msg" => $e->getMessage(),
                    "data" => array(),
                    "backtrace" => $e->getTraceAsString(),
                );
                echo json_encode($result, JSON_PRETTY_PRINT);
            }

        });
    }

    public function onException(){

        \WhetStone\Stone\Server\Event::register("exception", function ($param) {
            if(preg_match("/cli/i", php_sapi_name())){
                $e = $param["exception"];
                var_dump("Exception Was Founded:");
                var_dump($e->getMessage(),$e->getCode());
                var_dump($e->getTraceAsString());
            }

        });
    }
}