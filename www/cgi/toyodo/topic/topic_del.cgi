#!/usr/bin/perl


# require '../authen/authen_cnf.pl';
require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';


$g_encoding = 'sjis';		#�����R�[�h

$html_msg = './topic_msg.html';
$html_msg2 = './topic_msg2.html';

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


#���͎�t
&parseInput($g_encoding);
if ($method eq 'GET') { &exitError("�f�d�s���\�b�h�͋�����Ă��܂���B");}


# AuthenData�擾
# $id = $in{'authe_id'};
# my $adata = &FileReadMatch($AuthenDataFile,$id,1);
# &Split_AuthenData($adata);


#if($in{'authe_pwd'} ne $Authen{pwd}){
#	$msg = "�h�c�܂��̓p�X���[�h���Ԉ���Ă��܂��B";
#	&Disp_Html($html_msg2);
#	exit;
#}

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


## �p�X���[�h�`�F�b�N
if($in{'authe_pwd'} ne $passwd){
	$DispTopData = '�F�؃G���[���������܂����B�Ǘ���ʂ̓���������Ȃ����Ă��������B';
	&Disp_Html('./set_top.html');
	exit;
}

#if( ($Authen{group} ne 'admin') and (index($in{'fnamer'},$in{'authe_id'}) == -1) ){
#	$msg = "�폜����������܂���B";
#	&Disp_Html($html_msg2);
#	exit;
#}


open (FH,$in{'fnamer'});
	$readdata = <FH>;
close(FH);
&Split_InstiData($readdata);


unlink $in{'fnamer'};



$msg = '�L���ԍ��y' . $kjno . '�z�\���^�C�g���y' . $tpname . '�z�̃f�[�^���폜���܂����B';
$DispTopData .= &Get_File($html_msg,1);
&Disp_Html('./set_top.html');
exit;

