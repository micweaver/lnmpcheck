<?php  
/**
 * @file        config.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-3
 * @desc
 */


class lnmpConfig {
    
    const  NET_CARD_SPEED = 1000;  //1000M/s,在获取不到网卡带宽时取这个值
    
    
    const  WEB_SERVER_PORT = 80;  //web服务器监听端口
    const  PHP_SERVER_PORT = 9000; //php-cgi进程监听端口
    
    const REQUEST_TIME_COST = 100; //100ms, 服务器所有请求所花费平均时间
    const SERVER_THROUGHPUT = 300; //300QPS,服务器所能承受的合理吞吐量
    
    
    const  NGINX_CONF_PATH = '/usr/local/nginx/conf/nginx.conf'; //默认nginx配置文件路径，该值为空则尝试自动获取配置文件
    
    
    public static  $mysql_info =  array(  //mysql服务器配置
                                                  'host' => '127.0.0.1', 
                                                  'user' => 'root', 
                                                  'pass' => '', 
                                                  'port' => '3306'
                                              );
    
}


