#!/usr/bin/perl


require './lib/jcode.pl';
require './ci_util.pl';
require './file_upload.pl';


# �t�@�C���A�b�v���[�h�����ݒ�
# --- ��x�ŃA�b�v������
$Alimit = 150 * 1024;
# --- �ʃt�@�C�����
$Elimit = 150 * 1024;
# --- �A�b�v���g���q
$Allow  = 'jpeg,jpg';
# --- �Z�[�u�^�C�v  
#     -ow�F�A�b�v���[�h���ꂽ�t�@�C���̃t�@�C�������g�p
#�@�@ -tm�F�A�b�v���[�h�̍ۂ̓������t�@�C�����Ɏg�p
#�@�@ -fn�F�t�H�[����name=�Ŏw�肳�ꂽ�������t�@�C���Ɏg�p
$Savef  = '-ow';


# �f�B���N�g������e�ʂ̃f�t�H���g�l
# 5000KB
$Dlimit = 5000 * 1024;
# �Ώۗ̈�
$Dir = './pic/';




$wrtopHtml  = 'write_top.html';
$g_encoding = 'sjis';	#�����R�[�h

$msg = '';
$err = '';


#���͎�t -- �t�@�C���t�o�o�[�W����
($FU_flag,$FU_msg,$Buffer,$Savefile,$Savefilecnt,$p_fileName,$p_fileBody) = &FileUpload::receive($Alimit,$Elimit,$Allow,$Savef);
&parseInput($g_encoding,$Buffer);

$pass = $in{'pass'};

&Upload();
&Msg_View();
exit;



#------------------------------------------------
#  
#  �t�@�C���̃A�b�v���[�h
#  
#------------------------------------------------
sub Upload {
	
	## �G���[�t���O����������G���[�\��
	if($FU_flag eq 'f'){ ($msg = $FU_msg) =~ s/\t/<br>/g; return; }
	
	
	## �g�p�\�̈�ݒ�
	($DirCapa,$DirRCapa) = &Get_DirCapa('B',$Dir,$Dlimit);
	$Alimit = $DirRCapa;
	
	
	## �t�@�C���̃A�b�v���[�h
	($flg,$msg) = &FileUpload::write($Dir,$Alimit,$Savefilecnt,$p_fileName,$p_fileBody);
	
	
	## �G���[�\��
	if($flg){ return; }
	
	
	## �A�b�v����t�@�C�����P���Ȃ���΃G���[�\��
	if($flg eq '' and $msg <= 0){ $msg = '�摜�t�@�C�����w�肵�Ă��������B'; return; }
	
	$msg = '�摜��ǉ����܂����B';
#	$msg .= "$in{authe_id} -- $Dir -- $Alimit";
	
}








#------------------------------------------------
#  
#  ���b�Z�[�W�̕\��
#  
#------------------------------------------------
sub Msg_View {
	
	$DispTopData = '';
	$msg = '<br><b>' . $msg  . '</b></br>';
	&Disp_Html($wrtopHtml,1);
	exit;
	
}





