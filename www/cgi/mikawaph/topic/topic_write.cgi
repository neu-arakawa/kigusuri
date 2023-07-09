#!/usr/bin/perl

require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';



$g_encoding = 'sjis';		#�����R�[�h


$html_top = "./set_top.html";
$html_this = "./topic_write.html";
$msg = '';


# ���͎�t -- �t�@�C���t�o�o�[�W����
# ($FU_flag,$FU_msg,$Buffer,$Savefile,$Savefilecnt,$p_fileName,$p_fileBody) = &FileUpload::receive($Alimit,$Elimit,$Allow,$Savef);
# &parseInput($g_encoding,$Buffer);
&parseInput($g_encoding);

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
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
#	$sel_acflg[$in{acflg}] = 'selected';
	$DispTopData .= &Get_File($html_this,1);
	&Disp_Html($html_top);
	exit;
}



# ���͓��e�̃L���X�g
#if($in{'styy'}){ $in{'styy'} = sprintf("%04d",$in{'styy'}); }
#if($in{'stmm'}){ $in{'stmm'} = sprintf("%02d",$in{'stmm'}); }
#if($in{'stdd'}){ $in{'stdd'} = sprintf("%02d",$in{'stdd'}); }
#if($in{'edyy'}){ $in{'edyy'} = sprintf("%04d",$in{'edyy'}); }
#if($in{'edmm'}){ $in{'edmm'} = sprintf("%02d",$in{'edmm'}); }
#if($in{'eddd'}){ $in{'eddd'} = sprintf("%02d",$in{'eddd'}); }
#if($in{'msyy'}){ $in{'msyy'} = sprintf("%04d",$in{'msyy'}); }
#if($in{'msmm'}){ $in{'msmm'} = sprintf("%02d",$in{'msmm'}); }
#if($in{'msdd'}){ $in{'msdd'} = sprintf("%02d",$in{'msdd'}); }
#if($in{'meyy'}){ $in{'meyy'} = sprintf("%04d",$in{'meyy'}); }
#if($in{'memm'}){ $in{'memm'} = sprintf("%02d",$in{'memm'}); }
#if($in{'medd'}){ $in{'medd'} = sprintf("%02d",$in{'medd'}); }
#if($in{'acyy'}){ $in{'acyy'} = sprintf("%04d",$in{'acyy'}); }
#if($in{'acmm'}){ $in{'acmm'} = sprintf("%02d",$in{'acmm'}); }
#if($in{'acdd'}){ $in{'acdd'} = sprintf("%02d",$in{'acdd'}); }



# �C�x���g��񏑂����݃f�[�^�쐬
unless($in{'kjno'}){ $kjno = &Get_Kjno(); }
               else{ $kjno = $in{'kjno'}; }
unless($in{'crdy'}){ $crdy = &Get_Time(); }
               else{ $crdy = $in{'crdy'}; }
my $topicdata = &Join_InstiData();



# AuthenData�擾
# $id = $in{'authe_id'};
# my $adata = &FileReadMatch($AuthenDataFile,$id,1);
# &Split_AuthenData($adata);

# ($temp,$fid) = split('-',$in{'fnamer'});
# $fid =~ s/.df//;


# �����͎҂��烂�[�h������A�����ݒ���s�� --- ���ɈӖ��i�V���H
my $filename = '';
#if($Authen{'group'} eq 'admin'){
#	$MODE = 'root';
#	$filename = $EDDir . "$kjno-$in{'authe_id'}.df";
	$filename = $EDDir . "$kjno.df";
	$html_msg = "./topic_msg.html";
	$html_comp = "./topic_comp.html"; 
#}
	
#else{
#	## -- �Ǘ��҈ȊO
#	$MODE = 'admin';
#	$filename = $EDDir . "$kjno-$in{'authe_id'}.df";
#	$filename = $EDDir . "$kjno-$fid.df";
#	$topic_request_mf = 'topic_write_mailform.txt';
#	$html_msg = "./topic_msg.html";
#	$html_comp = "./topic_comp.html"; }
	





# �C�x���g��񏑂�����
if( index($in{'fnamer'},$EDDir) != -1 ){ unlink $in{'fnamer'}; }
&openLock(FH,">$filename") or $msg = '�t�@�C���̏������݂Ɏ��s���܂����B���Ԃ��󂯂Ă��Ȃ����Ă��������B';
	print FH "$topicdata\n";
&closeUnlock(FH,$filename);
chmod 0777,$filename;
if($msg){ &Disp_Html($html_this); exit;}
if( index($in{'fnamer'},$EDTDir) != -1 ){ unlink $in{'fnamer'}; }
if( index($in{'fnamer'},$EDSDir) != -1 ){ unlink $in{'fnamer'}; }




#�����I����ʕ\��
#open(TEMP,"$html_comp");
#	while (<TEMP>) {
#    	s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
#        $msg .= $_;
#    }
#close(TEMP);
# &Disp_Html($html_msg);

$DispTopData .= &Get_File($html_comp,1);
&Disp_Html($html_top);
exit;

