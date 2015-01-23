<?php  
/**
 * @file        linuxMemory.php
 * @author       zhonghuali241@163.com
 * @date        2015-1-23
 * @desc
 */

class linuxMemroy extends LnmpCheck {
    
    public $priority = 3;
    //参考 http://www.cnblogs.com/coldplayerest/archive/2010/02/20/1669949.html
    public function check(){
        
        $arrRes = Utils::get_cmd_res_split('free');
       
        $total = $arrRes[1][1];
        $free = $arrRes[2][3];
        
        $use_ratio = round(($total-$free)*100/$total,1); 
       
        if($use_ratio > 80) {
            $used = $total-$free;
            $total=round($total/1024,0);
            $used=round($used/1024,0);
            $free=round($free/1024,0);
            $msg = "total: {$total}M,  used:{$used}M, free:{$free}M, {$use_ratio}% used";
            Utils::print_error($msg);
        }
        
    }
}