<?php
/**
 * WebSFX
 * @author      Michail Urakov <mikbox74@gmail.com>
 * @copyright   Copyright (c) Michail Urakov
 * @license     MIT
 */
 
error_reporting(0);
function getList($d, &$files){
	$d = $d.(!empty($d)?'/':'');
	$files = array_merge(glob($d.'*.*'), $files);
	$dirs = glob($d.'*', GLOB_ONLYDIR);
	foreach($dirs as $dir){
		getList($dir, &$files);
	}
}
$files = array();
getList('', &$files);
$str = '<?php ';
$a = 0;
foreach($files as $filename){
	if($filename!=basename(__FILE__)){
		$content = file_get_contents($filename);
		if($content !== false){
			$str.= '_s(\''.$filename.'\', \''.base64_encode(gzcompress($content, 6)).'\');';
			echo '<font color=green>'.$filename.' - compress OK</font>';
			$a++;
		}else{
			echo '<font color=red>'.$filename.' - compress ERROR</font>';
		}
		echo '<br>';
	}
}
$str .= 'function _s($f,$s){_cd(dirname($f));if(file_put_contents($f,gzuncompress(base64_decode($s)))!==false) echo"<font color=green>".$f." - uncompress OK</font>"; else echo"<font color=red>".$f." - uncompress FAILED</font>"; echo"<br>";} function _cd($d){if(dirname($d)!=".")_cd(dirname($d));if(!empty($d)&&$d!="."&&!file_exists($d))mkdir($d,0777);}';
file_put_contents(
	'installer.php',
	$str
);

echo '<p>'.$a.' files packed</p>';
