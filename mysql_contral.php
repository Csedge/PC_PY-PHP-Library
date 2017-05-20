<?
class db_contral{
    public $db_serv="";
	public $db_usr="";
	public $db_pwd="";
	public $db_nam="";
	public $db_header="";
	public $db_code="";
	public $con;
	public $stmt;
	function __construct()
	{
	//	include 'config_global.php';
		include (dirname(__FILE__) . "/config_global.php");
		$this->db_serv=$mysql_db_serv;
		$this->db_usr=$mysql_db_usr;
		$this->db_pwd=$mysql_db_pwd;
		$this->db_nam=$mysql_db_nam;
		$this->db_code=$mysql_charset;
		$this->db_header=$mysql_header;
	}
	function connect_db()
	{
		$this->con = new mysqli($this->db_serv, $this->db_usr, $this->db_pwd, $this->db_nam);
	    if(!$this->con)
        {
	       echo "mysql connection error:".$this->con->connect_error;
        }
		else
		{
			$this->con->set_charset($this->db_code);
		}
	}
	function get_all_fetch()
	{
		$this->stmt->execute();
		$this->stmt->store_result();
		$var=array();
		$data_arr=array();
		$meta=$this->stmt->result_metadata();
		while($fid=$meta->fetch_field())
		{
			$var[]=&$data_arr[$fid->name];
		}
		call_user_func_array(array($this->stmt,"bind_result"), $var);
		$i=0;
		while($this->stmt->fetch())
		{
			$res_arr[$i]=array();
			foreach($data_arr as $key=>$tmp)
			{
				$res_arr[$i][$key]=$tmp;
			}
			$i++;
		}
		return $res_arr;
	}
	function db_query_n1($tab,$item,$q_value,$typ='=',$q_limit=1)
	{
		if($q_limit!=0)
		{
			//$i=0;
			$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ".$item.' '.$typ." ? LIMIT 0,".$q_limit;
			$this->stmt=$this->con->prepare($db_sql);
			$this->stmt->bind_param('s',$q_value);
			return $this->get_all_fetch();
		}
		else
		{
            $i=0;
			$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ".$item.' '.$typ." ?";
			$this->stmt=$this->con->prepare($db_sql);
			$this->stmt->bind_param('s',$q_value);
			return $this->get_all_fetch();
		}
	}
    function db_query_n1_count($tab,$item,$q_value,$typ='=')
	{
			$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ".$item.' '.$typ." ?";
			//echo $db_sql;
			$this->stmt=$this->con->prepare($db_sql);
			$this->stmt->bind_param('s',$q_value);
		//	return $this->get_all_fetch();
		    $this->stmt->execute();
		    $this->stmt->store_result();
			return $this->stmt->num_rows();
	}
	function db_query_normal($tab,$item_arr,$val_arr,$typ_arr,$relat_arr,$q_start=0,$q_limit=1)
	{
		if(count($item_arr)==count($typ_arr)&&count($item_arr)==count($val_arr))
		{
			$bind_str='';
			if($q_limit==0)//全输出模式
			{
				$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ";
				for($i=0;$i<count($item_arr);$i++)
				{
					if($i!=count($item_arr)-1)
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ".$relat_arr[$i].' ';
					}
					else
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ";
					}
				}
			}
			else//按需输出行数模式
			{
				$db_sql="SELECT * FROM ".$this->db_header.$tab."  WHERE  ";
				for($i=0;$i<count($item_arr);$i++)
				{
					if($i!=count($item_arr)-1)
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ".$relat_arr[$i].' ';
					}
					else
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ";
					}
				}
				$db_sql=$db_sql." LIMIT ".$q_start.",".$q_limit;		
			}
			$this->stmt=$this->con->prepare($db_sql);
			$str_b='';
			$bind_arr=array();
			for($i=0;$i<count($item_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr[0]=&$str_b;
			foreach($val_arr as $k=>$val)
			{
				$bind_arr[$k+1]=&$val_arr[$k];
			}
			call_user_func_array(array($this->stmt,'bind_param'), $bind_arr);
			//$this->stmt->bind_param('s',$val_arr[$i]);
			return $this->get_all_fetch();	
		}
	}
    function ins_data($tab,$item_arr,$data_arr)
	{
		$db_sql="INSERT INTO ".$this->db_header.$tab." (";
		if(count($item_arr)==count($data_arr))
		{
			for($i=0;$i<count($item_arr);$i++)
			{
				if($i!=count($item_arr)-1)
				{
					$db_sql=$db_sql."`".$item_arr[$i]."`,";
				}
				else
				{
					$db_sql=$db_sql."`".$item_arr[$i]."`) VALUES (";
				}
			}
			for($i=0;$i<count($item_arr);$i++)
			{
		     	if($i!=count($item_arr)-1)
		     	{
	     		 	$db_sql=$db_sql."?,";
		    	}
			    else
			    {
			 	    $db_sql=$db_sql."?)";
				}
			}
		 	$this->stmt=$this->con->prepare($db_sql);
			$str_b='';
			for($i=0;$i<count($item_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr=array();
			$bind_arr[0]=&$str_b;			 
			foreach($data_arr as $key=>$val)
			{
				$bind_arr[$key+1]=&$data_arr[$key];
			}
			call_user_func_array(array($this->stmt,'bind_param'), $bind_arr);
			if(!$this->stmt->execute())
			{
				return "ERROR-SQL执行失败！传参错误！";
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return "ERROR-字段数和数值数不等！";
		}
	}
	function update_data_basic($tab,$item_arr,$data_arr,$o_item,$typ,$o_val)
	{
		if(count($item_arr)==count($data_arr))
		{
			$db_sql="UPDATE ".$this->db_header.$tab." SET ";
			for($i=0;$i<count($item_arr);$i++)
			{
				 if($i!=count($item_arr)-1)
			     {
			     	$db_sql=$db_sql."`".$item_arr[$i]."`=?,";
			     }
				 else
				 {
				 	$db_sql=$db_sql."`".$item_arr[$i]."`=? WHERE ";
				 }
			}
			$db_sql=$db_sql.$o_item.$typ."?";
			$this->stmt=$this->con->prepare($db_sql);
			$str_b='';
			for($i=0;$i<count($item_arr)+1;$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr[0]=&$str_b;
			foreach($data_arr as $key=>$val)
			{
				$bind_arr[$key+1]=&$data_arr[$key];
				//$bind_arr[$key+1]=&$val;
			}
			$bind_arr[$key+2]=&$o_val;
			call_user_func_array(array($this->stmt,'bind_param'), $bind_arr);
			if(!$this->stmt->execute())
			{
				return "ERROR-SQL执行失败！";
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return "ERROR-字段数和数值数不等！";
		}
	}
	function del_data($tab,$item_arr,$typ_arr,$val_arr,$relat_arr)
	{
		if(count($item_arr)==count($typ_arr))
		{
			$db_sql="DELETE FROM ".$this->db_header.$tab." WHERE ";
			for($i=0;$i<count($item_arr);$i++)
             {
             	if($i!=count($item_arr)-1)
				{
					$db_sql=$db_sql.$item_arr[$i].' '.$typ_arr[$i]." ? ".$relat_arr[$i]." ";
				}
				else
				{
					$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ";
				}
             }
			$str_b='';
			$this->stmt=$this->con->prepare($db_sql);
			for($i=0;$i<count($item_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr[0]=&$str_b;
            foreach($val_arr as $key=>$val)
			{
				$bind_arr[$key+1]=&$val_arr[$key];
			}
			call_user_func_array(array($this->stmt,"bind_param"), $bind_arr);	
			if(!$this->stmt->execute()){
				echo "SQL-ERROR!";
			}
			
		}
		else
		{
			return "ERROR-字段数和数值数不等！";
		}
	}
	function creat_tab($tab,$field_arr,$field_typ){
	$tab=mysqli_real_escape_string($this->con,$db->db_header.$tab);
    $db_sql='CREATE TABLE IF NOT EXISTS '.$tab.' ( `id` int(11) NOT NULL,';
	   for($i=0;$i<count($field_arr);$i++)
	   {
	   	if($i!=count($field_arr)-1)
	 	{
			$field_arr[$i]=mysqli_real_escape_string($this->con,$field_arr[$i]);
			$db_sql=$db_sql."`".$field_arr[$i]."` ".$field_typ[$i]." NOT NULL,";
		}
		else
		{
			$field_arr[$i]=mysqli_real_escape_string($this->con,$field_arr[$i]);
			$db_sql=$db_sql."`".$field_arr[$i]."` ".$field_typ[$i]." NOT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8";
		}
	   }
	   $this->con->query($db_sql);
	}
	function update_data_normal($tab,$item_arr,$data_arr,$o_item_arr,$typ_arr,$o_val_arr,$rel_arr)
	{
		if(count($item_arr)==count($data_arr))
		{
			$db_sql="UPDATE ".$this->db_header.$tab." SET ";
			for($i=0;$i<count($item_arr);$i++)
			{
				 if($i!=count($item_arr)-1)
			     {
			     	$db_sql=$db_sql."`".$item_arr[$i]."`=?,";
			     }
				 else
				 {
				 	$db_sql=$db_sql."`".$item_arr[$i]."`=? WHERE ";
				 }
			}
			for($i=0;$i<count($o_item_arr);$i++)
			{
				if($i!=count($o_item_arr)-1)
				{
					$db_sql=$db_sql."`".$o_item_arr[$i]."` ".$typ_arr[$i]." ? ".$rel_arr[$i].' ';
				}
				else
				{
					$db_sql=$db_sql."`".$o_item_arr[$i]."` ".$typ_arr[$i]." ?";
				}
			}
	//		$db_sql=$db_sql.$o_item.$typ."?";
    //       	echo $db_sql;
			$this->stmt=$this->con->prepare($db_sql);
			$str_b='';
			for($i=0;$i<count($item_arr)+count($o_item_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr[0]=&$str_b;
			foreach($data_arr as $key=>$val)
			{
				$bind_arr[$key+1]=&$data_arr[$key];
			}
			$t=count($bind_arr);
			foreach($o_val_arr as $key=>$val)
			{
				$bind_arr[$t+$key]=&$o_val_arr[$key];
			}
			call_user_func_array(array($this->stmt,"bind_param"), $bind_arr);
			if(!$this->stmt->execute())
			{
				return "ERROR-SQL执行失败！";
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return "ERROR-字段数和数值数不等！";
		}
	}
	function db_query_n1_ord($tab,$item,$q_value,$typ='=',$q_limit=1,$ord='id',$ord_typ='DESC')
	{
		if($q_limit!=0)
		{
			$i=0;
			$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ".$item.' '.$typ." ? ".' ORDER BY '.$ord.' '.$ord_typ ." LIMIT 0,".$q_limit;
			//echo "$db_sql";
			$this->stmt=$this->con->prepare($db_sql);	
			$this->stmt->bind_param('s',$q_value);
			return $this->get_all_fetch();
		}
		else
		{
            $i=0;
			$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ".$item.' '.$typ." ?".' ORDER BY '.$ord.' '.$ord_typ;
			$this->stmt=$this->con->prepare($db_sql);
			$this->stmt->bind_param('s',$q_value);
			return $this->get_all_fetch();
		}
	}
	function db_query_nor_ord($tab,$item_arr,$val_arr,$typ_arr,$relat_arr,$q_start=0,$q_limit=1,$ord='id',$ord_typ='DESC')
	{
		if(count($item_arr)==count($typ_arr)&&count($item_arr)==count($val_arr))
		{
			$bind_str='';
			if($q_limit==0)//全输出模式
			{
				$db_sql="SELECT * FROM ".$this->db_header.$tab." WHERE ";
				for($i=0;$i<count($item_arr);$i++)
				{
					if($i!=count($item_arr)-1)
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ".$relat_arr[$i].' ';
					}
					else
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ";
					}
				}
				$db_sql.=' ORDER BY '.$ord.' '.$ord_typ;
			}
			else//按需输出行数模式
			{
				$db_sql="SELECT * FROM ".$this->db_header.$tab."  WHERE  ";
				for($i=0;$i<count($item_arr);$i++)
				{
					if($i!=count($item_arr)-1)
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ".$relat_arr[$i].' ';
					}
					else
					{
						$db_sql=$db_sql.$item_arr[$i]." ".$typ_arr[$i]." ? ";
					}
				}
				$db_sql.=' ORDER BY '.$ord.' '.$ord_typ;
				$db_sql=$db_sql." LIMIT ".$q_start.",".$q_limit;		
			}
			$this->stmt=$this->con->prepare($db_sql);
			$str_b='';
			$bind_arr=array();
			for($i=0;$i<count($item_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			$bind_arr[0]=&$str_b;
			foreach($val_arr as $k=>$val)
			{
				$bind_arr[$k+1]=&$val_arr[$k];
			}
			call_user_func_array(array($this->stmt,'bind_param'), $bind_arr);
			//$this->stmt->bind_param('s',$val_arr[$i]);
			return $this->get_all_fetch();	
		}
	}
    function all_query($sql)//自定义查询类，不带防注入~慎用~
	{
		$r=$this->con->query($sql);
		$res2=array();
		$i=0;
        while($row = $r->fetch_assoc())
		{
			foreach($row as $key=>$val)
			{
				$res2[$i][$key]=$val;
			}
		$i++;
		}
		return $res2;
	}
	function all_query_stmt($sql,$val_arr){//这里，输入带问号的sql指令，然后$val_arr用于是要替换的值
		$this->stmt=$this->con->prepare($sql);
		for($i=0;$i<count($val_arr);$i++)
			{
				$str_b=$str_b.'s';
			}
			if($str_b!=''){//带参数时候绑定参数
			$bind_arr[0]=&$str_b;
			foreach($val_arr as $k=>$val)
			{
				$bind_arr[$k+1]=&$val_arr[$k];
			}
						call_user_func_array(array($this->stmt,'bind_param'), $bind_arr);
			}
			return $this->get_all_fetch();	//返回关联数组
	}
	function __destruct(){
		$this->con->close();
	}
 }
?>
