#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';
require '../cart/user_cnf.pl';



$g_encoding = 'sjis';		#漢字コード


$html_this = "./topic_create.html";
$html_msg  = './topic_msg.html';
$html_comp = './topic_comp.html';

$filename = 'topic_list.df';
$msg = '';


## Topic画面 ファイル名
$TFile  = '../../../../www/shop/' . $UserName . '/topic_include.txt';
$TDFile = '../../../../www/shop/' . $UserName . '/topic.html';


$html_disp1 = './topic_disp1.txt';
$html_disp2 = './topic_disp2.txt';
$html_base  = './topic_disp_base.txt';

## パスワード読み込み
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


# 入力受付
&parseInput($g_encoding);

if($method eq 'GET') { &exitError("ＧＥＴメソッドは許可されていません。");}
if($in{'mode'} eq 'input'){ &Disp_Set($filename); exit; }
if($in{'mode'} eq 'w'){ &TopicCreate(); }

exit;











##--------------------------------------------------------------------
##
##     トピック情報作成メイン
##
##-------------------------------------------------------------------
sub TopicCreate {
	
	# 入力チェック
	$msg = &Chk_Input(*cf_topiclist);
	if($msg ne ''){
		&Disp_Html($html_this);
		exit;
	}
	
	my $topicdata = &Join_TopicListData();
	
	
	# AuthenData取得
#	$id = $in{'authe_id'};
#	my $adata = &FileReadMatch($AuthenDataFile,$id,1);
#	&Split_AuthenData($adata);
	
	
	
	
	# 書き込み
	&openLock(FH,">$filename") or $msg = 'ファイルの書き込みに失敗しました。時間を空けてやりなおしてください。';
		print FH "$topicdata\n";
	&closeUnlock(FH,$filename);
	chmod 0777,$filename;
	if($msg){ &Disp_Html($html_this); exit;}
	
	
	
	
	## トッピック画面作成
	my $tdispdata = &Create_Topic($topicdata);
	
	
	
	#処理終了画面表示
	$passwd = $in{authe_pwd};
	$DispTopData .= &Get_File($html_comp,1);
	&Disp_Html('./set_top.html'); 
	
	
}
#---------------------------------------
#   
#   トピック画面作成
#   
#---------------------------------------
sub Create_Topic {
	
	my ($data) = @_;
	my $cnt = 0;
	my $fname;
	my $readdata;
	my $rdata;
	
	
	
	
	## テンプレートの読込
	open(TEMP,"$html_disp1");
		while (<TEMP>) {   	$disp_html1 .= $_;	}
	close(TEMP);
	open(TEMP,"$html_disp2");
		while (<TEMP>) {	$disp_html2 .= $_;	}
	close(TEMP);
	open(TEMP,"$html_base");
		while (<TEMP>) {	$disp_base .= $_;	}
	close(TEMP);
	
	
	
	
	## NEW!表示用
	if($in{'new1'} eq 'on') { $new[1] = "<img src=\"img/new.gif\">"; }
	if($in{'new2'} eq 'on') { $new[2] = "<img src=\"img/new.gif\">"; }
	if($in{'new3'} eq 'on') { $new[3] = "<img src=\"img/new.gif\">"; }
	if($in{'new4'} eq 'on') { $new[4] = "<img src=\"img/new.gif\">"; }
	if($in{'new5'} eq 'on') { $new[5] = "<img src=\"img/new.gif\">"; }
	if($in{'new6'} eq 'on') { $new[6] = "<img src=\"img/new.gif\">"; }
	if($in{'new7'} eq 'on') { $new[7] = "<img src=\"img/new.gif\">"; }
	if($in{'new8'} eq 'on') { $new[8] = "<img src=\"img/new.gif\">"; }
	if($in{'new9'} eq 'on') { $new[9] = "<img src=\"img/new.gif\">"; }
	if($in{'new10'} eq 'on'){ $new[10] = "<img src=\"img/new.gif\">"; }
	
	
	@temp  = split($SPC,$data);
	foreach(@temp){
		$cnt += 1;
		
		if($_){
			## -- データファイルの読込
			$fname = $_  .'.df';
			open(FILE,"$EDDir$fname");
					$readdata = <FILE>;
			close(FILE);
			
			
			
			## -- 表示データ作成
			&Split_InstiData($readdata);
		#	$img{'img'} = $ImgDirPath . ''  . $tpimg;
		#	$img{'title'} = './img/' . $tpg . '.gif';
		#	$tptext = Unescape($tptext);
			$tptext = ToHtmlTag($tptext);
			
			if($tpurl){
				$tptitle = '<a href="' . $tpurl . '">' . $tpname . '</a>';
			}else{
				$tptitle = $tpname;
			}
			
			
			## トピックカラー
		#	if   ($tpg eq 'class'){	 $color = '#EEC322';	}
		#	elsif($tpg eq 'club'){	 $color = '#A23FFF';	}
		#	elsif($tpg eq 'event'){	 $color = '#00D2FF';	}
		#	elsif($tpg eq 'insti'){	 $color = '#3366FF';	}
		#	elsif($tpg eq 'mate'){	 $color = '#FF0000';	}
		#	elsif($tpg eq 'faq'){	 $color = '#33CC00';	}
		#	elsif($tpg eq 'school'){ $color = '#FF88A2';	}
		#	elsif($tpg eq 'link'){   $color = '#FF00FF';	}
			
			
			## NEW!表示用
			$news = $new[$cnt];
			
			
		#	if($cnt == 1){
		#		$_ = $disp_html1;
		#		s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
		#		$rdata1 .= $_;
		#	}
		#	elsif($cnt == 2){
				$_ = $disp_html1;
				s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
				$rdata1 .= $_;
		#	}
		#	else{
				$_ = $disp_html2;
				s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
				$rdata2 .= $_;
		#	}
		
		
		}
	}
	
	
#	if($rdata){
#			$rdata .= '<table width="440" border="0" align="center" cellpadding="3" cellspacing="0"><tr>';
#			$rdata .= '<td width="60" background="img/back_c02.gif" height="20" valign="middle" class="font01">　</td>';
#			$rdata .= '<td width="340" background="img/back_c02.gif" valign="middle" class="font01">　</td>';
#			$rdata .= '</tr></table>';
#	}
	
	
#	$rdata = '<table width=430><tr><td>' . $rdata1 . '</td><td>' . $rdata2 . '</td></tr></table>' . $rdata;
	
	
	
	&openLock(FH,">$TFile") or $msg = 'ファイルの書き込みに失敗しました。時間を空けてやりなおしてください。' . $TFile;
		print FH "$rdata1\n";
	&closeUnlock(FH,$TFile);
	chmod 0777,$TFile;
	if($msg){ $DispTopData .= &Get_File($html_this,1);	&Disp_Html('./set_top.html'); exit;}
	
	
	$wdata = &Get_File($html_base,1);
	&openLock(FH,">$TDFile") or $msg = 'ファイルの書き込みに失敗しました。時間を空けてやりなおしてください。' . $TFile;
		print FH "$wdata\n";
	&closeUnlock(FH,$TDFile);
	chmod 0777,$TDFile;
	if($msg){ $DispTopData .= &Get_File($html_this,1);	&Disp_Html('./set_top.html'); exit;}
	
	
	return($rdata);
	
}
#---------------------------------------
#   
#   編集画面表示
#   
#---------------------------------------
sub Disp_Set{
	
	my ($fnamer) = @_;
	my $readdata = "";
	
	
	
	open (FH,"$fnamer");
		$readdata = <FH>;
	close(FH);
	
	
	&Split_TopicListData($readdata);
	&Global2In_TopicListData();
	
	$DispTopData .= &Get_File($html_this,1);
	&Disp_Html('./set_top.html');
	
}



