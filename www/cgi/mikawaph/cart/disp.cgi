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

		print HTML "<!--edit-->\n<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html><head>\n";
		print HTML "<title>漢方薬局 相談 | 大阪市住之江区 | ミカワ薬局 | 商品案内 | 特定商取引に関する法律に基づく表\示</title>\n";
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
		print HTML "<tr class=\"main2\" align=\"center\"><th class=\"header\" colspan=\"2\">特定商取引に関する法律に基づく表\示</th></tr>\n";
		print HTML "<tr class=\"main2\"><th>販売業者名</th><td>$MYDATA{'company'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>販売責任者名</th><td>$MYDATA{'name'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>所在地</th><td>$MYDATA{'zip'}<BR>$MYDATA{'add'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>電話番号</th><td>$MYDATA{'tel'}</td></tr>\n";
		if ($MYDATA{'fax'} ne "") {
		print HTML "<tr class=\"main2\"><th>FAX番号</th><td>$MYDATA{'fax'}</td></tr>\n";
}		print HTML "<tr class=\"main2\"><th>販売価格</th><td>$MYDATA{'price'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th>送料</th><td>$MYDATA{'souryou'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>その他の付帯費用</th><td>$MYDATA{'other'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>代金の支払い方法</th><td>$MYDATA{'pay'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>返品・交換について</th><td>$MYDATA{'henpin'}</td></tr>\n";
		print HTML "<tr class=\"main2\"><th nowrap>商品の引渡し時期</th><td>$MYDATA{'watashi'}</td></tr>\n";
		if ($MYDATA{'seigen'} ne "") {
		print HTML "<tr class=\"main2\"><th>制限事項</th><td>$MYDATA{'seigen'}</td></tr>\n";
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