<?php  
/**
 * @file        engine.php
 * @author       zhonghuali241@163.com
 * @date        2015-1-21
 * @desc
 */

require('lnmpcheck.php');
require('lib/utils.php');


class Engine {
    
    private $allclass = array();
    private $allparentclass = array();
    
    private $objset =array();
    
    public function runCheck($print_reason = false,$print_data = false){
        
        if ($handle = opendir(dirname(__FILE_).'/checkitems')) {
        
            while (false !== ($entry = readdir($handle))) {
                if($entry == '.' || $entry == '..') continue;
                if(substr($entry, -4) == '.php'){
                    include './checkitems/'.$entry;
                }
            }
            $define_class = get_declared_classes();
            foreach ($define_class as $val){
                if(is_subclass_of($val, 'LnmpCheck')){
                    $this->allclass[$val] = $val;
                    $parent_class = get_parent_class($val);
                     if($parent_class != 'LnmpCheck') {
                        $this->allparentclass[$parent_class] = $parent_class;
                     }    

                }
            }
           
           foreach ($this->allparentclass as $val){
                unset($this->allclass[$val]);
           }
           foreach ($this->allclass as $val){
               $this->objset[$val] = new $val;
           }
         
           usort($this->objset, array($this,'cmp'));
           foreach ($this->objset as $obj){
               if(method_exists($obj, 'check')) {
                   $res = $obj->check();
                   if($res === false) {
                       if($print_reason && method_exists($obj, 'printReason')){
                           $obj->printReason();
                       }
                       if($print_data && method_exists($obj, 'printData')){
                           $obj->printData();
                       }
                   }
                   if($res === false && !$obj->fall) break;
               }
               
           }
            closedir($handle);
        }
        
    }

    
    public function cmp($a, $b) {
        if ($a->priority == $b->priority) {
            return 0;
        }
        return ($a->priority < $b->priority) ? -1 : 1;
    }

}