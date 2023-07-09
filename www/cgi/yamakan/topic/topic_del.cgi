#!/usr/bin/perl


# require '../authen/authen_cnf.pl';
require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';


$g_encoding = 'sjis';		#漢字コード

$html_msg = './topic_msg.html';
$html_msg2 = './topic_msg2.html';

## パスワード読み込み
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


#入力受付
&parseInput($g_encoding);
if ($method eq 'GET') { &exitError("ＧＥＴメソッドは許可されていません。");}


# AuthenData取得
# $id = $in{'authe_id'};
# my $adata = &FileReadMatch($AuthenDataFile,$id,1);
# &Split_AuthenData($adata);


#if($in{'authe_pwd'} ne $Authen{pwd}){
#	$msg = "ＩＤまたはパスワードが間違っています。";
#	&Disp_Html($html_msg2);
#	exit;
#}

## パスワード読み込み
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


## パスワードチェック
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '認証エラーが発生しました。管理画面の入室からやりなおしてください。';
	&Disp_Html('./set_top.html');
	exit;
}

#if( ($Authen{group} ne 'admin') and (index($in{'fnamer'},$in{'authe_id'}) == -1) ){
#	$msg = "削除権限がありません。";
#	&Disp_Html($html_msg2);
#	exit;
#}


open (FH,$in{'fnamer'});
	$readdata = <FH>;
close(FH);
&Split_InstiData($readdata);


unlink $in{'fnamer'};



$msg = '記事番号【' . $kjno . '】表示タイトル【' . $tpname . '】のデータを削除しました。';
$DispTopData .= &Get_File($html_msg,1);
&Disp_Html('./set_top.html');
exit;

