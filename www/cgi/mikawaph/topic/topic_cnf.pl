$selfURL  = $ENV{'SCRIPT_NAME'};
$selfURL  =~ /^(.*)\/[^\/]*$/;
$selfDIR  = $1;
$baseURL  = "http://$ENV{'HTTP_HOST'}";


## 使用サイトTOPページURL
$homeURL = "http://www.okayama-sports.jp";


## nkf 設定
$nkfmail = '/usr/local/bin/nkf -j';
$nkfhtml = '/usr/local/bin/nkf -e';


## Sendmail 設定
$sendmail = '/usr/sbin/sendmail';


## スプリット文字
$SPC = ',';
$SPCE = '，';


## 管理者メールアドレス
$MASTER_MAIL = 'info@kigusuri.com';


## 記事ＮＯ．ファイル
$KJNO_SEQr = './kjno_seq2';
$KJNO_SEQw = './kjno_seq1';


## 掲載以来ＮＯ.ファイル
$IRNO_SEQr = './irno_seq2';
$IRNO_SEQw = './irno_seq1';



## 仮用 データファイル ディレクトリ
$EDTDir = "./topicdatatemp/";
## 公開用 データファイル ディレクトリ
$EDDir = "./topicdata/";
## 保存用 ファイル ディレクトリ
$EDSDir = "./topicdatastore/";

## 画像保存用ディレクトリ
$ImgDir = "../fman/imgdata/";

## 画像用ディレクトリ TOPからのパス
$ImgDirPath = "./cgibin/fman/imgdata/";



## イベント情報入力画面　受渡データの定義
## 項目名：項目表示名：必要項目フラグ：入力制限バイト数：オートリンク：パターンマッチ
@cf_requesthtml = (
"tpg		###\表\示グループ		###0	###0	###0	###",
"tpname		###\表\示タイトル		###1	###200	###0	###",
"tpurl		###リンクアドレス		###0	###100	###0	###URL",
"tptext		###\表\示テキスト		###1	###50000	###0	###",
"tpimg		###トピック画像			###0	###50	###0	###"
);


## イベント情報入力画面　受渡データの定義
## 項目名：項目表示名：必要項目フラグ：入力制限バイト数：オートリンク：パターンマッチ
@cf_topiclist = (
"tpnum1		###記事 NO.1	###0	###50	###0	###NUMBER",
"tpnum2		###記事 NO.2	###0	###50	###0	###NUMBER",
"tpnum3		###記事 NO.3	###0	###50	###0	###NUMBER",
"tpnum4		###記事 NO.4	###0	###50	###0	###NUMBER",
"tpnum5		###記事 NO.5	###0	###50	###0	###NUMBER",
"tpnum6		###記事 NO.6	###0	###50	###0	###NUMBER",
"tpnum7		###記事 NO.7	###0	###50	###0	###NUMBER",
"tpnum8		###記事 NO.8	###0	###50	###0	###NUMBER",
"tpnum9		###記事 NO.9	###0	###50	###0	###NUMBER",
"tpnum10	###記事 NO.10	###0	###50	###0	###NUMBER"
);





#------------------------------------------------
#  仮用イベント情報データのジョイン
#------------------------------------------------
sub Join_InstiDataTemp {
	
	my @temp = ($irno,
				$in{'authe_id'},
				$in{'tpg'},
				$in{'tpname'},
				$in{'tpurl'},
				$in{'tptext'},
				$in{'tpimg'},
				$crdy);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  仮用イベント情報データのスプリット
#------------------------------------------------
sub Split_InstiDataTemp {
	
	my ($data) = @_;
	
	($irno,
	 $authe_id,
	 $tpg,
	 $tpname,
	 $tpurl,
	 $tptext,
	 $tpimg,
	 $crdy) = split($SPC,$data);
	
	chomp($crdy);
    
}

#------------------------------------------------
#  公開用イベント情報データのジョイン
#------------------------------------------------
sub Join_InstiData{
	
	my @temp = ($kjno,
				$in{'authe_id'},
				$in{'tpg'},
				$in{'tpname'},
				$in{'tpurl'},
				$in{'tptext'},
				$in{'tpimg'},
				$crdy);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  公開用イベント情報データのスプリット
#------------------------------------------------
sub Split_InstiData {
	
	my ($data) = @_;
	
	($kjno,
	 $authe_id,
	 $tpg,
	 $tpname,
	 $tpurl,
	 $tptext,
	 $tpimg,
	 $crdy ) = split($SPC,$data);
	
	chomp($crdy);
    
}


#------------------------------------------------
#  イベント情報データのハッシュ代入
#------------------------------------------------
sub Global2In_InstiDataTemp {
	$in{'irno'}    = $irno;
	$in{'tpg'}     = $tpg;
	$in{'tpname'}  = $tpname;
	$in{'tptext'}  = $tptext;
	$in{'tpurl'}   = $tpurl;
	$in{'tpimg'}   = $tpimg;
	$in{'crdy'}    = $crdy;
	
}
#------------------------------------------------
#  イベント情報データのハッシュ代入
#------------------------------------------------
sub Global2In_InstiData {
	$in{'kjno'}    = $kjno;
	$in{'tpg'}     = $tpg;
	$in{'tpname'}  = $tpname;
	$in{'tptext'}  = $tptext;
	$in{'tpurl'}   = $tpurl;
	$in{'tpimg'}   = $tpimg;
	$in{'crdy'}    = $crdy;
	
}






#------------------------------------------------
#  公開用 トピックリスト データのジョイン
#------------------------------------------------
sub Join_TopicListData{
	
	my @temp = (
				$in{'tpnum1'},
				$in{'tpnum2'},
				$in{'tpnum3'},
				$in{'tpnum4'},
				$in{'tpnum5'},
				$in{'tpnum6'},
				$in{'tpnum7'},
				$in{'tpnum8'},
				$in{'tpnum9'},
				$in{'tpnum10'}
				);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  公開用 トピックリスト データのスプリット
#------------------------------------------------
sub Split_TopicListData {
	
	my ($data) = @_;
	
	( $tpnum1,
	  $tpnum2,
	  $tpnum3,
	  $tpnum4,
	  $tpnum5,
	  $tpnum6,
	  $tpnum7,
	  $tpnum8,
	  $tpnum9,
	  $tpnum10 ) = split($SPC,$data);
	
	chomp($tpnum10);
    
}
#------------------------------------------------
#  トピックリスト データのハッシュ代入
#------------------------------------------------
sub Global2In_TopicListData {
	$in{'tpnum1'}  = $tpnum1;
	$in{'tpnum2'}  = $tpnum2;
	$in{'tpnum3'}  = $tpnum3;
	$in{'tpnum4'}  = $tpnum4;
	$in{'tpnum5'}  = $tpnum5;
	$in{'tpnum6'}  = $tpnum6;
	$in{'tpnum7'}  = $tpnum7;
	$in{'tpnum8'}  = $tpnum8;
	$in{'tpnum9'}  = $tpnum9;
	$in{'tpnum10'} = $tpnum10;
}
1;
