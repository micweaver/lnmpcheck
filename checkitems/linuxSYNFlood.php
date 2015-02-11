<?php  
/**
 * @file        linuxSYNFlood.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-5
 * @desc       synflood网络攻击检查
 */



class linuxSYNFlood extends LnmpCheck {

    public $priority = 3;
    public function check(){
     
        $cmd = "netstat -n  | grep SYN_RECV | wc -l";
        $arrRes = Utils::get_cmd_res($cmd);
        $syc_recv_num  = intval($arrRes[0]);  
       
        if($syc_recv_num > 10){
            $msg = "SYN_RECV  status num : {$syc_recv_num}, there may be a syn flood attack";
            Utils::print_error($msg);
        }
        
    }
    
    
    
}