#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#漢字コード


$html_top = "./set_top.html";
$html_this = "./topic_menu.html";
$msg = '';


&parseInput($g_encoding);


## パスワード読み込み
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# パスワードファイル名、セキュリティ確保のため変更(～2010/2/24）
$passwd = <PASS>;
close PASS;


## パスワードチェック
if($in{pass} ne $passwd){
	$DispTopData = '認証エラーが発生しました。管理画面の入室からやりなおしてください。';
	&Disp_Html($html_top);
	exit;
}


## 入力画面表示
$DispTopData .= &Get_File($html_this,1);
&Disp_Html($html_top);
exit;



