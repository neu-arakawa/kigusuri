#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';


$g_encoding = 'sjis';		#�����R�[�h

$html_msg = './topic_msg.html';
$html_msg2 = './topic_msg2.html';

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# �p�X���[�h�t�@�C�����A�Z�L�����e�B�m�ۂ̂��ߕύX(�`2010/2/24�j
$passwd = <PASS>;
close PASS;


#���͎�t
&parseInput($g_encoding);
if ($method eq 'GET') { &exitError("�f�d�s���\�b�h�͋�����Ă��܂���B");}


## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.pl" || die "Could not open the file";
#open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
# �p�X���[�h�t�@�C�����A�Z�L�����e�B�m�ۂ̂��ߕύX(�`2010/2/24�j
$passwd = <PASS>;
close PASS;


## �p�X���[�h�`�F�b�N
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '�F�؃G���[���������܂����B�Ǘ���ʂ̓���������Ȃ����Ă��������B';
	&Disp_Html('./set_top.html');
	exit;
}


open (FH,$in{'fnamer'});
	$readdata = <FH>;
close(FH);
&Split_InstiData($readdata);


unlink $in{'fnamer'};



$msg = '�L���ԍ��y' . $kjno . '�z�\���^�C�g���y' . $tpname . '�z�̃f�[�^���폜���܂����B';
$DispTopData .= &Get_File($html_msg,1);
&Disp_Html('./set_top.html');
exit;

