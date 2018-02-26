### 使用手册
南京天心网络科技有限公司出品，博彩类游戏管理网站。


## 环境
PHP 7.1 、Nginx 、 MySQL 5.6 、 Redis

init.sql 为 初始化数据库

# supervisor 配置
[program:queue-default]
process_name=%(program_name)s_%(process_num)02d
command= php /home/wwwroot/carrier.ttc.bet/artisan queue:work --sleep=3 --tries=3  ; 启动命令，可以看出与手动在命令行启动的命令是一样的
autostart=true ; 在 supervisord 启动的时候也自动启动
autorestart=true ; 程序异常退出后自动重启
user=joker  ; 用哪个用户启动
numprocs=4 ;开启几个进程
redirect_stderr=true ; 把 stderr 重定向到 stdout，默认 false
stdout_logfile=/home/wwwroot/carrier.ttc.bet/storage/logs/queue_stdout_2016120508.log  ; 日志路径


# 管理后台   
` 地址  /admin  
` 账号  winwin 
` 密码  123456

# 运营商后台
` 地址 /carrier
` 账号  tianxin
` 密码  123456
