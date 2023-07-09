#
# util.pl  -- Ver.2002/01/23
#



#
# Auto_Link			自動でリンクさせる			$return = &Auto_Link($target);
# ToHtmlTag			Html表示用文字変換			$return = &ToHtmlTag($target);
# Escape			エスケープ					$return = &Escape($target);
# Unescape			エスケープ済文字を元に戻す	$return = &Unescape($target);
# Chk_Input			入力データのチェック		$errStr = &Chk_Input(\@data);
# MachingPattern		固定正規表現ライブラリ		
# SetString_Array	文字列の入った配列作成		@rdata  = &SetString_Array($HashKeyNames,$SplitChar,$String);
# SetArray_Array	文字列の入った配列作成		@rdata  = &SetArray_Array($HashKeyNames,$SplitChar,\@StringArray);
#												@rdata  = &SetInitstr_Array($HashKeyNames,$SplitChar,$String,$配列最大Index数,$InitVal);
# Get_Dir			ﾃﾞｨﾚｸﾄﾘ内のﾌｧｲﾙ名取得		@rdata  = &Get_Dir($pass,$order);
# Send_Mail			メールの送信				$errFlg = &Send_Mail($adr,$mailform);
# Disp_Html			Htmlファイル表示			$errFlg = &Disp_Html($FileName);
# Get_File			ファイル内容取得			$return = &Get_File($FileName,$mode);
# FileRead			ファイル内容取得			@rdata  = &FileRead($FileName,$start,$cnt);
# 
# TimeCal			時間の計算					$return = &TimeCal($time,' + or - ' ,$time,$split);
# DataUnification	データの一元化				@rdata  = &DataUnification($targetDir);
# DataSort			データの並び替え					  &DataSort($target,$order,\@data);
# CounterUP			アクセスカウントアップ				  &CounterUP();
# Get_DirCapa		ディレクトリの容量計算		$DirCapa [$DirRCapa] = &Get_DirCapa($Dir,[$Alimit]);
#



#---------------------------------------
#
#	ディレクトリの容量計算
#
#---------------------------------------
sub Get_DirCapa {
	
	local($unit,$Dir,$Alimit) = @_;
	local($DirCapa,$DirRCapa);
	local @files;
	local @stattmp;
	
	
	# 単位のセット
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
# <IN>  encoding: 日本語コード(jis|sjis|euc)
# <OUT> Name=>Val のハッシュ（グロブ）
#------------------------------------------------------------------------
sub parseInput {
	local($encoding,$query) = @_;
	my($method) = $ENV{'REQUEST_METHOD'};
	local( @in, $key, $val);
	
	
	# 日本語が必要な場合は jcode.pl を取り込む
	require 'jcode.pl' if $encoding;
	
	
	# GETメソッドかPOSTメソッドかを判別する
	if($query eq ''){
		if ($method eq 'GET') { $query = $ENV{'QUERY_STRING'}; 	}
		elsif ($method eq 'POST') { read(STDIN, $query, $ENV{'CONTENT_LENGTH'}); }
	}
	
	
	# 入力データを分解する
	local(@query) = split(/&/, $query);
	
	
	# Name=Val を $in{'Name'} = 'Val' のハッシュにする。
	foreach (@query) {
		
		# + を空白文字に変換
		tr/+/ /;
		
		# Name=Val を分ける
		($key, $val) = split(/=/);
		
		# %HH形式を元の文字にデコードする。
		$key =~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack("c", hex($1))/ge;
		$val =~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack("c", hex($1))/ge;
		
		
		# if($key ne 'details' and $key ne 'keywords'){ $val =~ s/</&lt;/g; }
		# if($key ne 'details' and $key ne 'keywords'){ $val =~ s/>/&gt;/g; }
		$val = &Escape($val);
		
		
        		
		# 日本語コードが指定されている場合は変換する。
		jcode'convert(*key, $encoding) if ($encoding);
		jcode'convert(*val, $encoding) if ($encoding);
		
		
		
		# 連想配列（ハッシュ）にセット
        # added by SuGaC
		if($in{$key}){ $in{$key} .= "\t$val"; }
		else{ $in{$key} = $val; }
		
	}
	
	
	
	# 連想配列のグロブを返す
	return *in;
	
}
#---------------------------------------
#
#	クッキーの取得
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
#	データの一元化
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
#	データのソート
#
#---------------------------------------
# $target ･･･ ソートの条件対象カラム 
#             
#             
# $order  ･･･ ソートの順序
# 			  0:昇順
#             1:降順
#			  
# $Data   ･･･ 対象データ配列
#             \@対象配列 でリファレンスを渡すこと
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
#   時間の計算
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
	
	
	## たしざん
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
	
	## ひきざん
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
#   NULL文字埋め
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
#   自動リンク
#   
#---------------------------------------
sub AutoLink {
	$_[0] =~ s/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/$1<a href=\"$2\" target=\"_blank\">$2<\/a>/g;
	return($_[0]);
}



#---------------------------------------
#   
#   ＨＴＭＬ表示用に変換する
#   
#---------------------------------------
sub ToHtmlTag{
	local($w,$flg) = @_;
	if($flg eq ''){
		$w =~ s/&LT;/</g;
		$w =~ s/&GT;/>/g;
	}
	$w =~ s/&quot;/"/g;
	$w =~ s/￥ｎ/<br>/g;
	$val =~ s/$SPEC/$SPC/g;
	return($w);
}

#---------------------------------------
#   
#   エスケープ
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
	$w =~ s/\n/￥ｎ/g;
	$w =~ s/$SPC/$SPCE/g;
	return($w);
}

#---------------------------------------
#   
#   エスケープ済文字を元に戻す
#   
#---------------------------------------
sub Unescape{
	local($w) = @_;
	$w =~ s/&LT;/</g;
	$w =~ s/&GT;/>/g;
	$w =~ s/&quot;/"/g;
	$w =~ s/￥ｎ/\n/g;
	$val =~ s/$SPEC/$SPC/g;
	return($w);
}

#---------------------------------------
#
#   入力データのチェック
#
#   @ChkFormatに項目設定を行って使用する
#   Chk_Input(\@data);
#---------------------------------------
sub	Chk_Input{
	
	local($ChkFormat) = @_;
	
	local($key,$name,$must,$len,$match,$target_val,$errbuf,$lenStr);
	
	&MatchingPattern();
	
	$errbuf = '';
	### 入力チェック部 
	foreach (@$ChkFormat){
		
		$_ =~ s/\t//g;
		($key, $name, $must, $len ,$alink, $match) = split("###");
		$target_val = $in{"$key"};
		
		
		### Web表示用データ作成
		$disp{$key} = &ToHtmlTag($in{$key});
		if($alink == 1){ $disp{$key} = &AutoLink($disp{$key}); }
		
		
		### Mail表示用データ作成
		$mail{$key} = &Unescape($in{$key});
		
		
		
		### 必須チェック
		if($must && !$target_val){
			$errbuf .= "<li> $name は必須入力項目です。</li>\n";
			next;
		}
		
		
		### 文字数チェック
		if( (int($len) > 0) and (length($target_val) > int($len)) ) {
			$lenStr = int($len / 2);
			$errbuf .= "<li> $name は $lenStr 文字までです。</li>\n";
			next;
		}
		
		
		### 正規表現チェック
		if($match){
			$match = $PATTERN{$match} || $match;
			(!$target_val) || ($target_val =~ /$match/) || ($errbuf .= "<li> $name を正しく入力してください。 <i>(" . $in{"$key"} . ")</i></li>\n");
		}
		
		
		
	}
	
	if($errbuf){ $errbuf = "<font color=\"#ff2222\">　　　　入力エラー項目があります。</font>\n<ul>\n" . $errbuf . "</ul>\n";}
	return($errbuf);
	
}

#---------------------------------------
#
#   固定の正規表現ライブラリ
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
#   文字列の入った配列作成１
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
#   文字列(配列より選択)の入った配列作成２
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
#   文字列の入った配列作成３
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
#   現在の時刻を得る
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
#	記事ＮＯ　取得
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
#	アクセスカウンタ　カウントアップ
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
#	ディレクトリ内のファイル名ゲット
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
#	メール送信 
#
#---------------------------------------
sub Send_Mail{
 
 my($l_mail,$l_mailform) = @_;
 my $errflg = 0;
 
 
 # メール送信
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
# <IN>   表示するHTMLファイルのパス   
# <OUT>  正否                         
#-------------------------------------
sub Disp_Html {
    
    my($FileName) = @_;
    my $err = 0;
    
    if(!open(HTML,"$FileName")){ $err = 1; return $err; }
	print "Content-type: text/html\n\n";
    while (<HTML>) {
        s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
		s/￥ｎ/\n/;
        print;
    }
    close(HTML);
    
    return $err;
    
}
#---------------------------------------------------------
# Create_Html                                               
#---------------------------------------------------------
# <IN>   表示するHTMLファイルのパス,作成されるファイル名  
# <OUT>  正否                                             
#---------------------------------------------------------
sub Create_Html {
    
    my($FileName,$WFName) = @_;
    my $err = 0;
    
    if(!open(HTML,"$FileName")){ $err = 1; return $err; }
	&openLock(FH,">$WFName");
    while (<HTML>) {
        s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
		s/￥ｎ/\n/;
        print FH $_;
    }
    &closeUnlock(FH,$WFName);
	close(HTML);
    
    return $err;
    
}

#-------------------------------------
# Get_File                            
#-------------------------------------
#  mode = 1でファイル内変数名置換     
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
# 指定範囲内の行数をファイルから読み込む  
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















# ロック関連の設定値

$RetryNum = 100;		# リトライ回数

$Interval = 0.1;		# リトライのインターバル

$EX_LOCK = 2;			# 排他ロック

$UN_LOCK = 8;			# ロック解除

$LOCKTYPE = $EX_LOCK;		# ロックタイプは排他ロック

$useflock = 0;			# flock()を使う場合は1にする

$LPrefix = 'L-';		# ロックファイルのプリフィクス

#
# lock(lfh, lockfile)
# <IN>  lfh: ロックファイルのハンドル（openLockで指定されたファイル名
#       に等しい。
#       lockfile: ロックファイル名
# <OUT> true: 成功  false: 失敗
#
sub lock($$)	{
	my($lfh, $lockfile) = @_;
	
	if ($useflock) {		# flock()を使う
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
# <IN>  lfh: ロックファイルのハンドル（openLockで指定されたファイル名
#       に等しい。
#      lockfile: ロックファイル名
# <OUT> なし
#

sub unlock ($$){
	my($lfh, $lockfile) = @_;

	if ($useflock) {		# flock()を使う
		flock($lfh, $UN_LOCK);
	}
	else {
		close($lfh);
		unlink($lockfile);
	}
}


#
# openLock(fh, modefile)
# <IN>  fh: ハンドル
#       modefile: モードを含むファイル名
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
	
	$lockf = $path.$LPrefix."$filename";	# ロックファイル名
	
	lock($filename, $lockf) or return undef;
	
	
	
	open($fh, $modefile)
		or unlock($filename, $lockf), return undef;
	
	return 1;
	
}
#-------------------------------------------------
# closeUnlock
# <IN>  fh: ハンドル
#       modefile: モードを含むファイル名
# <OUT> なし
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
	my($lockfile) = $path.'L-'."$filename";	# ロックファイル名
	unlock($filename, $lockfile);
	
	close($fh);
	
}
#-------------------------------------------------
# exitError()
# <IN>  エラーメッセージ
# <OUT> なし
#-------------------------------------------------
sub exitError {

	my($msg) = @_;



	$msg =~ s/\n/<BR>\n/g;

print <<END_OF_ERR_HTML;

Content-Type: text/html


<HTML>
<HEAD>
<TITLE>エラー！！</TITLE>
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
# クッキーを作成する
# 
#---------------------------------------
#
#	Set-Cookie: クッキー名=クッキー値;
#				ブラウザーに保存したい変数名とその値をセットします。ス
#				ペース、カンマ、セミコロンは含まれてはいけません。
#				そのため URL エンコードをする必要があります。（後述）
#				Cookie をセットする際には、この項目のみが必須です。
#			  expires=有効期限;
#				クッキーの有効期限をセットします。有効期限は GMT で指定します。
#				フォーマットは以下のとおりです。 
#					Wdy, DD-Mon-YYYY HH:MM:SS GMT
#					Fri, 05-Mar-2010 04:00:00 GMT;
#				有効期限が省略されると、テンポラリークッキーとして扱われます。
#				つまり、ブラウザーを閉じた時点で、そのクッキーは無効となります。
#			  domain=ドメイン名（サーバ名）;
#				セットしたクッキーが送信されるドメインを指定します。
#				サーバ名で指定すれば、指定サーバへのアクセスの時だけセットしたクッキーを送信します。#				ドメインが省略されると、そのときアクセスしたサーバ名がセットされます。
#			  path=パス;
#				セットしたクッキーが送信されるパスを指定します。
#				パスが省略されると、アクセスしたリソース（HTML、CGI）のパスがセットされます。
#			  secure
#				この項目が指定されていると、アクセス先が SSL などのような安全なサイトの場合のみに
#				クッキーを送信するようになります。ドメイン、パスが一致したとしても、
#				アクセス先が安全とみなされないと、クッキーを送信しません。
#
# URL エンコード
#   $CookieValue = 'テスト';
#   $CookieValue =~ s/([^\w\=\& ])/'%' . unpack("H2", $1)/eg;
#   $CookieValue =~ tr/ /+/;
#	
# URL デコード
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
#   GMT を得る
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
# ツェラーの公式より曜日を求める
# グレゴリウス暦(1582年10月15日(金)午後以後)で有効
#
# <IN> 年、月、日
# <OUT> 曜日 (0:日曜日 - 6:土曜日)
#
sub getDayOfWeek

{

	my($year, $month, $day) = @_;



	# 1月または2月の場合は前年の13月および14月とみなす

	if ($month <= 2) {

		--$year;

		$month += 12;

	}



	return (($year + int($year/4) - int($year/100) + int($year/400)

					+ int((13*$month + 8)/5) + $day) % 7);

}



#
# <IN>	西暦年号
# <OUT> うるう年: true  平年: false
#
sub leap

{

	my($year) = @_;



	if ($year % 100) {		# 西暦年号が 100 で割り切れない

		if ($year % 4) {	# 西暦年号が 4 で割り切れない

			return 0;	# 平年

		}

		else {

			return 1;	# うるう年

		}

	}

	else {					# 西暦年号が 100 で割り切れる

		if ($year % 400) {	# 西暦年号が 400 で割り切れない

			return 0;	# 平年

		}

		else {				# 西暦年号が 400 で割り切れる

			if ($year % 4000) {	# 西暦年号が 4000 で割り切れない

				return 1;	# うるう年

			}

			else {			# 西暦年号が 4000 で割り切れる

				return 0;	#平年

			}

		}

	}

}



@LeapYear = (0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

@NormYear = (0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);



#
# <IN>  年、月
# <OUT> その月の合計日数
#
sub getDaysOfMonth

{

	my($year, $month) = @_;



	return leap($year) ? $LeapYear[$month] : $NormYear[$month];

}



1;

