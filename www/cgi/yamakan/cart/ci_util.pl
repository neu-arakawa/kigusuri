#
# util.pl  -- Ver.2002/01/23
#



#
# Auto_Link			�����Ń����N������			$return = &Auto_Link($target);
# ToHtmlTag			Html�\���p�����ϊ�			$return = &ToHtmlTag($target);
# Escape			�G�X�P�[�v					$return = &Escape($target);
# Unescape			�G�X�P�[�v�ϕ��������ɖ߂�	$return = &Unescape($target);
# Chk_Input			���̓f�[�^�̃`�F�b�N		$errStr = &Chk_Input(\@data);
# MachingPattern		�Œ萳�K�\�����C�u����		
# SetString_Array	������̓������z��쐬		@rdata  = &SetString_Array($HashKeyNames,$SplitChar,$String);
# SetArray_Array	������̓������z��쐬		@rdata  = &SetArray_Array($HashKeyNames,$SplitChar,\@StringArray);
#												@rdata  = &SetInitstr_Array($HashKeyNames,$SplitChar,$String,$�z��ő�Index��,$InitVal);
# Get_Dir			�ިڸ�ؓ���̧�ٖ��擾		@rdata  = &Get_Dir($pass,$order);
# Send_Mail			���[���̑��M				$errFlg = &Send_Mail($adr,$mailform);
# Disp_Html			Html�t�@�C���\��			$errFlg = &Disp_Html($FileName);
# Get_File			�t�@�C�����e�擾			$return = &Get_File($FileName,$mode);
# FileRead			�t�@�C�����e�擾			@rdata  = &FileRead($FileName,$start,$cnt);
# 
# TimeCal			���Ԃ̌v�Z					$return = &TimeCal($time,' + or - ' ,$time,$split);
# DataUnification	�f�[�^�̈ꌳ��				@rdata  = &DataUnification($targetDir);
# DataSort			�f�[�^�̕��ёւ�					  &DataSort($target,$order,\@data);
# CounterUP			�A�N�Z�X�J�E���g�A�b�v				  &CounterUP();
# Get_DirCapa		�f�B���N�g���̗e�ʌv�Z		$DirCapa [$DirRCapa] = &Get_DirCapa($Dir,[$Alimit]);
#



#---------------------------------------
#
#	�f�B���N�g���̗e�ʌv�Z
#
#---------------------------------------
sub Get_DirCapa {
	
	local($unit,$Dir,$Alimit) = @_;
	local($DirCapa,$DirRCapa);
	local @files;
	local @stattmp;
	
	
	# �P�ʂ̃Z�b�g
	if   ($unit eq 'KB'){ $u = 10 ** 3; }
	elsif($unit eq 'MB'){ $u = 10 ** 6; }
	elsif($unit eq 'TB'){ $u = 10 ** 9; }
	else                { $u = 1;       }
	
	
	$DirCapa = 0;
	opendir(DF,$Dir);
	@files = readdir DF;
	closedir(DF);
	foreach(@files){
		unless(-d $_){ 
			$filesize = (-s "$Dir$_");
			$DirCapa = $DirCapa + $filesize;
#			@stattmp = stat "$Dir$_";
#			$DirCapa = $DirCapa + $stattmp[7];
		}
	}
	
#	($DirCapa = readpipe "du $Dir") =~ s/(\d+)(\D+)/$1/;
#	if($DirCapa -1 == 0){ $DirCapa = 0; }else{ $DirCapa = $DirCapa * 10 ** 3; }
	
	if($Alimit){
		$DirRCapa = $Alimit - $DirCapa;
		if($DirCapa != 0){ $DirCapa  = int($DirCapa / $u); }
		if($DirRCapa != 0){ $DirRCapa  = int($DirRCapa / $u); }
		return($DirCapa,$DirRCapa);
	}else{
		if($DirCapa != 0){ $DirCapa  = int($DirCapa / $u); }
		return($DirCapa);
	}
}

#------------------------------------------------------------------------
# parseInput(encoding)
# <IN>  encoding: ���{��R�[�h(jis|sjis|euc)
# <OUT> Name=>Val �̃n�b�V���i�O���u�j
#------------------------------------------------------------------------
sub parseInput {
	local($encoding,$query) = @_;
	my($method) = $ENV{'REQUEST_METHOD'};
	local( @in, $key, $val);
	
	
	# ���{�ꂪ�K�v�ȏꍇ�� jcode.pl ����荞��
	require 'jcode.pl' if $encoding;
	
	
	# GET���\�b�h��POST���\�b�h���𔻕ʂ���
	if($query eq ''){
		if ($method eq 'GET') { $query = $ENV{'QUERY_STRING'}; 	}
		elsif ($method eq 'POST') { read(STDIN, $query, $ENV{'CONTENT_LENGTH'}); }
	}
	
	
	# ���̓f�[�^�𕪉�����
	local(@query) = split(/&/, $query);
	
	
	# Name=Val �� $in{'Name'} = 'Val' �̃n�b�V���ɂ���B
	foreach (@query) {
		
		# + ���󔒕����ɕϊ�
		tr/+/ /;
		
		# Name=Val �𕪂���
		($key, $val) = split(/=/);
		
		# %HH�`�������̕����Ƀf�R�[�h����B
		$key =~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack("c", hex($1))/ge;
		$val =~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack("c", hex($1))/ge;
		
		
		# if($key ne 'details' and $key ne 'keywords'){ $val =~ s/</&lt;/g; }
		# if($key ne 'details' and $key ne 'keywords'){ $val =~ s/>/&gt;/g; }
		$val = &Escape($val);
		
		
        		
		# ���{��R�[�h���w�肳��Ă���ꍇ�͕ϊ�����B
		jcode'convert(*key, $encoding) if ($encoding);
		jcode'convert(*val, $encoding) if ($encoding);
		
		
		
		# �A�z�z��i�n�b�V���j�ɃZ�b�g
        # added by SuGaC
		if($in{$key}){ $in{$key} .= "\t$val"; }
		else{ $in{$key} = $val; }
		
	}
	
	
	
	# �A�z�z��̃O���u��Ԃ�
	return *in;
	
}
#---------------------------------------
#
#	�N�b�L�[�̎擾
#
#---------------------------------------
sub Get_Cookie {
	
	local $cookies = $ENV{'HTTP_COOKIE'};
	local @pairs = split(/;/,$cookies);
	local($pair,$name,$value,$NAME,$DUMMY,);
	
	foreach $pair (@pairs) {
		($NAME, $value) = split(/=/, $pair);
		$NAME =~ s/ //g;
		$DUMMY{$NAME} = $value;
	}
	@pairs = split(/,/,$DUMMY{'DATA'});
	foreach $pair (@pairs) {
		($name, $value) = split(/:/, $pair);
		$COOKIE{$name} = $value;
	}
	
	
}
#---------------------------------------
#
#	�f�[�^�̈ꌳ��
#
#---------------------------------------
sub DataUnification{
	
	local ($targetDir) = @_; 
	local @fnames;
	local @rdata;
	local $fname = '';
	
	
	@fnames = &Get_Dir($targetDir,1);
	
	foreach $fname (@fnames){
		open(FH,"$targetDir$fname");
    		while (<FH>) { push(@rdata,$_); }
    	close(FH);
	}
	
	return(@rdata);
	
}
#---------------------------------------
#
#	�f�[�^�̃\�[�g
#
#---------------------------------------
# $target ��� �\�[�g�̏����ΏۃJ���� 
#             
#             
# $order  ��� �\�[�g�̏���
# 			  0:����
#             1:�~��
#			  
# $Data   ��� �Ώۃf�[�^�z��
#             \@�Ώ۔z�� �Ń��t�@�����X��n������
#
#---------------------------------------
sub DataSort {
 	
	local ($target,$order,$Data) = @_;
	local $num = $target;
	
	if($num eq ''){ $num = 1; }
	
	
	if($order == 0){
		@$Data = map {$_->[0]}
					sort {$a->[$num] cmp $b->[$num]}
					map {[$_, split /$SPC/]} @$Data;
	}else{
		@$Data = map {$_->[0]}
					sort {$b->[$num] cmp $a->[$num]}
					map {[$_, split /$SPC/]} @$Data;
	}
	
}
#---------------------------------------
#   
#   ���Ԃ̌v�Z
#   
#---------------------------------------
sub TimeCal {
	
	my ($a,$flg,$b,$pattern) = @_;
	my ($r_hh,$r_mm,$rdata);
	my ($a_hh,$a_mm);
	my ($b_hh,$b_mm);
	
	
	if($pattern eq ''){ $pattern = ':'; }
	
	($a_hh,$a_mm) = split($pattern,$a);
	($b_hh,$b_mm) = split($pattern,$b);
	
	
	## ��������
	if($flg eq '+'){
		$r_hh = int($a_hh) + int($b_hh);
		$r_mm = int($a_mm) + int($b_mm);
		
		if(int($r_mm) >= 60){
			$r_hh = int($r_hh) + 1;
			$r_mm = int($r_mm) - 60;
		}
		
		if(int($r_hh) >= 24){
			$r_hh = int($r_hh) - 24;
		}
	}
	
	## �Ђ�����
	elsif($flg eq '-'){
		$r_hh = int($a_hh) - int($b_hh);
		$r_mm = int($a_mm) - int($b_mm);
		
		if(int($r_mm) < 0){
			$r_hh = int($r_hh) - 1;
			$r_mm = int($r_mm) + 60;
		}
		if(int($r_hh) < 0){
			$r_hh = int($r_hh) + 24;
		}
	}
	
	$rdata = sprintf("%02d:%02d", $r_hh, $r_mm);
	return($rdata);
	
}





#---------------------------------------
#   
#   NULL��������
#   
#---------------------------------------
sub SetNull {
	
	my ($data) = @_;
	my $rdata;
	
	if($data){ $rdata = $data; }else{ $rdata='NULL'; }
	
	return($rdata);
}



#---------------------------------------
#   
#   ���������N
#   
#---------------------------------------
sub AutoLink {
	$_[0] =~ s/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/$1<a href=\"$2\" target=\"_blank\">$2<\/a>/g;
	return($_[0]);
}



#---------------------------------------
#   
#   �g�s�l�k�\���p�ɕϊ�����
#   
#---------------------------------------
sub ToHtmlTag{
	local($w,$flg) = @_;
	if($flg eq ''){
		$w =~ s/&LT;/</g;
		$w =~ s/&GT;/>/g;
	}
	$w =~ s/&quot;/"/g;
	$w =~ s/����/<br>/g;
	$val =~ s/$SPEC/$SPC/g;
	return($w);
}

#---------------------------------------
#   
#   �G�X�P�[�v
#   
#---------------------------------------
sub Escape{
	local($w) = @_;
	$w =~ s/\r\n/\n/g;
	$w =~ s/\r/\n/g;
	$w =~ s/\n+$/\n/;
	$w =~ s/\cM//g;
	$w =~ s/</&LT;/g;
	$w =~ s/>/&GT;/g;
	$w =~ s/\n/����/g;
	$w =~ s/$SPC/$SPCE/g;
	return($w);
}

#---------------------------------------
#   
#   �G�X�P�[�v�ϕ��������ɖ߂�
#   
#---------------------------------------
sub Unescape{
	local($w) = @_;
	$w =~ s/&LT;/</g;
	$w =~ s/&GT;/>/g;
	$w =~ s/&quot;/"/g;
	$w =~ s/����/\n/g;
	$val =~ s/$SPEC/$SPC/g;
	return($w);
}

#---------------------------------------
#
#   ���̓f�[�^�̃`�F�b�N
#
#   @ChkFormat�ɍ��ڐݒ���s���Ďg�p����
#   Chk_Input(\@data);
#---------------------------------------
sub	Chk_Input{
	
	local($ChkFormat) = @_;
	
	local($key,$name,$must,$len,$match,$target_val,$errbuf,$lenStr);
	
	&MatchingPattern();
	
	$errbuf = '';
	### ���̓`�F�b�N�� 
	foreach (@$ChkFormat){
		
		$_ =~ s/\t//g;
		($key, $name, $must, $len ,$alink, $match) = split("###");
		$target_val = $in{"$key"};
		
		
		### Web�\���p�f�[�^�쐬
		$disp{$key} = &ToHtmlTag($in{$key});
		if($alink == 1){ $disp{$key} = &AutoLink($disp{$key}); }
		
		
		### Mail�\���p�f�[�^�쐬
		$mail{$key} = &Unescape($in{$key});
		
		
		
		### �K�{�`�F�b�N
		if($must && !$target_val){
			$errbuf .= "<li> $name �͕K�{���͍��ڂł��B</li>\n";
			next;
		}
		
		
		### �������`�F�b�N
		if( (int($len) > 0) and (length($target_val) > int($len)) ) {
			$lenStr = int($len / 2);
			$errbuf .= "<li> $name �� $lenStr �����܂łł��B</li>\n";
			next;
		}
		
		
		### ���K�\���`�F�b�N
		if($match){
			$match = $PATTERN{$match} || $match;
			(!$target_val) || ($target_val =~ /$match/) || ($errbuf .= "<li> $name �𐳂������͂��Ă��������B <i>(" . $in{"$key"} . ")</i></li>\n");
		}
		
		
		
	}
	
	if($errbuf){ $errbuf = "<font color=\"#ff2222\">�@�@�@�@���̓G���[���ڂ�����܂��B</font>\n<ul>\n" . $errbuf . "</ul>\n";}
	return($errbuf);
	
}

#---------------------------------------
#
#   �Œ�̐��K�\�����C�u����
#
#---------------------------------------
sub MatchingPattern{
	$PATTERN{'NUMBER'} = '^\d+$';
	$PATTERN{'WORD'} = '^\w+$';
	$PATTERN{'EMAIL'} = '^[\w\-\+\.]+\@[\w\-\+\.]+$';
	$PATTERN{'URL'} = '^http://.+$';
	$PATTERN{'ZIP'} = '^\d{3}(-\d{2,4})?$';
	$PATTERN{'TEL'} = '^\d+-\d+-\d+$';
	$PATTERN{'YEAR'} = '^20\d{2}$';
	$PATTERN{'MONTH'} = '(^1$|^2$|^3$|^4$|^5$|^6$|^7$|^8$|^9$|^10$|^11$|^12$|^01$|^02$|^03$|^04$|^05$|^06$|^07$|^08$|^09$)';
	$PATTERN{'DAY'} = '(^1$|^2$|^3$|^4$|^5$|^6$|^7$|^8$|^9$|^10$|^11$|^12$|^13$|^14$|^15$|^16$|^17$|^18$|^19$|^20$|^21$|^22$|^23$|^24$|^25$|^26$|^27$|^28$|^29$|^30$|^31$|^01$|^02$|^03$|^04$|^05$|^06$|^07$|^08$|^09$)';
	$PATTERN{'MAIL_OR_TEL'} = '(^[\w\-\+\.]+\@[\w\-\+\.]+$$|^^[\d\-]+$)';
	
	
}



#---------------------------------------
#   
#   ������̓������z��쐬�P
#   
#---------------------------------------
sub SetString_Array {
	
	local ($hkn,$spchar,$string) = @_;
	local @data;
	local @rdata;
	
	@data = split($spchar,$hkn);
	foreach(@data){
		
		$rdata[$_] = $string;
		
	}
	
	return(@rdata);
	
}
#---------------------------------------
#   
#   ������(�z����I��)�̓������z��쐬�Q
#   
#---------------------------------------
sub SetArray_Array {
	
	local ($hkn,$spchar,$string) = @_;
	local @data;
	local @rdata;
	
	@data = split($spchar,$hkn);
	foreach(@data){
		
		$rdata[$_] = @$string[$_];
		
	}
	
	return(@rdata);
	
}
#---------------------------------------
#   
#   ������̓������z��쐬�R
#   
#---------------------------------------
sub SetInitstr_Array {
	
	local ($hkn,$spchar,$string,$max,$initval) = @_;
	local @data;
	local @rdata;
	local $cnt;
	
	for( $cnt=0; $cnt <= $max; ++$cnt){
		$rdata[$cnt] = $initval;
	}
	
	
	@data = split($spchar,$hkn);
	foreach(@data){
		
		$rdata[$_] = $string;
		
	}
	
	return(@rdata);
	
}




#---------------------------------------
#
#   ���݂̎����𓾂�
#
#---------------------------------------
sub Get_Time{
	local($tsec, $format) = @_;
	($tsec = 0) || ($tsec = time());
	local($sec, $min, $hour, $mday, $mon, $year) = localtime($tsec);
	$mon++;
	$year += 1900;
	unless($format){ $format = 0; }
	if($format == 0) { return sprintf("%04d/%02d/%02d", $year, $mon, $mday); }
	if($format == 1) { return sprintf("%04d/%02d/%02d %02d:%02d", $year, $mon, $mday, $hour, $min); }
	if($format == 2) { return sprintf("%04d%02d%02d%02d%02d%02d", $year, $mon, $mday, $hour, $min, $sec); }
	if($format == 3) { return sprintf("%02d%02d%02d%02d%02d",  $mon, $mday, $hour, $min, $sec); }
}



#---------------------------------------
#
#	�L���m�n�@�擾
#
#---------------------------------------
sub Get_Kjno{
 	
	$KJNO_SEQr = './kjno_seq2';
	$KJNO_SEQw = './kjno_seq1';
	
	local $fnamer = $KJNO_SEQr;
	local $fnamew = $KJNO_SEQw;
	local $retcnt;
	
 	if ( ( -M $fnamer ) > ( -M $fnamew ) ){
 		local $fnamea = $fnamer;
		      $fnamer = $fnamew;
		      $fnamew = $fnamea;
}
	
	open (SEQ_FILE,"$fnamer");
		local $oldcnt = <SEQ_FILE>;
	close (SEQ_FILE);
	local $newcnt = $oldcnt + 1;
	open (SEQ_FILE,">$fnamew");
		print SEQ_FILE $newcnt;
	close (SEQ_FILE);
	
	$retcnt = sprintf("%08d",$oldcnt);
	return $retcnt;
}



#---------------------------------------
#
#	�A�N�Z�X�J�E���^�@�J�E���g�A�b�v
#
#---------------------------------------
sub CounterUP{
 	
	local $fnamer = $ACNT_SEQr;
	local $fnamew = $ACNT_SEQw;
	
	if ( ( -M $fnamer ) > ( -M $fnamew ) ){
 		local $fnamea = $fnamer;
	      $fnamer = $fnamew;
		      $fnamew = $fnamea;
	}
	
	open (SEQ_FILE,"$fnamer");
		local $oldcnt = <SEQ_FILE>;
	close (SEQ_FILE);
	local $newcnt = $oldcnt + 1;
	open (SEQ_FILE,">$fnamew");
		print SEQ_FILE $newcnt;
	close (SEQ_FILE);
	
}



#---------------------------------------
#
#	�f�B���N�g�����̃t�@�C�����Q�b�g
#
#---------------------------------------
sub Get_Dir {
 	
	local ($path,$order) = @_;
	local @rdata;
	local @newlist;
	local @list;
	
	opendir(DIR,"$path");
		@list = readdir(DIR);
	closedir(DIR);
	
	if($order == 1){
		@newlist = sort(@list);
	}else{
		@newlist = sort{ $b cmp $a }@list;
	}
	
	foreach(@newlist){
		if($_ !~ /^\./ ){ push(@rdata,$_); }
	}
	
	return(@rdata);
	
}




#---------------------------------------
#
#	���[�����M 
#
#---------------------------------------
sub Send_Mail{
 
 my($l_mail,$l_mailform) = @_;
 my $errflg = 0;
 
 
 # ���[�����M
 if(!open(MAIL, "|$nkfmail|$sendmail $l_mail")) { $errflg = 1; return($errflg); }
 if(!open(TEMP,"$l_mailform"))                  { $errflg = 1; return($errflg); }
     while (<TEMP>) {
         s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
         print MAIL $_;
     }
     close(TEMP);
 close(MAIL);
 
 return($errflg);
 
}





#-------------------------------------
# Disp_Html                           
#-------------------------------------
# <IN>   �\������HTML�t�@�C���̃p�X   
# <OUT>  ����                         
#-------------------------------------
sub Disp_Html {
    
    my($FileName) = @_;
    my $err = 0;
    
    if(!open(HTML,"$FileName")){ $err = 1; return $err; }
	print "Content-type: text/html\n\n";
    while (<HTML>) {
        s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
		s/����/\n/;
        print;
    }
    close(HTML);
    
    return $err;
    
}
#---------------------------------------------------------
# Create_Html                                               
#---------------------------------------------------------
# <IN>   �\������HTML�t�@�C���̃p�X,�쐬�����t�@�C����  
# <OUT>  ����                                             
#---------------------------------------------------------
sub Create_Html {
    
    my($FileName,$WFName) = @_;
    my $err = 0;
    
    if(!open(HTML,"$FileName")){ $err = 1; return $err; }
	&openLock(FH,">$WFName");
    while (<HTML>) {
        s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
		s/����/\n/;
        print FH $_;
    }
    &closeUnlock(FH,$WFName);
	close(HTML);
    
    return $err;
    
}

#-------------------------------------
# Get_File                            
#-------------------------------------
#  mode = 1�Ńt�@�C�����ϐ����u��     
#-------------------------------------
sub Get_File {
    
    my($FileName,$mode) = @_;
    my $rdata;
    my $err=0;
    
    if(!open(FH,"$FileName")){ return('Error'); }
    while (<FH>) {
        if($mode == 1){ s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg; }
        $rdata = $rdata . $_;
    }
    close(FH);
    return($rdata);
    
}


#-----------------------------------------
# FileRead                                
#-----------------------------------------
# �w��͈͓��̍s�����t�@�C������ǂݍ���  
#-----------------------------------------
sub FileRead {
    
    my($FileName,$start,$cnt) = @_;
    my @rdata;
    my $err=0;
    my $indx=1;
	my $flg=0;
	
	if(($start ne '') and ($cnt ne '')){ my $end = $start + $cnt; $flg = 1; }
	
	
    if(!open(FH,"$FileName")){ return(1); }
    while (<FH>) {
        if($flg){
			if( ($indx >= $start) and ($indx <= $end) ){ push(@rdata,$_); }
			$indx = $indx + 1;
		}else{
			push(@rdata,$_);
		}
    }
    close(FH);
    return(@rdata);
    
}















# ���b�N�֘A�̐ݒ�l

$RetryNum = 100;		# ���g���C��

$Interval = 0.1;		# ���g���C�̃C���^�[�o��

$EX_LOCK = 2;			# �r�����b�N

$UN_LOCK = 8;			# ���b�N����

$LOCKTYPE = $EX_LOCK;		# ���b�N�^�C�v�͔r�����b�N

$useflock = 0;			# flock()���g���ꍇ��1�ɂ���

$LPrefix = 'L-';		# ���b�N�t�@�C���̃v���t�B�N�X

#
# lock(lfh, lockfile)
# <IN>  lfh: ���b�N�t�@�C���̃n���h���iopenLock�Ŏw�肳�ꂽ�t�@�C����
#       �ɓ������B
#       lockfile: ���b�N�t�@�C����
# <OUT> true: ����  false: ���s
#
sub lock($$)	{
	my($lfh, $lockfile) = @_;
	
	if ($useflock) {		# flock()���g��
		flock($lfh, $LOCKTYPE);
		return 1;
	}
	else {
		my($retry) = $RetryNum;
		while (-f $lockfile) {
			select(undef, undef, undef, $Interval);
			return undef if (--$retry <= 0);
		}
		return open($lfh, ">$lockfile");
	}
}





#
# unlock(lfh, lockfile)
# <IN>  lfh: ���b�N�t�@�C���̃n���h���iopenLock�Ŏw�肳�ꂽ�t�@�C����
#       �ɓ������B
#      lockfile: ���b�N�t�@�C����
# <OUT> �Ȃ�
#

sub unlock ($$){
	my($lfh, $lockfile) = @_;

	if ($useflock) {		# flock()���g��
		flock($lfh, $UN_LOCK);
	}
	else {
		close($lfh);
		unlink($lockfile);
	}
}


#
# openLock(fh, modefile)
# <IN>  fh: �n���h��
#       modefile: ���[�h���܂ރt�@�C����
# <OUT> true or false
#
sub openLock(*$){
	my($fh, $modefile) = @_;
	my($lockf);
	
	
	($mode, $file) = ($modefile =~ /^(\+?(?:<|>>?)\s*?)(.+)$/);
	
	if ($file =~ /(\/|\\)/) {
		($path, $filename) = ($file =~ /^(.*[\/|\\])(.+)$/);
	}else {
		$path = '';
		$filename = $file;
	}
	
	
	
	return undef unless $filename;
	
	$lockf = $path.$LPrefix."$filename";	# ���b�N�t�@�C����
	
	lock($filename, $lockf) or return undef;
	
	
	
	open($fh, $modefile)
		or unlock($filename, $lockf), return undef;
	
	return 1;
	
}
#-------------------------------------------------
# closeUnlock
# <IN>  fh: �n���h��
#       modefile: ���[�h���܂ރt�@�C����
# <OUT> �Ȃ�
#-------------------------------------------------
sub closeUnlock(*$){
	my($fh, $file) = @_;
	
	if ($file =~ /(\/|\\)/) {
		($path, $filename) = ($file =~ /^(.*[\/|\\])(.+)$/);
	}
	else {
		$path = '';
		$filename = $file;
	}
	my($lockfile) = $path.'L-'."$filename";	# ���b�N�t�@�C����
	unlock($filename, $lockfile);
	
	close($fh);
	
}
#-------------------------------------------------
# exitError()
# <IN>  �G���[���b�Z�[�W
# <OUT> �Ȃ�
#-------------------------------------------------
sub exitError {

	my($msg) = @_;



	$msg =~ s/\n/<BR>\n/g;

print <<END_OF_ERR_HTML;

Content-Type: text/html


<HTML>
<HEAD>
<TITLE>�G���[�I�I</TITLE>
</HEAD>
<BODY>
<FONT color="#FF0000">$msg</FONT><BR>
<FONT color="#0000FF">$!</FONT>
</BODY>
</HTML>
END_OF_ERR_HTML


	exit(1);
}
#---------------------------------------
# 
# �N�b�L�[���쐬����
# 
#---------------------------------------
#
#	Set-Cookie: �N�b�L�[��=�N�b�L�[�l;
#				�u���E�U�[�ɕۑ��������ϐ����Ƃ��̒l���Z�b�g���܂��B�X
#				�y�[�X�A�J���}�A�Z�~�R�����͊܂܂�Ă͂����܂���B
#				���̂��� URL �G���R�[�h������K�v������܂��B�i��q�j
#				Cookie ���Z�b�g����ۂɂ́A���̍��ڂ݂̂��K�{�ł��B
#			  expires=�L������;
#				�N�b�L�[�̗L���������Z�b�g���܂��B�L�������� GMT �Ŏw�肵�܂��B
#				�t�H�[�}�b�g�͈ȉ��̂Ƃ���ł��B 
#					Wdy, DD-Mon-YYYY HH:MM:SS GMT
#					Fri, 05-Mar-2010 04:00:00 GMT;
#				�L���������ȗ������ƁA�e���|�����[�N�b�L�[�Ƃ��Ĉ����܂��B
#				�܂�A�u���E�U�[��������_�ŁA���̃N�b�L�[�͖����ƂȂ�܂��B
#			  domain=�h���C�����i�T�[�o���j;
#				�Z�b�g�����N�b�L�[�����M�����h���C�����w�肵�܂��B
#				�T�[�o���Ŏw�肷��΁A�w��T�[�o�ւ̃A�N�Z�X�̎������Z�b�g�����N�b�L�[�𑗐M���܂��B#				�h���C�����ȗ������ƁA���̂Ƃ��A�N�Z�X�����T�[�o�����Z�b�g����܂��B
#			  path=�p�X;
#				�Z�b�g�����N�b�L�[�����M�����p�X���w�肵�܂��B
#				�p�X���ȗ������ƁA�A�N�Z�X�������\�[�X�iHTML�ACGI�j�̃p�X���Z�b�g����܂��B
#			  secure
#				���̍��ڂ��w�肳��Ă���ƁA�A�N�Z�X�悪 SSL �Ȃǂ̂悤�Ȉ��S�ȃT�C�g�̏ꍇ�݂̂�
#				�N�b�L�[�𑗐M����悤�ɂȂ�܂��B�h���C���A�p�X����v�����Ƃ��Ă��A
#				�A�N�Z�X�悪���S�Ƃ݂Ȃ���Ȃ��ƁA�N�b�L�[�𑗐M���܂���B
#
# URL �G���R�[�h
#   $CookieValue = '�e�X�g';
#   $CookieValue =~ s/([^\w\=\& ])/'%' . unpack("H2", $1)/eg;
#   $CookieValue =~ tr/ /+/;
#	
# URL �f�R�[�h
#	$CookieValue =~ s/\+/ /g;
#	$CookieValue =~ s/%([0-9a-fA-F][0-9a-fA-F])/pack("C",hex($1))/eg;
#	
#---------------------------------------
sub setCookie {
	
	local(*data) = @_;
	my($cookie);
	
	
	$cookie = qq(Set-Cookie: );
	foreach (keys %data) {
		$cookie = qq($cookie $_=$data{$_};);
	}
	
	return $cookie;
}
#---------------------------------------
#
#   GMT �𓾂�
#
#---------------------------------------
sub Get_GMT{
	
	local ($addtime) = @_;
	local ($month,$youbi,$date_gmt);
	
	if($addtime eq ''){ $addtime = 30*24*60*60; }
	($secg,$ming,$hourg,$mdayg,$mong,$yearg,$wdayg,$ydayg,$isdstg) = gmtime(time + $addtime);
	$yearg += 1900;
	if($secg  < 10){$secg ="0$secg"; }
	if($ming  < 10){$ming ="0$ming"; }
	if($hourg < 10){$hourg="0$hourg";}
	if($mdayg < 10){$mdayg="0$mdayg";}
	$month = ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec')[$mong];
	$youbi = ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')[$wdayg];
	$date_gmt = "$youbi, $mdayg\-$month\-$yearg $hourg:$ming:$secg GMT";
	
	return($date_gmt);
}



#
# �c�F���[�̌������j�������߂�
# �O���S���E�X��(1582�N10��15��(��)�ߌ�Ȍ�)�ŗL��
#
# <IN> �N�A���A��
# <OUT> �j�� (0:���j�� - 6:�y�j��)
#
sub getDayOfWeek

{

	my($year, $month, $day) = @_;



	# 1���܂���2���̏ꍇ�͑O�N��13�������14���Ƃ݂Ȃ�

	if ($month <= 2) {

		--$year;

		$month += 12;

	}



	return (($year + int($year/4) - int($year/100) + int($year/400)

					+ int((13*$month + 8)/5) + $day) % 7);

}



#
# <IN>	����N��
# <OUT> ���邤�N: true  ���N: false
#
sub leap

{

	my($year) = @_;



	if ($year % 100) {		# ����N���� 100 �Ŋ���؂�Ȃ�

		if ($year % 4) {	# ����N���� 4 �Ŋ���؂�Ȃ�

			return 0;	# ���N

		}

		else {

			return 1;	# ���邤�N

		}

	}

	else {					# ����N���� 100 �Ŋ���؂��

		if ($year % 400) {	# ����N���� 400 �Ŋ���؂�Ȃ�

			return 0;	# ���N

		}

		else {				# ����N���� 400 �Ŋ���؂��

			if ($year % 4000) {	# ����N���� 4000 �Ŋ���؂�Ȃ�

				return 1;	# ���邤�N

			}

			else {			# ����N���� 4000 �Ŋ���؂��

				return 0;	#���N

			}

		}

	}

}



@LeapYear = (0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

@NormYear = (0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);



#
# <IN>  �N�A��
# <OUT> ���̌��̍��v����
#
sub getDaysOfMonth

{

	my($year, $month) = @_;



	return leap($year) ? $LeapYear[$month] : $NormYear[$month];

}



1;

