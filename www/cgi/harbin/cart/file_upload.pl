package FileUpload;


#�@�����p���@
#�@�E�t�@�C���A�b�v���[�h
#�@�P�D�܂��A�b�v���[�h�p�t�H�[���̂g�s�l�k���ɋL�q�����FORM
#�@�@�@�^�O�ł� METHOD="POST" �� ENCTYPE="multipart/form-data"
#�@�@�@���K���ݒ肳��Ă��Ȃ���΂����܂���B
#�@�@�@����ȊO���w�肳��Ă���ꍇ��A����炪�w�肳��Ă���
#�@�@�@���Ƃ��m�F�ł��Ȃ������ꍇ(*1)�Afileupload.pl�� �G���[��
#�@�@�@�o�͂��܂��B
#�@�@�i*1�F���ϐ�REQUEST_METHOD,CONTENT_TYPE�Ŋm�F���Ă��܂��j
#      <input type="file" name="">
#
#
#�@�Q�D�g�p��
#�@�@�@require'file_upload.pl';
#�@�@�@$alimit = '500';
#�@�@�@$elimit = '100';
#�@�@�@$dir    = './savefile/';
#�@�@�@$allow  = 'gif,jpeg,jpg';
#�@�@�@$savef  = '-ow';
#
#�@�@�@($flag,$msg,$buffer,$savefile) = &FileUpload::receive($alimit,$elimit,$allow,$savef);
#
#�@�@�@��fileupload.pl�ɓn���f�[�^
#
#�@�@�@$alimit�F �t�H�[�����瑗���Ă���f�[�^�S�̂̃T�C�Y�ɂ��āA
#�@�@�@�@�@�@�@�@���������������ꍇ�ɐ��l���w�肵�܂��B[�P��=byte]
#�@�@�@�@�@�@�@�@�Ⴆ�� 500���w�肵���ꍇ�A���ϐ�CONTENT_LENGTH
#�@�@�@�@�@�@�@�@�̒l�� 500�ȏ�Ȃ�G���[���o�͂���܂��B
#�@�@�@�@�@�@�@�@���������������Ȃ��ꍇ�͋�ɂ��܂��B
#
#�@�@�@$elimit�F �t�H�[�����瑗���Ă���A�A�b�v���[�h����t�@�C��
#�@�@�@�@�@�@�@�@�̃T�C�Y�ɂ��Đ��������������ꍇ�ɐ��l���w�肵��
#�@�@�@�@�@�@�@�@���B[�P��=byte]
#�@�@�@�@�@�@�@�@�Ⴆ�� 100���w�肵���ꍇ�A�A�b�v���[�h�����t�@�C
#�@�@�@�@�@�@�@�@���̂����P�ł�100�o�C�g���z���Ă���ƃG���[���o��
#�@�@�@�@�@�@�@�@����܂��B
#�@�@�@�@�@�@�@�@���������������Ȃ��ꍇ�͋�ɂ��܂��B
#
#�@�@�@$dir�@ �F �A�b�v���[�h����t�@�C���̕ۑ���f�B���N�g�����w��
#�@�@�@�@�@�@�@�@���܂��B/ �ŏI����Ă���K�v������܂��B�ۑ���f�B
#�@�@�@�@�@�@�@�@���N�g���̃p�[�~�b�V�����͂b�f�h���t�@�C���𐶐���
#�@�@�@�@�@�@�@�@����l�ɂ��Ă����Ă��������B
#
#�@�@�@$allow �F �A�b�v���[�h��������t�@�C���g���q���w�肵�܂��B
#�@�@�@�@�@�@�@�@�����Ŏw�肵���g���q�ȊO�̃t�@�C�����P�ł�����ƁA
#�@�@�@�@�@�@�@�@�G���[���o�͂���܂��B
#�@�@�@�@�@�@�@�@�Ȃ��A�����w��\�ł����A���̏ꍇ�͔��p�J���}�ŋ�
#�@�@�@�@�@�@�@�@�؂��Ă��������B
#�@�@�@�@�@�@�@�i�����w��̏ꍇ�A�K����L�̗�̂悤�� ��x �ϐ��ɓ�
#�@�@�@�@�@�@�@�@�ꂽ���̂�bfupload.pl�ɓn���悤�ɂ��A���� ()�̒���
#�@�@�@�@�@�@�@�@�����Ȃ��ł��������j
#
#�@�@�@$savef �F �t�@�C����ۑ�����^�C�v�̎w��ł��B
#�@�@�@�@�@�@�@�@-ow�F�A�b�v���[�h���ꂽ�t�@�C���̃t�@�C�������g�p
#�@�@�@�@�@�@�@�@-tm�F�A�b�v���[�h�̍ۂ̓������t�@�C�����Ɏg�p
#�@�@�@�@�@�@�@�@-fn�F�t�H�[����name=�Ŏw�肳�ꂽ�������t�@�C���Ɏg�p
#�@�@�@�@�@�@�@�@���j�����t�@�C���������݂����ꍇ�͏㏑������܂��B
#
#
#�@�@�@��fileupload.pl����Ԃ����f�[�^
#
#�@�@�@$flag�@�@�F �Ȃ�炩�̌����ŃG���[�ɂȂ�A�b�v���[�h�ł��Ȃ���
#�@�@�@�@�@�@�@�@�@���ꍇ�A������ f ���Ԃ���܂��B
#
#�@�@�@$msg�@ �@�F �G���[�������ꍇ�ȂǁA�����Ƀ��b�Z�[�W���Ԃ���܂��B
#
#�@�@�@$buffer�@�F �ʏ�̃t�H�[�����M�Ɠ��l�ɂt�q�k�G���R�[�h���ꂽ��
#�@�@�@�@�@�@�@�@�@�ԂŃt�H�[���̓��e���Ԃ���܂��B
#
#�@�@�@$savefile�F �ۑ������t�@�C�����Aname=�ۑ��t�@�C�����̌`�ŕԂ�
#�@�@�@�@�@�@�@�@�@��܂��Bname�����̓t�H�[����name=�Ŏw�肳�ꂽ����
#�@�@�@�@�@�@�@�@�@�ł��B��������ꍇ��&�Ō����������̂��Ԃ���܂��B
#�@�@�@�@�@�@�@�@�@��jname1=file1.txt&name2=file2.txt
#
#�����l
#
#�E�A�b�v���[�h����t�@�C���^�C�v�̃`�F�b�N�͊g���q�݂̂ōs���Ă��܂��B
#
#�E�t�@�C���ۑ��^�C�v�� -ow�̏ꍇ�A��M�����t�@�C���p�X�̖����ɂ���t
#�@�@�C�������g�p���܂��B
#
#�E�t�@�C���ۑ��^�C�v�� -ow�ȊO�̏ꍇ�A�K�؂ȃt�@�C�����{.�{�g���q ��
#�@�����`�ɂȂ�A���̂Ƃ��̊g���q�͎�M�����t�@�C���p�X�̖����ɂ���t
#�@�@�C�����ɂ����g���q���g�p����܂��B�i�t�@�C���ۑ��^�C�v�� -ow��
#�@�O�̏ꍇ�A�b�v���[�h����t�@�C���͕K���g���q���̃t�@�C�����ɂȂ�
#�@�Ă���K�v������܂��j
#
#�E��M�����f�[�^�͉��H�������̂܂ܕۑ����Ă��܂��B
#
#�E�����̃t�@�C�����A�b�v���[�h����ꍇ�A�����ꂩ�̃t�@�C���ŃT�C�Y��
#�@����t�@�C���^�C�v�̂��߃G���[�ɂȂ�ƁA�t�@�C���͂P���A�b�v���[
#�@�h����܂���B
#�@�ǂ̃t�@�C�����G���[�ɂȂ�Ȃ������ꍇ�̂݁A���ׂẴt�@�C�����A�b
#�@�v���[�h����܂��B
#


# �ǉ��\��
# �}�b�N�o�C�i���΍�
#	if ($_ =~ /application\/x-macbinary/i) { $macbin=1; }
#	if ($macbin) {
#		$length = substr($upfile,83,4);
#		$length = unpack("%N",$length);
#		$upfile = substr($upfile,128,$length);
#	}

sub receive {
	
	local @set = @_;
	local ($time,$buffer,$errflag,$errmsg,$boundary,$head,$body,$j);
    local ($fname,$name,$file,$filename,$formdata,$ctype,$allow,$savefile);
	local @allows;
	local @ar_file;
	local @ar_body;
	local @ar_errmsg;
	
	$errflag = '';
    $errmsg = '';
	
	#get_time
	local($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
	$year = ($year + 1900);
	$mon++;
	if ($sec < 10)	{$sec = "0$sec";}
	if ($min < 10)	{$min = "0$min";}
	if ($hour < 10)	{$hour = "0$hour";}
	if ($mon < 10)	{$mon = "0$mon";}
	if ($mday < 10)	{$mday = "0$mday";}
	$time = "$year$mon$mday$hour$min$sec";
	
	#check_all_data_size
	if($set[0] ne '' && $ENV{'CONTENT_LENGTH'} > $set[0]){
		push(@ar_errmsg,'�f�[�^�e�ʂ������l���z���Ă��܂��B');
		$errflag = 'f';
	}
	
	#check_form_type
	if($ENV{'REQUEST_METHOD'} eq "POST" && $ENV{'CONTENT_TYPE'} =~ /^multipart\/form-data/){
		binmode(STDIN);
		read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
	}
	else{
		push(@ar_errmsg,'�t�H�[���̐ݒ�Ɍ�肪����܂��B');
		$errflag = 'f';
	}
	
	
	#get_boundary
	if($ENV{'CONTENT_TYPE'} =~ /boundary.*?=.*?(-+\w*)$/){
		$boundary = $1;
	}
	
	@buffer = split(/$boundary/,$buffer);
	$j = 0;
	foreach $buffer(@buffer){
		if($buffer eq '' || $buffer =~ /^-+$/){
			next;
		}
		$buffer =~ s/^-+(\r\n)//;
		$buffer =~ s/(\r\n)-+$//;
		$buffer =~ s/^(\r\n)+|(\r\n)+$//g;
		($head,$body) = split(/\r\n\r\n/,$buffer,2);
		$head .= "\r\n";
		$head =~ m/name\s*?=\s*?"(.*?)"/;
		$fname = $name = $1;

		$name =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
		$name =~ s/ /+/g;

		if($head =~ /filename\s*?=\s*?"?(.*?)"?\r\n/){
			$file = $filename = $1;
			$file =~ m/.*[\/\\\:](.*)$/;
			$file = $1;
			if($set[4] eq '-tm'){
				$file =~ s/.*(\..*)/$time$1/;
			}
			elsif($set[4] eq '-fn'){
				$file =~ s/.*(\..*)/$fname$1/g;
			}
			$filename =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
			$filename =~ s/ /+/g;
			$formdata .= '&'.$name.'='.$filename;

			#check_each_file_size
			if($set[1] ne '' && length($body) ne '' && length($body) > $set[1]){
				push(@ar_errmsg, "$file �̃t�@�C���T�C�Y�������l���z���Ă��܂��B");
				$errflag = 'f';
			}

			#check_file_type
			$head =~ m/Content\-Type\:\s*(.*?)\r\n/i;
			$ctype = $1;
			@allows = split(/,/,$set[2]);
			$i = 0;
			foreach $allow(@allows){
				if($filename eq '' || $filename =~ /\.$allow$/){
					$i++;
				}
			}
			if($i == 0){
				push(@ar_errmsg, "$file �͈����Ȃ��t�@�C���^�C�v�ł��B");
				$errflag = 'f';
			}
			
			#ready_save
			push(@ar_file,$file);
			push(@ar_body,$body);
			$j++;
			$savefile .= '&'.$name.'='.$file;
		}
		else{
			$body =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
			$body =~ s/ /+/g;
			$formdata .= '&'.$name.'='.$body;
		}
	}
	
	
	$formdata =~ s/^\&//;
	$savefile =~ s/^\&//;
	
	if($#ar_errmsg+1 > 0){ $errmsg = join("\t",@ar_errmsg); }
	
	return($errflag,$errmsg,$formdata,$savefile,$j,\@ar_file,\@ar_body);
}




#-----------------------------------------------------------------------
#	������
#	
#   $errmsg = &FileUpload::write($dir,$alimit,$cnt,\@file_name,\@file_data);
#	
#-----------------------------------------------------------------------
sub write {
	
	local ($dir,$alimit,$j,$file,$body) = @_;
	local ($i,$f,$b,$errmsg,$cnt,$limit);
	
	
	#�f�B���N�g���`�F�b�N
	if($dir !~ /\/$/){
		$dir .= '/';
	}
	if($dir eq '' || ! -d $dir || ! -w $dir){
		$errmsg = '�ۑ��f�B���N�g���̐ݒ�Ɍ�肪����܂��B';
		return('1',$errmsg);
	}
	
	
	#�t�@�C���̗e��
	if($j != 0){
		$i = 0;
		while($i <= $j){
			$f = @$file[$i];
			$b = @$body[$i];
			if($f){	$limit = $limit + length($b); }
			$i++;
		}
	}
	if($limit > $alimit){
		$errmsg = '�g�p�\�̈�𒴂��Ă��܂��B';
		return('1',$errmsg);
	}
	
	
	$cnt = 0;
	#�t�@�C���̕ۑ�
	if($j != 0){
		$i = 0;
		while($i <= $j){
			$f = @$file[$i];
			$b = @$body[$i];
			if($f){
				open (FILE,">$dir$f");
				binmode (FILE);
				print FILE $b;
				close (FILE);
				$cnt++;
			}
			$i++;
		}
	}
	return('',$cnt);
	
}


1;
