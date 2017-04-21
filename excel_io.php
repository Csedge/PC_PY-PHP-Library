<?
require_once 'PHPExcel.php';
require_once (dirname(__FILE__).'/PHPExcel/IOFactory.php');
require_once (dirname(__FILE__).'/PHPExcel/Reader/Excel5.php');
class excel_io {
	function Toarray($path, $_f) {
		$tmparray = explode(".", $_f);
		$tmpnum = count($tmparray);
		$tmpnum = $tmpnum - 1;
		if ($tmparray[$tmpnum] == 'xls') {
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$excelpath = $path . $_f;
			$objPHPExcel = $objReader -> load($excelpath);
			$sheet = $objPHPExcel -> getSheet(0);
			$highestRow = $sheet -> getHighestRow();
			$highestColumn = $sheet -> getHighestColumn();
		} else {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$excelpath = $path . $_f;
			$objPHPExcel = $objReader -> load($excelpath);
			$sheet = $objPHPExcel -> getSheet(0);
			$highestRow = $sheet -> getHighestRow();
			$highestColumn = $sheet -> getHighestColumn();
		}
		$data = array();
		for ($j = 1; $j <= $highestRow; $j++)//从第二行开始读取数据
		{
			$str = "";
			for ($k = 'A'; $k <= $highestColumn; $k++)//从A列读取数据
			{
				$str .= $objPHPExcel -> getActiveSheet() -> getCell("$k$j") -> getValue() . '|*|';
				//读取单元格
			}
			$str = mb_convert_encoding($str, 'UTF-8', 'auto');
			//根据自己编码修改
			$strs = explode("|*|", $str);
			$data[$j] = $strs;
		}
		return $data;
	}

	function Toxls($data, $f_name, $title = '', $creator = '') {
		//	$data = array(0 => array('id' => 1001, 'username' => '张飞', 'password' => '123456', 'address' => '三国时高老庄250巷101室'), 1 => array('id' => 1002, 'username' => '关羽', 'password' => '123456', 'address' => '三国时花果山'), 2 => array('id' => 1003, 'username' => '曹操', 'password' => '123456', 'address' => '延安西路2055弄3号'), 3 => array('id' => 1004, 'username' => '刘备', 'password' => '654321', 'address' => '愚园路188号3309室'));
		$objPHPExcel = new PHPExcel();
		$objPHPExcel -> getProperties() -> setCreator($creator) -> setLastModifiedBy($creator) -> setTitle($title) -> setSubject('Office 2003 XLS Document') -> setDescription('Document for Office 2007 XLSX, generated using PHP classes.') -> setKeywords('office 2007 openxml php') -> setCategory('Result file');
		$i = 1;
		$list = array(0 =>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E', 5=>'F', 6=>'G', 7=>'H', 8=>'I', 9=>'J', 10=>'K', 11=>'L', 12=>'M', 13=>'N', 14=>'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z',26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',50=>'AY',51=>'AZ');
		foreach ($data as $k => $v) {
			$j=0;
			foreach ($v as $key => $val) {
				//echo $list[$j].$i.'<br>';
				$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue($list[$j].$i, $val);
				$j++;
			}
			$i++;
			//i是行数，list是列名称
		}
		$objPHPExcel -> getActiveSheet() -> setTitle($title);
		$objPHPExcel -> setActiveSheetIndex(0);
		$outputFileName = $f_name;
		//ob_start(); ob_flush();
    	header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . $f_name . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$xlsWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$xlsWriter -> save("php://output");
	}
}
?>