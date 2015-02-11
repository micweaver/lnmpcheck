<?php  
/**
 * @file        linuxCpu.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-23
 * @desc    检查CPU idle以及与 IO有关的数据
 */


class linuxCpu extends LnmpCheck {

    public $priority = 2;
    public $fall = true;
    public function check(){

        $arrRes = Utils::get_cmd_res_split('vmstat 1 2'); //计算最近的2s  IOWAIT也在这里查看
        
        $idle = $arrRes[3][14];
        if($idle < 20) {
            $msg = "idle:{$idle}";
            Utils::print_error($msg);
        }
        $wa = $arrRes[3][15];
        if($wa > 10) {
            $msg = "cpu wa:{$wa}, too many time spent waiting for IO"; // iostat -x %util也会相应的高
            Utils::print_error($msg);
        }
        
     
        $si = $arrRes[3][6];
        $so = $arrRes[3][7];
        
        if($si >= 200 || $so >= 200) {
            $msg = "si: {$si} , so: {$so} , the swap rate is too high, may not have enough memory";
            Utils::print_error($msg);
        }
        
        
    }
     
}