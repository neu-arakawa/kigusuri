$selfURL  = $ENV{'SCRIPT_NAME'};
$selfURL  =~ /^(.*)\/[^\/]*$/;
$selfDIR  = $1;
$baseURL  = "http://$ENV{'HTTP_HOST'}";


## �g�p�T�C�gTOP�y�[�WURL
$homeURL = "http://www.okayama-sports.jp";


## nkf �ݒ�
$nkfmail = '/usr/local/bin/nkf -j';
$nkfhtml = '/usr/local/bin/nkf -e';


## Sendmail �ݒ�
$sendmail = '/usr/sbin/sendmail';


## �X�v���b�g����
$SPC = ',';
$SPCE = '�C';


## �Ǘ��҃��[���A�h���X
$MASTER_MAIL = 'info@kigusuri.com';


## �L���m�n�D�t�@�C��
$KJNO_SEQr = './kjno_seq2';
$KJNO_SEQw = './kjno_seq1';


## �f�ڈȗ��m�n.�t�@�C��
$IRNO_SEQr = './irno_seq2';
$IRNO_SEQw = './irno_seq1';



## ���p �f�[�^�t�@�C�� �f�B���N�g��
$EDTDir = "./topicdatatemp/";
## ���J�p �f�[�^�t�@�C�� �f�B���N�g��
$EDDir = "./topicdata/";
## �ۑ��p �t�@�C�� �f�B���N�g��
$EDSDir = "./topicdatastore/";

## �摜�ۑ��p�f�B���N�g��
$ImgDir = "../fman/imgdata/";

## �摜�p�f�B���N�g�� TOP����̃p�X
$ImgDirPath = "./cgibin/fman/imgdata/";



## �C�x���g�����͉�ʁ@��n�f�[�^�̒�`
## ���ږ��F���ڕ\�����F�K�v���ڃt���O�F���͐����o�C�g���F�I�[�g�����N�F�p�^�[���}�b�`
@cf_requesthtml = (
"tpg		###\�\\���O���[�v		###0	###0	###0	###",
"tpname		###\�\\���^�C�g��		###1	###200	###0	###",
"tpurl		###�����N�A�h���X		###0	###100	###0	###URL",
"tptext		###\�\\���e�L�X�g		###1	###50000	###0	###",
"tpimg		###�g�s�b�N�摜			###0	###50	###0	###"
);


## �C�x���g�����͉�ʁ@��n�f�[�^�̒�`
## ���ږ��F���ڕ\�����F�K�v���ڃt���O�F���͐����o�C�g���F�I�[�g�����N�F�p�^�[���}�b�`
@cf_topiclist = (
"tpnum1		###�L�� NO.1	###0	###50	###0	###NUMBER",
"tpnum2		###�L�� NO.2	###0	###50	###0	###NUMBER",
"tpnum3		###�L�� NO.3	###0	###50	###0	###NUMBER",
"tpnum4		###�L�� NO.4	###0	###50	###0	###NUMBER",
"tpnum5		###�L�� NO.5	###0	###50	###0	###NUMBER",
"tpnum6		###�L�� NO.6	###0	###50	###0	###NUMBER",
"tpnum7		###�L�� NO.7	###0	###50	###0	###NUMBER",
"tpnum8		###�L�� NO.8	###0	###50	###0	###NUMBER",
"tpnum9		###�L�� NO.9	###0	###50	###0	###NUMBER",
"tpnum10	###�L�� NO.10	###0	###50	###0	###NUMBER"
);





#------------------------------------------------
#  ���p�C�x���g���f�[�^�̃W���C��
#------------------------------------------------
sub Join_InstiDataTemp {
	
	my @temp = ($irno,
				$in{'authe_id'},
				$in{'tpg'},
				$in{'tpname'},
				$in{'tpurl'},
				$in{'tptext'},
				$in{'tpimg'},
				$crdy);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  ���p�C�x���g���f�[�^�̃X�v���b�g
#------------------------------------------------
sub Split_InstiDataTemp {
	
	my ($data) = @_;
	
	($irno,
	 $authe_id,
	 $tpg,
	 $tpname,
	 $tpurl,
	 $tptext,
	 $tpimg,
	 $crdy) = split($SPC,$data);
	
	chomp($crdy);
    
}

#------------------------------------------------
#  ���J�p�C�x���g���f�[�^�̃W���C��
#------------------------------------------------
sub Join_InstiData{
	
	my @temp = ($kjno,
				$in{'authe_id'},
				$in{'tpg'},
				$in{'tpname'},
				$in{'tpurl'},
				$in{'tptext'},
				$in{'tpimg'},
				$crdy);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  ���J�p�C�x���g���f�[�^�̃X�v���b�g
#------------------------------------------------
sub Split_InstiData {
	
	my ($data) = @_;
	
	($kjno,
	 $authe_id,
	 $tpg,
	 $tpname,
	 $tpurl,
	 $tptext,
	 $tpimg,
	 $crdy ) = split($SPC,$data);
	
	chomp($crdy);
    
}


#------------------------------------------------
#  �C�x���g���f�[�^�̃n�b�V�����
#------------------------------------------------
sub Global2In_InstiDataTemp {
	$in{'irno'}    = $irno;
	$in{'tpg'}     = $tpg;
	$in{'tpname'}  = $tpname;
	$in{'tptext'}  = $tptext;
	$in{'tpurl'}   = $tpurl;
	$in{'tpimg'}   = $tpimg;
	$in{'crdy'}    = $crdy;
	
}
#------------------------------------------------
#  �C�x���g���f�[�^�̃n�b�V�����
#------------------------------------------------
sub Global2In_InstiData {
	$in{'kjno'}    = $kjno;
	$in{'tpg'}     = $tpg;
	$in{'tpname'}  = $tpname;
	$in{'tptext'}  = $tptext;
	$in{'tpurl'}   = $tpurl;
	$in{'tpimg'}   = $tpimg;
	$in{'crdy'}    = $crdy;
	
}






#------------------------------------------------
#  ���J�p �g�s�b�N���X�g �f�[�^�̃W���C��
#------------------------------------------------
sub Join_TopicListData{
	
	my @temp = (
				$in{'tpnum1'},
				$in{'tpnum2'},
				$in{'tpnum3'},
				$in{'tpnum4'},
				$in{'tpnum5'},
				$in{'tpnum6'},
				$in{'tpnum7'},
				$in{'tpnum8'},
				$in{'tpnum9'},
				$in{'tpnum10'}
				);
	
	my $data = join($SPC,@temp);
	return($data);
    
}
#------------------------------------------------
#  ���J�p �g�s�b�N���X�g �f�[�^�̃X�v���b�g
#------------------------------------------------
sub Split_TopicListData {
	
	my ($data) = @_;
	
	( $tpnum1,
	  $tpnum2,
	  $tpnum3,
	  $tpnum4,
	  $tpnum5,
	  $tpnum6,
	  $tpnum7,
	  $tpnum8,
	  $tpnum9,
	  $tpnum10 ) = split($SPC,$data);
	
	chomp($tpnum10);
    
}
#------------------------------------------------
#  �g�s�b�N���X�g �f�[�^�̃n�b�V�����
#------------------------------------------------
sub Global2In_TopicListData {
	$in{'tpnum1'}  = $tpnum1;
	$in{'tpnum2'}  = $tpnum2;
	$in{'tpnum3'}  = $tpnum3;
	$in{'tpnum4'}  = $tpnum4;
	$in{'tpnum5'}  = $tpnum5;
	$in{'tpnum6'}  = $tpnum6;
	$in{'tpnum7'}  = $tpnum7;
	$in{'tpnum8'}  = $tpnum8;
	$in{'tpnum9'}  = $tpnum9;
	$in{'tpnum10'} = $tpnum10;
}
1;
