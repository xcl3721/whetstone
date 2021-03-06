<?php

namespace WhetStone\Controller\Test;

class Test extends \WhetStone\Stone\Controller
{

    public function index($context, $request, $response)
    {
        return $this->response("success", 0);
    }

    public function info($context, $request, $response)
    {
        $param = $request->getGet();

        $redis = \WhetStone\Stone\Driver\Redis\Redis::Factory("test_redis_shard");

        mt_srand(time());
        $redis->set("a" . mt_rand(1, 1000000), "aa");
        /*$ret = $redis->get("a");
        if($ret != "aa"){
            echo 12;
        }*/
        return $this->response("nothing to do", -123, $param);
    }

    public function status($context, $request, $response)
    {
        $redis      = \WhetStone\Stone\Driver\Redis\Redis::Factory("test_redis_shard");
        $connection = $redis->getConnectionStatus();
        return $this->response("OK", 0, $connection);
    }
}