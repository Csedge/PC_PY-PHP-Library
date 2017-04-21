<?
	class sms{
	     public $sms_usr='';
		 public $sms_pwd='';
		 function __construct()
		 {
		 	include (dirname(__FILE__) . "/config_global.php");
			$this->sms_pwd=$sms_pwd;
			$this->sms_usr=$sms_usr;
		 }
		 function send_sms($aim,$content)
		 {
			$url2='http://118.26.135.176/interface/tomsg.jsp?user='.$this->sms_usr.'&pwd='.$this->sms_pwd.'&phone='.$aim.'&msgcont='.urlencode(iconv('UTF-8', 'GB2312', $content));
			return file_get_contents($url2);
		 }
	}
?>