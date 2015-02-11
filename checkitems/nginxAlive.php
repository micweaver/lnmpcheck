<?php  
/**
 * @file        nginxAlive.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-5
 * @desc        nginx进程存活检查
 */


class nginxAlive extends LnmpCheck {

    public $priority = 3;
    public function check(){

        $cmd = "ps -ef | grep 'nginx: worker process' | grep -v grep  | wc -l";
        $arrRes = Utils::get_cmd_res($cmd);
        $nginx_num  = intval($arrRes[0]);
        if($nginx_num == 0){
            $msg = "there are no nginx process";
            Utils::print_error($msg);
        }
        
    }
    
    
}