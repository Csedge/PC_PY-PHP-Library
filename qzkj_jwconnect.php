<?
	class qzkj_jw{//强智科技教务系统对接类（适用于北京理工大学珠海学院新版学分制教务系统）
   	  public $jw_login_cookurl='http://e.zhbit.com/jsxsd/';
	  public $jw_post_add='http://e.zhbit.com/jsxsd/xk/LoginToXk';
	  public $kb_get='http://e.zhbit.com/jsxsd/xskb/xskb_list.do';
	  public $kb_main='http://e.zhbit.com/jsxsd/framework/xsMain.jsp';
	  public $xk_index=''
	  public $post_user_login=array();
	  public $post_user_kcb=array();
	  public $cookie_jar;
      function __construct($en_str)//模拟登入
	  {	     
            $this->cookie_jar = dirname(__FILE__)."/pic.cookie";
		   	$url = $this->jw_login_cookurl;
            $ch = curl_init();//获取唯一的cookie。。。
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
            $content = curl_exec($ch);
			curl_close($ch);
			$a['encoded']=$en_str;
			$d=$this->request_post($this->jw_post_add,$a);
	  }
	  function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }
        
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_jar);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);   
        return $data;
     }
	  function get_kcb()
	  {
			 $ch=curl_init();//获取课表
			curl_setopt($ch, CURLOPT_URL, $this->kb_get);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);        
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
            $html=curl_exec($ch);
            curl_close($ch);
		    return $html;
	  }
	  function get_xk()
	  {
	  	
	  	    $ch=curl_init();//获取课表
			curl_setopt($ch, CURLOPT_URL, $this->kb_get);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);        
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
            $html=curl_exec($ch);
            curl_close($ch);
		    return $html;
	  	
	  }
}
?>