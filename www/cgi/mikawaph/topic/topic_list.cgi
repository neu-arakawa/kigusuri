#!/usr/bin/perl


require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';
require './topic_list.pl';


$g_encoding = 'sjis';		#�����R�[�h


$html_msg  = './topic_msg.html';
$html_tlist = './topic_tlist.html';
$html_plist = './topic_plist.html';
$html_slist = './topic_slist.html';

$msg = '';

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;





#���͎�t
&parseInput($g_encoding);
if (($in{'mode'} ne 'view') and ($method eq 'GET')) { &exitError("�f�d�s���\�b�h�͋�����Ă��܂���B");}




#���[�h�Z���N�g             �^�[�Q�b�g�f�B���N�g��,�\���g�s�l�k,���e�g�s�l�k,���݂̋L���ԍ�,�\����,�y�[�W�t���O,���[�h
   if($in{'mode'} eq 'tempall')   { &Event_List($EDTDir ,$html_msg,$html_tlist,$in{'now_kjnm'},$in{'cnt'},$in{'pflg'},'all'); }
elsif($in{'mode'} eq 'publicall') { &Event_List($EDDir  ,$html_msg,$html_plist,$in{'now_kjnm'},$in{'cnt'},$in{'pflg'},'all'); }
elsif($in{'mode'} eq 'publicself'){ &Event_List($EDDir  ,$html_msg,$html_plist,$in{'now_kjnm'},$in{'cnt'},$in{'pflg'},$in{'authe_id'}); }
elsif($in{'mode'} eq 'storeall')  { &Event_List($EDSDir ,$html_msg,$html_slist,$in{'now_kjnm'},$in{'cnt'},$in{'pflg'},'all'); }
elsif($in{'mode'} eq 'storeself') { &Event_List($EDSDir ,$html_msg,$html_slist,$in{'now_kjnm'},$in{'cnt'},$in{'pflg'},$in{'authe_id'}); }


exit;



