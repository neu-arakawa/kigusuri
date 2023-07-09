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



$cgifile      = "set.cgi";              #  各種情報設定ページのファイルの名前
$writecgifile = "write.cgi";            #  商品情報編集CGIファイルの名前
$dispcgifile  = "disp.cgi";             #  特定商取引に関する法律に基づく表示編集ファイルの名前
$disp_readfile = "disp.txt";             #  各種情報ファイルの名前




#
# 基本設定読込み処理
#
sub read {
open TEMP,"<set/$disp_readfile" || die "Could not open the file";
    @templine = <TEMP>;
	foreach (@templine) {
		($company,$name,$zip,$add,$tel,$fax,$price,$souryou,$other,$pay,$henpin,$watashi,$seigen) = split (/:=:/, $_);
	}
    close TEMP;
}

if ($pass eq ""){ &exitError("直接のアクセスは禁止されています。"); }
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

		print HTML "<!--edit--><html><head><title>特定商取引に関する法律に基づく表\示</title></head><body bgcolor=\"white\"><center><!--/edit-->\n";

		print HTML "<table bgcolor=\"333333\" cellpadding=\"0\" cellspacing=\"0\" width=\"500\" border=\"0\">\n";
		print HTML "<tr>\n";
		print HTML "<td>\n";
		print HTML "<table cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" border=\"0\">\n";
		print HTML "<tr bgcolor=\"caed54\" class=\"main2\" align=\"center\"><td colspan=\"2\">特定商取引に関する法律に基づく表\示</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>販売業者名</td><td>$MYDATA{'company'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>販売責任者名</td><td>$MYDATA{'name'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>所在地</td><td>$MYDATA{'zip'}<BR>$MYDATA{'add'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>電話番号</td><td>$MYDATA{'tel'}</td></tr>\n";
		if ($MYDATA{'fax'} ne "") {
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>FAX番号</td><td>$MYDATA{'fax'}</td></tr>\n";
}		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>販売価格</td><td>$MYDATA{'price'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>送料</td><td>$MYDATA{'souryou'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>その他の付帯費用</td><td>$MYDATA{'other'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>代金の支払い方法</td><td>$MYDATA{'pay'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>返品・交換について</td><td>$MYDATA{'henpin'}</td></tr>\n";
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td nowrap>商品の引渡し時期</td><td>$MYDATA{'watashi'}</td></tr>\n";
		if ($MYDATA{'seigen'} ne "") {
		print HTML "<tr bgcolor=\"ffffcc\" class=\"main2\"><td>制限事項</td><td>$MYDATA{'seigen'}</td></tr>\n";
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
	$from = '[０-９]';
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