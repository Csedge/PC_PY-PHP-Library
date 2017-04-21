<?
/*************MYSQL数据库设置******************/
$mysql_db_serv="127.0.0.1";   //数据库服务器
$mysql_db_usr="root";     //数据库用户名
$mysql_db_pwd="11111111";     //数据库密码
$mysql_db_nam="transport";    //数据库名字
$mysql_charset="utf8";    //数据库编码模式
$mysql_header="tr_";    //数据表前缀
/*************MSSQL数据库设置******************/
$mssql_db_serv="";   //数据库服务器
$mssql_db_usr="";     //数据库用户名
$mssql_db_pwd="";     //数据库密码
$mssql_db_nam="";    //数据库名字
$mssql_charset="";    //数据库编码模式
$mssql_header="";    //数据表前缀
/*************NOSQL数据库设置******************/
$nosql_db_serv="";   //数据库服务器
$nosql_db_usr="";     //数据库用户名
$nosql_db_pwd="";     //数据库密码
$nosql_db_nam="";    //数据库名字
$nosql_charset="";    //数据库编码模式
$nosql_header="";    //数据表前缀
/*************SMTP邮件服务器设置******************/
$smtp_serv="";         //SMTP服务器
$smtp_usr="";          //邮件用户名
$smtp_pwd="";          //邮件密码
$smtp_port="25";         //端口号，默认25
/*************FTP服务器设置******************/
$ftp_serv="";
$ftp_usr="";
$ftp_pwd="";
$ftp_port="";
/*************memcache/memcached服务设置************************/
$memcache_serv='127.0.0.1';
$memcache_port='11211';
$memcached_serv='';
$memcached_port='';
/*****************微信SDK开发相关设置*****************************/
$wechat_appid='';
$wechat_appscert='';
$wechat_base_access_token_proxy="";
$wechat_proxy_key="";
$wechat_redirect="";
$wechat_mch='';
$wechat_notify_url=''; 
$wechat_pay_key='';
$wechat_SSLCERT_PATH='';
$wechat_SSLKEY_PATH='';
/*****************AliPay Aop SDK开发相关设置*****************************/
$alipay_appid='';
$alipay_rsaPrivateKeyFilePath='';
$alipay_PublicKeyPath='';
/*****************AliPay 网页调用开发相关设置*****************************/
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$alipay_config['partner']= '';
//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
$alipay_config['seller_id']	= $alipay_config['partner'];
//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
$alipay_config['private_key']	= '';
//支付宝的公钥，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$alipay_config['alipay_public_key']= '';
// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['notify_url'] = "";
// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['return_url'] = "";
//签名方式
$alipay_config['sign_type']=strtoupper('RSA');
//字符编码格式 目前支持utf-8
$alipay_config['input_charset']= strtolower('utf-8');
//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = getcwd().'\\cacert.pem';
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';
// 支付类型 ，无需修改
$alipay_config['payment_type'] = "1";
// 产品类型，无需修改
$alipay_config['service'] = "alipay.wap.create.direct.pay.by.user";
/*****************BT/P2P相关设置****************/
$site_address="";
$tracker_address="";
$tracker_type="";
/*******************云打印系统相关设置***********************/
/*************调试设置************************/
$deg_sw=0;
$deg_mode=0;
$big_data_mode=0;
$memcached_enadbled=0;
/************************session设置********************/
$lifetime=1800;
/*****************redis数据库设置*****************************/
$redis_server='';
$redis_point='';
/********************ssd缓存设置********************************/
$ssd_path='';
$hdd_path='';
/**************mysql读写分离及多服务器集群********************/
$mysql_write_server[0]['serv']='';//这里是个数组，你要多少台服务器就看着一路加上去
$mysql_write_server[0]['usr']='';
$mysql_write_server[0]['pwd']='';
$mysql_write_server[0]['nam']='';
$mysql_read_server[0]['serv']='';
$mysql_read_server[0]['usr']='';
$mysql_read_server[0]['pwd']='';
$mysql_read_server[0]['nam']='';
/**************短信设置********************/
$sms_usr='';
$sms_pwd='';
?>