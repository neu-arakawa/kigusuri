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

		print HTML "<!--edit--><html><head><title>���菤����Ɋւ���@���Ɋ�Â��\\��</title></head><body bgcolor=\"white\"><center><!--/edit-->\n";

		print HTML "<table bgcolor=\"333333\" cellpadding=\"0\" cellspacing=\"0\" width=\"500\" border=\"0\">\n";
		print HTML "<tr>\n";
		print HTML "<td>\n";
		print HTML "<table cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" border=\"0\">\n";
		print HTML "<tr bgcolor=\"caed54\" class=\"main2\" align=\"center\"><td colspan=\"2\">���菤����Ɋւ���@���Ɋ�Â��\\��</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>�̔��ƎҖ�</td><td>$MYDATA{'company'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>�̔��ӔC�Җ�</td><td>$MYDATA{'name'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>���ݒn</td><td>$MYDATA{'zip'}<BR>$MYDATA{'add'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>�d�b�ԍ�</td><td>$MYDATA{'tel'}</td></tr>\n";
		if ($MYDATA{'fax'} ne "") {
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>FAX�ԍ�</td><td>$MYDATA{'fax'}</td></tr>\n";
}		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>�̔����i</td><td>$MYDATA{'price'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>����</td><td>$MYDATA{'souryou'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>���̑��̕t�є�p</td><td>$MYDATA{'other'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>����̎x�������@</td><td>$MYDATA{'pay'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>�ԕi�E�����ɂ���</td><td>$MYDATA{'henpin'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>���i�̈��n������</td><td>$MYDATA{'watashi'}</td></tr>\n";
		if ($MYDATA{'seigen'} ne "") {
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>��������</td><td>$MYDATA{'seigen'}</td></tr>\n";
}		
		print HTML "</table>";
		print HTML "</td>";
		print HTML "</tr>";
		print HTML "</table>\n";

		print HTML "<!--edit--></center></body></html><!--/edit-->\n";

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