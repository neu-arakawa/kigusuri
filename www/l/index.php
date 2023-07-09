<?php
/*************************************************
 *
 * url短縮システム 
 * KazumaNishihata [to-R]
 * http://blog.webcreativepark.net
 *
 ************************************************/

/*設定する所 管理者パスワード*/
$password = "ky46y";

//.htaccessによるwrite処理を行わない場合はコメントアウト
$htaccess = "on";

/*設定する所 ログファイル*/
$logfile = "log.csv.php";
define(THISFILE,basename($_SERVER['SCRIPT_NAME']));//このファイル名を取得
define(LOGFILE,$logfile);

//url転送
if($_GET['u']){
	if(file_exists(LOGFILE)){
		$fp = fopen(LOGFILE,'r');
		if($fp){
			flock($fp,2);
			while($line = fgetcsv($fp,20000)){
				if($line['1']==$_GET['u']){
					$url = $line['0'];
					break;
				}
			}
			flock($fp,3);
		}
		fclose($fp);
	}
	if($url){
		header("Location:".$url);
	}else{
		header("HTTP/1.0 404 Not Found");
		print "This url is not found";
	}
	exit;
}

switch($_GET[mode]){
	//ログイン画面
	default :
		$html = "
		<form action='?mode=login_check' method='post'>
			パスワード<input type='password' name='pass' /><input type='submit' value='送信' />
		</form>";
		break;
	
	//ログイン認証
	case "login_check";
		$html = login($password);
		break;
	
	//管理画面
	case "main" :
		
		if($_POST['url'])$html= "<h2>".tinyURL($htaccess)."</h2>";
		
		$html.= "
		<form action='?mode=main' method='post'>
			短縮したいURL<input type='text' name='url' value='".$_POST['url']."' class='url' /><input type='submit' value='短縮' />
		</form>";
		break;
		
}

//ログイン認証
function login($password){
	if(!$_POST[pass]){
		$html = "<p class='input'>パスワードを入力してください";
	}elseif($_POST[pass]!=$password){
		$html = "<p class='input'>パスワードが異なります";
	}else{
		setcookie("login_check","ok",time()+12*60*60);
		header("Location:?mode=main");
	}
	$html .= "<a href='".THISFILE."'>[再入力]</a></p>";
	return $html;
}

function tinyURL($htaccess="off"){
	//ログファイルの検索
	$nl = "
";
	$i=1;
	if(file_exists(LOGFILE)){
		$fp = fopen(LOGFILE,'r');
		if($fp){
			flock($fp,2);
			while($line = fgetcsv($fp,20000)){
				if($line['0']==$_POST['url']){
					return "http://".$_SERVER['SERVER_NAME'].(($htaccess=="on")?str_replace(THISFILE,$i,$_SERVER['PHP_SELF']):$_SERVER['PHP_SELF']."?u=".$i);
					exit;
				}
				$add_csv.= '"'.$line['0'].'","'.$line['1'].'"'.$nl;
				$i++;
			}
			flock($fp,3);
		}
		fclose($fp);
		$fp = fopen(LOGFILE,'w');
	}else{
		$fp = fopen(LOGFILE,'a');
	}
	if($fp){
		flock($fp,2);
		$add_csv.= '"'.$_POST['url'].'","'.$i.'"'.$nl;
		fputs( $fp,$add_csv);
		flock($fp,3);
	}
	fclose($fp);
	return "http://".$_SERVER['SERVER_NAME'].(($htaccess=="on")?str_replace(THISFILE,$i,$_SERVER['PHP_SELF']):$_SERVER['PHP_SELF']."?u=".$i);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>URL短縮システム</title>
	<style type="text/css">
	body{
		margin:0;
		padding:0;
		background:#7CADB6;
		text-align:center;
	}
	h1{
		color:white;
		font-size:large;
		margin-left:12px;
		text-align:left;
	}
	h2{
		background:white;
		padding:1em;
	}
	form{
		margin:3em;
		color:white;
	}
	input.url{
		width:300px;
	}
	address{
		padding-top:3em;
		font-style:normal;
		color:white;
	}
	address a{
		color:white;
		text-decoration:none;
	}
	</style>
</head>
<body>
	<h1>URL短縮システム</h1>
	<?= $html ?>
	<address>powerd by <a href="http://blog.webcreativepark.net" target='_blank'>to-R</a></address>
</body>
</html>