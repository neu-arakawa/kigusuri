#!/usr/bin/perl

require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#漢字コード


$html_top = "./set_top.html";
$html_this = "./topic_write.html";
$msg = '';


&parseInput($g_encoding);

## パスワード読み込み
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# パスワードファイル名、セキュリティ確保のため変更(～2010/2/24）
$passwd = <PASS>;
close PASS;


## パスワードチェック
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '認証エラーが発生しました。管理画面の入室からやりなおしてください。';
	&Disp_Html($html_top);
	exit;
}



## 入力画面表示
if($in{'mode'} eq 'input'){ 
							$DispTopData .= &Get_File($html_this,1);
							&Disp_Html($html_top);
							exit;
}





# 入力チェック
$msg = &Chk_Input(*cf_requesthtml);
if($msg ne ''){
	$in{'tptext'}  = Unescape($in{'tptext'});
	$DispTopData .= &Get_File($html_this,1);
	&Disp_Html($html_top);
	exit;
}




# 情報書き込みデータ作成
unless($in{'kjno'}){ $kjno = &Get_Kjno(); }
               else{ $kjno = $in{'kjno'}; }
unless($in{'crdy'}){ $crdy = &Get_Time(); }
               else{ $crdy = $in{'crdy'}; }
my $topicdata = &Join_InstiData();




my $filename = '';
$filename = $EDDir . "$kjno.df";
$html_msg = "./topic_msg.html";
$html_comp = "./topic_comp.html"; 



# 情報書き込み
if( index($in{'fnamer'},$EDDir) != -1 ){ unlink $in{'fnamer'}; }
&openLock(FH,">$filename") or $msg = 'ファイルの書き込みに失敗しました。時間を空けてやりなおしてください。';
	print FH "$topicdata\n";
&closeUnlock(FH,$filename);
chmod 0777,$filename;
if($msg){ &Disp_Html($html_this); exit;}
if( index($in{'fnamer'},$EDTDir) != -1 ){ unlink $in{'fnamer'}; }
if( index($in{'fnamer'},$EDSDir) != -1 ){ unlink $in{'fnamer'}; }




$DispTopData .= &Get_File($html_comp,1);
&Disp_Html($html_top);
exit;

