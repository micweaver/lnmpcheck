<?php  
/**
 * @file        linuxIO.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-2
 * @desc     磁盘IO数据检查
 */


class   linuxIO extends LnmpCheck {

    public $priority = 4;
    public $fall = true;
    public function check(){
        
        $arrRes = Utils::get_cmd_res_split('iostat -d -x 1 2'); //计算最近的1s  IOWAIT也在这里查看
       
        for($i = 6 ;;$i++) {
            $hd = $arrRes[$i];
            if(empty($hd[0])) break;
            if($hd[11] >= 20) {
                $msg = "{$hd[0]} : {$hd[11]}, percentage of CPU time during which I/O requests were issued to the device, the IO is too high";
                Utils::print_error($msg);
            }
        
            $await = $hd[9];
            $svctm = $hd[10];
            
            if($await >  5*$svctm) {
                $msg = "await : {$await}, svctm:{$svctm}, the await time is greater than svctm much more, the IO is too high";
                Utils::print_error($msg);
            }
            
        }
        
    }
     
}