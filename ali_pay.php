<?
require (dirname(__FILE__) . '/alipay-sdk/AopSdk.php');
require_once (dirname(__FILE__) . "/alipay-sdk/lib/alipay_submit.class.php");
require_once (dirname(__FILE__) . "/alipay-sdk/lib/alipay_notify.class.php");
class Alipay {
	public $request_url = 'https://openapi.alipay.com/gateway.do';
	public $appid = '';
	public $rsa_key = '';
	public $public_key_path = '';
	public $alipay_config;
	function __construct() {
		include 'config_global.php';
		$this -> appid = $alipay_appid;
		$this -> rsa_key = $alipay_rsaPrivateKeyFilePath;
		$this -> public_key = $alipay_PublicKeyPath;
		$this -> alipay_config = $alipay_config;
	}
	function aopclient_request_execute($request, $token = NULL) {
		//	global $config;
		//	require 'config.php';
		$aop = new AopClient();
		$aop -> gatewayUrl = $this -> request_url;
		$aop -> appId = $this -> appid;
		$aop -> rsaPrivateKeyFilePath = $this -> rea_key;
		$aop -> apiVersion = "1.0";
		$result = $aop -> execute($request, $token);
		writeLog("response: " . var_export($result, true));
		return $result;
	}

	public function f2f_qrpay($out_trade_no, $total_amount, $subject) {//这个用于线下客户扫你的二维码
		date_default_timezone_set('Asia/Shanghai');
		$time_expire = date('Y-m-d H:i:s', time() + 60 * 60);
		$biz_content = "{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content .= "\"total_amount\":\"" . $total_amount . "\",\"discountable_amount\":\"0.00\",";
		$biz_content .= "\"subject\":\"" . $subject . "\",\"body\":\"test\",";
		$biz_content .= "\"goods_detail\":[{\"goods_id\":\"apple-01\",\"goods_name\":\"ipad\",\"goods_category\":\"7788230\",\"price\":\"88.00\",\"quantity\":\"1\"},{\"goods_id\":\"apple-02\",\"goods_name\":\"iphone\",\"goods_category\":\"7788231\",\"price\":\"88.00\",\"quantity\":\"1\"}],";
		$biz_content .= "\"operator_id\":\"op001\",\"store_id\":\"pudong001\",\"terminal_id\":\"t_001\",";
		$biz_content .= "\"time_expire\":\"" . $time_expire . "\"}";
		//echo $biz_content;
		$request = new AlipayTradePrecreateRequest();
		$request -> setBizContent($biz_content);
		$response = aopclient_request_execute($request);
		return $response;
	}

	public function f2f_barpay($out_trade_no, $auth_code, $total_amount, $subject) {
		date_default_timezone_set('Asia/Shanghai');
		$time_expire = date('Y-m-d H:i:s', time() + 60 * 60);
		$biz_content = "{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content .= "\"scene\":\"bar_code\",";
		$biz_content .= "\"auth_code\":\"" . $auth_code . "\",";
		$biz_content .= "\"total_amount\":\"" . $total_amount . "\",\"discountable_amount\":\"0.00\",";
		$biz_content .= "\"subject\":\"" . $subject . "\",\"body\":\"test\",";
		$biz_content .= "\"goods_detail\":[{\"goods_id\":\"apple-01\",\"goods_name\":\"ipad\",\"goods_category\":\"7788230\",\"price\":\"88.00\",\"quantity\":\"1\"},{\"goods_id\":\"apple-02\",\"goods_name\":\"iphone\",\"goods_category\":\"7788231\",\"price\":\"88.00\",\"quantity\":\"1\"}],";
		$biz_content .= "\"operator_id\":\"op001\",\"store_id\":\"pudong001\",\"terminal_id\":\"t_001\",";
		$biz_content .= "\"time_expire\":\"" . $time_expire . "\"}";
		//echo $biz_content;
		$request = new AlipayTradePayRequest();
		$request -> setBizContent($biz_content);
		$response = aopclient_request_execute($request);
		return $response;
	}

	public function f2f_query($out_trade_no) {
		$biz_content = "{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeQueryRequest();
		$request -> setBizContent($biz_content);
		$response = aopclient_request_execute($request);
		return $response;
	}

	public function f2f_cancel($out_trade_no) {
		$biz_content = "{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeCancelRequest();
		$request -> setBizContent($biz_content);
		$response = aopclient_request_execute($request);
		return $response;
	}

	public function f2f_refund($trade_no, $refund_amount, $out_request_no) {
		$biz_content = "{\"trade_no\":\"" . $trade_no . "\",\"refund_amount\":\"" . $refund_amount . "\",\"out_request_no\":\"" . $out_request_no . "\",\"refund_reason\":\"reason\",\"store_id\":\"store001\",\"terminal_id\":\"terminal001\"}";

		$request = new AlipayTradeRefundRequest();
		$request -> setBizContent($biz_content);
		$response = aopclient_request_execute($request);
		return $response;
	}

	public function mobile_website_pay($out_trade_no, $subject, $total_fee, $show_url, $body = '') {
		$this -> alipay_config['service'] = 'alipay.wap.create.direct.pay.by.user';
		$parameter = array("service" => $this -> alipay_config['service'], "partner" => $this -> alipay_config['partner'], "seller_id" => $this -> alipay_config['seller_id'], "payment_type" => $this -> alipay_config['payment_type'], "notify_url" => $this -> alipay_config['notify_url'], "return_url" => $this -> alipay_config['return_url'], "_input_charset" => trim(strtolower($this -> alipay_config['input_charset'])), "out_trade_no" => $out_trade_no, "subject" => $subject, "total_fee" => $total_fee, "show_url" => $show_url, "app_pay" => "Y", //启用此参数能唤起钱包APP支付宝
		"body" => $body,
		//其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
		//如"参数名"	=> "参数值"   注：上一个参数末尾需要“,”逗号。
		);
		//建立请求
		$alipaySubmit = new AlipaySubmit($this -> alipay_config);
		$html_text = $alipaySubmit -> buildRequestForm($parameter, "get", "确认");
		return $html_text;
	}

	public function website_return() {//同步跳转专用方法,PC和手机同样
		$alipayNotify = new AlipayNotify($this -> alipay_config);
		$verify_result = $alipayNotify -> verifyReturn();
		if ($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
			//交易状态
			$trade_status = $_GET['trade_status'];
			if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				return $out_trade_no;
				//成功 返回成功的商户订单号
			} else {
				return $_GET['trade_status'];
				//返回错误信息
			}
			//echo "验证成功<br />";
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			//验证失败
			//如要调试，请看alipay_notify.php页面的verifyReturn函数
			//echo "验证失败";
			return 0;
			//0表示验证失败
		}
	}

	public function pc_website_pay($out_trade_no, $subject, $total_fee, $body = '') {//网页扫码支付
		//构造要请求的参数数组，无需改动
		$this -> alipay_config['service'] = 'create_direct_pay_by_user';
		$parameter = array("service" => $this -> alipay_config['service'], "partner" => $this -> alipay_config['partner'], "seller_id" => $this -> alipay_config['seller_id'], "payment_type" => $this -> alipay_config['payment_type'], "notify_url" => $this -> alipay_config['notify_url'], "return_url" => $this -> alipay_config['return_url'], "anti_phishing_key" => $this -> alipay_config['anti_phishing_key'], "exter_invoke_ip" => $this -> alipay_config['exter_invoke_ip'], "out_trade_no" => $out_trade_no, "subject" => $subject, "total_fee" => $total_fee, "body" => $body, "_input_charset" => trim(strtolower($this -> alipay_config['input_charset']))
		//其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
		//如"参数名"=>"参数值"
		);
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit -> buildRequestForm($parameter, "get", "确认");
		return $html_text;
	}

	public function website_notify() {
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify -> verifyNotify();
		if ($verify_result) {//验证成功
			$out_trade_no = $_POST['out_trade_no'];
			$trade_no = $_POST['trade_no'];
			$trade_status = $_POST['trade_status'];
			if ($_POST['trade_status'] == 'TRADE_FINISHED') {
               return $_POST['trade_status'];
			} else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
               return $_POST['trade_status'];
			}
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			echo "success";
			//请不要修改或删除
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			//验证失败
			echo "fail";
            return 0;//出错返回0
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
}
?>