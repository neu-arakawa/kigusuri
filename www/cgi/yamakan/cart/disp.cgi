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

if ($pass eq ""){
	&exitError("直接のアクセスは禁止されています。");
}else{
if ($write eq ok) {
	&write;
}elsif ($write2 eq ok) {
	&write2;
}elsif ($write3 eq ok) {
	&write3;
} else {


&disptop;

exit;
}
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

		print HTML "<!--edit--><html><head><title></title></head><body bgcolor=white><center><!--/edit-->\n";

		print HTML "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
		print HTML "<TR>\n";
		print HTML "<TD>\n";
		print HTML "<TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
		print HTML "<TR BGCOLOR=\"CAED54\" class=\"main2\" ALIGN=center><TD colspan=2>特定商取引に関する法律に基づく表\示</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>販売業者名</TD><TD>$MYDATA{'company'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>販売責任者名</TD><TD>$MYDATA{'name'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>所在地</TD><TD>$MYDATA{'zip'}<BR>$MYDATA{'add'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>電話番号</TD><TD>$MYDATA{'tel'}</TD></TR>\n";
		if ($MYDATA{'fax'} ne "") {
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>FAX番号</TD><TD>$MYDATA{'fax'}</TD></TR>\n";
}		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>販売価格</TD><TD>$MYDATA{'price'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>送料</TD><TD>$MYDATA{'souryou'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD nowrap>その他の付帯費用</TD><TD>$MYDATA{'other'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD nowrap>代金の支払い方法</TD><TD>$MYDATA{'pay'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD nowrap>返品・交換について</TD><TD>$MYDATA{'henpin'}</TD></TR>\n";
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD nowrap>商品の引渡し時期</TD><TD>$MYDATA{'watashi'}</TD></TR>\n";
		if ($MYDATA{'seigen'} ne "") {
		print HTML "<TR BGCOLOR=\"FFFFCC\" class=\"main2\"><TD>制限事項</TD><TD>$MYDATA{'seigen'}</TD></TR>\n";
}		
		print HTML "</TABLE>";
		print HTML "</TD>";
		print HTML "</TR>";
		print HTML "</TABLE>\n";

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