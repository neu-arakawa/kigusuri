#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';
require '../cart/user_cnf.pl';



$g_encoding = 'sjis';		#�����R�[�h


$html_this = "./topic_create.html";
$html_msg  = './topic_msg.html';
$html_comp = './topic_comp.html';

$filename = 'topic_list.df';
$msg = '';


## Topic��� �t�@�C����
$TFile  = '../../../shop/' . $UserName . '/topic_include.txt';
$TDFile = '../../../shop/' . $UserName . '/topic.html';


$html_disp1 = './topic_disp1.txt';
$html_disp2 = './topic_disp2.txt';
$html_base  = './topic_disp_base.txt';

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


# ���͎�t
&parseInput($g_encoding);

if($method eq 'GET') { &exitError("�f�d�s���\�b�h�͋�����Ă��܂���B");}
if($in{'mode'} eq 'input'){ &Disp_Set($filename); exit; }
if($in{'mode'} eq 'w'){ &TopicCreate(); }

exit;











##--------------------------------------------------------------------
##
##     �g�b�s�N���쐬���C��
##
##-------------------------------------------------------------------
sub TopicCreate {
	
	# ���̓`�F�b�N
	$msg = &Chk_Input(*cf_topiclist);
	if($msg ne ''){
		&Disp_Html($html_this);
		exit;
	}
	
	my $topicdata = &Join_TopicListData();
	
	
	
	# ��������
	&openLock(FH,">$filename") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B';
		print FH "$topicdata\n";
	&closeUnlock(FH,$filename);
	chmod 0777,$filename;
	if($msg){ &Disp_Html($html_this); exit;}
	
	
	
	
	## �g�b�s�b�N��ʍ쐬
	my $tdispdata = &Create_Topic($topicdata);
	
	
	
	#�����I����ʕ\��
	$passwd = $in{authe_pwd};
	$DispTopData .= &Get_File($html_comp,1);
	&Disp_Html('./set_top.html'); 
	
	
}
#---------------------------------------
#   
#   �g�s�b�N��ʍ쐬
#   
#---------------------------------------
sub Create_Topic {
	
	my ($data) = @_;
	my $cnt = 0;
	my $fname;
	my $readdata;
	my $rdata;
	
	
	
	
	## �e���v���[�g�̓Ǎ�
	open(TEMP,"$html_disp1");
		while (<TEMP>) {   	$disp_html1 .= $_;	}
	close(TEMP);
	open(TEMP,"$html_disp2");
		while (<TEMP>) {	$disp_html2 .= $_;	}
	close(TEMP);
	open(TEMP,"$html_base");
		while (<TEMP>) {	$disp_base .= $_;	}
	close(TEMP);
	
	
	
	
	## NEW!�\���p
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
			## -- �f�[�^�t�@�C���̓Ǎ�
			$fname = $_  .'.df';
			open(FILE,"$EDDir$fname");
					$readdata = <FILE>;
			close(FILE);
			
			
			
			## -- �\���f�[�^�쐬
			&Split_InstiData($readdata);
			$tptext = ToHtmlTag($tptext);
			
			if($tpurl){
				$tptitle = '<a href="' . $tpurl . '">' . $tpname . '</a>';
			}else{
				$tptitle = $tpname;
			}
			
			
			
			## NEW!�\���p
			$news = $new[$cnt];
			
			
				$_ = $disp_html1;
				s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
				$rdata1 .= $_;
				$_ = $disp_html2;
				s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
				$rdata2 .= $_;
		
		
		}
	}
	
	
	
	&openLock(FH,">$TFile") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B' . $TFile;
		print FH "$rdata1\n";
	&closeUnlock(FH,$TFile);
	chmod 0777,$TFile;
	if($msg){ $DispTopData .= &Get_File($html_this,1);	&Disp_Html('./set_top.html'); exit;}
	
	
	$wdata = &Get_File($html_base,1);
	&openLock(FH,">$TDFile") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B' . $TFile;
		print FH "$wdata\n";
	&closeUnlock(FH,$TDFile);
	chmod 0777,$TDFile;
	if($msg){ $DispTopData .= &Get_File($html_this,1);	&Disp_Html('./set_top.html'); exit;}
	
	
	return($rdata);
	
}
#---------------------------------------
#   
#   �ҏW��ʕ\��
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


