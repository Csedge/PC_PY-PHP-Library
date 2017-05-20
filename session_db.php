<?
        include (dirname(__FILE__) . "/mysql_contral.php");
		include (dirname(__FILE__) . "/config_global.php");
		function session_open($save_path, $session_name) 
        {
             return(true);
        }
		function session_read($session_id)
		{
		    $db=new db_contral();
	        $db->connect_db();
			$tab='session';
			$item='session_id';
			$res=$db->db_query_n1($tab, $item, $session_id);
			if(count($res)!=0)
			{
				$item_arr=array("session_life");
				$data_arr=array(time());
				$db->update_data_basic($tab, $item_arr, $data_arr, "session_id","=",$session_id);
				return $res[0]['session_data'];
			}
			else
			{
				return '0';
			}
		}
		function session_write($session_id,$session_data){
			$db=new db_contral();
	       $db->connect_db();
			$res=$db->db_query_n1("session", "session_id", $session_id);
			if(count($res)!=0)
			{
				$s_arr_item[0]="session_data";
				$s_arr_item[1]="session_life";
				$s_arr_data[0]=$session_data;
				$s_arr_data[1]=time();
				$db->update_data_basic("session",$s_arr_item,$s_arr_data,"session_id","=",$session_id);
			}
			else
			{
				$s_arr_item[0]="session_id";
				$s_arr_item[1]="session_data";
				$s_arr_item[2]="session_life";
				$s_arr_data[0]=$session_id;
				$s_arr_data[1]=$session_data;
				$s_arr_data[2]=time();
				$db->ins_data("session",$s_arr_item,$s_arr_data);
			}			
			 return(true);
		}
		function session_gc($lifetime)
		{
			$db=new db_contral();
	    $db->connect_db();
			$item_arr[0]="session_life";
			$typ_arr[0]="<";
			global $lifetime;
			$tmp=$lifetime;
			$val_arr[0]=time()-$tmp;
			$relate_arr[0]='';
			$db->del_data("session", $item_arr, $typ_arr, $val_arr, $relat_arr);
			return(true);
		}
		function session_destro($session_id)
		{
        $db=new db_contral();
	    $db->connect_db();
			$item_arr[0]="session_id";
			$val_arr[0]=$session_id;
			$typ_arr[0]="=";
			$relat_arr=array();
			$db->del_data("session", $item_arr, $typ_arr, $val_arr, $relat_arr);
			return(true);
		}
		function session_close() {
			global $lifetime;
			$tmp=$lifetime;
           return session_gc($tmp);
        }
      session_set_save_handler("session_open","session_close","session_read","session_write","session_destro","session_gc");
      session_start();
?>
