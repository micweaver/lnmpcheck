# lnmpcheck
lnmp环境检测工具，检测部署linux,nginx,php,mysql服务机器的问题。通过运行lnmpcheck脚本，就可以将lnmp环境中的各种问题报告出来，如磁盘满了、cpu负载过高、磁盘IO过高、网络出现了问题、遭遇了synflood攻击、php进程hang在了某个地方等等,甚至还会检查nginx文件日志是否异常。与监控工具不同的是，lnmpcheck能够直接指明问题所在，主要用于对一台已出现服务异常的机器进行具体问题的排查。

lnmpcheck很容易进行扩展添加新的检查项，另外也可以对已有的检测项添加自己的特殊的版本而不影响原来的版本。


###**检测的问题项**
- 磁盘占用  
- inode使用  
- CPU  
  包括CPU idle,CPU负载，CPU在I/O时花费的时间比
- 内存  
  包括内存占用比，swap交换数
- I/O  
  I/O所消耗的CPU时间比
  I/O总共花费时间与实际I/O操作所花费时间比(await/svctm)
- 网络流量  
  检查目前流量占网卡带宽的比
- 网络错误  
  包括网络传输丢包及网络传输缓存区空间不足的错误
- 文件句柄数  
  目前已使用的文件句柄数与系统限制的比
- core文件  
  检查是否由于进程运行异常而产生了core文件
- synflood攻击  
  检测系统是否遭到了synflood攻击
- nginx压力  
  通过网络连接数来估值nginx并发度
  并发度= 请求处理时间 * QPS
  进而评估nginx是否压力过大
- php压力  
  评估方式类似nginx压力
- nginx存活  
  检查nginx进程是否正常运行
- php存活  
  检查php进程是否正常运行
- nginx日志检查  
  检查nginx日志文件中状态码非200的个数，从而发现请求返回不正常的情况
- php进程hang住检查  
  检查php进程是否都hang在了某个操作上，这往往由于某个外部请求超时导致
- mysql压力   
  通过show processlist命令检查mysql正在进行操作处理的线程数

lnmpcheck的检查是多维度的，例如为了检查I/O是否达到了瓶颈，不但检查I/O使用率数值，也会关心 I/O总共花费时间与实际I/O操作所花费时间比(await/svctm),如果这个比值过大，说明I/O请求在队列中等待的时间过长，达到了I/O处理的瓶颈。
  
  
  
###**使用方法**

lnmpcheck用php开发，下载所有php文件，命令行下执行start.php脚本文件即可:
>[root@xsl1x-nova ~/lnmpcheck]# php start.php  
>checking......  
>  
>sda1 : 20.00, percentage of CPU time during which I/O requests were issued to the device, the IO is too high  
>\------------------------------  
>  
>\*******************  
>check completed  
>\*******************  

执行完毕会将检查到的问题一项项打印出来。最好以root用户执行，有些检测项需要root权限，用其它帐号会导致这些检测项无效。

  
 
###**添加新的检测项**

目前已有的检测项只是最常需要检测的问题，还有许多其它问题需要检测，这只需要添加一个php文件放到checkitems目录下即可，以检查cpu负载的代码为例，在checkitems/linuxCpuLoad.php 文件中实现，基本代码结构如下:

    class linuxCpuLoad extends LnmpCheck {
    
        public function check(){
        
            $arrRes = Utils::get_cmd_res(' mpstat -P ALL | wc -l');
            $cpu_num = $arrRes[0] - 4;

            $arrRes = Utils::get_cmd_res_split('uptime');
        
            $load_one_minute = trim($arrRes[0][7],' ,');
            if($load_one_minute > 2*$cpu_num) {
                $msg =  "cpu number: {$cpu_num}, load in 1 minute: {$load_one_minute}";
                Utils::print_error($msg);
            }
        
        }
    
    }
    
新的检测项需要继承LnmpCheck类，并在check函数中实现自己的检查逻辑即可。另外可以定义问题检测的优先级,代表了检测的顺序，在类中声明$priority变量即可，值越小，优先级越高，默认是最低检测优先级。
