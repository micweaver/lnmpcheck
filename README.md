# lnmpcheck
lnmp环境检测工具，检测linux,nginx,php,mysql的各种问题.通过运行lnmpcheck脚本，就可以将lnmp环境中的各种问题报告出来，如磁盘满了、cpu负载过高、磁盘IO过高、网络出现了问题、是否遭遇synflood攻击、php进程hang在了某个地方等等,甚至还会检查nginx文件日志是否常。监控工具往往只报告具体的数据，lnmpcheck能够直接指明系统存在的问题。

lnmpcheck很容易进行扩展添加新的检查项，另外也可以对已有的检测项添加自己的特殊的版本而不影响原来的版本。


###**检测的问题项**
- 磁盘占用
- inode使用
- CPU
  包括CPU idle,CPU负载，CPU在IO时花费的时间比
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
  目前已使用的文件句柄数
- core文件
  检查进程是否产生了core dump文件
- synflood攻击
  检测系统是否遭受了synflood攻击
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
  通过show processlist命令检查mysql正在运行的线程数

lnmpcheck的检查是多维度的，例如为了检查I/O是否达到了瓶颈，不但检查I/O使用率数值，也会关心 I/O总共花费时间与实际I/O操作所花费时间比(await/svctm),如果这个比值过大，说明I/O在队列中
等待的时间过长，达到了I/O处理的瓶颈。
