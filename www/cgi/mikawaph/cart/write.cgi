#!/usr/bin/perl

# if($MYDATA{'menu'} eq 'm1'){ $menuHtml = 'set_kihon.html'; }
# elsif($MYDATA{'menu'} eq 'm2'){ $menuHtml = 'set_souryou.html'; $souryou = &read_add(); }
# elsif($MYDATA{'menu'} eq 'm3'){ $menuHtml = 'set_mailset.html'; $mailset = &read_mail(); }
# elsif($MYDATA{'menu'} eq 'm4'){ $menuHtml = 'set_pass.html'; }
# else						  { $menuHtml = 'set_home.html'; }
# $DispTopData = '';
# $DispTopData .= &Get_File($menuHtml,1);
# &Disp_Html('set_top.html',1);




$|=1;

require './lib/cgi-lib.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';

&ReadParse(*MYDATA);
$delete     = $MYDATA{'delete'};
$delete_file= $MYDATA{'delete_file'};

$write      = $MYDATA{'write'};
$page_num   = $MYDATA{'page_num'};
$num        = $MYDATA{'num'};
$num_edit   = $MYDATA{'num_edit'};
$name       = $MYDATA{'name'};
$price      = $MYDATA{'price'};
$company    = $MYDATA{'company'};
$pic        = $MYDATA{'pic'};
$zaiko      = $MYDATA{'zaiko'};
$comment    = $MYDATA{'comment'};
$edit       = $MYDATA{'edit'};
$del        = $MYDATA{'del'};
$open_file  = $MYDATA{'open_file'};

$disp       = $MYDATA{'disp'};
$display2   = $MYDATA{'display2'};
$display3   = $MYDATA{'display3'};
$pass       = $MYDATA{'pass'};

$display    = 5;                        # ���i�f�[�^��1�y�[�W������̕\������    

$cgifile    = "write.cgi";              #  ��CGI�t�@�C���̖��O
$setcgifile = "set.cgi";                #  �e����ҏWCGI�t�@�C���̖��O

$kbnMode    = 0;						# ���i�敪�̗L���@�敪�Ȃ��c�O�@�敪����c�P
$wrtopHtml  = 'write_top.html';
$wrILHtml   = 'write_imglist.html';

if (($pass eq "") and ($delete eq 'imglist')){
	&exitError("���ڂ̃A�N�Z�X�͋֎~����Ă��܂��B");
}else{
	if ($delete eq logfile){ &delete_log; }
	
	
	if($delete eq 'file'){ &delete_file; exit;}
	
	if($delete eq 'on'){ &delete; exit;}
	
	if($write eq 'ok'){ &change; exit;}
	
	if($write eq 'new'){ &change2; exit;}
	
	if($edit eq 'new'){ &write2; exit;}
	
	if($edit eq 'img'){ &write3; exit;}
	
	if($open_file ne ''){ &read; exit;}
	
	if($delete eq 'img_list'){ &img_list; exit;}
	
	if($kbnMode == 0){
		$open_file = 'db0.txt';
		&read;
		exit;
	}
	
	
	&top_page;
	exit;
}






#---------------------------------------------------------------------
#
# ���i�폜
#
#---------------------------------------------------------------------
sub del {
	open DATA,"<db/$open_file" || die "Could not open the file";
		@templine = <DATA>;
	close DATA;
	
	open WRITE,">db/$open_file" || die "Could not open the file";
	foreach (@templine) {
		($page_num_old,$num_old,$name_old,$price_old,$company_old,$pic_old,$zaiko_old,$comment_old) = split (/:=:/, $_);
		$out  ="";
		$out2 ="$page_num_old:=:$num_old:=:$name_old:=:$price_old:=:$company_old:=:$pic_old:=:$zaiko_old:=:$comment_old";
		if ($del eq $num_old){
			print WRITE "$out";
		}else {
			print WRITE "$out2";
		}
	}
	close WRITE;
}





##--------------------------------------------------------------------
#
# ���i�f�[�^�ҏW
#
##--------------------------------------------------------------------
sub change {
	
	open DATA,"<db/$open_file" || die "Could not open the file";
		@templine = <DATA>;
	close DATA;
	
	open WRITE,">db/$open_file" || die "Could not open the file";
		$comment =~ s/\s+/<BR>/g;
		print WRITE "$page_num:=:$num:=:$name:=:$price:=:$company:=:$pic:=:$zaiko:=:$comment\n";
		foreach (@templine) {
			($page_num_old,$num_old,$name_old,$price_old,$company_old,$pic_old,$zaiko_old,$comment_old) = split (/:=:/, $_);
			$out = "$page_num_old:=:$num_old:=:$name_old:=:$price_old:=:$company_old:=:$pic_old:=:$zaiko_old:=:$comment_old";
			$outnew = "$page_num:=:$num:=:$name:=:$price:=:$company:=:$pic:=:$zaiko:=:$comment";
			$comment_old =~ s/\s*//g;
			if ($num_edit ne $num_old){
				print WRITE "$out";
			}
		}
	close WRITE;
#	&read;
	
	## ���b�Z�[�W��ʕ\��
	$DispTopData = '';
	$msg = '<br><br>���i�ԍ��y ' . $num_edit . ' �z�̃f�[�^��ҏW���܂����B';
	&Disp_Html($wrtopHtml,1);
	exit;
	
}





##--------------------------------------------------------------------
#
# �V�K���i�ǉ�
#
##--------------------------------------------------------------------
sub change2 {
	
#	&check("ERROR","���ꏤ�i�ԍ������݂��܂��B","BACK");
	
	$msg = '';
#	if ($num eq ''){   $msg .= '<li>���i�ԍ��������͂ł��B</li>'; }
	if ($name eq ''){  $msg .= '<li>���i���������͂ł��B</li>'; }
	if ($price eq ''){ $msg .= '<li>���i�P���������͂ł��B</li>'; }
	$match = 'NUMBER';
	$PATTERN{'NUMBER'} = '^\d+$';
	$match = $PATTERN{$match} || $match;
	(!$price) || ($price =~ /$match/) || ($msg .= "<li> ���i�P���͔��p�����œ��͂��Ă��������B <i>(" . $price . ")</i></li>\n");
	if ($msg ne ''){
		$msg .= "<br><br><br><A href=\"javascript:history.back()\">���͉�ʂ֖߂�</A>";
		&Disp_Html($wrtopHtml,1);
		exit;
	}
	
	$num = &Get_Kjno();
	open DATA,"<db/$open_file" || die "Could not open the file";
		@templine = <DATA>;
	close DATA;	
	
	
	$comment =~ s/\s+/<BR>/g;
	open WRITE,">db/$open_file" || die "Could not open the file";
	print WRITE "$page_num:=:$num:=:$name:=:$price:=:$company:=:$pic:=:$zaiko:=:$comment\n";
	foreach (@templine) {
		($page_num_old,$num_old,$name_old,$price_old,$company_old,$pic_old,$zaiko_old,$comment_old) = split (/:=:/, $_);
		print WRITE "$page_num_old:=:$num_old:=:$name_old:=:$price_old:=:$company_old:=:$pic_old:=:$zaiko_old:=:$comment_old";
	}
	close WRITE;
	
	
	
	## �ǉ����b�Z�[�W�\��
	$DispTopData = '';
	$msg = '<br><br>���i�ԍ��y ' . $num . ' �z�Ƃ��� �y ' . $name . ' �z �̃f�[�^��ǉ����܂����B';
	&Disp_Html($wrtopHtml,1);
	exit;
	
	
	
}





##--------------------------------------------------------------------
#
# �g�b�v�y�[�W
#
##--------------------------------------------------------------------
sub read{
	if ($edit ne "") {
		&write;
		
		exit;
	}
	if ($del ne "") {
		&del;
		
		## ���b�Z�[�W��ʕ\��
		$DispTopData = '';
		$msg = '<br><br>���i�ԍ��y ' . $del . ' �z�̃f�[�^���폜���܂����B';
		&Disp_Html($wrtopHtml,1);
		exit;
	}
	
	
	
	
	open DATA,"<db/$open_file" || die "Could not open the file";
		@templine = <DATA>;
		@templine = sort @templine;
	close DATA;
	$line=@templine;
	
	
	if ($disp == 0){
		$display2= $display - 1;
	}
	
	$display3 = $display2 - $display;
	if ($display3 < 0){ $display3 = 0; 	}
	if ($display3 > 0){ $display3 = $display3 +1; }
	
	
	
	## �e���v���[�g�ǂݍ���
	$DispTopData = '';
	if($kbnMode == 1){ $temp = './write_list1.html'; }
	else			 { $temp = './write_list0.html'; }
	$BaseHtml = &Get_File($temp);
	foreach (@templine[$disp .. $display2]) {
		
		
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$data_num  = $1;
			$num       = $2;
			$name      = $3;
			$price     = $4;
			$company   = $5;
			$pic_name  = $6;
			$zaiko     = $7;
			$comment   = $8;
			$disp++;
			
			
			if($zaiko > 0)	{ $ZaikoSelected = '�\������'; }
			else			{ $ZaikoSelected = '�\�����Ȃ�'; }
			
			
			## �\���p�ɐݒ�
			$tempBaseHtml = $BaseHtml;
			$tempBaseHtml =~ s/(\$[\w\d\{\}\[\]\'\:\$]+)/$1/eeg;
			$DispTopData .= $tempBaseHtml;
			
			
			
			# print "<TR class=\"main2\" BGCOLOR=\"FFF8DC\"><TD>$data_num</TD><TD>$num</TD><TD>$name</TD><TD ALIGN=right>$price</TD><TD>$company</TD><TD>$pic_name</TD><TD ALIGN=right>$zaiko</TD><TD>$comment</TD><TD BGCOLOR=\"FFF8DC\" nowrap><A href =\"$cgifile?open_file=$open_file&edit=$num&display2=$display2&disp=$display3&display3=$display3&pass=$pass\">�ҏW</A></TD><TD BGCOLOR=\"FF0000\" nowrap><A href =\"$cgifile?open_file=$open_file&del=$num&display2=$display2&disp=$display3&display3=$display3&pass=$pass\"><FONT COLOR=\"#FFFFFF\">�폜</FONT></A></TD></TR>\n";			  
		}
	}
	
	
	
	
	$display2 = $display + $display2;
	$disp = $display2 - $display + 1;
	
	$disp_back     = $disp - $display - $display;
	$display2_back = $display2 - $display - $display;
	
	
	$temp = '';
	$temp .= "<center><TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=600 BORDER=0>\n";
	$temp .= "<tr align=right>\n";
	$temp .= "<td><br><br><IMG SRC=\"./common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0><br>\n";
	
	
	if ($disp > $display){
		$temp .= "<A HREF=\"$cgifile?open_file=$open_file&disp=$disp_back&display2=$display2_back&pass=$pass\"><font size=3><< �O��$display��</font></A>�@\n";
	}
	if ($line > $disp) {
		$temp .= "<A HREF=\"$cgifile?open_file=$open_file&disp=$disp&display2=$display2&pass=$pass\"><font size=3>����$display�� >></font></A>\n";
	}
		$temp .= "</td>\n";
		$temp .= "</tr>\n";
		$temp .= "</table></center>\n";
	
	
	
	$DispTopData .= $temp;
	
	## ��ʕ\��
	&Disp_Html($wrtopHtml,1);
	
	
}






##--------------------------------------------------------------------
#
# ���i�ꗗ�\��
#
##--------------------------------------------------------------------
sub top_page{

		print "<A HREF =\"$setcgifile?pass=$pass\" class=\"main2\"><IMG SRC=\"common/but_01.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>�@<A HREF=\"write.cgi?delete=file&pass=$pass\" target=\"_blank\"><IMG SRC=\"common/but_05.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>\n";
		print "<BR>\n";
		print "<BR>\n";

		print "<TABLE CELLPADDING=2 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
	
		opendir DIR , "./db/";
		for ( grep /.+\.txt$/, readdir DIR ) {
			++$count;
			
			if ($count == 1 || $count == 5 || $count == 9 || $count == 13 || $count == 17 || $count == 21 || $count == 25) {print "<TR class=main>\n";}

		print "<TD><A HREF=$cgifile?open_file=$_&pass=$pass>��$_</A></TD><TD>�@</TD>\n";

			if ($count == 4 || $count == 8 || $count == 12 || $count == 16 || $count == 20 || $count == 24) {print "</TR>\n";}
		}
		closedir DIR;
		print "</TR>\n";
		print "</TABLE>\n";
		print "<BR>\n";
		print "<BR>\n";
	
}





##--------------------------------------------------------------------
#
# ���i�f�[�^�ҏW�y�[�W�\��
#
##--------------------------------------------------------------------
sub write {
	
	
	
	open TEMP,"<db/$open_file" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			++$count2;
			($page_num_r,$num_r,$name_r,$price_r,$company_r,$pic_r,$zaiko_r,$comment_r) = split (/:=:/, $_);
			$page_num   = $page_num_r;
			$num        = $num_r;
			$name       = $name_r;
			$price      = $price_r;
			$company    = $company_r;
			$pic        = $pic_r;
			$zaiko      = $zaiko_r;
			$comment    = $comment_r;
			
			$comment     =~ s/\s*//g;
			
			if ($num eq $edit){ last; }
		}
		
		close TEMP;
		
		
		
		if($zaiko == 0)	{ $ZaikoSelected[0] = 'selected'; }
		else			{ $ZaikoSelected[1] = 'selected'; }
		
		
		if($kbnMode == 1){ $temp = './write_edit1.html'; }
		else			 { $temp = './write_edit0.html'; }
		$DispTopData = '';
		$DispTopData .= &Get_File($temp,1);
		&Disp_Html($wrtopHtml,1);
}






##--------------------------------------------------------------------
#
# �V�K���i�ǉ��y�[�W�\��
#
##--------------------------------------------------------------------
sub write2 {
	
	open WRITE,"<db/$open_file" || die "Could not open the file";
	@templine = <WRITE>;
	foreach (@templine) {
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$page_num = $1;
		}
	}
	close WRITE;
	if($kbnMode == 0){ $page_num = 0; }
	
	
	
	if($kbnMode == 1){ $temp = './write_newadd1.html'; }
	else			 { $temp = './write_newadd0.html'; }
	
	
	$DispTopData = '';
	$DispTopData .= &Get_File($temp,1);
	&Disp_Html($wrtopHtml,1);
	exit;
}





##--------------------------------------------------------------------
#
# ���i�摜�ǉ��y�[�W�\��
#
##--------------------------------------------------------------------
sub write3 {
	
	$DispTopData = '';
	$DispTopData .= &Get_File('./write_imgadd.html',1);
	&Disp_Html($wrtopHtml,1);
	exit;
	
}







##--------------------------------------------------------------------
#
# ���ꏤ�i�ԍ��`�F�b�N
#
##--------------------------------------------------------------------
sub check {
	open DATA,"<db/$open_file" || die "Could not open the file";
	@templine = <DATA>;
	close DATA;	

	 foreach (@templine) {
            if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
$page_num_old = $1;
$num_old      = $2;
	if ($num_old eq $num){
		&error("ERROR","���ꏤ�i�ԍ������݂��܂�!","BACK");
	}
	}
}
}

sub top {
       print "Content-type: text/html\n\n";
       print "<HTML>";
       print "<HEAD>";
       print "<style type=\"text/css\"><!-- .main {font-size: 10px; line-height: 18px}  .main2 {font-size: 10px}--></style>\n";
       print "<TITLE>$_[0]</TITLE>";
       print "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=x-sjis\">";
       print "</HEAD>";
       print "$setting";
       print "<CENTER>\n";
       print "<IMG SRC=\"common/line_600.gif\" WIDTH=600 HEIGHT=11 BORDER=0>";
       print "<BR>";
       print "<BR>";
       print "$_[1]";

}

#
# OFFICE HAMANO �ւ̃����N�ł��B(�폜�s��)
#
sub copy {
        print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>";
        print "<TR>";
        print "<TD><IMG SRC=\"common/line_600.gif\" WIDTH=600 HEIGHT=11 BORDER=0></TD>";
        print "</TR>";
        print "</TABLE>";
        print "</CENTER>\n";
        print "</BODY></HTML>\n";
		exit;
}

sub ok {
       print "Content-type: text/html\n\n";
       print "<HTML>";
       print "<HEAD>";
       print "<TITLE>$_[0]</TITLE>";
       print "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=x-sjis\">";
       print "</HEAD>";
       print "$setting";
       print "<CENTER><H2>$_[1]</H2>";
       print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"top.location.href='$cgifile'\"></FORM>\n";
       print "</CENTER></BODY></HTML>\n";
exit;
}



sub error {
&top;
       print "<BR>";
       print "<BR>";
       print "<BR>";
       print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"history.go(-1)\" NAME=\"button\"></FORM>\n";
&copy;
exit;
}
sub conv {
&jcode'convert(*num, 'sjis');
&jcode'convert(*name, 'sjis');
&jcode'convert(*price, 'sjis');
&jcode'convert(*zaiko, 'sjis');
&jcode'convert(*comment, 'sjis');
}






#-----------------------------------------------------------
# 
# ���i�摜�ꗗ�\��
# 
#-----------------------------------------------------------
sub delete_file{
	
	
	$DispTopData = '';
	
	
	$DispTopData .= "<center><br><B>�摜���폜����ꍇ�͉摜���̍폜�{�^�����N���b�N���Ă�������</B>\n";
	$DispTopData .= "<BR>";
	$DispTopData .= "<BR>";
	$DispTopData .= "<IMG SRC=\"common/line_600.gif\" WIDTH=600 HEIGHT=11 BORDER=0>";
	$DispTopData .= "<BR>\n";
	$DispTopData .= "<BR>\n";
	
	$DispTopData .= "<TABLE CELLPADDING=2 CELLSPACING=0 WIDTH=0 BORDER=1>\n";
	
	opendir DIR , "./pic/";
#	for ( grep /.+\.txt$/, readdir DIR ) {
	for ( grep /.+\.\w/, readdir DIR ) {
		++$count;
		
		if($cnt == 0) { $DispTopData .= "<TR class=main>\n"; }
		
		$DispTopData .= "<TD valign=\"bottom\" align=\"center\"><IMG SRC=\"pic/$_\" border=\"0\"><br>�摜���F<input type=\"text\" value=\"$_\" size=\"10\">\n";
		$DispTopData .= "<br><form method=\"post\" action=\"$writecgifile\">\n";
		$DispTopData .= "<input type=\"hidden\" name=\"delete\" value=\"on\">\n";
		$DispTopData .= "<input type=\"hidden\" name=\"delete_file\" value=\"$_\">\n";
		$DispTopData .= "<input type=\"hidden\" name=\"pass\" value=\"$pass\">\n";
		$DispTopData .= "<input type=\"submit\" value=\"$_ ���폜����\">\n";
		$DispTopData .= "</form><br><br>\n";
		$DispTopData .= "</TD>\n";
		++$cnt;
		
		if($cnt == 4) {
			$DispTopData .= "</TR>\n";
			$cnt = 0;
		}
	}
	closedir DIR;
	$DispTopData .= "</TR>\n";
	$DispTopData .= "</TABLE>\n";
	$DispTopData .= "</center><BR>\n";
	
	
	&Disp_Html($wrtopHtml,1);
	
	
}





#-----------------------------------------------------------
# 
# ���i�摜�ꗗ�\��
# 
#-----------------------------------------------------------
sub img_list{
	
	
	$DispTopData = '';
	
	
	$DispTopData .= "<BR>";
	$DispTopData .= "<BR>";
	$DispTopData .= "<IMG SRC=\"common/line_600.gif\" WIDTH=600 HEIGHT=11 BORDER=0>";
	$DispTopData .= "<BR>\n";
	$DispTopData .= "<BR>\n";
	
	$DispTopData .= "<TABLE CELLPADDING=2 CELLSPACING=0 WIDTH=0 BORDER=1>\n";
	
	opendir DIR , "./pic/";
#	for ( grep /.+\.txt$/, readdir DIR ) {
	for ( grep /.+\.\w/, readdir DIR ) {
		++$count;
		
		if($cnt == 0) { $DispTopData .= "<TR class=main>\n"; }
		
		$DispTopData .= "<TD valign=\"bottom\" align=\"center\"><IMG SRC=\"pic/$_\" border=\"0\"><br>�摜���F<input type=\"text\" value=\"$_\" size=\"10\">\n";
		$DispTopData .= "<br><br>\n";
		$DispTopData .= "</TD>\n";
		++$cnt;
		
		if($cnt == 4) {
			$DispTopData .= "</TR>\n";
			$cnt = 0;
		}
	}
	closedir DIR;
	$DispTopData .= "</TR>\n";
	$DispTopData .= "</TABLE>\n";
	$DispTopData .= "</center><BR>\n";
	
	
	&Disp_Html($wrILHtml,1);
	
	
}





#-----------------------------------------------------------
# 
# ���i�摜�ꗗ�\��
# 
#-----------------------------------------------------------
sub delete {
	unlink "pic/$delete_file";   #  �폜�{�^���ɂ��J�[�g�t�@�C���폜
	$DispTopData = '<br><br><center>';
	$DispTopData .= "�w�肳�ꂽ�t�@�C�����폜���܂����B\n";
	$DispTopData .= "</center><BR>\n";
	$DispTopData .= "<BR>\n";
	$DispTopData .= "<BR>\n";
	&Disp_Html($wrtopHtml,1);
	exit;
}

__END__	 