#!/usr/bin/perl



$|=1;

require './lib/cgi-lib.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';

&ReadParse(*MYDATA);

$write        = $MYDATA{'write'};
$write2       = $MYDATA{'write2'};
$write3       = $MYDATA{'write3'};

$url_name     = $MYDATA{'url_name'};
$url_jump     = $MYDATA{'url_jump'};

$user         = $MYDATA{'user'};
$subject      = $MYDATA{'subject'};
$memory       = $MYDATA{'memory'};
$tax_in       = $MYDATA{'tax_in'};
$f_lock       = $MYDATA{'f_lock'};
$mail_pass1   = $MYDATA{'mail_pass1'};
$mail_pass2   = $MYDATA{'mail_pass2'};
$free         = $MYDATA{'free'};

$souryou      = $MYDATA{'souryou'};
$souryou1     = $MYDATA{'souryou1'};
$souryou2     = $MYDATA{'souryou2'};

$daibiki_0    = $MYDATA{'daibiki_0'};
$daibiki_1    = $MYDATA{'daibiki_1'};
$daibiki_3    = $MYDATA{'daibiki_3'};
$daibiki_10   = $MYDATA{'daibiki_10'};

$ginkou_pay   = $MYDATA{'ginkou_pay'};
$yuubin_pay   = $MYDATA{'yuubin_pay'};
$daibiki_pay  = $MYDATA{'daibiki_pay'};
$time_appoint = $MYDATA{'time_appoint'};
$day_appoint  = $MYDATA{'day_appoint'};
$zaiko_disp   = $MYDATA{'zaiko_disp'};
$pass         = $MYDATA{'pass'};
$password     = $MYDATA{'password'};
$del_minute   = $MYDATA{'del_minute'};

$cgifile      = "set.cgi";              #  ��CGI�t�@�C���̖��O
$writecgifile = "write.cgi";            #  ���i���ҏWCGI�t�@�C���̖��O
$dispcgifile  = "disp.cgi";             #  ���菤����Ɋւ���@���Ɋ�Â��\���ҏW�t�@�C���̖��O
$readfile     = "set.txt";              #  �e����t�@�C���̖��O
$addfile      = "add.txt";              #  �s���{�����t�@�C���̖��O
$mailfile1    = "mail1.txt";            #  �ԐM���[���p�R�����g�t�@�C���̖��O
$mailfile2    = "mail2.txt";            #  �ԐM���[���p�R�����g�t�@�C���̖��O
$passfile     = "pass.txt";             #  �p�X���[�h�t�@�C���̖��O
$messfile     = 'mess.txt';             #  �X�܂���̃��b�Z�[�W�t�@�C��
$headmessfile = 'headmess.txt';
$footmessfile = 'footmess.txt';


$mise_message      = $MYDATA{'mise_message'};
&jcode'convert(*mise_message, 'sjis');


$HeaderMessage      = $MYDATA{'HeaderMessage'};
&jcode'convert(*HeaderMessage, 'sjis');


$FooterMessage     = $MYDATA{'FooterMessage'};
&jcode'convert(*FooterMessage, 'sjis');


#
# ��{�ݒ�Ǎ��ݏ���
#
	open PASS,"<set/$passfile" || die "Could not open the file";
	$passwd = <PASS>;
	close PASS;

sub read {
	open TEMP,"<set/$readfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		      ($user,$subject,$memory,$f_lock,$souryou,$souryou1,$souryou2,$daibiki_0,$daibiki_1,$daibiki_3,$daibiki_10,$ginkou_pay,$yuubin_pay,$daibiki_pay,$time_appoint,$zaiko_disp,$mail_pass1,$mail_pass2,$free,$day_appoint,$url_name,$url_jump,$del_minute) = split (/:=:/, $_);
	}
	close TEMP;
	
	$mise_message = '';
	open TEMP,"<set/$messfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$mise_message .= $_;
	}
	close TEMP;
	
	
	$HeaderMessage = '';
	open TEMP,"<set/$headmessfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$HeaderMessage .= $_;
	}
	close TEMP;
	
	
	$FooterMessage = '';
	open TEMP,"<set/$footmessfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$FooterMessage .= $_;
	}
	close TEMP;
	
	
}

if ($write eq "pass") {
	&write4;
&pass_ok("�p�X���[�h�ύX����","���O�C���p�X���[�h��ύX���܂����B","�V�p�X���[�h�Ń��O�C��");
exit;
}


  if($pass eq ""){ &login; }
  if($pass ne "$passwd"){ &login; }
else{
	   if($write eq ok){	&write;	 }
	elsif($write2 eq ok){	&write2; }
	elsif($write3 eq ok){	&write3; }
	else{					&disptop; exit; }
}





#---------------------------------------------------------------------
#
#  ���C�����
#
#---------------------------------------------------------------------
sub disptop {

	##
	&read;

	if ($memory eq "on") {
		#$memory_ = "<input type=\"radio\" name=\"memory\" VALUE=\"on\" checked>�l�����L��������<input type=\"radio\" name=\"memory\" VALUE=\"off\">�l�����L�������Ȃ�";
		$memory1 = 'checked';
	}else{
		#$memory_ = "<input type=\"radio\" name=\"memory\" VALUE=\"on\">�l�����L��������<input type=\"radio\" name=\"memory\" VALUE=\"off\" checked>�l�����L�������Ȃ�";
		$memory2 = 'checked';
	}
	
	
	if ($f_lock eq "on") {
		#$f_lock_ = "<input type=\"radio\" name=\"f_lock\" VALUE=\"on\" checked>�t�@�C�����b�N�I��<input type=\"radio\" name=\"f_lock\" VALUE=\"off\">�t�@�C�����b�N�I�t";
		$f_lock1 = 'checked';
	}else{
		#$f_lock_ = "<input type=\"radio\" name=\"f_lock\" VALUE=\"on\">�t�@�C�����b�N�I��<input type=\"radio\" name=\"f_lock\" VALUE=\"off\" checked>�t�@�C�����b�N�I�t";
		$f_lock2 = 'checked';
	}
	
	if ($ginkou_pay eq "on") {
		#$ginkou_pay_ = "<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"on\" checked>��s�U���I��<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"off\">��s�U���I�t";
		$ginkou_pay1 = 'checked';
	}else{
		#$ginkou_pay_ = "<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"on\">��s�U���I��<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"off\" checked>��s�U���I�t";
		$ginkou_pay2 = 'checked';
	}
	
	if ($yuubin_pay eq "on") {
		#$yuubin_pay_ = "<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"on\" checked>�X�֐U���I��<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"off\">�X�֐U���I�t";
		$yuubin_pay1 = 'checked';
	}else{
		#$yuubin_pay_ = "<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"on\">�X�֐U���I��<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"off\" checked>�X�֐U���I�t";
		$yuubin_pay2 = 'checked';
	}
	
	if ($daibiki_pay eq "on") {
		#$daibiki_pay_ = "<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"on\" checked>������������I��<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"off\">������������I�t";
		$daibiki_pay1 = 'checked';
	}else{
		#$daibiki_pay_ = "<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"on\">������������I��<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"off\" checked>������������I�t";
		$daibiki_pay2 = 'checked';
	}
	
	if ($time_appoint eq "on") {
		#$time_appoint_ = "<input type=\"radio\" name=\"time_appoint\" VALUE=\"on\" checked>�z�B���ԑюw��I��<input type=\"radio\" name=\"time_appoint\" VALUE=\"off\">�z�B���ԑюw��I�t";
		$time_appoint1 = 'checked';
	}else{
		$time_appoint_ = "<input type=\"radio\" name=\"time_appoint\" VALUE=\"on\">�z�B���ԑюw��I��<input type=\"radio\" name=\"time_appoint\" VALUE=\"off\" checked>�z�B���ԑюw��I�t";
		$time_appoint2 = 'checked';
	}
	
	if ($day_appoint eq "on") {
		#$day_appoint_ = "<input type=\"radio\" name=\"day_appoint\" VALUE=\"on\" checked>�z�B���w��I��<input type=\"radio\" name=\"day_appoint\" VALUE=\"off\">�z�B���w��I�t";
		$day_appoint1 = 'checked';
	}else{
		#$day_appoint_ = "<input type=\"radio\" name=\"day_appoint\" VALUE=\"on\">�z�B���w��I��<input type=\"radio\" name=\"day_appoint\" VALUE=\"off\" checked>�z�B���юw��I�t";
		$day_appoint2 = 'checked';
	}
	
	if ($zaiko_disp eq "on") {
		#$zaiko_disp_ = "<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"on\" checked>�݌ɕ\\���I��<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"off\">�݌ɕ\\���I�t";
		$zaiko_disp1 = ' checked';
	}else{
		#$zaiko_disp_ = "<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"on\">�݌ɕ\\���I��<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"off\" checked>�݌ɕ\\���I�t";
		$zaiko_disp2 = ' checked';
	}
	
	if ($free eq "on") {
		#$free_ = "<input type=\"radio\" name=\"free\" VALUE=\"on\" checked>���z�w�����͑�������(���ɋ��z�����)<input type=\"radio\" name=\"free\" VALUE=\"off\">�ݒ�Ȃ�";
		$free1 = 'checked';
	}else{
		#$free_ = "<input type=\"radio\" name=\"free\" VALUE=\"on\">���z�w�����͑�������(���ɋ��z�����)<input type=\"radio\" name=\"free\" VALUE=\"off\" checked>�ݒ�Ȃ�";
		$free2 = 'checked';
	}
	
	if ($souryou == 0){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\" SELECTED>�����Ȃ�(����)
             <option VALUE=\"1\">�����Œ�i���������ݒ肠��j
             <option VALUE=\"2\">�����Œ�
             <option VALUE=\"3\">�s���{���ʑ���
             </select>";
		$souryou0 = 'selected';
	}
	if ($souryou == 1){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">�����Ȃ�(����)
             <option VALUE=\"1\" SELECTED>�����Œ�i���������ݒ肠��j
             <option VALUE=\"2\">�����Œ�
             <option VALUE=\"3\">�s���{���ʑ���
             </select>";
#		$souryou1 = 'selected';
	}
	if ($souryou == 2){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">�����Ȃ�(����)
             <option VALUE=\"1\">�����Œ�i���������ݒ肠��j
             <option VALUE=\"2\" SELECTED>�����Œ�
             <option VALUE=\"3\">�s���{���ʑ���
             </select>";
#		$souryou2 = 'selected';
	}
	if ($souryou == 3){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">�����Ȃ�(����)
             <option VALUE=\"1\">�����Œ�i���������ݒ肠��j
             <option VALUE=\"2\">�����Œ�
             <option VALUE=\"3\" SELECTED>�s���{���ʑ���
             </select>";
#		$souryou3 = 'selected';
	}
	
	
	
	
	
	if($MYDATA{'menu'} eq 'm1'){ $menuHtml = 'set_kihon.html'; }
	elsif($MYDATA{'menu'} eq 'm2'){ $menuHtml = 'set_souryou.html'; $souryou = &read_add(); }
	elsif($MYDATA{'menu'} eq 'm3'){ $menuHtml = 'set_mailset.html'; $mailset = &read_mail(); }
	elsif($MYDATA{'menu'} eq 'm4'){ $menuHtml = 'set_pass.html'; }
#	else						  { $menuHtml = 'set_home.html'; }
	else						  { $menuHtml = 'set_kihon.html'; }
	$DispTopData = '';
	$DispTopData .= &Get_File($menuHtml,1);
	&Disp_Html('set_top.html',1);
	
	
	
	
}




 sub testdisp {	
	print "<A HREF =\"$writecgifile?pass=$pass\" class=\"main2\"><IMG SRC=\"common/but_03.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>�@�@�@<A HREF =\"$dispcgifile?pass=$pass\" class=\"main2\"><IMG SRC=\"common/but_04.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>\n";
    print "<BR>\n";
    print "<BR>\n";
    print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
    print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
    print "<TR>\n";
    print "<TD>\n";
	



	#---------------------------------------------------------------------
	#
	#  �p�X���[�h�ݒ�
	#
	#---------------------------------------------------------------------
#    print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
#    print "<TR BGCOLOR=800000 ALIGN=center><TD colspan=2 class=\"main2\"><font color=\"FFFFFF\">���O�C���p�X���[�h�ݒ�</font></TD></TR>\n";
#    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\" ALIGN=\"center\"><input type=password name=password value=$passwd size=20><INPUT TYPE=\"hidden\"NAME=\"write\"VALUE=\"pass\"><input type=\"submit\" value=\"�� ��\"></TD></TR>\n";
#    print "</TD>\n";
#    print "</TR>\n";
#    print "</TABLE>\n";
#    print "</TABLE>\n";
#    print "</FORM>\n\n";
#    print "<IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0>\n";
#    print "<BR>\n";
#    print "<BR>\n";





	#---------------------------------------------------------------------
	#
	#  ���C���ݒ�
	#
	#---------------------------------------------------------------------
    print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
    print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
    print "<TR>\n";
    print "<TD>\n";
    print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">��{���ڐݒ�</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�������[���̑��M��</TD><TD class=\"main2\"><input type=text name=user value=$user size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�������[���̃^�C�g��</TD><TD class=\"main2\"><input type=text name=subject value=$subject size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�l���̋L���ݒ�</TD><TD class=\"main2\">$memory_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�t�@�C�����b�N�̐ݒ�</TD><TD class=\"main2\">$f_lock_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">��s�U���̐ݒ�</TD><TD class=\"main2\">$ginkou_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�X�֐U���̐ݒ�</TD><TD class=\"main2\">$yuubin_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">������������̐ݒ�</TD><TD class=\"main2\">$daibiki_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�z�B���ԑюw��̐ݒ�</TD><TD class=\"main2\">$time_appoint_	</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�z�B���w��̐ݒ�</TD><TD class=\"main2\">$day_appoint_	</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�݌ɕ\\���̐ݒ�</TD><TD class=\"main2\">$zaiko_disp_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">sendmail�̃p�X�ݒ�</TD><TD class=\"main2\"><input type=text name=mail_pass1 value=$mail_pass1 size=10> / <input type=text name=mail_pass2 value=$mail_pass2 size=10> / sendmail</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�z�[���y�[�W��URL</TD><TD class=\"main2\"><input type=text name=url_jump value=$url_jump size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�{�^���̕\\��</TD><TD class=\"main2\"><input type=text name=url_name value=$url_name size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�J�[�g���O�̊����ݒ�</TD><TD class=\"main2\"><input type=text name=del_minute value=$del_minute size=5> ����ɃJ�[�g���O���̏��i���f�[�^�x�[�X�֕��A�����܂�</TD></TR>\n";

    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">�����ݒ�</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">��{�����ݒ�</TD><TD class=\"main2\">$souryou_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�s���{���ʑ����I����</TD><TD class=\"main2\">$free_ </TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">���������̊���z</TD><TD class=\"main2\"><input type=text name=souryou2 value=$souryou2 size=8> �s���{���ʁA���z�w����������I�������ꍇ</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">�����Œ�̏ꍇ�̑���</TD><TD class=\"main2\"><input type=text name=souryou1 value=$souryou1 size=8>�~</TD></TR>\n";
    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">��������萔���ݒ�</TD></TR>\n";
    print "<!--\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">10,000�~�����̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_0 value=$daibiki_0 size=5>�~</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">10,000�~�ȏ�<BR>30,000�~�����̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_1 value=$daibiki_1 size=5>�~</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">30,000�~�ȏ�<BR>100,000�~�����̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_3 value=$daibiki_3 size=5>�~</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">100,000�~�ȏ�<BR>300,000�~�����̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_10 value=$daibiki_10 size=5>�~</TD></TR>\n";
    print "-->\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">8,000�~�����̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_0 value=$daibiki_0 size=5>�~</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">8,000�~�ȏ�̏ꍇ</TD><TD class=\"main2\"><input type=text name=daibiki_1 value=$daibiki_1 size=5>�~</TD></TR>\n";
    print "</TD>";
    print "</TR>";
    print "</TABLE>";
    print "</TABLE><BR>";
    print "<INPUT TYPE=\"hidden\"NAME=\"pass\"VALUE=\"$pass\">\n";
    print "<INPUT TYPE=\"hidden\"NAME=\"write\"VALUE=\"ok\">\n";
    print "<input type=\"submit\" value=\"�� ��\">\n";
    print "</FORM>\n\n";
	
	
	
	
	
	#---------------------------------------------------------------------
	#
	#  ���̑��ݒ�
	#
	#---------------------------------------------------------------------
    print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>";
    print "<TR>";
    print "<TD><IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0></TD>";
    print "</TR>";
    print "</TABLE>";
    print "<BR>";
	&read_add;
    print "<IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0>";
    print "<BR>";
    print "<BR>";
	&read_mail;
    print "<BR>";
    print "<BR>";
}





#---------------------------------------------------------------------
#
#  ���[���ݒ�
#
#---------------------------------------------------------------------
sub read_mail {


	$retData = '';
	open TEMP,"<set/mail1.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			$retData1 .= $_;
		}
	close TEMP;
	$retData = "�󒍊m�F���[�����O����<br><TEXTAREA rows=\"20\" cols=\"50\" name=\"comment1\">$retData1</TEXTAREA>";
	
	open TEMP,"<set/mail2.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			$retData2 .= $_;
		}
	close TEMP;
	$retData .= "<br><br><div align=\"center\"><font style=\"writing-mode: tb-rl\">�`</font><br>�O�����ƌ㔼���̊Ԃɂ��������e�������I�ɑ}������܂�<br><font style=\"writing-mode: tb-rl\">�`</font><br></center><br>�󒍊m�F���[�����㔼��<br><TEXTAREA rows=\"20\" cols=\"50\" name=\"comment2\">$retData2</TEXTAREA><br><br>";
	
	
	return($retData);
}





#---------------------------------------------------------------------
#
#  �s���{���ʑ����ݒ�
#
#---------------------------------------------------------------------
sub read_add {

		$retData = '';
		open TEMP,"<set/$addfile" || die "Could not open the file";
		     @templine = <TEMP>;
		     foreach (@templine) {
				++$count;
				
				($kingaku,$kenmei) = split (/:=:/, $_);
				if ($count == 1 || $count == 4 || $count == 7 || $count == 10 || $count == 13 || $count == 13 || $count == 16 || $count == 19 || $count == 22 || $count == 25 || $count == 28 || $count == 31 || $count == 34 || $count == 37 || $count == 40 || $count == 43 || $count == 46) {
					$retData .= "<TR BGCOLOR=\"FFFFCC\" class=\"main\"align=\"right\">\n";
				}
				
				if ($kenmei ne '') {
					$retData .= "<TD>$kenmei<input type=\"text\" name=\"kingaku$count\" VALUE=\"$kingaku\" size=\"15\"></TD>";
				}else {
					$retData .= "<TD> </TD>";
				}
				
				if ($count == 3 || $count == 6 || $count == 9  || $count == 12  || $count == 15 || $count == 18 || $count == 21 || $count == 24 || $count == 27 || $count == 30 || $count == 33 || $count == 36 || $count == 39 || $count == 42 || $count == 45 || $count == 48) {
					$retData .= "</TR>\n";
				}
			}


	close TEMP;
	return($retData);
}





#---------------------------------------------------------------------
#
#  ���C���ݒ� - �ۑ�
#
#---------------------------------------------------------------------
sub write {
	 	&conv1;
	 	&conv;
		open OUT,">set/$readfile" || die "Could not open the file";
		print OUT "$user:=:$subject:=:$memory:=:$f_lock:=:$souryou:=:$souryou1:=:$souryou2:=:$daibiki_0:=:$daibiki_1:=:$daibiki_3:=:$daibiki_10:=:$ginkou_pay:=:$yuubin_pay:=:$daibiki_pay:=:$time_appoint:=:$zaiko_disp:=:$mail_pass1:=:$mail_pass2:=:$free:=:$day_appoint:=:$url_name:=:$url_jump:=:$del_minute";
		close OUT;
	
	open OUT,">set/$messfile" || die "Could not open the file";
		print OUT "$mise_message";
	close OUT;
	
	
	open OUT,">set/$headmessfile" || die "Could not open the file";
		print OUT "$HeaderMessage";
	close OUT;
	
	
	open OUT,">set/$footmessfile" || die "Could not open the file";
		print OUT "$FooterMessage";
	close OUT;
	
	
#	$help = "$user:=:$subject:=:$memory:=:$f_lock:=:$souryou:=:$souryou1:=:$souryou2:=:$daibiki_0:=:$daibiki_1:=:$daibiki_3:=:$daibiki_10:=:$ginkou_pay:=:$yuubin_pay:=:$daibiki_pay:=:$time_appoint:=:$zaiko_disp:=:$mail_pass1:=:$mail_pass2:=:$free:=:$day_appoint:=:$url_name:=:$url_jump:=:$del_minute";
#	&Disp_Html('help.html');
#	exit;


	&disptop;
	exit;
}






#---------------------------------------------------------------------
#
#  �s���{���ʑ����ݒ� - �ۑ�
#
#---------------------------------------------------------------------
sub write2 {

open OUT,">set/$addfile" || die "Could not open the file";
    print OUT "$MYDATA{'kingaku1'}:=:�k�C��\n";
    print OUT "$MYDATA{'kingaku2'}:=:�X��\n";
    print OUT "$MYDATA{'kingaku3'}:=:��茧\n";
    print OUT "$MYDATA{'kingaku4'}:=:�{�錧\n";
    print OUT "$MYDATA{'kingaku5'}:=:�H�c��\n";
    print OUT "$MYDATA{'kingaku6'}:=:�R�`��\n";
    print OUT "$MYDATA{'kingaku7'}:=:������\n";
    print OUT "$MYDATA{'kingaku8'}:=:��錧\n";
    print OUT "$MYDATA{'kingaku9'}:=:�Ȗ،�\n";
    print OUT "$MYDATA{'kingaku10'}:=:�Q�n��\n";
    print OUT "$MYDATA{'kingaku11'}:=:��ʌ�\n";
    print OUT "$MYDATA{'kingaku12'}:=:��t��\n";
    print OUT "$MYDATA{'kingaku13'}:=:�����s\n";
    print OUT "$MYDATA{'kingaku14'}:=:�_�ސ쌧\n";
    print OUT "$MYDATA{'kingaku15'}:=:�R����\n";
    print OUT "$MYDATA{'kingaku16'}:=:���쌧\n";
    print OUT "$MYDATA{'kingaku17'}:=:�V����\n";
    print OUT "$MYDATA{'kingaku18'}:=:�x�R��\n";
    print OUT "$MYDATA{'kingaku19'}:=:�ΐ쌧\n";
    print OUT "$MYDATA{'kingaku20'}:=:���䌧\n";
    print OUT "$MYDATA{'kingaku21'}:=:�򕌌�\n";
    print OUT "$MYDATA{'kingaku22'}:=:�É���\n";
    print OUT "$MYDATA{'kingaku23'}:=:���m��\n";
    print OUT "$MYDATA{'kingaku24'}:=:�O�d��\n";
    print OUT "$MYDATA{'kingaku25'}:=:���ꌧ\n";
    print OUT "$MYDATA{'kingaku26'}:=:���s�{\n";
    print OUT "$MYDATA{'kingaku27'}:=:���{\n";
    print OUT "$MYDATA{'kingaku28'}:=:���Ɍ�\n";
    print OUT "$MYDATA{'kingaku29'}:=:�ޗǌ�\n";
    print OUT "$MYDATA{'kingaku30'}:=:�a�̎R��\n";
    print OUT "$MYDATA{'kingaku31'}:=:���挧\n";
    print OUT "$MYDATA{'kingaku32'}:=:������\n";
    print OUT "$MYDATA{'kingaku33'}:=:���R��\n";
    print OUT "$MYDATA{'kingaku34'}:=:�L����\n";
    print OUT "$MYDATA{'kingaku35'}:=:�R����\n";
    print OUT "$MYDATA{'kingaku36'}:=:������\n";
    print OUT "$MYDATA{'kingaku37'}:=:���쌧\n";
    print OUT "$MYDATA{'kingaku38'}:=:���Q��\n";
    print OUT "$MYDATA{'kingaku39'}:=:���m��\n";
    print OUT "$MYDATA{'kingaku40'}:=:������\n";
    print OUT "$MYDATA{'kingaku41'}:=:���ꌧ\n";
    print OUT "$MYDATA{'kingaku42'}:=:���茧\n";
    print OUT "$MYDATA{'kingaku43'}:=:�F�{��\n";
    print OUT "$MYDATA{'kingaku44'}:=:�啪��\n";
    print OUT "$MYDATA{'kingaku45'}:=:�{�茧\n";
    print OUT "$MYDATA{'kingaku46'}:=:��������\n";
    print OUT "$MYDATA{'kingaku47'}:=:���ꌧ\n";
    print OUT ":=:";
    close OUT;

	&disptop;
	
	exit;
}





#---------------------------------------------------------------------
#
#  ���[���R�����g�ݒ� - �ۑ�
#
#---------------------------------------------------------------------
sub write3 {

open OUT,">set/$mailfile1" || die "Could not open the file";
	print OUT "$MYDATA{'comment1'}\n";
close OUT;
open OUT,">set/$mailfile2" || die "Could not open the file";
	print OUT "$MYDATA{'comment2'}\n";
close OUT;

	
	&disptop;
	
	exit;
}





sub write4 {
open OUT,">set/$passfile" || die "Could not open the file";
    print OUT "$password";
    close OUT;
}	 	






#---------------------------------------------------------------------
#
#  �w�b�_�[
#
#---------------------------------------------------------------------
sub top {
       print "Content-type: text/html\n\n";
       print "<HTML>";
       print "<HEAD>";
       print "<style type=\"text/css\"><!-- .main {font-size: 10px; line-height: 18px}  .main2 {font-size: 10px}--></style>\n";
       print "<TITLE>$_[0]</TITLE>";
       print "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=x-sjis\">";
       print "</HEAD>";
       print "<CENTER>\n";
       print "<IMG SRC=\"img/pro.gif\" WIDTH=429 HEIGHT=46 BORDER=0>\n";
       print "<IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0>";
       print "<BR>";
       print "<BR>";
       print "$_[1]";

}





#---------------------------------------------------------------------
#
#  �t�b�^�[
#
#---------------------------------------------------------------------
sub copy {
        print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>";
        print "<TR>";
        print "	<TD><IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0></TD>";
        print "</TR>";
        print "</TABLE>";
        print "</CENTER>\n";
        print "</BODY></HTML>\n";
}






sub pass_ok {
#&top;
#		print "<BR>\n";
#		print "<BR>\n";
#		print "<form METHOD=post ACTION=$cgifile>\n";
#		print "<table width=\"0\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
#		print "<TR bgcolor=\"#FFFFFF\">";
#		print "<TD><INPUT TYPE=\"password\" name=\"pass\" value=\"$password\"></TD><TD><INPUT TYPE=\"submit\" VALUE=\"$_[2]\"></TD>";
#		print "</TR>";
#		print "</TABLE>";
#		print "</FORM>\n";
#&copy;

$msg = '<br>�p�X���[�h��ݒ肢�����܂����B<br>�ݒ肳�ꂽ�p�X���[�h�ōă��O�C�����Ă��������B';
&Disp_Html('set_login.html');

}






sub login {

       &Disp_Html('set_login.html');

exit;
}






sub conv1 {
	$from = '[�O-�X]';
	$to   = '[0-9]';
&jcode'convert(*souryou1, 'euc');
&jcode'convert(*souryou2, 'euc');
&jcode'convert(*daibiki_0, 'euc');
&jcode'convert(*daibiki_1, 'euc');
&jcode'convert(*daibiki_3, 'euc');
&jcode'convert(*daibiki_10, 'euc');
&jcode'convert(*from, 'euc');
&jcode'convert(*to, 'euc');
&jcode'tr(*souryou1, $from, $to);
&jcode'convert(*souryou1, 'sjis');
&jcode'tr(*souryou2, $from, $to);
&jcode'convert(*souryou2, 'sjis');
&jcode'tr(*daibiki_0, $from, $to);
&jcode'convert(*daibiki_0, 'sjis');
&jcode'tr(*daibiki_1, $from, $to);
&jcode'convert(*daibiki_1, 'sjis');
&jcode'tr(*daibiki_3, $from, $to);
&jcode'convert(*daibiki_3, 'sjis');
&jcode'tr(*daibiki_10, $from, $to);
&jcode'convert(*daibiki_10, 'sjis');
}


sub conv {
&jcode'convert(*user, 'sjis');
&jcode'convert(*subject, 'sjis');
&jcode'convert(*memory, 'sjis');
&jcode'convert(*tax_in, 'sjis');
&jcode'convert(*f_lock, 'sjis');
&jcode'convert(*souryou, 'sjis');
&jcode'convert(*souryou1, 'sjis');
&jcode'convert(*souryou2, 'sjis');
&jcode'convert(*daibiki_0, 'sjis');
&jcode'convert(*daibiki_1, 'sjis');
&jcode'convert(*daibiki_3, 'sjis');
&jcode'convert(*daibiki_10, 'sjis');
&jcode'convert(*url_name, 'sjis');
}


__END__

