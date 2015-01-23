<?php  
/**
 * @file        lnmpcheck.php
 * @author       zhonghuali241@163.com
 * @date        2015-1-21
 * @desc
 */

interface LnmpCheckBase {

    public function check();//实际检查
    public function printReason();//打印错误原因
    public function printData();//打印相关检测相关数据

}


interface LnmpInfoBase {

    public function printInfo();//打印相关信息
}



interface LnmpBottleneckBase {

    public function find();//找出系统瓶颈

}



abstract class  LnmpCheck implements LnmpCheckBase{
    
    public $priority = 2048;  //检测优先级,数字越小，优先级越大
    public $fall = true; //检测到某一项出错后是否继续往下执行
    
    public function printReason(){
        
    }
    public function printData(){
        
    }
    
    public function setPriority($priority){
        $this->priority = $priority;
    }
    
    public function setfall($fall){
        $this->fall = $fall;
    }

}


abstract class  LnmpInfo implements  LnmpInfoBase{
    
}



abstract class  LnmpBottleneck implements  LnmpBottleneckBase {
    
}
