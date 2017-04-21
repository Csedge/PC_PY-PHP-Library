<?
require_once 'mysql_contral.php';
require_once 'class_other.php';
class a_cookie {
	function auth() {
		if (isset($_COOKIE['uid']) && isset($_COOKIE['cookie_key'])) {
            $db=new db_contral();
			$db->connect_db();
			$rel[0]='AND';
			$typ[0]='=';
			$typ[1]='=';
			$item[0]='uid';
			$item[1]='cookie_key';
			$val[0]=$_COOKIE['uid'];
			$val[1]=$_COOKIE['cookie_key'];
			$res=$db->db_query_normal("oa_acc", $item, $val, $typ, $rel);
			if(isset($res[0]['usr'])&&$res[0]['usr']!='')
			{
				setcookie("uid",$val[0],time()+1800);
				setcookie($item[1],$val[1],time()+1800);
			    return 0;
			}
		} else {
           $oth=new other_func();
		   $oth->jref("login.php");
		}
	}
}
?>
