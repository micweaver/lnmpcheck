<?php  
/**
 * @file        utils.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-23
 * @desc
 */


class Utils {
    
    public static function get_cmd_res($cmd){
        $arrRes = array();
        exec($cmd,$arrRes);
        return $arrRes;
    }
    
    public static function get_cmd_res_split($cmd){
        
        $arrRes = array();
        exec($cmd,$arrRes);
        foreach ($arrRes as &$val) {
            $val = trim($val);
            $val = self::split_line_space($val);
        }
        return $arrRes;
    }
    
    public static function print_error($msg){
        echo $msg."\n";
        echo "------------------------------\n";
    }
    public static function split_line_space($line){
        return  preg_split('/\s+/', $line);
    }
    
    
    public static function get_current_uname(){
        
        $c_uid = posix_getuid();
        $u_uinfo = posix_getpwuid($c_uid);
        return $u_uinfo['name'];
    }
    
    
    
}