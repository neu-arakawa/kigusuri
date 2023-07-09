#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';


$g_encoding = 'sjis';		#漢字コード

$html_msg = './topic_msg.html';
$html_msg2 = './topic_msg2.html';

## パスワード読み込み
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# パスワードファイル名、セキュリティ確保のため変更(～2010/2/24）
$passwd = <PASS>;
close PASS;


#入力受付
&parseInput($g_encoding);
if ($method eq 'GET') { &exitError("ＧＥＴメソッドは許可されていません。");}


## パスワード読み込み
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# パスワードファイル名、セキュリティ確保のため変更(～2010/2/24）
$passwd = <PASS>;
close PASS;


## パスワードチェック
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '認証エラーが発生しました。管理画面の入室からやりなおしてください。';
	&Disp_Html('./set_top.html');
	exit;
}


open (FH,$in{'fnamer'});
	$readdata = <FH>;
close(FH);
&Split_InstiData($readdata);


unlink $in{'fnamer'};



$msg = '記事番号【' . $kjno . '】表示タイトル【' . $tpname . '】のデータを削除しました。';
$DispTopData .= &Get_File($html_msg,1);
&Disp_Html('./set_top.html');
exit;

