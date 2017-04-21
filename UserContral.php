<?php
require_once(dirname(__FILE__) . "/mysql_contral.php");
require_once(dirname(__FILE__) . "/class_other.php");
/*
 * 用户验证类
 * 1.微信openid验证
 * 2.用户名密码验证,密码采用单层md5加密
 * 3.用户名密码验证,密码采用md5($pwd+$salt)加密
 * 4.用户名密码验证,密码采用md5(md5($pwd+$salt)+$salt)加密
 * 5.用户名密码+验证码架构，加密方式同第三种适合安全性较高的应用
 * 6.用户名密码+验证码架构，加密方式同第四种，适合安全性极高应用，有较大的资源开销
 * */
class UserContral extends db_contral{
	public function c_session($data){
		foreach($t as $k=>$v){
		$_SESSION[$k]=$v;
		}
		return 1;
	}
	public function usr_auth($tab,$type,$usr,$pwd='',$v_code=''){
	  $this->connect_db();
		switch($type){
			case 1:
			$r=$this->db_query_n1($tab,'openid',$usr);
		    if(count($r)){
                $this->c_session($r[0]);
		    	return 1; 
		    }
			else{
				return 0;
			}
			break;
			case 2:
			$pwd=md5($pwd);
			$r=$this->db_query_normal($tab, array('usr','pwd'), array($usr,$pwd), array('=','='), array('AND'),0,1);
			if(count($r)){
				$this->c_session($r[0]);
				return 1;
			}
			else
			{
				return 0;
			}
			break;
			case 3:
			$r=$this->db_query_n1($tab, 'usr', $usr);
			if(count($r)){
				$salt=$r[0]['salt'];
				$pwd=md5($pwd.$salt);
                $r=$this->db_query_normal($tab, array('usr','pwd'), array($usr,$pwd), array('=','='), array('AND'),0,1);
				if(count($r)){
					$this->c_session($r[0]);
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else{
				return 0;
			}
			break;
			case 4:
			$r=$this->db_query_n1($tab, 'usr', $usr);
			if(count($r)){
				$salt=$r[0]['salt'];
				$pwd=md5(md5($pwd.$salt).$salt);
                $r=$this->db_query_normal($tab, array('usr','pwd'), array($usr,$pwd), array('=','='), array('AND'),0,1);
				if(count($r)){
					$this->c_session($r[0]);
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else{
				return 0;
			}
			break;
			case 5:
			if($_SESSION['vcode']!=$v_code){
				return 0;
			}
            $r=$this->db_query_n1($tab, 'usr', $usr);
			if(count($r)){
				$salt=$r[0]['salt'];
				$pwd=md5($pwd.$salt);
                $r=$this->db_query_normal($tab, array('usr','pwd'), array($usr,$pwd), array('=','='), array('AND'),0,1);
				if(count($r)){
					$this->c_session($r[0]);
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else{
				return 0;
			}
			break;
			case 6:
			if($_SESSION['vcode']!=$v_code){
				return 0;
			}
			$r=$this->db_query_n1($tab, 'usr', $usr);
			if(count($r)){
				$salt=$r[0]['salt'];
				$pwd=md5(md5($pwd.$salt).$salt);
                $r=$this->db_query_normal($tab, array('usr','pwd'), array($usr,$pwd), array('=','='), array('AND'),0,1);
				if(count($r)){
					$this->c_session($r[0]);
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else{
				return 0;
			}
			break;
			default:
			return 0;
		}
	}
}
?>