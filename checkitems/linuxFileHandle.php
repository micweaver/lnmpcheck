<?php  
/**
 * @file        linuxFileHandle.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-4
 * @desc       检查文件名柄数是否超过系统限制,可检测出句柄泄露的问题
 */


class linuxFileHandle extends LnmpCheck {

    public $priority = 3;
    public function check(){
        $arrRes = Utils::get_cmd_res('ulimit -n');
        $max_open_file = intval($arrRes[0]);
           
        $arrRes = Utils::get_cmd_res_split('sar -v 1');
        $open_file = $arrRes[3][2];
        if($open_file * 100 / $max_open_file > 30) {
            $msg = "open file handle num : {$open_file}, max open handle num : {$max_open_file}";
            Utils::print_error($msg); 
        }
    }        
        
}