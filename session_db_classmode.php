<?php
require_once (dirname(__FILE__) . "/mysql_contral.php");
class SessionToDb extends db_contral{
	/*
	 * 数据表名称 session
	 * 字段：
	 * 1.session_id
	 * 2.session_data
	 * 3.session_life
	 * */
	public $db_serv="";
	public $db_usr="";
	public $db_pwd="";
	public $db_nam="";
	public $db_header="";
	public $db_code="";
	public $lifetime="";
	public $con;
	public $stmt;
	function __construct()
	{
		include (dirname(__FILE__) . "/config_global.php");
		$this->db_serv=$mysql_db_serv;
		$this->db_usr=$mysql_db_usr;
		$this->db_pwd=$mysql_db_pwd;
		$this->db_nam=$mysql_db_nam;
		$this->db_code=$mysql_charset;
		$this->db_header=$mysql_header;
		$this->lifetime=$lifetime;
		$this->con = new mysqli($this->db_serv, $this->db_usr, $this->db_pwd, $this->db_nam);
	    if(!$this->con)
        {
	       echo "mysql connection error:".$this->con->connect_error;
        }
		else
		{
			$this->con->set_charset($this->db_code);
		}
		session_set_save_handler(array($this,"open"),array($this,"close"),array($this,"read"),array($this,"write"),array($this,"destroy"),array($this,"gc"));
		session_start();
	}
	function open($save_path, $session_name) 
     {
          return(true);
     }
	function read($session_id)
	{
			$tab='session';
			$item='session_id';
			$res=$this->db_query_n1($tab, $item, $session_id);
			if(count($res)!=0)
			{
				$item_arr=array("session_life");
				$data_arr=array(time());
				$this->update_data_basic($tab, $item_arr, $data_arr, "session_id","=",$session_id);
				return $res[0]['session_data'];
			}
			else
			{
				return '0';
			}
		}
		function write($session_id,$session_data){
			$res=$this->db_query_n1("session", "session_id", $session_id);
			if(count($res)!=0)
			{
				$s_arr_item[0]="session_data";
				$s_arr_item[1]="session_life";
				$s_arr_data[0]=$session_data;
				$s_arr_data[1]=time();
				$this->update_data_basic("session",$s_arr_item,$s_arr_data,"session_id","=",$session_id);
			}
			else
			{
				$s_arr_item[0]="session_id";
				$s_arr_item[1]="session_data";
				$s_arr_item[2]="session_life";
				$s_arr_data[0]=$session_id;
				$s_arr_data[1]=$session_data;
				$s_arr_data[2]=time();
				$this->ins_data("session",$s_arr_item,$s_arr_data);
			}			
			 return(true);
		}
		function gc($lifetime)
		{
			$item_arr[0]="session_life";
			$typ_arr[0]="<";
			$val_arr[0]=time()-$this->lifetime;
			$relate_arr[0]='';
			$this->del_data("session", $item_arr, $typ_arr, $val_arr, $relat_arr);
			return(true);
		}
		function destroy($session_id)
		{
			$item_arr[0]="session_id";
			$val_arr[0]=$session_id;
			$typ_arr[0]="=";
			$relat_arr=array();
			$this->del_data("session", $item_arr, $typ_arr, $val_arr, $relat_arr);
			return(true);
		}
		function close() {
           return session_gc($this->lifetime);
        }
}
?>
