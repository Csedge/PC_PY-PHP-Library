<?php
class session_memcache{
	    //使用此类请确保服务器支持memcache缓存加速类
	    public $memcache_serv='';
		public $memcache_port='';
		public $lifetime='';
		public $mem_handler;
		function __construct()
     	{
		    include (dirname(__FILE__) . "/config_global.php");
			$this->memcache_serv=$memcache_serv;
			$this->memcache_port=$memcache_port;
			$this->lifetime=$lifetime;
			$this->mem_handler=new Memcache();
			$this->mem_handler->connect($this->memcache_serv,$this->memcache_port);
			session_set_save_handler(array($this,"open"),array($this,"close"),array($this,"read"),array($this,"write"),array($this,"destroy"),array($this,"gc"));
		    session_start();
	    }
		public function open($save_path, $session_name) 
        {
             return(true);
        }
		public function read($session_id)
		{
		   $session_data=$this->mem_handler->get($session_id);
		   if($session_data!='')
		   {
		   	$this->mem_handler->set($session_id,$session_data,0,$this->lifetime);
		   	return $session_data;
		   }
		   else
		   {
		   	return 0;
		   }
		}
		public function write($session_id,$session_data){
            $this->mem_handler->set($session_id,$session_data,0,$this->lifetime);
			return(true);
		}
		public function gc($lifetime)
		{
			return(true);
		}
		public function destroy($session_id)
		{
		    $this->mem_handler->delete($session_id,0);
		    return(true);
		}
		public function close() {
           return (true);
        }
}
?>