<?
class wechat_contral{
	public $appid='';
	public $appscert='';
	public $openid='';
	public $token_normall='';
	public $token_oauth='';
	public $redirecturl='';
	public $code='';
	public $oauthtoken='';
	public $nor_token='';
    function __construct()
    {
     	include (dirname(__FILE__) . "/config_global.php");
		$this->appid=$wechat_appid;
		$this->appscert=$wechat_appscert;
		$this->redirecturl=$wechat_redirect;
     }	
	function random($r, $typ) {$c = "";
		switch($typ) {case 0 :
				//小写字母+数字
				for ($j = 0; $j < $r; $j++) {$a = chr(rand(97, 122));
					$b = rand(0, 9);
					$c .= $a . $b;
				}
				break;
			case 1 :
				//数字
				for ($j = 0; $j < $r; $j++) {$b = rand(0, 9);
					$c .= $b;
				}
				break;
			case 2 :
				//纯小写字母
				for ($j = 0; $j < $r; $j++) {$a = chr(rand(97, 122));
					$c .= $a;
				}
				break;
			case 3 :
				//大写字母和数字
				for ($j = 0; $j < $r; $j++) {$a = chr(rand(65, 90));
					$b = rand(0, 9);
					$c .= $a . $b;
				}
				break;
			case 4 :
				//大写字母
				for ($j = 0; $j < $r; $j++) {$a = chr(rand(65, 90));
					$c .= $a;
				}
				break;
			case 5 :
				//大小写字母
				for ($j = 0; $j < $r; $j++) {$a = chr(rand(65, 90));
					$b = chr(rand(97, 122));
					$c .= $a . $b;
				}
				break;
			default :
				echo "ER!";
				break;
		}
		return $c;
	}
	function ToUrlParams($val) {
		$buff = "";
		foreach ($val as $k => $v) {
			if ($k != "sign" && $v != "" && !is_array($v)) {
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}
    function wechat_redirect_base($r_url='') 
	{
	if($r_url=='')
	{
			$redirect_uri = $this->redirecturl;
	}
	else
	{
		$redirect_uri=$r_url;
	}
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
	//echo $url;
	header("Location:" . $url);
	}
	function wechat_redirect($r_url='') 
	{
	if($r_url=='')
	{
			$redirect_uri = $this->redirecturl;
	}
	else
	{
		$redirect_uri=$r_url;
	}
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
	//echo $url;
	header("Location:" . $url);
	}
	function getOpenId_only($r_url='')
	{
		if(isset($_GET['code']))
		{
			$appid = $this->appid;
			$secret = $this->appscert;
			$code = $_GET["code"];
			$get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=" . $code . "&grant_type=authorization_code";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
						curl_setopt($ch, CURLOPT_URL, $get_token_url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						$html = curl_exec($ch);
						curl_close($ch);
						$tmp_dec = json_decode($html);
						$openid = $tmp_dec -> openid;
						$this->openid=$openid;
            return $openid;
		}
		else
		{
			if($r_url==''){
			$r_uri = $this->redirecturl;
	     	}
			$this->wechat_redirect_base($r_url);
		}
	}
	function getOpenId($r_url='')
	{
		if(isset($_GET['code']))
		{
			$appid = $this->appid;
			$secret = $this->appscert;
			$code = $_GET["code"];
			$get_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=" . $code . "&grant_type=authorization_code";
		 //   echo $get_token_url;
						$ch = curl_init();
						//设置选项，包括URL
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
						curl_setopt($ch, CURLOPT_URL, $get_token_url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						//执行并获取HTML文档内容
						$html = curl_exec($ch);
					//	释放curl句柄
						curl_close($ch);
						//$html = file_get_contents($get_token_url);
						//echo $html;
						$tmp_dec = json_decode($html);
						$openid = $tmp_dec -> openid;
						$token = $tmp_dec -> access_token;
						$this->openid=$openid;
			$this->oauthtoken=$token;
            return $openid;
		}
		else
		{
			if($r_url==''){
			$r_uri = $this->redirecturl;
	     	}
			$this->wechat_redirect($r_url);
		}
	}
	function get_oauth_token()
	{
         return $this->oauthtoken;
	}
	function get_normally_token()
	{
		$as_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appscert;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL, $as_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$html = curl_exec($ch);
		curl_close($ch);
		$tmp_dec = json_decode($html);
		$token_n=$tmp_dec->access_token;
		$this->nor_token=$token_n;
		return $token_n;
	}
    function check_focus()
	{
		$subscribe_msg = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" .$this->nor_token. "&openid=" .$this->openid. '&lang=zh_CN';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL, $subscribe_msg);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$subscribe_info = curl_exec($ch);
		curl_close($ch);
		$subscribe_info = json_decode($subscribe_info);
		if ($subscribe_info -> subscribe == 1)
		{
			return 1;//返回已经关注
		}
		else
		{
			return 0;//为未关注
		}
	}
	function get_user_info()
	{
		$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$this->oauthtoken.'&openid='.$this->openid.'&lang=zh_CN';
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$html = curl_exec($ch);
		curl_close($ch);
		$html = json_decode($html);
		$user_info=array();
		$user_info = get_object_vars($html);
		//print_r($user_info);
		return $user_info;
	}
	function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
	function template_message($touser, $template_id, $url, $data, $topcolor = '#7B68EE')
	{
        /*
         * data=>array(
                'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
                'name'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
                'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
            )
         */
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->nor_token;
        $dataRes = $this->request_post($url, urldecode($json_template));
        if ($dataRes['errcode'] == 0) {
            return true;
        } else {
            return false;
        }	
	}
	function jsapi_ticket(){
		$url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->nor_token.'&type=jsapi';
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$html = curl_exec($ch);
		curl_close($ch);
		$html = json_decode($html);
		$user_info=array();
		$user_info = get_object_vars($html);
		//print_r($user_info);
		return $user_info;
	}
	function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      //$accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
       $url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->nor_token.'&type=jsapi';
        $ch=curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$html = curl_exec($ch);
		curl_close($ch);
		$html = json_decode($html);
		$user_info=array();
		$ticket = get_object_vars($html);
		//print_r($user_info);
		//return $user_info;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }
    return $ticket;
  }
}
?>
