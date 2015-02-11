<?php  
/**
 * @file        linuxCoreDump.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-26
 * @desc        检查系统是否生成了core文件
 */


class linuxCoreDump extends LnmpCheck {

    public $priority = 4;
    public $fall = true;
    public function check(){
        $arrRes = Utils::get_cmd_res('ulimit -c');
        if($arrRes[0] === '0') {
            Utils::print_error("没有打开coredump设置");
            return ;
        }
        $arrRes = Utils::get_cmd_res('cat /proc/sys/kernel/core_pattern');
        
        $pattern = $arrRes[0];
        if (empty($pattern))
            return;
      
        $dir_name = dirname($pattern);
        
        if ($dir_name === '.') {
            return;
        }
        
        if ($handle = @opendir($dir_name)) {
            $msg = '';
            $delimiter = '';
            while (false !== ($entry = readdir($handle))) {
                if ($entry == '.' || $entry == '..')
                    continue;
                $msg.="{$delimiter}corefile: $entry ";
                $delimiter = "\n";
                
            }
            Utils::print_error($msg);
            closedir($handle);
        }
        
        
    }
}