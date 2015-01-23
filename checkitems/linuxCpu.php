<?php  
/**
 * @file        linuxCpu.php
 * @author       zhonghuali241@163.com
 * @date        2015-1-23
 * @desc
 */


class linuxCpu extends LnmpCheck {

    public $priority = 2;
    public $fall = true;
    public function check(){
        
        $arrRes = Utils::get_cmd_res_split('vmstat 1 2'); //计算最近的2s
        $idle = $arrRes[3][15];
        if($idle < 20) {
            $msg = "idle:{$idle}";
            Utils::print_error($msg);
        }
    }
     
}