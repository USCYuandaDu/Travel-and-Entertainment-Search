# Weekly Report
## 

# 1.完成spark在kubernete上的部署（单机MAC）

* 在mac上安装virtualbox， minikube， kubectl。
* 新建namespace: spark-cluster
* 使用yaml文件生成spark-master， spark-worker（replica：2），spark-web-ui, zeppelin的Replicationcontroller和service
* 通过zeppelin和spark-master进行交互作业（截图在附件里）
* 部署service的时候使用nodePort，可以通过节点直接访问service
* 出现的问题：访问zeppelin的时间过长，并且节点不稳定，会出现如下图所示的错误，上网查好像是因为机器内存不够。待进一步检查!!（截图在附件里）

# 2.完成作业（4天）

* 基于google place api， nodejs， express，html5的搜索网站，可以搜索附近的饭店，学校等，还可以显示地图位置，以及对应路线，大量使用api，所以比较耗时，html大约写了600行（3天）
* 实现机器学习模型（隐马尔科夫中的求最优路径，viterbi算法）（1天）

# 3.做关于机器学习ppt

* 准备下周做关于soft-margin svm的演讲

# 4.运动，羽毛球（2\*2hours）

# 5.上课（1天）

# 6.下周计划

* 了解zeppelin 并且和您讨论一下实习方向
