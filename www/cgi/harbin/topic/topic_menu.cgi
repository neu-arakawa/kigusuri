#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#�����R�[�h


$html_top = "./set_top.html";
$html_this = "./topic_menu.html";
$msg = '';


&parseInput($g_encoding);


## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;


## �p�X���[�h�`�F�b�N
if($in{pass} ne $passwd){
	$DispTopData = '�F�؃G���[���������܂����B�Ǘ���ʂ̓���������Ȃ����Ă��������B';
	&Disp_Html($html_top);
	exit;
}


## ���͉�ʕ\��
$DispTopData .= &Get_File($html_this,1);
&Disp_Html($html_top);
exit;



