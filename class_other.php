<?
class other_func {
	function jref($url = "") {
		echo "<script>window.location.href='$url';</script>";
		exit();
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

	function getIp() {
		$onlineip = '';
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}

	function jsec($info = "error", $url = "") {
		if ($url == "") {echo "<script>alert('$info');history.go(-1);</script>";
			exit();
		} else {echo "<script>alert('$info');window.location.href='$url';</script>";
			exit();
		}
	}

	function QrSort_ASC($array, $key)//二维数组排序
	{
		foreach ($array as $val) {
			$score[] = $val[$key];
		}
		array_multisort($score, SORT_ASC, $array);
		return $array;
	}

	function QrSort_DESC($array, $key)//二维数组排序-倒叙
	{
		foreach ($array as $val) {
			$score[] = $val[$key];
		}
		array_multisort($score, SORT_DESC, $array);
		return $array;
	}

	function ToXml($val)//一个关联数组
	{
		$xml = "<xml>";
		foreach ($val as $k => $v) {
			if (is_numeric($v)) {
				$xml .= "<" . $k . ">" . $v . "</" . $k . ">";
			} else {
				$xml .= "<" . $k . "><![CDATA[" . $v . "]]></" . $k . ">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}

	function FromXml($xml) {
		libxml_disable_entity_loader(true);
		$values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $values;
	}

	function getMillisecond() {
		//获取毫秒的时间戳
		$time = explode(" ", microtime());
		$time = $time[1] . ($time[0] * 1000);
		$time2 = explode(".", $time);
		$time = $time2[0];
		return $time;
	}

	function writeLog($text) {
		// $text=iconv("GBK", "UTF-8//IGNORE", $text);
		$text = characet($text);
		file_put_contents(dirname(__FILE__) . "/log/log.txt", date("Y-m-d H:i:s") . "  " . $text . "\r\n", FILE_APPEND);
	}

	//转换编码
	function characet($data) {
		if (!empty($data)) {
			$fileType = mb_detect_encoding($data, array('UTF-8', 'GBK', 'GB2312', 'LATIN1', 'BIG5'));
			if ($fileType != 'UTF-8') {
				$data = mb_convert_encoding($data, 'UTF-8', $fileType);
			}
		}
		return $data;
	}
  function get_td_array($table) {//读取表格数据转换为二维数组
  $table = preg_replace("'<table[^>]*?>'si","",$table);
  $table = preg_replace("'<tr[^>]*?>'si","",$table);
  $table = preg_replace("'<td[^>]*?>'si","",$table);
  $table = preg_replace("'<th[^>]*?>'si","",$table);
  $table = str_replace("</tr>","{tr}",$table);
  $table = str_replace("</td>","{td}",$table);
  $table = str_replace("</th>","{td}",$table);
  //去掉 HTML 标记 
  $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
  //去掉空白字符 
  $table = preg_replace("'([rn])[s]+'","",$table);
  $table = str_replace(" ","",$table);
  $table = str_replace(" ","",$table);
  $table = explode('{tr}', $table);
  array_pop($table);
  foreach ($table as $key=>$tr) {
    $td = explode('{td}', $tr);
    array_pop($td);
    $td_array[] = $td;
  }
  return $td_array;
   }
   function get_current_url()
   {
   	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	return $url;
   }
     function cutstr($string, $length, $charset, $dot) { //字符，截取长度，字符集，结尾符 
        if (strlen($string) <= $length) {
            return $string;
        }
        $pre = chr(1);
        $end = chr(1);
        //保护特殊字符串 
        $string = str_replace(array('&', '"', '<', '>'), array($pre.
            '&'.$end, $pre.
            '"'.$end, $pre.
            '<'.$end, $pre.
            '>'.$end), $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                }
                elseif(194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                }
                elseif(224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                }
                elseif(240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                }
                elseif(248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                }
                elseif($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut.= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }
        //还原特殊字符串 
        $strcut = str_replace(array($pre.
            '&'.$end, $pre.
            '"'.$end, $pre.
            '<'.$end, $pre.
            '>'.$end), array('&', '"', '<', '>'), $strcut);
        //修复出现特殊字符串截段的问题 
        $pos = strrpos($s, chr(1));
        if ($pos !== false) {
            $strcut = substr($s, 0, $pos);
        }
        return $strcut.$dot;
    }
   function cutstr_html($string,$length=0,$ellipsis='…'){    //实现类似于搜索引擎那种 你好...的东西
       $string=strip_tags($string);    
        $string=preg_replace('/\n/is','',$string);    
       $string=preg_replace('/ |　/is','',$string);    
       $string=preg_replace('/ /is','',$string);    
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);    
        if(is_array($string)&&!empty($string[0])){    
           if(is_numeric($length)&&$length){    
                $string=join('',array_slice($string[0],0,$length)).$ellipsis;    
            }else{    
                $string=implode('',$string[0]);    
            }    
       }else{    
            $string='';    
        }    
       return $string;    
    }  
}
?>