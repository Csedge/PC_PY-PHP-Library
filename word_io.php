<?php
    require (dirname(__FILE__) . '/PHPWord.php');
    class word_io{
    	public $PHPWord;
    	function __construct()
    	{
    		$this->PHPWord = new PHPWord();
    	}
    function TemplateCreateWord($file,$data,$output)
     {
	$document = $this->PHPWord->loadTemplate($file);
	if(count($data))
	{
		print_r($data);
		foreach ($data as $key => $value) {
			$replace='Value'.$key;
			 $document->setValue($replace, $value);
			 // echo $replace;
		}
	 }
    $document->save($output);
  }  
}
?>