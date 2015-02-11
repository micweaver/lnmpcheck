<?php  
/**
 * @file        linuxCpuLoad.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-28
 * @desc        检查CPU负载
 */


class linuxCpuLoad extends LnmpCheck {
    
    public function check(){
        
        $arrRes = Utils::get_cmd_res(' mpstat -P ALL | wc -l');
        $cpu_num = $arrRes[0] - 4;

        $arrRes = Utils::get_cmd_res_split('uptime');
        
        $load_one_minute = trim($arrRes[0][7],' ,');
        if($load_one_minute > 2*$cpu_num) {
            $msg =  "cpu number: {$cpu_num}, load in 1 minute: {$load_one_minute}";
            Utils::print_error($msg);
        }
        
    }
    
}