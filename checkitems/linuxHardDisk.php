<?php  
/**
 * @file        linuxHardDisk.php
 * @author       zhonghuali241@163.com
 * @date        2015-1-23
 * @desc      磁盘空间占用检查
 */


class linuxHardDisk extends LnmpCheck {
    
    public $priority = 3;
    public function check(){
      
        /*----------检查磁盘空间-------------*/
        $cmd = "df -h";
        $arrRes = array();
        exec($cmd,$arrRes);
        unset($arrRes[0]);

        foreach ($arrRes as $val){
            $val = Utils::split_line_space($val);
            if($val[4] == '100%'){
                $msg =  'space:'.$val[0]."\t".$val[4];
                Utils::print_error($msg);
            }
        }
      
        /*----------检查inode使用-------------*/
        
        $cmd = "df -i";
        $arrRes = array();
        exec($cmd,$arrRes);
        unset($arrRes[0]);
        
        foreach ($arrRes as $val){
            $val = Utils::split_line_space($val);
            if($val[4] == '100%'){
                $msg =  'inode:'.$val[0]."\t".$val[4];
                Utils::print_error($msg);
            }
        }
        
        
    }
     
}