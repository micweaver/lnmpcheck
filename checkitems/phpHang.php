<?php  
/**
 * @file        phpHang.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-28
 * @desc    php进程是否hang住的检查
 */

//需要root权限运行
class phpHang extends LnmpCheck {

    public $priority = 4;
    public function check(){
        
        $c_user = Utils::get_current_uname();
        if($c_user != 'root') {
            Utils::print_error("'".__CLASS__."' need root privilege to run check");
            return ;
        }
        $arrRes = Utils::get_cmd_res_split(" ps -efH | grep php-cgi | grep -v grep | awk '{print $2}'");
        unset($arrRes[0]);
        $stack = array();
        foreach ($arrRes as $val) {
            $cmd = "pstack {$val[0]} | head -1 | awk '{print $4}'";
            $hang_fun = Utils::get_cmd_res($cmd);
            $stack[] = $hang_fun[0];
        }

        $cnt_val = array_count_values($stack);
        arsort($cnt_val);
        $sum_process = count($arrRes);

        foreach ($cnt_val as $key => $val) {
            if($key == '__accept_nocancel') continue;
            if($val*2  >= $sum_process) {
                $msg = "php process hang on system function '{$key}' ";
                Utils::print_error($msg);
            }
        }
    }
        
        
        
 }