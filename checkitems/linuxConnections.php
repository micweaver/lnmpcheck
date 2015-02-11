<?php  
/**
 * @file        linuxConnections.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-4
 * @desc       nginx连接数(并发度)检查,并发度过高说明压力过大
 */


class linuxConnections extends LnmpCheck {

    public $priority = 3;
    public function check(){
        $web_server_port = lnmpConfig::WEB_SERVER_PORT;
        $cmd = "netstat -n | grep ESTABLISHED | awk '{print $4}' | grep :{$web_server_port} | wc -l";
        $arrRes = Utils::get_cmd_res($cmd);
        $concurrency = intval($arrRes[0]);  //并发度，不活跃进的连接也算作了并发度，在繁忙的服务器上，连接一般都是活跃的，所以误差不会很大, 长连接除外。
        if($concurrency*1000 > lnmpConfig::REQUEST_TIME_COST*lnmpConfig::SERVER_THROUGHPUT){
            $msg = "the server connections is too many , connections:  {$concurrency} , time_cost : ".lnmpConfig::REQUEST_TIME_COST."ms, throughput :".lnmpConfig::SERVER_THROUGHPUT."qps";
            Utils::print_error($msg);
        }
        
    }
    
    
    
}