#!/usr/bin/perl


require './lib/jcode.pl';
require './ci_util.pl';
require './file_upload.pl';


# ファイルアップロード初期設定
# --- 一度でアップする上限
$Alimit = 150 * 1024;
# --- 個別ファイル上限
$Elimit = 150 * 1024;
# --- アップ許可拡張子
$Allow  = 'jpeg,jpg';
# --- セーブタイプ  
#     -ow：アップロードされたファイルのファイル名を使用
#　　 -tm：アップロードの際の日時をファイル名に使用
#　　 -fn：フォームのname=で指定された文字をファイルに使用
$Savef  = '-ow';


# ディレクトリ上限容量のデフォルト値
# 5000KB
$Dlimit = 5000 * 1024;
# 対象領域
$Dir = './pic/';




$wrtopHtml  = 'write_top.html';
$g_encoding = 'sjis';	#漢字コード

$msg = '';
$err = '';


#入力受付 -- ファイルＵＰバージョン
($FU_flag,$FU_msg,$Buffer,$Savefile,$Savefilecnt,$p_fileName,$p_fileBody) = &FileUpload::receive($Alimit,$Elimit,$Allow,$Savef);
&parseInput($g_encoding,$Buffer);

$pass = $in{'pass'};

&Upload();
&Msg_View();
exit;



#------------------------------------------------
#  
#  ファイルのアップロード
#  
#------------------------------------------------
sub Upload {
	
	## エラーフラグがあったらエラー表示
	if($FU_flag eq 'f'){ ($msg = $FU_msg) =~ s/\t/<br>/g; return; }
	
	
	## 使用可能領域設定
	($DirCapa,$DirRCapa) = &Get_DirCapa('B',$Dir,$Dlimit);
	$Alimit = $DirRCapa;
	
	
	## ファイルのアップロード
	($flg,$msg) = &FileUpload::write($Dir,$Alimit,$Savefilecnt,$p_fileName,$p_fileBody);
	
	
	## エラー表示
	if($flg){ return; }
	
	
	## アップするファイルが１つもなければエラー表示
	if($flg eq '' and $msg <= 0){ $msg = '画像ファイルを指定してください。'; return; }
	
	$msg = '画像を追加しました。';
#	$msg .= "$in{authe_id} -- $Dir -- $Alimit";
	
}








#------------------------------------------------
#  
#  メッセージの表示
#  
#------------------------------------------------
sub Msg_View {
	
	$DispTopData = '';
	$msg = '<br><b>' . $msg  . '</b></br>';
	&Disp_Html($wrtopHtml,1);
	exit;
	
}





