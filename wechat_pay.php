<?
include (dirname(__FILE__) . "/class_other.php");
require(dirname(__FILE__).'/phpqrcode/phpqrcode.php');
class wechat_pay {
	public $post_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
	public $query_url = 'https://api.mch.weixin.qq.com/pay/orderquery';
	public $close_url = 'https://api.mch.weixin.qq.com/pay/closeorder';
	public $refund_url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
	public $refunf_query_addr = 'https://api.mch.weixin.qq.com/pay/refundquery';
	public $appid = '';
	public $mch_id = '';
	public $appscert = '';
	public $notify_url = '';
	public $api_key = '';
	public $nonce_str = '';
	public $SSLCERT_PATH='';
	public $SSLKEY_PATH='';
	function __construct() {
		include 'config_global.php';
		$this -> mch_id = $wechat_mch;
		$this -> appscert = $wechat_appscert;
		$this -> appid = $wechat_appid;
		$this -> notify_url = $wechat_notify_url;
		$this -> api_key = $wechat_pay_key;
		$this -> SSLCERT_PATH=$wechat_SSLCERT_PATH;
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

	function get_sign($val_arr) {
		ksort($val_arr);
		$string = $this -> ToUrlParams($val_arr);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=" . $this -> api_key;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}

	function creat_detail() {
      
	}

	function u_order_data($body, $out_trade_no, $total_fee, $trade_type, $detail = '', $attach = '', $time_start = 0, $time_expire = 0, $goods_tag = '', $product_id = '', $limit_pay = '', $openid = '', $fee_type = 'CNY') {
		$o = new other_func();
		$ip = $o -> getIp();
		$x_ip =explode(',',$ip);
		$ip=$x_ip[0];
		$nonce_str = $o -> random(16, 0);
		$this -> nonce_str = $nonce_str;
		$val = array("appid" => $this -> appid, "body" => $body, "mch_id" => $this -> mch_id, "nonce_str" => $nonce_str, "notify_url" => $this -> notify_url, "out_trade_no" => $out_trade_no, "spbill_create_ip" => $ip, "total_fee" => $total_fee, "trade_type" => $trade_type, "fee_type" => $fee_type);
		if ($detail != '') {
			$val['detail'] = $detail;
		}
		if ($attach != '') {
			$val['attach'] = $attach;
		}
		if ($time_start != 0) {
			$val['time_start'] = $time_start;
		}
		if ($time_expire != '') {
			$val['time_expire'] = $time_expire;
		}
		if ($goods_tag != '') {
			$val['goods_tag'] = $goods_tag;
		}
		if ($product_id != '') {
			$val['product_id'] = $product_id;
		}
		if ($limit_pay != '') {
			$val['limit_pay'] = $limit_pay;
		}
		if ($openid != '') {
			$val['openid'] = $openid;
		}
		$sign = $this -> get_sign($val);
		$val['sign'] = $sign;
		$xml = $o -> ToXml($val);
	//	echo "xml=".$xml;
		return $xml;
	}

	function curl_post($xml, $url,$use_cert=FALSE) {
		$ch = curl_init();
		if($use_cert==TRUE)
		{
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, $this->SSLCERT_PATH);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, $this->SSLKEY_PATH);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		//严格校验
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$data = curl_exec($ch);
		if ($data) {
			curl_close($ch);
			return $data;
		} else {
			$error = curl_errno($ch);
			curl_close($ch);
			echo 'curl='.$error;
		}
	}

	function u_order($body, $out_trade_no, $total_fee, $trade_type, $detail = '', $attach = '', $time_start = 0, $time_expire = 0, $goods_tag = '', $product_id = '', $limit_pay = '', $openid = '', $fee_type = 'CNY') {
		$o = new other_func();
		$xml = $this -> u_order_data($body, $out_trade_no, $total_fee, $trade_type, $detail, $attach, $time_start, $time_expire, $goods_tag,$product_id,$limit_pay,$openid,$fee_type);
		$res = $this -> curl_post($xml, $this -> post_url);
		$val = $o -> FromXml($res);
		if ($val['return_code'] == "SUCCESS" && $val["result_code"] == "SUCCESS") {
			return $val;
		} else {
			return $val['return_msg'];
		}
	}

	function query_order($out_trade_no)//输入商户程序订单号
	{
		$o = new other_func();
		$nonce_str = $o -> random(16, 0);
		$val = array("appid" => $this -> appid, "mch_id" => $this -> mch_id, "nonce_str" => $nonce_str, "out_trade_no" => $out_trade_no, );
		$sign = $this -> get_sign($val);
		$val['sign'] = $sign;
		$xml = $o -> ToXml($val);
		$res = $this -> curl_post($xml, $this -> query_url);
		$data = $o -> FromXml($res);
		if ($data['return_code'] == "SUCCESS" && $data["result_code"] == "SUCCESS") {
			return $data;
		} else {
			return $data['return_msg'];
		}
	}

	function close_order($out_trade_no) {
		$o = new other_func();
		$nonce_str = $o -> random(16, 0);
		$val = array("appid" => $this -> appid, "mch_id" => $this -> mch_id, "nonce_str" => $nonce_str, "out_trade_no" => $out_trade_no, );
		$sign = $this -> get_sign($val);
		$val['sign'] = $sign;
		$xml = $o -> ToXml($val);
		$res = $this -> curl_post($xml, $this -> close_url);
		$data = $o -> FromXml($res);
		if ($data['return_code'] == "SUCCESS" && $data["result_code"] == "SUCCESS") {
			return $data;
		} else {
			return $data['return_msg'];
		}
	}

	function refund_order($out_trade_no, $refund_id, $totel_fee, $refund_fee, $op_user_id = '', $refund_fee_type = 'CNY') {
		$o = new other_func();
		$nonce_str = $o -> random(16, 0);
		$val = array("appid" => $this -> appid, "mch_id" => $this -> mch_id, "nonce_str" => $nonce_str, "out_trade_no" => $out_trade_no, "total_fee" => $totel_fee, "out_refund_no" => $refund_id, "refund_fee" => $refund_fee, "op_user_id" => $op_user_id, "refund_fee_type" => $refund_fee_type);
		$val['sign'] = $this -> get_sign($val);
		$xml = $o -> ToXml($val);
		$res = $this -> curl_post($xml, $this -> refund_url,TRUE);
		$data = $o -> FromXml($res);
		if ($data['return_code'] == "SUCCESS" && $data["result_code"] == "SUCCESS") {
			return $data;
		} else {
			return $data['return_msg'];
		}
	}
    function check_sign($xml)
	{
		$o=new other_func();
		$val=$o->FromXml($xml);
		$sign=$val['sign'];
		$n_sign=$this->get_sign($val);
		if($sign==$n_sign)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	function get_qrcode($code_url) {
		$o=new other_func();
		$tmp=$o->random(24, 0);
		$f='../tmp/'.$tmp.".png";
         QRcode::png($code_url,$f);
		 $path='./tmp/'.$tmp.'.png';
		 return $path;
	}
    function refund_query($out_trade_no)
	{
		$o=new other_func();
		$val=array(
		"appid"=>$this->appid,
		"mch_id"=>$this->mch_id,
		"nonce_str"=>$o->random(16,0),
		"out_trade_no"=>$out_trade_no
		);
		$sign=$this->get_sign($val);
		$val['sign']=$sign;
		$xml=$o->ToXml($val);
		$data=$this->curl_post($xml, $this->refunf_query_addr);
		$data=$o->FromXml($data);
		if ($data['return_code'] == "SUCCESS" && $data["result_code"] == "SUCCESS") {
			return $data;
		} else {
			return $data['return_msg'];
		}
	}
	function create_jsapi($prepay_id) {
		$o = new other_func();
		$nonce_str = $o -> random(16, 0);
		$val = array("appId" => $this -> appid, "timeStamp" => time(), "nonceStr" => $nonce_str, "package" => "prepay_id=" . $prepay_id, "signType" => 'MD5');
		$sign = $this -> get_sign($val);
		return "function onBridgeReady(){
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {" . '
                "appId" : "' . $val['appId'] . '",     //公众号名称，由商户传入     
                 "timeStamp":"' . $val['timeStamp'] . '",         //时间戳，自1970年以来的秒数     
                 "nonceStr" : "' . $val['nonceStr'] . '", //随机串     
                 "package" : "' . $val['package'] . '",     
                 "signType" : "'.$val['signType'].'",         //微信签名方式：     
                 "paySign" : "' . $sign . '" //微信签名 
             },
           function(res){     
                 if(res.err_msg == "get_brand_wcpay_request：ok" ) {}else{}     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
            }
        ); 
     }
     if (typeof WeixinJSBridge == "undefined"){
         if( document.addEventListener ){' . "
             document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
             }else if (document.attachEvent){
             document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
             document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
           }
      }else{
       onBridgeReady();}";
	}
	function GET_Notice()
	{
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		if($this->check_sign($xml)==TRUE)
		{
			$o=new other_func();
			$r=$o->FromXml($xml);
		    $val=array(
		     "return_code"=>"SUCCESS"
			);
			$xml=$o->ToXml($val);
			return $r;
		}
		else
	    {
	    	return 0;
	    };
	}
}
?>