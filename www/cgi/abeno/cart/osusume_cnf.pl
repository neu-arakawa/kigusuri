$selfURL  = $ENV{'SCRIPT_NAME'};
$selfURL  =~ /^(.*)\/[^\/]*$/;
$selfDIR  = $1;
$baseURL  = "http://$ENV{'HTTP_HOST'}";


## �g�p�T�C�gTOP�y�[�WURL
$homeURL = "http://www.kigusuri.com/";


## nkf �ݒ�
$nkfmail = '/usr/local/bin/nkf -j';
$nkfhtml = '/usr/local/bin/nkf -e';


## Sendmail �ݒ�
$sendmail = '/usr/sbin/sendmail';


## �X�v���b�g����
$SPC = ',';
$SPCE = '�C';



## ���p �f�[�^�t�@�C�� �f�B���N�g��
$EDTDir = "./topicdatatemp/";
## ���J�p �f�[�^�t�@�C�� �f�B���N�g��
$EDDir = "./topicdata/";
## �ۑ��p ���Ł[���@�t�@�C�� �f�B���N�g��
$EDSDir = "./topicdatastore/";

## �摜�ۑ��p�f�B���N�g��
$ImgDir = "../fman/imgdata/";

## �摜�p�f�B���N�g�� TOP����̃p�X
$ImgDirPath = "./cgibin/fman/imgdata/";



## �C�x���g�����͉�ʁ@��n�f�[�^�̒�`
## ���ږ��F���ڕ\�����F�K�v���ڃt���O�F���͐����o�C�g���F�I�[�g�����N�F�p�^�[���}�b�`
@cf_topiclist = (
"tpnum1		###�L�� NO.1	###0	###50	###0	###WORD",
"tpnum2		###�L�� NO.2	###0	###50	###0	###WORD",
"tpnum3		###�L�� NO.3	###0	###50	###0	###WORD",
"tpnum4		###�L�� NO.4	###0	###50	###0	###WORD"
);




#------------------------------------------------
#  ���J�p �g�s�b�N���X�g �f�[�^�̃W���C��
#------------------------------------------------
sub Join_TopicListData{
	
	my @temp = (
				$in{'tpnum1'},
				$in{'tpnum2'},
				$in{'tpnum3'},
				$in{'tpnum4'} );
	
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
	  $tpnum4 ) = split($SPC,$data);
	
	chomp($tpnum4);
    
}
#------------------------------------------------
#  �g�s�b�N���X�g �f�[�^�̃n�b�V�����
#------------------------------------------------
sub Global2In_TopicListData {
	$in{'tpnum1'}  = $tpnum1;
	$in{'tpnum2'}  = $tpnum2;
	$in{'tpnum3'}  = $tpnum3;
	$in{'tpnum4'}  = $tpnum4;
}
1;
