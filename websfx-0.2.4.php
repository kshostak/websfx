<?php
/**
 * WebSFX - PHP self-extract archive builder
 * @author      Michail Urakov <mikbox74@gmail.com>
 * @copyright   Copyright (c) Michail Urakov
 * @license     CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 * You may modify and use this script and SFX archives created by it in opensource or commerce projects but You may not remove or hide link to site of the project from the script and SFX archives created by it.
 */
//initialize
error_reporting(0);
//parameters
$SFXversion = '0.2.4 stable';
$tmpfile	= 'wsfxtmpfile.php';
$instfile	= 'installer.php';
$packdir	= dirname(__FILE__);
$thisfile	= basename(__FILE__);
$customdir	= 'websfxcustom';
$R			= 100;
$scanLimit	= 300;
$dirslimit	= 200;
$fileslimit = 200;

 //FRONT INTERFACE
if(!isset($_GET['stop'])&&!isset($_GET['step'])){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>WebSFX</title>
	<style type="text/css">
		body{
			font-family: Sans-serif;
			background: #5c7896;
		}
			#progress{
				position: absolute;
				left: 50%;
				top: 50%;
				margin: -50px 0 0 -325px;
				/*margin: 250px auto;*/
				width: 650px;
				height: 100px;
				background: #404855;
				padding-top: 10px;
				border-radius: 5px;
				-o-border-radius: 5px;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border: 1px solid #6E97C9;
				transition: all 0.30s ease-in-out;
				-o-transition: all 0.30s ease-in-out;
				-webkit-transition: all 0.30s ease-in-out;
				-moz-transition: all 0.30s ease-in-out;
				background: -moz-linear-gradient(#404855, #000);
				background: -ms-linear-gradient(#404855, #000);
				background: -o-linear-gradient(#404855, #000);
				background: -webkit-linear-gradient(#404855, #000);
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#404855', endColorstr='#000000');
			}
			#progress:hover{
				border: 1px solid #8BBBF4;
			}
				#bargroove{
					background: #282E36;
					margin-left: 10px;
					width: 500px;
					height: 10px;
					border-radius: 3px;
					-o-border-radius: 3px;
					-webkit-border-radius: 3px;
					-moz-border-radius: 3px;
					padding: 0;
					border: 1px groove #404855;
					float:left;
					line-height: 1px;
					font-size: 1px;
					display: inline;
				}
					#bar{
						float:left;
						background: #bedcff;
						margin: 0;
						width: 0%;
						height: 10px;
						line-height: 1px;
						font-size: 1px;
						border-radius: 3px;
						-o-border-radius: 3px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						transition: all 0.30s ease-in-out;
						-o-transition: all 0.30s ease-in-out;
						-webkit-transition: all 0.30s ease-in-out;
						-moz-transition: all 0.30s ease-in-out;
					}
					#bar.proc{
						box-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-o-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
					}
				#percent{
					width: 120px;
					float:right;
					color: #bedcff;
					font-size: 40px;
					line-height: 40px;
					height: 40px;
					text-align: right;
					margin-right: 10px;
					
					transition: all 0.30s ease-in-out;
					-o-transition: all 0.30s ease-in-out;
					-webkit-transition: all 0.30s ease-in-out;
					-moz-transition: all 0.30s ease-in-out;
				}
					#percent.proc{
						text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					}
				#current{
					float:left;
					color: #bedcff;
					margin: 10px;
					font-size: 12px;
					height: 15px;
					display: inline;
					width: 500px;
					overflow: hidden;
					text-overflow: ellipsis;
					white-space: nowrap;
				}
				#header{
					color: #bedcff;
					font-size: 40px;
					line-height: 40px;
					height: 40px;
					margin: 0 0 10px 10px;
					float: left;
					width: 170px;
					text-decoration:none;
					
					transition: all 0.30s ease-in-out;
					-o-transition: all 0.30s ease-in-out;
					-webkit-transition: all 0.30s ease-in-out;
					-moz-transition: all 0.30s ease-in-out;
				}
				#header:hover{
					text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
				}
				#indicator{
					font-size: 12px;
					line-height: 12px;
					height: 40px;
					margin: 0 10px 10px 0;
					float: right;
					width: 160px;
					text-align: right;
					font-weight: bold;
				}
				.red{
					color: #f00;
					text-decoration: blink;
				}
				.green{
					color: #0f0;
				}
				a.button{
					float: left;
					width: 30px;
					height: 30px;
					text-decoration: none;
					border: 1px solid #6E97C9;
					line-height: 26px;
					font-size: 30px;
					color: #bedcff;
					text-align: center;
					font-weight: bold;
					margin: 4px 0 0 10px;
					outline: 0;
					
					border-radius: 3px;
					-o-border-radius: 3px;
					-webkit-border-radius: 3px;
					-moz-border-radius: 3px;
					
					transition: all 0.30s ease-in-out;
					-o-transition: all 0.30s ease-in-out;
					-webkit-transition: all 0.30s ease-in-out;
					-moz-transition: all 0.30s ease-in-out;
					
					background: -moz-linear-gradient(#404855, #000);
					background: -ms-linear-gradient(#404855, #000);
					background: -o-linear-gradient(#404855, #000);
					background: -webkit-linear-gradient(#404855, #000);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#404855', endColorstr='#000000');
				}
				a.button:hover{
					text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					border: 1px solid #bedcff;
				}
				#footer{
					position: absolute;
					left: 50%;
					top: 50%;
					margin: 100px 0 0 -100px;
					font-size: 12px;
					background: #404855;
					border-radius: 3px;
					-o-border-radius: 3px;
					-webkit-border-radius: 3px;
					-moz-border-radius: 3px;
					padding: 5px;
					width: 200px;
					text-align: center;
				}
					#footer div{
						overflow:hidden;
					}
					#footer img{
						float: left;
						margin: 0 5px 5px 0;
					}
					#footer a{
						color: #bedcff;
						text-decoration: none;
					}
					#projectlink{
						font-size: 16px;
					}
					#footer a:hover{
						color: #fff;
					}
		#sfxversion{
			color: #bedcff;
		}
	</style>
	<script type="text/javascript">
		window.onload = function(){
				function getXmlHttp(){
				  var xmlhttp;
				  try {
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				  } catch (e) {
					try {
					  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (E) {
					  xmlhttp = false;
					}
				  }
				  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
					xmlhttp = new XMLHttpRequest();
				  }
				  return xmlhttp;
				}
				
				var START = document.getElementById('start');
				var PAUSE = document.getElementById('pause');
				var STOP = document.getElementById('stop');
				
				var PERCENT = document.getElementById('percent');
				var BAR = document.getElementById('bar');
				var CURRENT = document.getElementById('current');
				var INDICATOR = document.getElementById('indicator');
				
				var xmlhttp = getXmlHttp();
				var xmlhttpS = getXmlHttp();
				var timeout;
				var tm = 200;
				
				var STAGE = 0;
				var STATUS='stop';
				var STEP=0;
				var TITLE = document.title;
				
				
				START.onclick = function(event){
					if(STATUS!='start'){
						STATUS='start';
						indicator('Execute');
						if(STAGE==100) setStage(0); else setStage(STAGE);
						startRequest();
					}
					return stopClick(event);
				}
				STOP.onclick = function(event){
					stopProcess();
					return stopClick(event);
				}
				PAUSE.onclick = function(event){
					pauseProcess();
					return stopClick(event);
				}
				
				function pauseProcess(){
					if(STATUS!='stop'){
						STATUS='pause';
						indicator('Paused');
					}					
				}
				function stopProcess(){
					STATUS='stop';
					STEP=0;
					indicator('');
					setStage(0);
					xmlhttp.abort();
					xmlhttpS.open('GET', '?stop=1&r=' + new Date().getTime(), false);
					xmlhttpS.onreadystatechange = function(){}
					xmlhttpS.send(null);
					clearTimeout(timeout);
				}
				function stopClick(event){
					event = event || window.event;
					event.preventDefault ? event.preventDefault() : (event.returnValue=false);
					event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
					return false;
				}
				function startRequest(){
					if(STATUS=='start');
					xmlhttp.open('GET', '?step='+STEP+'&r=' + new Date().getTime(), true);
					xmlhttp.onreadystatechange = function(){
					  if(xmlhttp.readyState == 4){
						if(xmlhttp.status == 200){
							try{
								var jsonResponse = eval('(' + xmlhttp.responseText + ')');
								STEP = parseInt(jsonResponse.step);
								if(STATUS=='start' && !jsonResponse.error){
									var today=new Date();
									var h=checkTime(today.getHours());
									var m=checkTime(today.getMinutes());
									var s=checkTime(today.getSeconds());
									setStage(parseInt(jsonResponse.percent), h+':'+m+':'+s+' '+jsonResponse.operation);
									if(!jsonResponse.complete){
										timeout = setTimeout(function(){startRequest();}, tm);
									}else{
										indicator('Completed', true);
										CURRENT.innerHTML = 'Completed';
										BAR.className = '';
										PERCENT.className = '';
										STATUS='stop';
										STEP=0;
									}
								}else{
									if(jsonResponse.error) pauseProcess();
									STAGE=parseInt(jsonResponse.percent);
									CURRENT.innerHTML = jsonResponse.operation;
								}
								//if(jsonResponse.debug) console.log(jsonResponse.debug);
							}catch(e){
								stopProcess();
								CURRENT.innerHTML = 'Responce '+e.name+': '+e.message;
							}
						}
					  }
					}
					xmlhttp.send(null);
				}
				function setStage(s, t){
					STAGE = s;
					if(STAGE>100) STAGE=100;
					var p = STAGE+'%';
					document.title = TITLE+': '+p;
					PERCENT.innerHTML = p;
					BAR.style.width = p;
					if(t) CURRENT.innerHTML = t;
					if(STAGE==0 && STATUS!='start'){
						CURRENT.innerHTML = 'Ready';
						BAR.className = '';
						PERCENT.className = '';
						document.title = TITLE;
					}else{
						indicator('Execute');
						PERCENT.className = 'proc';
						BAR.className = 'proc';
					}
				}
				function indicator(s, c){
					if(c) INDICATOR.className = 'green'; else INDICATOR.className = 'red';
					INDICATOR.innerHTML = s;
				}
				function checkTime(i){
					return (i<10?("0"+i):i);
				}
		}
	</script>
  </head>
  <body>
    <div id="progress">
		<a href="#" class="button" style="font-size: 16px;line-height: 32px;" id="pause" title="PAUSE">ll</a>
		<a href="#" class="button" style="line-height: 30px;" id="stop" title="STOP">&#x25AA;</a>
		<a href="#" class="button" id="start" title="START">&#x25B8;</a>
		<a href="http://websfx.org/" target="_blank" id="header">WebSFX</a><span id="sfxversion"><?php echo $SFXversion; ?></span>
		<div id="indicator">
			
		</div>
		<div id="bargroove">
			<div id="bar"></div>
		</div>
		<div id="percent">
			0%
		</div>
		<div id="current">
			Ready
		</div>
	</div>
	<div id="footer">
		<div>
		<a href="http://websfx.org/" id="projectlink">&copy; 2012 WebSFX project</a>
		</div>
	</div>
  </body>
</html>
<?php
exit;
}
if(isset($_GET['stop'])){
	//process cancelling
	if(file_exists($tmpfile)) unlink($tmpfile);
	if(file_exists($instfile)) unlink($instfile);
	echo 1;
	exit;
}
//main variables
$strlenp	= strlen($packdir)+1;
$step		= intval($_GET['step']);
$complete	= false;
$result		= true;
$debug		= array();
$error		= false;
$custom		= 0;
$redirect	= 'index.php';
if($step==0){
	//directories scanning
	//this step repeats until target directory and all its children is scanned
	if(file_exists($tmpfile)){
		include $tmpfile;
		$stage++;
	}else{
		$stage=1;
		$files = $dirs = $nolist = array();
	}
	
	function scanDirs($d, &$files, &$dirs, &$nolist, $limit, $customdir, $instfile, $thisfile){
		//$d    = (str) target directory
		//$files= (array) list of scanned files
		//$dirs = (array) list of scanned dirs
		//$limit= (int) how much objects per one stage
		$first = false;
		if(!sizeof($dirs)){
			$first = true;
		}
		//returns (bool) scanning state
		$dn = $d.(!empty($d)?'/':'');
		$all = glob($dn.'*');
		$drs = glob($dn.'*', GLOB_ONLYDIR);
		
		$all = array_diff($all, $drs); //to separate files and directories
		
		//search 'websfxcustom' dir
		if($first){
			$del = array_search($dn.$customdir, $drs, true);
			if($del!==false){
				unset($drs[$del]);
			}
			$del = array_search($dn.$instfile, $all, true);
			if($del!==false){
				unset($all[$del]);
			}
			$del = array_search($dn.$thisfile, $all, true);
			if($del!==false){
				unset($all[$del]);
			}
		}
		
		$files= array_merge($all, $files);
		$dirs = array_merge($drs, $dirs);
		//--partial scan
		//new directory put in a "task list"
		//and remove from it after the scanning.
		$s = sizeof($drs);
		if($s){
			$nolist = array_merge(array_combine($drs, array_fill(0, $s, true)), $nolist);
		}
		if(isset($nolist[$d])){
			unset($nolist[$d]);
		}
		if((sizeof($files)+sizeof($dirs))>=$limit){
			return false;
		}
		//when limit is reached, function stops and returns false - the scaning must be continued.
		//--partial scan
		foreach($drs as $dir){
			if(!scanDirs($dir, $files, $dirs, $nolist, $limit, $customdir, $instfile, $thisfile)){
				return false;
			}
		}
		return true;
	}
	if($stage==1){
		file_put_contents($instfile,'<?php ob_start(); ?>');
		if(file_exists($packdir.'/'.$customdir)){
			//scann & compress websfxcustom directory
			function scanCustom($d, &$out){
				$dn  = $d.(!empty($d)?'/':'');
				$dirs = glob($dn.'*');
				foreach($dirs as $k=>$dir){
					if(is_dir($dir)){
						$drs = scanCustom($dir, $out);
						$dirs = array_merge($drs, $dirs);
					}else{
						$gz  = str_replace('<?', '--phplt--?', gzcompress(file_get_contents($dir), 6));
						$out.= strlen($dir).'/'.strlen($gz).':'.$dir.$gz; //!
						unset($dirs[$k]);
					}
				}
				return $dirs;
			}
			$out = '';
			$customdirs = scanCustom($packdir.'/'.$customdir, $out);
			$cdirs=str_replace('<?', '--phplt--?', gzcompress('$wsfxcdirs='.var_export($customdirs, true).';', 6));
			$cdout = strlen($cdirs).':'.$cdirs.$out;
			$custom = strlen($cdout);
			file_put_contents($instfile, $cdout, FILE_APPEND);
		}
		//on first scannibg stage embowel target directory
		$result = scanDirs($packdir, $files, $dirs, $nolist, $scanLimit, $customdir, $instfile, $thisfile);	
	}else{
		//on next stages scan directories from the "task list" only and cancel a cicle when limit is reached.
		foreach($nolist as $path=>$x){
			$result = scanDirs($path, $files, $dirs, $nolist, $stage*$scanLimit, $customdir, $instfile, $thisfile);
			if(!$result)
				break;
		}
	}
	file_put_contents($tmpfile,'<?php $stage='.$stage.'; $custom='.$custom.'; $nolist='.var_export($nolist, true).'; $dirs='.var_export($dirs, true).'; $files='.var_export($files, true).';');
	$operation = 'Scanning (found '.sizeof($files).' files in '.sizeof($dirs).' directories)';
	if(!$result){
		echo json_encode(array(
			'steps' => 0,
			'percent' => 0,
			'step' => 0,
			'complete' => false,
			'operation' => $operation,
			'debug' => $debug,
			'error' => $error,
		));
		exit;		
	}
	
}elseif($step==1){
	//archive creating
	include $tmpfile;
	//directories inserting
	$d=str_replace('<?', '--phplt--?', gzcompress('$this->dirs='.var_export($dirs, true).';', 6));
	file_put_contents($instfile, strlen($d).':'.$d, FILE_APPEND);
	$operation = 'Archive creating';
}else{
	//compress files
	include $tmpfile;
	for($i=($step-2)*$R; $i<($step-1)*$R; $i++){
		if(!isset($files[$i])) break;
		$filename=$files[$i];
		if($filename!=basename(__FILE__) && $filename!=$tmpfile){
			$content = file_get_contents($filename);
			if($content !== false){
				$fn = substr($filename, $strlenp);
				$gz = str_replace('<?', '--phplt--?', gzcompress($content, 6));
				file_put_contents($instfile, strlen($fn).'/'.strlen($gz).':'.$fn.':'.$gz, FILE_APPEND);
			}else{
				$error = $filename;
			}
		}
		$dirname = dirname($files[$i]);
	}
	if($error){
		$operation = 'Error packing file: '.$error.', press &#9658; to continue';
	}else{
		$operation = 'Compress: '.$dirname;
	}
}

$steps = ceil(sizeof($files)/$R+2);
$percent = $step*(100/$steps);
$step++;
if($step==$steps){
	//final step
	file_put_contents($instfile,'<?php
define("SFX_DS","'.addslashes(DIRECTORY_SEPARATOR).'");
define("SFX_VERSION","'.$SFXversion.'");
$gz = ob_get_clean();
error_reporting(0);
class websfx{
	protected $dirscount='.sizeof($dirs).'; 
	protected $filescount='.sizeof($files).';
	protected $strlenp='.$strlenp.';
	protected $custom='.$custom.';
	protected $dirslimit='.$dirslimit.';
	protected $fileslimit='.$fileslimit.';
	protected $stcount;
	protected $dirs;
	protected $ln;
	public    $step;
	public    $jsonout;
	public    $current;
	public	  $gz;
	public	  $redirect=\''.$redirect.'\';
	function __construct(&$gz){
		$this->ln = strlen($gz);
		$this->gz = $gz;
		$this->clearLog();
		set_error_handler(array($this, \'errorHandler\'));
		//statistic
		$this->stcount = (ceil($this->dirscount/$this->dirslimit)+ceil($this->filescount/$this->fileslimit));
		if(!isset($_GET[\'step\'])){
			if(isset($_GET[\'stop\'])){
				//temporary data deleting
				if(file_exists(\'websfxcustom\')) $this->rmdir(\'websfxcustom\');
				echo \'1\';
				//callback
				exit;
			}
		}
	}
	function launching(){
		if(!isset($_GET[\'step\'])){
			//preparation
			if(!isset($_GET[\'stop\'])){
				//is target directory writable?
				if(!is_writable(\'.\')){
					$this->displayView(\'Target directory &quot;\'.basename(dirname(__FILE__)).\'&quot; is not writable\');
					exit;
				}
				//uncompress interface if it exists
				if($this->custom){
					$pos = 0;
					$pre = 0;
					$itr = substr($this->gz, $pos, $this->custom);
					mkdir(\'websfxcustom\', 0777);
					while($pos<$this->custom){
						if($pre == 0){
							//interface directories: $wsfxcdirs
							$pos = strpos($itr, \':\', $pre);
							$siz = $this->getstr($itr,$pre,$pos);
							$pre = $pos+1;
							$pos = $pre+intval($siz);
							$this->jsonout[\'gzpoint\']=$pos;
							eval($this->ungzip($itr,$pre,$pos));
							sort($wsfxcdirs);
							foreach($wsfxcdirs as $wsfxcdir){
								$wsfxcdir = substr($wsfxcdir, $this->strlenp);
								mkdir(str_replace(SFX_DS, DIRECTORY_SEPARATOR, $wsfxcdir), 0777);
							}
						}else{
							//interface files
							$pos = strpos($itr, \':\', $pre);
							list($sizN, $sizC) = explode(\'/\', $this->getstr($itr,$pre,$pos));
							
							$pre = $pos+1;
							$pos = $pre+intval($sizN);
							$nam = str_replace(SFX_DS, DIRECTORY_SEPARATOR, $this->getstr($itr,$pre,$pos));
							
							$pre = $pos;
							$pos = $pre+intval($sizC);
							if(file_put_contents(substr($nam, $this->strlenp), $this->ungzip($itr,$pre,$pos))===false){
								$this->displayView(\'Customize error: can&apos;t uncompress &quot;\'.$nam.\'&quot;\');
								exit;
							}
						}
						$pre = $pos;
					}
				}
				//starting trigger
				$this->onBeforeExtractAll();
				return true;
			}
		}
		return false;
	}
	function process(){
		if(isset($_GET[\'step\'])){
			//uncompress executing
			$gz = $this->gz;
			$this->step = intval($_GET[\'step\'])+1;
			
			$this->jsonout[\'complete\']=false;
			$this->jsonout[\'process\']=\'f\';
			$this->jsonout[\'step\']=$this->step;
			$this->jsonout[\'percent\']=$this->step*(100/$this->stcount);
			
			$pre = $this->custom;
			//decode directories array
			if($_GET[\'process\']==\'d\'){
				$pos = strpos($gz, \':\', $pre);
				$siz = $this->getstr($gz,$pre,$pos);
				$pre = $pos+1;
				$pos = $pre+intval($siz);
				$this->jsonout[\'gzpoint\']=$pos;
				eval($this->ungzip($gz,$pre,$pos));
				sort($this->dirs);
				// 1) directories creating
				$this->jsonout[\'process\']=\'d\';
				if(sizeof($this->dirs)){
					for($i=($this->step-1)*$this->dirslimit; $i<($this->step*$this->dirslimit); $i++){
						$this->current = $this->dirs[$i];
						$dir = substr($this->dirs[$i], $this->strlenp);
						$this->onBeforeCreateDir($dir);
						mkdir(str_replace(SFX_DS, DIRECTORY_SEPARATOR, $dir), 0777);
						$this->onAfterCreateDir($dir);
						$this->jsonout[\'operation\']=\'Create directory: \'.$dir;
						if(!isset($this->dirs[($i+1)])){
							$this->jsonout[\'process\']=\'f\';
							break;
						}
					}
				}else{
					$this->jsonout[\'process\']=\'f\';
					$this->extractFiles($pos);
				}
			}else{
				// 2) files creating
				$this->extractFiles();
			}
			// 3) installer and temporary data deleting, final callbacks sending
			if($this->step==$this->stcount){
				//unlink(__FILE__);
				if($this->custom){
					$this->rmdir(\'websfxcustom\');
				}
				$this->onAfterExtractAll();
				$this->jsonout[\'complete\']=true;
			}
			$this->json();
		}
	}
	function extractFiles($pos=0){
		$gzpoint = $pos?$pos:(isset($_GET[\'gzpoint\'])?intval($_GET[\'gzpoint\']):0);
		$pre = $pos = $gzpoint+$this->custom;
		$pre = $pos = $gzpoint;
		if($this->filescount){
			for($i=($this->step-1)*$this->fileslimit; $i<($this->step*$this->fileslimit); $i++){
				$pos = strpos($this->gz, \':\', $pre);
				list($sizN, $sizC) = explode(\'/\', $this->getstr($this->gz,$pre,$pos));
				
				$pre = $pos+1;
				$pos = $pre+intval($sizN);
				$nam = str_replace(SFX_DS, DIRECTORY_SEPARATOR, $this->getstr($this->gz,$pre,$pos));
				$this->current = $nam;
				$pre = $pos+1;
				$pos = $pre+intval($sizC);
				$this->onBeforeExtractFile($nam, $sizC);
				$dn = dirname($nam);
				$this->jsonout[\'operation\']=\'Uncompress: \'.($dn==\'.\'?$nam:$dn);
				file_put_contents($nam, $this->ungzip($this->gz,$pre,$pos));
				$this->onAfterExtractFile($nam, $sizC);
				$pre = $pos;
				$this->jsonout[\'gzpoint\']=$pos;
				if($pos==$this->ln){
					return;
				}
			}
		}
	}
	function mkdir($dir){
		if(!file_exists($dir)) mkdir($dir, 0777);
	}
	function onBeforeExtractAll(){}
	function onAfterExtractAll(){}
	function onBeforeCreateDir(){}
	function onAfterCreateDir(){}
	function onBeforeExtractFile(){}
	function onAfterExtractFile(){}
	function errorHandler($errno, $errstr, $errfile, $errline){
		file_put_contents(\'websfxerror.log.txt\',\'Date: \'.date(\'d.m.Y H:i:s\').\', line: \'.$errline.\', file: \'.$errfile.\', step: \'.$this->step.\', object: \'.$this->current."\n".\'========\'."\n".strip_tags($errstr)."\n".\'========\'."\n", FILE_APPEND);
		$this->jsonout[\'error\']=\'PHP error: <a href="websfxerror.log.txt" target="_blank">See log</a>\';
	}
	function clearLog(){
		if(file_exists(\'websfxerror.log.txt\')) unlink(\'websfxerror.log.txt\');
	}
	function displayView($message=false){
		ob_start();
		$this->view($message);
		$HTML = ob_get_clean();
		echo str_replace(\'</body>\', \'	<div id="footer">
		<div>
		<a href="http://websfx.org/" id="projectlink">&copy; 2012 WebSFX project</a>
		</div>
	</div></body>\', $HTML);
	}
	function view($message=false){
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<title>WebSFX</title>
		<style type="text/css">
			body{
				font-family: Sans-serif;
				background: #5c7896;
			}
				#progress{
					position: absolute;
					left: 50%;
					top: 50%;
					margin: -50px 0 0 -325px;
					/*margin: 250px auto;*/
					width: 650px;
					height: 100px;
					background: #404855;
					padding-top: 10px;
					border-radius: 5px;
					-o-border-radius: 5px;
					-webkit-border-radius: 5px;
					-moz-border-radius: 5px;
					border: 1px solid #6E97C9;
					transition: all 0.30s ease-in-out;
					-o-transition: all 0.30s ease-in-out;
					-webkit-transition: all 0.30s ease-in-out;
					-moz-transition: all 0.30s ease-in-out;
					background: -moz-linear-gradient(#404855, #000);
					background: -ms-linear-gradient(#404855, #000);
					background: -o-linear-gradient(#404855, #000);
					background: -webkit-linear-gradient(#404855, #000);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#404855\', endColorstr=\'#000000\');
				}
				#progress:hover{
					border: 1px solid #8BBBF4;
				}
					#bargroove{
						background: #282E36;
						margin-left: 10px;
						width: 500px;
						height: 10px;
						border-radius: 3px;
						-o-border-radius: 3px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						padding: 0;
						border: 1px groove #404855;
						float:left;
						line-height: 1px;
						font-size: 1px;
						display: inline;
					}
						#bar{
							float:left;
							background: #bedcff;
							margin: 0;
							width: 0%;
							height: 10px;
							line-height: 1px;
							font-size: 1px;
							border-radius: 3px;
							-o-border-radius: 3px;
							-webkit-border-radius: 3px;
							-moz-border-radius: 3px;
							transition: all 0.30s ease-in-out;
							-o-transition: all 0.30s ease-in-out;
							-webkit-transition: all 0.30s ease-in-out;
							-moz-transition: all 0.30s ease-in-out;
						}
						#bar.proc{
							box-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-o-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
						}
					#percent{
						width: 120px;
						float:right;
						color: #bedcff;
						font-size: 40px;
						line-height: 40px;
						height: 40px;
						text-align: right;
						margin-right: 10px;
						
						transition: all 0.30s ease-in-out;
						-o-transition: all 0.30s ease-in-out;
						-webkit-transition: all 0.30s ease-in-out;
						-moz-transition: all 0.30s ease-in-out;
					}
						#percent.proc{
							text-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
							-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						}
					#current{
						float:left;
						color: #bedcff;
						margin: 10px;
						font-size: 12px;
						height: 15px;
						display: inline;
						width: 500px;
						overflow: hidden;
						text-overflow: ellipsis;
						white-space: nowrap;
					}
					#current a{
						color: #bedcff;
					}
					#header{
						color: #bedcff;
						font-size: 40px;
						line-height: 40px;
						height: 40px;
						margin: 0 0 10px 10px;
						float: left;
						width: 170px;
						text-decoration:none;
						
						transition: all 0.30s ease-in-out;
						-o-transition: all 0.30s ease-in-out;
						-webkit-transition: all 0.30s ease-in-out;
						-moz-transition: all 0.30s ease-in-out;
					}
					#header:hover{
						text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
					}
					#indicator{
						font-size: 12px;
						line-height: 12px;
						height: 40px;
						margin: 0 10px 10px 0;
						float: right;
						width: 160px;
						text-align: right;
						font-weight: bold;
					}
					.red{
						color: #f00;
						text-decoration: blink;
					}
					.green{
						color: #0f0;
					}
					a.button{
						float: left;
						width: 30px;
						height: 30px;
						text-decoration: none;
						border: 1px solid #6E97C9;
						line-height: 26px;
						font-size: 30px;
						color: #bedcff;
						text-align: center;
						font-weight: bold;
						margin: 4px 0 0 10px;
						
						border-radius: 3px;
						-o-border-radius: 3px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						
						transition: all 0.30s ease-in-out;
						-o-transition: all 0.30s ease-in-out;
						-webkit-transition: all 0.30s ease-in-out;
						-moz-transition: all 0.30s ease-in-out;
						
						background: -moz-linear-gradient(#404855, #000);
						background: -ms-linear-gradient(#404855, #000);
						background: -o-linear-gradient(#404855, #000);
						background: -webkit-linear-gradient(#404855, #000);
						filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#404855\', endColorstr=\'#000000\');
					}
					a.button:hover{
						text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-o-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-webkit-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						-moz-text-shadow: 0 0 5px rgba(81, 203, 238, 1);
						border: 1px solid #bedcff;
					}
					#footer{
						position: absolute;
						left: 50%;
						top: 50%;
						margin: 100px 0 0 -100px;
						font-size: 12px;
						background: #404855;
						border-radius: 3px;
						-o-border-radius: 3px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						padding: 5px;
						width: 200px;
						text-align: center;
					}
						#footer div{
							overflow:hidden;
						}
						#footer img{
							float: left;
							margin: 0 5px 5px 0;
						}
						#footer a{
							color: #bedcff;
							text-decoration: none;
						}
						#projectlink{
							font-size: 16px;
						}
						#footer a:hover{
							color: #fff;
						}
					#sfxversion{
						color: #bedcff;
					}
		</style>
		<?php 
			if(!$message){
				$this->javascript();
			}
		?>
	  </head>
	  <body>
	<?php if($message){ ?>
		<?php echo $message; ?>
	<?php }else{ ?>
		<div id="progress">
		<a href="#" class="button" style="font-size: 16px;line-height: 32px;" id="pause" title="PAUSE">ll</a>
		<a href="#" class="button" style="line-height: 30px;" id="stop" title="STOP">&#x25AA;</a>
		<a href="#" class="button" id="start" title="START">&#x25B8;</a>
		<a href="http://websfx.org/" target="_blank" id="header">WebSFX</a>
		<span id="sfxversion"><?php echo SFX_VERSION; ?></span>
		<div id="indicator">
			
		</div>
		<div id="bargroove">
			<div id="bar"></div>
		</div>
		<div id="percent">
			0%
		</div>
		<div id="current">
			Ready
		</div>
		</div>
	<?php } ?>
	  </body>
	</html><?php
	}
	function javascript(){ ?>
			<script type="text/javascript">
			window.onload = function(){
					function getXmlHttp(){
					  var xmlhttp;
					  try {
						xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
					  } catch (e) {
						try {
						  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (E) {
						  xmlhttp = false;
						}
					  }
					  if (!xmlhttp && typeof XMLHttpRequest!=\'undefined\') {
						xmlhttp = new XMLHttpRequest();
					  }
					  return xmlhttp;
					}
					
					var START = document.getElementById(\'start\');
					var PAUSE = document.getElementById(\'pause\');
					var STOP = document.getElementById(\'stop\');
					
					var PERCENT = document.getElementById(\'percent\');
					var BAR = document.getElementById(\'bar\');
					var CURRENT = document.getElementById(\'current\');
					var INDICATOR = document.getElementById(\'indicator\');
					
					var xmlhttp = getXmlHttp();
					var xmlhttpS = getXmlHttp();
					var timeout;
					var tm = 200;
					
					var STAGE = 0;
					var STATUS=\'stop\';
					var STEP=0;
					var TITLE = document.title;
					var PROCESS = \'d\';
					var GZPOINT = 0;
					
					START.onclick = function(event){
						if(STATUS!=\'start\'){
							STATUS=\'start\';
							indicator(\'Execute\');
							if(STAGE==100){
								setStage(0);
							}else{
								setStage(STAGE);
							}
							startRequest();
						}
						return stopClick(event);
					}
					STOP.onclick = function(event){
						stopProcess();
						return stopClick(event);
					}
					PAUSE.onclick = function(event){
						pauseProcess();
						return stopClick(event);
					}
					
					function pauseProcess(){
						if(STATUS!=\'stop\'){
							STATUS=\'pause\';
							indicator(\'Paused\');
						}					
					}
					function stopProcess(){
						STATUS=\'stop\';
						STEP=0;
						PROCESS = \'d\';
						GZPOINT = 0;
						indicator(\'\');
						setStage(0);
						xmlhttp.abort();
						xmlhttpS.open(\'GET\', \'installer.php?stop=1&r=\' + new Date().getTime(), false);
						xmlhttpS.onreadystatechange = function(){}
						xmlhttpS.send(null);
						clearTimeout(timeout);
						START.style.display = "none";
						STOP.style.display = "none";
						PAUSE.style.display = "none";
						CURRENT.innerHTML = \'<a href="javascript:document.location=document.location">Refresh page</a> to resume process.\';
					}
					function stopClick(event){
						event = event || window.event;
						event.preventDefault ? event.preventDefault() : (event.returnValue=false);
						event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
						return false;
					}
					function startRequest(){
						if(STATUS==\'start\');
						xmlhttp.open(\'GET\', \'installer.php?step=\'+STEP+\'&process=\'+PROCESS+\'&gzpoint=\'+GZPOINT+\'&r=\'+ new Date().getTime(), true);
						xmlhttp.onreadystatechange = function(){
						  if(xmlhttp.readyState == 4){
							if(xmlhttp.status == 200){
								try{
									var jsonResponse = eval(\'(\' + xmlhttp.responseText + \')\');
									STEP = parseInt(jsonResponse.step);
									if(STATUS==\'start\' && !jsonResponse.error){
										var today=new Date();
										var h=checkTime(today.getHours());
										var m=checkTime(today.getMinutes());
										var s=checkTime(today.getSeconds());
										setStage(parseInt(jsonResponse.percent), h+\':\'+m+\':\'+s+\' \'+jsonResponse.operation);
										PROCESS = jsonResponse.process;
										GZPOINT = jsonResponse.gzpoint;
										if(!jsonResponse.complete){
											timeout = setTimeout(function(){startRequest();}, tm);
										}else{
											indicator(\'Completed\', true);
											CURRENT.innerHTML = \'Completed\';
											BAR.className = \'\';
											PERCENT.className = \'\';
											STATUS=\'stop\';
											STEP=0;
											PROCESS = \'d\';
											GZPOINT = 0;
											<?php echo ($this->redirect?"document.location=\'".$this->redirect."\';":\'\') ?>
										}
									}else{
										if(jsonResponse.error){
											CURRENT.innerHTML = jsonResponse.error;
											pauseProcess();
										}
										STAGE=parseInt(jsonResponse.percent);
										PROCESS = jsonResponse.process;
										GZPOINT = jsonResponse.gzpoint;
									}
									if(jsonResponse.debug) console.log(jsonResponse.debug);
								}catch(e){
									stopProcess();
									CURRENT.innerHTML = \'Responce \'+e.name+\': \'+e.message;
								}
							}
						  }
						}
						xmlhttp.send(null);
					}
					function setStage(s, t){
						STAGE = s;
						if(STAGE>100) STAGE=100;
						var p = STAGE+\'%\';
						document.title = TITLE+\': \'+p;
						PERCENT.innerHTML = p;
						BAR.style.width = p;
						if(t) CURRENT.innerHTML = t;
						if(STAGE==0 && STATUS!=\'start\'){
							CURRENT.innerHTML = \'Ready\';
							BAR.className = \'\';
							PERCENT.className = \'\';
							document.title = TITLE;
						}else{
							indicator(\'Execute\');
							PERCENT.className = \'proc\';
							BAR.className = \'proc\';
						}
					}
					function indicator(s, c){
						if(c) INDICATOR.className = \'green\'; else INDICATOR.className = \'red\';
						INDICATOR.innerHTML = s;
					}
					function checkTime(i){
						return (i<10?("0"+i):i);
					}
			}
		</script>
	<?php
	}
	function json(){
		echo json_encode($this->jsonout);
	}
	private function rmdir($dir){
	  $list=glob($dir.\'*\',GLOB_MARK);
	  foreach($list as $item){
		if(is_dir($item)){
		  $this->rmdir($item);
		}else{
			unlink($item);
		}
	  }
	  if(is_dir($dir)) rmdir($dir);
	}
	private function ungzip(&$gz,$pre,$pos){
		return gzuncompress(str_replace(\'--phplt--?\',\'<?\', $this->getstr($gz,$pre,$pos)));
	}
	private function getstr(&$gz,$pre,$pos){
		return substr($gz,$pre,($pos-$pre));
	}
}
//customize first
$websfx = new websfx($gz);
$res = $websfx->launching();
if(file_exists(\'websfxcustom/class.php\')){
	include \'websfxcustom/class.php\';
}
//other operations
if(!class_exists(\'myWebsfx\')){
	class myWebsfx extends websfx{}
}
$myWebsfx = new myWebsfx($gz);

if($res){
	$myWebsfx->displayView();
}
$myWebsfx->process();', FILE_APPEND);
	unlink($tmpfile);
	$operation = 'Finalize';
	$complete = true;
	$percent = 100;
}
echo json_encode(array(
	'steps' => $steps,
	'percent' => $percent,
	'step' => $step,
	'complete' => $complete,
	'operation' => $operation,
	'debug' => $debug,
	'error' => $error,
));