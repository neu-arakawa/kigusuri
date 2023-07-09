$selfURL  = $ENV{'SCRIPT_NAME'};
$selfURL  =~ /^(.*)\/[^\/]*$/;
$selfDIR  = $1;
$baseURL  = "http://$ENV{'HTTP_HOST'}";


## 使用サイトTOPページURL
$homeURL = "http://www.kigusuri.com/";


## nkf 設定
$nkfmail = '/usr/local/bin/nkf -j';
$nkfhtml = '/usr/local/bin/nkf -e';


## Sendmail 設定
$sendmail = '/usr/sbin/sendmail';


## スプリット文字
$SPC = ',';
$SPCE = '，';



## 仮用 データファイル ディレクトリ
$EDTDir = "./topicdatatemp/";
## 公開用 データファイル ディレクトリ
$EDDir = "./topicdata/";
## 保存用 情報でーた　ファイル ディレクトリ
$EDSDir = "./topicdatastore/";

## 画像保存用ディレクトリ
$ImgDir = "../fman/imgdata/";

## 画像用ディレクトリ TOPからのパス
$ImgDirPath = "./cgibin/fman/imgdata/";



## イベント情報入力画面　受渡データの定義
## 項目名：項目表示名：必要項目フラグ：入力制限バイト数：オートリンク：パターンマッチ
@cf_topiclist = (
"tpnum1		###記事 NO.1	###0	###50	###0	###WORD",
"tpnum2		###記事 NO.2	###0	###50	###0	###WORD",
"tpnum3		###記事 NO.3	###0	###50	###0	###WORD",
"tpnum4		###記事 NO.4	###0	###50	###0	###WORD"
);




#------------------------------------------------
#  公開用 トピックリスト データのジョイン
#------------------------------------------------
sub Join_TopicListData{
	
	my @temp = (
				$in{'tpnum1'},
				$in{'tpnum2'},
				$in{'tpnum3'},
				$in{'tpnum4'} );
	
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
	  $tpnum4 ) = split($SPC,$data);
	
	chomp($tpnum4);
    
}
#------------------------------------------------
#  トピックリスト データのハッシュ代入
#------------------------------------------------
sub Global2In_TopicListData {
	$in{'tpnum1'}  = $tpnum1;
	$in{'tpnum2'}  = $tpnum2;
	$in{'tpnum3'}  = $tpnum3;
	$in{'tpnum4'}  = $tpnum4;
}
1;
