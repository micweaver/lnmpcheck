<?php  
/**
 * @file        mysqlThreadStatus.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-10
 * @desc          mysql 线程状态检查
 */



class mysqlThreadStatus extends LnmpCheck {

    public $priority = 4;
    public $fall = true;
    public function check(){
        
        $mysql_info = lnmpConfig::$mysql_info;
        
        $link = mysql_connect($mysql_info['host'].':'.$mysql_info['port'], $mysql_info['user'], $mysql_info['pass']);
        
        if ($link === false ) {
            $msg =  "Failed to connect to MySQL: " . mysql_error();
            Utils::print_error($msg);
            exit;
        }
        
        $result = mysql_query("show processlist;");
      
        $sum_cnt = 0;
        $no_sleep_cnt = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            if($line['Command'] != 'Sleep') $no_sleep_cnt++;
            $sum_cnt++;
        }

        if($sum_cnt > 3 && $no_sleep_cnt * 5 > $sum_cnt) {
            $msg = "Too many mysql threads is running";
            Utils::print_error($msg);
        }
        
    }
    
    
    
}