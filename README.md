##30到家，快递到社区的最后100米


##版本说明
-	增加Dashboard
-	增加短信Redis队列缓存
-	增加微信通知管理
-	增加短信通知
-	修复短信模版Bug


##Copyright@   Godtoy


##Version 0.2 beta

##Version 0.3 beta
-   Docker部署编排
-   Docker自动化部署


##Usage
1.git clone .

2.docker-compose up --build

##第一次需要手动导入测试数据
1.查看docker-compose 网络
```
$docker network ls
NETWORK ID          NAME                  DRIVER              SCOPE
eb94c90c4aae        30goinghome_default   bridge              local
70e90a580013        bridge                bridge              local
92abe5bb5b5e        host                  host                local
4e85e7e6b5f3        none                  null                local

```

2.查看backup真实路径
```
$ pwd
/c/Users/zhaojunlike/Documents/WorkSpace/PHP/30-going-home
```

3.导入默认得数据
//启动一个迁移数据容器
```shell
$ docker run -it --link mysql-db:mysql --network 30goinghome_default -v /c/Users/zhaojunlike/Documents/WorkSpace/PHP/30-going-home/data/backup:/data/backup:ro --rm mysql:5.7 sh -c 'exec mysql -h"mysql" -P"3306" -uroot -p"zhaojun" packag
e_v1</data/backup/data-default.sql'
```
