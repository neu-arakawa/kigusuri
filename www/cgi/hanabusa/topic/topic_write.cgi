#!/usr/bin/perl

require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#�����R�[�h


$html_top = "./set_top.html";
$html_this = "./topic_write.html";
$msg = '';


&parseInput($g_encoding);

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# �p�X���[�h�t�@�C�����A�Z�L�����e�B�m�ۂ̂��ߕύX(�`2010/2/24�j
$passwd = <PASS>;
close PASS;


## �p�X���[�h�`�F�b�N
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '�F�؃G���[���������܂����B�Ǘ���ʂ̓���������Ȃ����Ă��������B';
	&Disp_Html($html_top);
	exit;
}



## ���͉�ʕ\��
if($in{'mode'} eq 'input'){ 
							$DispTopData .= &Get_File($html_this,1);
							&Disp_Html($html_top);
							exit;
}





# ���̓`�F�b�N
$msg = &Chk_Input(*cf_requesthtml);
if($msg ne ''){
	$in{'tptext'}  = Unescape($in{'tptext'});
	$DispTopData .= &Get_File($html_this,1);
	&Disp_Html($html_top);
	exit;
}




# ��񏑂����݃f�[�^�쐬
unless($in{'kjno'}){ $kjno = &Get_Kjno(); }
               else{ $kjno = $in{'kjno'}; }
unless($in{'crdy'}){ $crdy = &Get_Time(); }
               else{ $crdy = $in{'crdy'}; }
my $topicdata = &Join_InstiData();




my $filename = '';
$filename = $EDDir . "$kjno.df";
$html_msg = "./topic_msg.html";
$html_comp = "./topic_comp.html"; 



# ��񏑂�����
if( index($in{'fnamer'},$EDDir) != -1 ){ unlink $in{'fnamer'}; }
&openLock(FH,">$filename") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B';
	print FH "$topicdata\n";
&closeUnlock(FH,$filename);
chmod 0777,$filename;
if($msg){ &Disp_Html($html_this); exit;}
if( index($in{'fnamer'},$EDTDir) != -1 ){ unlink $in{'fnamer'}; }
if( index($in{'fnamer'},$EDSDir) != -1 ){ unlink $in{'fnamer'}; }




$DispTopData .= &Get_File($html_comp,1);
&Disp_Html($html_top);
exit;

