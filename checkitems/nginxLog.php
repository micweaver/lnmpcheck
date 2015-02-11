<?php  
/**
 * @file        nginxLog.php
 * @author     zhonghuali241@163.com
 * @date        2015-2-6
 * @desc       nginx日志检查
 */

//需要root权限运行
class nginxLog extends LnmpCheck {

    public $priority = 4;
    public function check(){
        
        $c_user = Utils::get_current_uname();
        if($c_user != 'root') {
            Utils::print_error("'".__CLASS__."' need root privilege to run check");
            return ;
        }
        
        $cmd = "ps -ef | grep 'nginx: master process' | grep -v grep";
        $arrRes = Utils::get_cmd_res_split($cmd);

        $pid = $arrRes[0][1]; 
        
        $cwd = "/proc/{$pid}/cwd/";
        $conf = lnmpConfig::NGINX_CONF_PATH;
        if(!empty($conf)) {
           list($log_format,$access_log,$error_log) = $this->getItem($conf);
        } else {
            $conf_path = $cwd.'conf/';
            if ($handle = @opendir($conf_path)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry == '.' || $entry == '..')
                        continue;
                       if(substr($entry, -5) == '.conf'){
                            list($log_format,$access_log,$error_log) = $this->getItem($conf_path.$entry);
                           if(!empty($log_format)) break;
                       }
                }
                closedir($handle);
            }
        
        }
        
        //echo "log_format:".$log_format."\n";
         
        $f_items = array();
        preg_match_all('/([^ \'\"\\[\]]+)/', $log_format,$f_items);

        $n_items = array();
        foreach ($f_items[1] as $key => $val) {
            $n_items[$val] = $key;
        }
       
        $access_log_file = $cwd.$access_log;
        
        $log_line = Utils::get_cmd_res('tail -5 '.$access_log_file);
      
        $time_index = $n_items['$time_local'];
        
        $n_log_line = array();
        foreach ($log_line as $key => $val) {
             $s_res  = $this->splitLine($val);
            $time_str = $s_res[$time_index];
            $time_int = strtotime($time_str);
            if(time()-$time_int > 60) continue;
            $n_log_line[] = $s_res;
        }
       //print_r($n_log_line);
        if(empty($n_log_line)) return ;
               
        $this->checkStatus($n_log_line, $n_items);
    }
    
    
    public function checkStatus($log_line,$log_format){
        
        $index = $log_format['$status'];
        $status = array();
        foreach($log_line as $val) {
            $status[$val[$index]]++; 
        }
        if($status[200] != count($log_line)) {
            $msg = "http status num :\n".var_export($status,true);
            Utils::print_error($msg);
        }
        
    }
    
    
    public function getItem($conf) {
        
        $file_str = file_get_contents($conf);
        $file_str = str_replace("\n", " ", $file_str);
        $pattern = '/log_format(\s+)(\w+)(\s+)([^;]+);/';
        $matches = array();
        
        $res = preg_match($pattern, $file_str,$matches);
        if(empty($res)) {
            return false;
        }
        
        $log_format = $matches[4];
         
        $pattern = '/access_log(\s+)(\S+)(\s+)([^;]+);/';
        preg_match($pattern, $file_str,$matches);
        $access_log = $matches[2];
         
        $pattern = '/error_log(\s+)(\S+)(\s+)([^;]+);/';
        preg_match($pattern, $file_str,$matches);
        $error_log = $matches[2];
        
        $log_format = str_replace('\'', '', $log_format);
        $log_format = preg_replace('/\s+/', ' ', $log_format);
        $res =  array(
                $log_format,
                $access_log,
                $error_log,
        );
        
        return $res;
        
    }
    
    
    
    public function splitLine($line){
        $res = array();
        $len = strlen($line);
        $status = 0;
        $item = '';
        for($i=0;$i< $len; $i++){
            // echo  $line[$i].PHP_EOL;
            if(($line[$i] == '\'' || $line[$i] == '"' || $line[$i] == '[' || $line[$i] == ']') &&($i ==0 || $line[$i-1] !='\\')){
                if($status == 3){
                    $res[] = $item;
                    $item = '';
                    $status = 0;
                } else {
                    if($status == 1){
                        $res[] = $item;
                        $item = '';
                    }
                    $status = 3;
                }
            } elseif($this->isSpace($line[$i])) {
                if($status == 3) {
                    $item.=$line[$i];
                } elseif($status == 2 || $status==0) {
                    $status = 2;
                } else {
                    $res[] = $item;
                    $item = '';
                    $status =2;
                }
            } else {
                if($status == 3){
                    $item.=$line[$i];
                } else {
                    $item.=$line[$i];
                    $status = 1;
                }
            }
        }
    
        if(!empty($item)) {
            $res[] = $item;
        }
        return $res;
    }
    
    
    public function isSpace($char){
        //HT (9), LF (10), VT (11), FF (12), CR (13), and space (32).
        // if(in_array($char, array('\n','\r','\t','\v','\f',' '))){
        //   return true;
            //  }
            if($char == '\n' || $char =='\r' || $char == '\t' || $char =='\v' || $char == '\f' || $char == ' '){
                return true;
            }
            return false;
        }
    
    
}