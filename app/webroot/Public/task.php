<?php
/*TASK************************/
# index/index    短信任务
#
#1.单是&, 后台运行，你关掉终端会停止运行
#2.nohup command &    后台运行，你关掉终端也会继续运行
#3.jobs -l
# $
# nohup /home/programs/php-5.6.29/bin/php task.php &
#
/*EndTASK************************/
require '../Data/common.php';
define('BIND_MODULE', 'Task');
require '../ThinkPHP/ThinkPHP.php';
