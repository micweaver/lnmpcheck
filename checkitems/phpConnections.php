<?php  
/**
 * @file        phpConnections.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-5
 * @desc      php连接数(并发度)检查
 */


class phpConnections extends LnmpCheck {

    public $priority = 3;
    public function check(){
        $php_server_port = lnmpConfig::PHP_SERVER_PORT;
        $cmd = "netstat -n | grep ESTABLISHED | awk '{print $4}' | grep :{$php_server_port} | wc -l";
        $arrRes = Utils::get_cmd_res($cmd);
        $concurrency = intval($arrRes[0]);  //并发度
        if($concurrency*1000 > lnmpConfig::REQUEST_TIME_COST*lnmpConfig::SERVER_THROUGHPUT){
            $msg = "the php connections is too many , connections:  {$concurrency} , time_cost : ".lnmpConfig::REQUEST_TIME_COST."ms, throughput :".lnmpConfig::SERVER_THROUGHPUT."qps";
            Utils::print_error($msg);
        }

    }



}