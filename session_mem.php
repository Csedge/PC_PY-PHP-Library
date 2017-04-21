<?
include (dirname(__FILE__) . "/config_global.php");//使用此类请确保服务器支持memcache缓存加速类
		function session_open($save_path, $session_name) 
        {
             return(true);
        }
		function session_read($session_id)
		{
			global $memcache_serv;
			global $memcache_port;
			$port=$memcache_port;
			$host=$memcache_serv;
           $mem=new Memcache();
		   $mem->connect($host,$port);
		   $session_data=$mem->get($session_id);
		   if($session_data!='')
		   {
		   	global $lifetime;
			$_l=$lifetime;
		   	$mem->set($session_id,$session_data,0,$_l);
			$mem->close();
		   	return $session_data;
		   }
		   else
		   {
		   	$mem->close();
		   	return 0;
		   }
		}
		function session_write($session_id,$session_data){
			global $memcache_serv;
			global $memcache_port;
			$port=$memcache_port;
			$host=$memcache_serv;
                $mem=new Memcache();
		        $mem->connect($host,$port);
		    	global $lifetime;
			    $_l=$lifetime;
                $mem->set($session_id,$session_data,0,$_l);		
				$mem->close();
			    return(true);
		}
		function session_gc($lifetime)
		{
			return(true);
		}
		function session_destro($session_id)
		{
 			global $memcache_serv;
			global $memcache_port;
			$port=$memcache_port;
			$host=$memcache_serv;
                $mem=new Memcache();
		        $mem->connect($host,$port);
				$mem->delete($session_id,0);
				return(true);
		}
		function session_close() {
           return (true);
        }
      session_set_save_handler("session_open","session_close","session_read","session_write","session_destro","session_gc");
      session_start();
?>