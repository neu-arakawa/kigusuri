#!/usr/bin/perl




$|=1;

require './lib/cgi-lib.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';



&ReadParse(*MYDATA);

$write        = $MYDATA{'write'};
$write2       = $MYDATA{'write2'};
$write3       = $MYDATA{'write3'};
$pass         = $MYDATA{'pass'};



$cgifile      = "set.cgi";              #  �e����ݒ�y�[�W�̃t�@�C���̖��O
$writecgifile = "write.cgi";            #  ���i���ҏWCGI�t�@�C���̖��O
$dispcgifile  = "disp.cgi";             #  ���菤����Ɋւ���@���Ɋ�Â��\���ҏW�t�@�C���̖��O
$disp_readfile = "disp.txt";             #  �e����t�@�C���̖��O




#
# ��{�ݒ�Ǎ��ݏ���
#
sub read {
open TEMP,"<set/$disp_readfile" || die "Could not open the file";
    @templine = <TEMP>;
	foreach (@templine) {
		($company,$name,$zip,$add,$tel,$fax,$price,$souryou,$other,$pay,$henpin,$watashi,$seigen) = split (/:=:/, $_);
	}
    close TEMP;
}

if ($pass eq ""){ &exitError("���ڂ̃A�N�Z�X�͋֎~����Ă��܂��B"); }
else{
	if ($write eq ok) {	&write; }
	elsif ($write2 eq ok) {	&write2; }
	elsif ($write3 eq ok) {	&write3; }
	else { &disptop;exit;}
}

sub write {
	 	&conv1;
		 	&conv;
	$MYDATA{'henpin'}  =~ s/\s+/<BR>/g;
	$MYDATA{'souryou'} =~ s/\s+/<BR>/g;
	$MYDATA{'other'}   =~ s/\s+/<BR>/g;
	$MYDATA{'pay'}     =~ s/\s+/<BR>/g;
	$MYDATA{'watashi'} =~ s/\s+/<BR>/g;
	$MYDATA{'seigen'}  =~ s/\s+/<BR>/g;
		 	
open OUT,">set/$disp_readfile" || die "Could not open the file";
    print OUT "$MYDATA{'company'}:=:$MYDATA{'name'}:=:$MYDATA{'zip'}:=:$MYDATA{'add'}:=:$MYDATA{'tel'}:=:$MYDATA{'fax'}:=:$MYDATA{'price'}:=:$MYDATA{'souryou'}:=:$MYDATA{'other'}:=:$MYDATA{'pay'}:=:$MYDATA{'henpin'}:=:$MYDATA{'watashi'}:=:$MYDATA{'seigen'}";
    close OUT;


open HTML,">set/disp.html" || die "Could not open the file";

		print HTML "<!--edit-->\n<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html><head>\n";
		print HTML "<title>������� ���k | ���s�Z�V�]�� | �~�J����� | ���i�ē� | ���菤����Ɋւ���@���Ɋ�Â��\\��</title>\n";
		print HTML "<link href=\"https://www.kigusuri.com/cgi/css/type_c/base.css\" rel=\"stylesheet\" type=\"text/css\">\n";
		print HTML "<link href=\"https://www.kigusuri.com/cgi/css/type_c/type_c.css\" rel=\"stylesheet\" type=\"text/css\">\n";
		print HTML "<link href=\"http://www.kigusuri.com/shop/mikawaph/css/original-c.css\" rel=\"stylesheet\" type=\"text/css\">\n";
		print HTML "<style type=\"text/css\">\n";
		print HTML "\n";
		print HTML "#contents-product { margin:2em auto 0;}\n";
		print HTML "#contents-product .disp { width: 860px; border: 1px solid #9b5014; border-collapse: collapse;}\n";
		print HTML "#contents-product .box_sdw.disp th { padding: 3px; width:9em; font-size: 90%;}\n";
		print HTML "#contents-product .box_sdw.disp td { padding: 3px; font-size: 90%;}\n";
		print HTML "#contents-product .disp tr { border-top: solid 1px #9b5014; padding: 4px 2px 2px 2px;}\n";
		print HTML "#contents-product .disp tr th { background-color: #ffeed8; border-top: solid 1px #9b5014; border-right: solid 1px #9b5014; padding: 4px 2px 2px 10px;}\n";
		print HTML "#contents-product .disp tr td { background-color: #fff; border-top: solid 1px #9b5014; padding: 4px 2px 2px 8px;}\n";
		print HTML "#contents-product .disp th.header { background-color: #d9ac75; font-weight: bold; text-align: center;}\n";
		print HTML "\n";
		print HTML "</style>";
		print HTML "<meta http-equiv=\"content-type\" content=\"text/html; charset=shift-jis\"></head>\n<body>\n<!--/edit-->\n";
		print HTML "<div id=\"contents-product\">\n<table class=\"box_sdw disp conf\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print HTML "<tr class=\"main2\" align=\"center\"><th class=\"header\" colspan=\"2\">���菤����Ɋւ���@���Ɋ�Â��\\��</th></tr>\n";
		print HTML "<tr class=\"main2\"><th>�̔��ƎҖ�</th><td>$MYDATA{'company'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>�̔��ӔC�Җ�</th><td>$MYDATA{'name'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>���ݒn</th><td>$MYDATA{'zip'}<BR>$MYDATA{'add'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>�d�b�ԍ�</th><td>$MYDATA{'tel'}</td></tr>\n";
		if ($MYDATA{'fax'} ne "") {
		print HTML "<tr class=\"main2\"><th>FAX�ԍ�</th><td>$MYDATA{'fax'}</td></tr>\n";
}		print HTML "<tr class=\"main2\"><th>�̔����i</th><td>$MYDATA{'price'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>����</th><td>$MYDATA{'souryou'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>���̑��̕t�є�p</th><td>$MYDATA{'other'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>����̎x�������@</th><td>$MYDATA{'pay'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>�ԕi�E�����ɂ���</th><td>$MYDATA{'henpin'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>���i�̈��n������</th><td>$MYDATA{'watashi'}</td></tr>\n";
		if ($MYDATA{'seigen'} ne "") {
		print HTML "<tr class=\"main2\"><th>��������</th><td>$MYDATA{'seigen'}</td></tr>\n";
}		
		print HTML "</table>\n</div>";

		print HTML "<!--edit-->\n</body></html>\n<!--/edit-->\n";

		close HTML;

&disptop;

	exit;
}	 	





sub disptop {

	$MYDATA{'henpin'} =~ s/\s+//g;
	$MYDATA{'souryou'} =~ s/\s+//g;
	$MYDATA{'other'} =~ s/\s+//g;
	$MYDATA{'pay'} =~ s/\s+//g;
	$MYDATA{'watashi'} =~ s/\s+//g;
	$MYDATA{'seigen'} =~ s/\s+//g;


&read;



$menuHtml = 'set_tokutei.html';
$DispTopData = '';
$DispTopData .= &Get_File($menuHtml,1);
&Disp_Html('set_top.html',1);


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
}


__END__	 