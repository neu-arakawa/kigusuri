#!/usr/bin/perl


require './osusume_cnf.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';
require './user_cnf.pl';


$g_encoding = 'sjis';		#�����R�[�h


$html_this = "./write_top.html";
$html_msg  = './write_top.html';
$html_comp = './write_top.html';

$filename = 'osusume_list.df';
$msg = '';


## ��� �t�@�C����
$TFile = '../../../shop/yamakan/' . $UserName . '/osusume_include.txt';



$html_disp1 = './osusume_table1.html';
$html_disp2 = './osusume_table2.html';



# ���͎�t
&parseInput($g_encoding);


open PASS,"<set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;
$pass = $in{'pass'};

if($in{'pass'} eq ''){ &exitError("�s���ȃA�N�Z�X�ł��B"); }
if($in{'pass'} ne $passwd ){ &exitError("�s���ȃA�N�Z�X�ł��B"); }
if($in{'mode'} eq 'input'){ &Disp_Set($filename); exit; }
if($in{'mode'} eq 'w'){ &TopicCreate(); }

exit;











##--------------------------------------------------------------------
##
##     �g�s�b�N���쐬���C��
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
	
	
	&openLock(FH,">$TFile") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B' . $TFile;
		print FH "$tdispdata\n";
	&closeUnlock(FH,$TFile);
	chmod 0777,$TFile;
	if($msg){ &Disp_Html($html_this); exit;}
	
	
	
	
	#�����I����ʕ\��
	$msg = '�������ߏ��i���X�V���܂����B';
	&Disp_Html($html_msg);
	
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
	
	
	
	
	@temp  = split($SPC,$data);
	foreach $idno (@temp){
		if($idno ne ''){
			
			
			## -- �f�[�^�t�@�C���̓Ǎ�
			$fname = './db/db0.txt';
			open(FILE,"$fname");
			while (<FILE>) {
				
				$readdata = $_;
				($db{page_num},$db{num},$db{name},$db{price},$db{company},$db{pic},$db{zaiko},$db{comment}) = split (/:=:/, $readdata);
				
				
				if($idno eq $db{num}){
					
					
					## -- �\���f�[�^�쐬
					$title = $db{name};
					$imgurl = 'https://www.kigusuri.com' . $selfDIR . '/pic/' . $db{pic};
					$temp_a = '';
					$temp_b = substr($db{comment},0,200);
					while ($temp_b =~ /([\x00-\x7f]|..)/g) {
						$temp_a = $temp_a.$1;
					}
					$summary = $temp_a;
					
					$linkurl = 'https://www.kigusuri.com' . $selfDIR . '/cart_list.cgi?itemid=' . $db{num};
					
					if($db{pic} eq ''){
						$_ = $disp_html2;
						s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
						$rdata .= $_;
					}
					else{
						$_ = $disp_html1;
						s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
						$rdata .= $_;
					}
					last;
					
				}
			}
			close(FILE);
			
		}else{ $rdata .= "$idno error \n"; }
	}
	
	
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
	
	
	#�\���p�e���v���[�g�ǂݍ���
	$DispTopData = &Get_File('osusume_create.html',1);
	&Disp_Html($html_this);
	
}



