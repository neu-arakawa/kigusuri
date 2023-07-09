#!/usr/bin/perl

require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#漢字コード


$html_top = "./set_top.html";
$html_this = "./topic_write.html";
$msg = '';


# 入力受付 -- ファイルＵＰバージョン
# ($FU_flag,$FU_msg,$Buffer,$Savefile,$Savefilecnt,$p_fileName,$p_fileBody) = &FileUpload::receive($Alimit,$Elimit,$Allow,$Savef);
# &parseInput($g_encoding,$Buffer);
&parseInput($g_encoding);

## パスワード読み込み
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
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
#	$sel_acflg[$in{acflg}] = 'selected';
	$DispTopData .= &Get_File($html_this,1);
	&Disp_Html($html_top);
	exit;
}



# 入力内容のキャスト
#if($in{'styy'}){ $in{'styy'} = sprintf("%04d",$in{'styy'}); }
#if($in{'stmm'}){ $in{'stmm'} = sprintf("%02d",$in{'stmm'}); }
#if($in{'stdd'}){ $in{'stdd'} = sprintf("%02d",$in{'stdd'}); }
#if($in{'edyy'}){ $in{'edyy'} = sprintf("%04d",$in{'edyy'}); }
#if($in{'edmm'}){ $in{'edmm'} = sprintf("%02d",$in{'edmm'}); }
#if($in{'eddd'}){ $in{'eddd'} = sprintf("%02d",$in{'eddd'}); }
#if($in{'msyy'}){ $in{'msyy'} = sprintf("%04d",$in{'msyy'}); }
#if($in{'msmm'}){ $in{'msmm'} = sprintf("%02d",$in{'msmm'}); }
#if($in{'msdd'}){ $in{'msdd'} = sprintf("%02d",$in{'msdd'}); }
#if($in{'meyy'}){ $in{'meyy'} = sprintf("%04d",$in{'meyy'}); }
#if($in{'memm'}){ $in{'memm'} = sprintf("%02d",$in{'memm'}); }
#if($in{'medd'}){ $in{'medd'} = sprintf("%02d",$in{'medd'}); }
#if($in{'acyy'}){ $in{'acyy'} = sprintf("%04d",$in{'acyy'}); }
#if($in{'acmm'}){ $in{'acmm'} = sprintf("%02d",$in{'acmm'}); }
#if($in{'acdd'}){ $in{'acdd'} = sprintf("%02d",$in{'acdd'}); }



# イベント情報書き込みデータ作成
unless($in{'kjno'}){ $kjno = &Get_Kjno(); }
               else{ $kjno = $in{'kjno'}; }
unless($in{'crdy'}){ $crdy = &Get_Time(); }
               else{ $crdy = $in{'crdy'}; }
my $topicdata = &Join_InstiData();



# AuthenData取得
# $id = $in{'authe_id'};
# my $adata = &FileReadMatch($AuthenDataFile,$id,1);
# &Split_AuthenData($adata);

# ($temp,$fid) = split('-',$in{'fnamer'});
# $fid =~ s/.df//;


# 情報入力者からモードを決定、処理設定を行う --- 特に意味ナシか？
my $filename = '';
#if($Authen{'group'} eq 'admin'){
#	$MODE = 'root';
#	$filename = $EDDir . "$kjno-$in{'authe_id'}.df";
	$filename = $EDDir . "$kjno.df";
	$html_msg = "./topic_msg.html";
	$html_comp = "./topic_comp.html"; 
#}
	
#else{
#	## -- 管理者以外
#	$MODE = 'admin';
#	$filename = $EDDir . "$kjno-$in{'authe_id'}.df";
#	$filename = $EDDir . "$kjno-$fid.df";
#	$topic_request_mf = 'topic_write_mailform.txt';
#	$html_msg = "./topic_msg.html";
#	$html_comp = "./topic_comp.html"; }
	





# イベント情報書き込み
if( index($in{'fnamer'},$EDDir) != -1 ){ unlink $in{'fnamer'}; }
&openLock(FH,">$filename") or $msg = 'ファイルの書き込みに失敗しました。時間を空けてやりなおしてください。';
	print FH "$topicdata\n";
&closeUnlock(FH,$filename);
chmod 0777,$filename;
if($msg){ &Disp_Html($html_this); exit;}
if( index($in{'fnamer'},$EDTDir) != -1 ){ unlink $in{'fnamer'}; }
if( index($in{'fnamer'},$EDSDir) != -1 ){ unlink $in{'fnamer'}; }




#処理終了画面表示
#open(TEMP,"$html_comp");
#	while (<TEMP>) {
#    	s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
#        $msg .= $_;
#    }
#close(TEMP);
# &Disp_Html($html_msg);

$DispTopData .= &Get_File($html_comp,1);
&Disp_Html($html_top);
exit;

