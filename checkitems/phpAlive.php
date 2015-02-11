<?php  
/**
 * @file        phpAlive.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-5
 * @desc     php进程存活检查
 */


class phpAlive extends LnmpCheck {

    public $priority = 3;
    public function check(){

        $cmd = "ps -ef | grep 'php-cgi' | grep -v grep  | wc -l";
        $arrRes = Utils::get_cmd_res($cmd);
        $php_num  = intval($arrRes[0]);
         
        if($php_num == 0){
            $msg = "there are no php process";
            Utils::print_error($msg);
        }

    }


}