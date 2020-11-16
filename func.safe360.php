<?php
/**
 * @filesource Code By Safe3 Form 360.cn
 * @copyright HelloHT
 * @since 2012.09.06
 * @author HelloHT Changed
 */

function customError($errno, $errstr, $errfile, $errline){
	echo "<b>Error number:</b> [$errno],error on line $errline in $errfile<br />";
	die();
}

set_error_handler("customError",E_ERROR);

$getfilter = "'|(and|or)\\b.+?(>|<|=|in|like|sleep)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b|sleep)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b|sleep)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
 
//$ArrPGC = array_merge($_GET,$_POST,$_COOKIE);
/** 过滤GET、POST、COOKIE字符串 */
foreach($_GET as $key=>$value){
	$value = GetClearVal($value);
	$_GET[$key] = $value;
	
	StopAttack($key,$value,$getfilter);
}
foreach($_POST as $key=>$value){
	$value = GetClearVal($value);
	$_POST[$key] = $value;
	
	StopAttack($key,$value,$postfilter);
}
foreach($_COOKIE as $key=>$value){
	$value = GetClearVal($value);
	$_COOKIE[$key] = $value;
	
	StopAttack($key,$value,$cookiefilter);
}

if (file_exists('update360.php')) {
	echo "请重命名文件update360.php，防止黑客利用<br/>" ;
    die();
}

/**
 * 获取干净的HTML字符串
 */
function GetClearVal($value){
	$value = !is_array($value) ? htmlspecialchars($value) : $value;
	
	return $value;
}

/**
 * 过滤字符串
 */
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){
	if(is_array($StrFiltValue)){
	    $StrFiltValue = implode($StrFiltValue);
	}  
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
		//write_safe_logs("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
		print "360websec notice:Illegal operation!";
		exit();
	}
} 

/**
 * 写安全日志
 */
function write_safe_logs($logs){
	$toppath = $_SERVER["DOCUMENT_ROOT"] . "/log.htm" ;
	$filestream = fopen($toppath, "a+") ;
	fputs($filestream, $logs."\r\n") ;
	fclose($filestream) ;
}
?>