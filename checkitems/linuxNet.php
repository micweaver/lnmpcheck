<?php  
/**
 * @file        linuxNet.php
 * @author     zhonghuali241@163.com
 * @date        2015-1-23
 * @desc        网络流量及网络错误检查
 */

class linuxNet extends LnmpCheck {

    public $priority = 3;
    public function check(){
     
     /********网络流量检测*******/
        
     $arrRes = Utils::get_cmd_res_split('sar -n DEV 1');
   
     for($i = 3;;$i++) {
         $IFACE = $arrRes[$i][1];
         if(empty($IFACE)) break;
         
         $rxbyt   = $arrRes[$i][4];
         $txbyt    = $arrRes[$i][5];
         
         $speed =   $this->getNetCardSpeed('IFACE');
         if($rxbyt*100/$speed > 80) {
             $msg = "{$IFACE}   received speed : {$rxbyt} byte/s, net card speed: {$speed} byte/s";
             Utils::print_error($msg);
         }
         
         if($txbyt*100/$speed > 80) {
             $msg = "{$IFACE}   send speed : {$rxbyt} byte/s, net card speed: {$speed} byte/s";
             Utils::print_error($msg);
         }
         
     }
     
     
     /********网络错误检测*******/
     $arrRes = Utils::get_cmd_res_split('sar -n EDEV 1');
    
     for($i = 3;;$i++) {
         $IFACE = $arrRes[$i][1];
         if(empty($IFACE)) break;
          
         $rxerr   = $arrRes[$i][2];
         $txerr    = $arrRes[$i][3];
         $coll      = $arrRes[$i][4];
         $rxdrop    = $arrRes[$i][5];
         $txdrop   = $arrRes[$i][6];
         $txcarr    = $arrRes[$i][7];
         $rxfram   = $arrRes[$i][8];
         $rxfifo    = $arrRes[$i][9];
         $txfifo    = $arrRes[$i][10];
          
       
         if($rxerr > 10) {
             $msg = "{$IFACE}   Total number of bad packets received per second: {$rxerr}";
             Utils::print_error($msg);
         }
          
         if($txerr > 10) {
             $msg = "{$IFACE}   Total number of errors that happened per second while transmitting packets: {$txerr} ,  there are some wrong with the net ";
             Utils::print_error($msg);
         }
         
         if($coll > 10) {
             $msg = "{$IFACE}   Number of collisions that happened per second while transmitting packets: {$coll}";
             Utils::print_error($msg);
         }
         
         if($rxdrop > 10) {
             $msg = "{$IFACE}   Number of received packets dropped per second because of a lack of space in linux buffers: {$rxdrop} ";
             Utils::print_error($msg);
         }
         
         if($txdrop > 10) {
             $msg = "{$IFACE}  Number of transmitted packets dropped per second because of a lack of space in linux buffers: {$txdrop} ";
             Utils::print_error($msg);
         }
       
         if($txcarr > 10) {
             $msg = "{$IFACE} Number of carrier-errors that happened per second while transmitting packets: {$txcarr} ";
             Utils::print_error($msg);
         } 
         
         if($rxfram > 10) {
             $msg = "{$IFACE} Number of frame alignment errors that happened per second on received packets: {$rxfram} ";
             Utils::print_error($msg);
         }
         
         if($rxfifo > 10) {
             $msg = "{$IFACE} Number of FIFO overrun errors that happened per second on received packets: {$rxfifo} ";
             Utils::print_error($msg);
         }
         
         if($txfifo > 10) {
             $msg = "{$IFACE} Number of FIFO overrun errors that happened per second on transmitted packets: {$txfifo} ";
             Utils::print_error($msg);
         }
         
     }
     
    }
    
    
    
    public function getNetCardSpeed($interface) {
        
        $cmd = "ethtool {$interface} 2> /dev/null | grep -i Speed ";
        $arrRes = Utils::get_cmd_res($cmd);

        if(empty($arrRes)) { //虚拟机环境下无法获取网卡信息
            return lnmpConfig::NET_CARD_SPEED*1024*1024;
        }
        $arrRes ='Speed: 1000Mb/s';
        $speed = trim($arrRes);
        $pattern = '/Speed:\s*(\d+)Mb\/s/i';
        $matches  = array();
        preg_match($pattern, $speed,$matches);
        if(empty($matches[1])) {
            return lnmpConfig::NET_CARD_SPEED*1024*1024;
        }
        return $matches[1]*1024*1024;
    }
}