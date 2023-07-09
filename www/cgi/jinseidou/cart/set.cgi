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

$cgifile      = "set.cgi";              #  当CGIファイルの名前
$writecgifile = "write.cgi";            #  商品情報編集CGIファイルの名前
$dispcgifile  = "disp.cgi";             #  特定商取引に関する法律に基づく表示編集ファイルの名前
$readfile     = "set.txt";              #  各種情報ファイルの名前
$addfile      = "add.txt";              #  都道府県情報ファイルの名前
$mailfile1    = "mail1.txt";            #  返信メール用コメントファイルの名前
$mailfile2    = "mail2.txt";            #  返信メール用コメントファイルの名前
$passfile     = "pass.txt";             #  パスワードファイルの名前
$messfile     = 'mess.txt';             #  店舗からのメッセージファイル
$headmessfile = 'headmess.txt';
$footmessfile = 'footmess.txt';


$mise_message      = $MYDATA{'mise_message'};
&jcode'convert(*mise_message, 'sjis');


$HeaderMessage      = $MYDATA{'HeaderMessage'};
&jcode'convert(*HeaderMessage, 'sjis');


$FooterMessage     = $MYDATA{'FooterMessage'};
&jcode'convert(*FooterMessage, 'sjis');


#
# 基本設定読込み処理
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
&pass_ok("パスワード変更完了","ログインパスワードを変更しました。","新パスワードでログイン");
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
#  メイン画面
#
#---------------------------------------------------------------------
sub disptop {

	##
	&read;

	if ($memory eq "on") {
		#$memory_ = "<input type=\"radio\" name=\"memory\" VALUE=\"on\" checked>個人情報を記憶させる<input type=\"radio\" name=\"memory\" VALUE=\"off\">個人情報を記憶させない";
		$memory1 = 'checked';
	}else{
		#$memory_ = "<input type=\"radio\" name=\"memory\" VALUE=\"on\">個人情報を記憶させる<input type=\"radio\" name=\"memory\" VALUE=\"off\" checked>個人情報を記憶させない";
		$memory2 = 'checked';
	}
	
	
	if ($f_lock eq "on") {
		#$f_lock_ = "<input type=\"radio\" name=\"f_lock\" VALUE=\"on\" checked>ファイルロックオン<input type=\"radio\" name=\"f_lock\" VALUE=\"off\">ファイルロックオフ";
		$f_lock1 = 'checked';
	}else{
		#$f_lock_ = "<input type=\"radio\" name=\"f_lock\" VALUE=\"on\">ファイルロックオン<input type=\"radio\" name=\"f_lock\" VALUE=\"off\" checked>ファイルロックオフ";
		$f_lock2 = 'checked';
	}
	
	if ($ginkou_pay eq "on") {
		#$ginkou_pay_ = "<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"on\" checked>銀行振込オン<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"off\">銀行振込オフ";
		$ginkou_pay1 = 'checked';
	}else{
		#$ginkou_pay_ = "<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"on\">銀行振込オン<input type=\"radio\" name=\"ginkou_pay\" VALUE=\"off\" checked>銀行振込オフ";
		$ginkou_pay2 = 'checked';
	}
	
	if ($yuubin_pay eq "on") {
		#$yuubin_pay_ = "<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"on\" checked>郵便振込オン<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"off\">郵便振込オフ";
		$yuubin_pay1 = 'checked';
	}else{
		#$yuubin_pay_ = "<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"on\">郵便振込オン<input type=\"radio\" name=\"yuubin_pay\" VALUE=\"off\" checked>郵便振込オフ";
		$yuubin_pay2 = 'checked';
	}
	
	if ($daibiki_pay eq "on") {
		#$daibiki_pay_ = "<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"on\" checked>代金引換払いオン<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"off\">代金引換払いオフ";
		$daibiki_pay1 = 'checked';
	}else{
		#$daibiki_pay_ = "<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"on\">代金引換払いオン<input type=\"radio\" name=\"daibiki_pay\" VALUE=\"off\" checked>代金引換払いオフ";
		$daibiki_pay2 = 'checked';
	}
	
	if ($time_appoint eq "on") {
		#$time_appoint_ = "<input type=\"radio\" name=\"time_appoint\" VALUE=\"on\" checked>配達時間帯指定オン<input type=\"radio\" name=\"time_appoint\" VALUE=\"off\">配達時間帯指定オフ";
		$time_appoint1 = 'checked';
	}else{
		$time_appoint_ = "<input type=\"radio\" name=\"time_appoint\" VALUE=\"on\">配達時間帯指定オン<input type=\"radio\" name=\"time_appoint\" VALUE=\"off\" checked>配達時間帯指定オフ";
		$time_appoint2 = 'checked';
	}
	
	if ($day_appoint eq "on") {
		#$day_appoint_ = "<input type=\"radio\" name=\"day_appoint\" VALUE=\"on\" checked>配達日指定オン<input type=\"radio\" name=\"day_appoint\" VALUE=\"off\">配達日指定オフ";
		$day_appoint1 = 'checked';
	}else{
		#$day_appoint_ = "<input type=\"radio\" name=\"day_appoint\" VALUE=\"on\">配達日指定オン<input type=\"radio\" name=\"day_appoint\" VALUE=\"off\" checked>配達日帯指定オフ";
		$day_appoint2 = 'checked';
	}
	
	if ($zaiko_disp eq "on") {
		#$zaiko_disp_ = "<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"on\" checked>在庫表\示オン<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"off\">在庫表\示オフ";
		$zaiko_disp1 = ' checked';
	}else{
		#$zaiko_disp_ = "<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"on\">在庫表\示オン<input type=\"radio\" name=\"zaiko_disp\" VALUE=\"off\" checked>在庫表\示オフ";
		$zaiko_disp2 = ' checked';
	}
	
	if ($free eq "on") {
		#$free_ = "<input type=\"radio\" name=\"free\" VALUE=\"on\" checked>一定額購入時は送料無料(下に金額を入力)<input type=\"radio\" name=\"free\" VALUE=\"off\">設定なし";
		$free1 = 'checked';
	}else{
		#$free_ = "<input type=\"radio\" name=\"free\" VALUE=\"on\">一定額購入時は送料無料(下に金額を入力)<input type=\"radio\" name=\"free\" VALUE=\"off\" checked>設定なし";
		$free2 = 'checked';
	}
	
	if ($souryou == 0){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\" SELECTED>送料なし(無料)
             <option VALUE=\"1\">送料固定（送料無料設定あり）
             <option VALUE=\"2\">送料固定
             <option VALUE=\"3\">都道府県別送料
             </select>";
		$souryou0 = 'selected';
	}
	if ($souryou == 1){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">送料なし(無料)
             <option VALUE=\"1\" SELECTED>送料固定（送料無料設定あり）
             <option VALUE=\"2\">送料固定
             <option VALUE=\"3\">都道府県別送料
             </select>";
#		$souryou1 = 'selected';
	}
	if ($souryou == 2){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">送料なし(無料)
             <option VALUE=\"1\">送料固定（送料無料設定あり）
             <option VALUE=\"2\" SELECTED>送料固定
             <option VALUE=\"3\">都道府県別送料
             </select>";
#		$souryou2 = 'selected';
	}
	if ($souryou == 3){
		$souryou_ = "<select NAME=\"souryou\">
             <option VALUE=\"0\">送料なし(無料)
             <option VALUE=\"1\">送料固定（送料無料設定あり）
             <option VALUE=\"2\">送料固定
             <option VALUE=\"3\" SELECTED>都道府県別送料
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
	print "<A HREF =\"$writecgifile?pass=$pass\" class=\"main2\"><IMG SRC=\"common/but_03.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>　　　<A HREF =\"$dispcgifile?pass=$pass\" class=\"main2\"><IMG SRC=\"common/but_04.gif\" WIDTH=130 HEIGHT=16 BORDER=0></A>\n";
    print "<BR>\n";
    print "<BR>\n";
    print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
    print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
    print "<TR>\n";
    print "<TD>\n";
	



	#---------------------------------------------------------------------
	#
	#  パスワード設定
	#
	#---------------------------------------------------------------------
#    print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
#    print "<TR BGCOLOR=800000 ALIGN=center><TD colspan=2 class=\"main2\"><font color=\"FFFFFF\">ログインパスワード設定</font></TD></TR>\n";
#    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\" ALIGN=\"center\"><input type=password name=password value=$passwd size=20><INPUT TYPE=\"hidden\"NAME=\"write\"VALUE=\"pass\"><input type=\"submit\" value=\"決 定\"></TD></TR>\n";
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
	#  メイン設定
	#
	#---------------------------------------------------------------------
    print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
    print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
    print "<TR>\n";
    print "<TD>\n";
    print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">基本項目設定</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">注文メールの送信先</TD><TD class=\"main2\"><input type=text name=user value=$user size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">注文メールのタイトル</TD><TD class=\"main2\"><input type=text name=subject value=$subject size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">個人情報の記憶設定</TD><TD class=\"main2\">$memory_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">ファイルロックの設定</TD><TD class=\"main2\">$f_lock_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">銀行振込の設定</TD><TD class=\"main2\">$ginkou_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">郵便振込の設定</TD><TD class=\"main2\">$yuubin_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">代金引換払いの設定</TD><TD class=\"main2\">$daibiki_pay_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">配達時間帯指定の設定</TD><TD class=\"main2\">$time_appoint_	</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">配達日指定の設定</TD><TD class=\"main2\">$day_appoint_	</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">在庫表\示の設定</TD><TD class=\"main2\">$zaiko_disp_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">sendmailのパス設定</TD><TD class=\"main2\"><input type=text name=mail_pass1 value=$mail_pass1 size=10> / <input type=text name=mail_pass2 value=$mail_pass2 size=10> / sendmail</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">ホームページのURL</TD><TD class=\"main2\"><input type=text name=url_jump value=$url_jump size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">ボタンの表\示</TD><TD class=\"main2\"><input type=text name=url_name value=$url_name size=50></TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">カートログの期限設定</TD><TD class=\"main2\"><input type=text name=del_minute value=$del_minute size=5> 分後にカートログ内の商品をデータベースへ復帰させます</TD></TR>\n";

    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">送料設定</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">基本送料設定</TD><TD class=\"main2\">$souryou_</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">都道府県別送料選択時</TD><TD class=\"main2\">$free_ </TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">送料無料の基準金額</TD><TD class=\"main2\"><input type=text name=souryou2 value=$souryou2 size=8> 都道府県別、一定額購入時無料を選択した場合</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">送料固定の場合の送料</TD><TD class=\"main2\"><input type=text name=souryou1 value=$souryou1 size=8>円</TD></TR>\n";
    print "<TR BGCOLOR=DEB887 ALIGN=center><TD colspan=2 class=\"main2\">代金引換手数料設定</TD></TR>\n";
    print "<!--\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">10,000円未満の場合</TD><TD class=\"main2\"><input type=text name=daibiki_0 value=$daibiki_0 size=5>円</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">10,000円以上<BR>30,000円未満の場合</TD><TD class=\"main2\"><input type=text name=daibiki_1 value=$daibiki_1 size=5>円</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">30,000円以上<BR>100,000円未満の場合</TD><TD class=\"main2\"><input type=text name=daibiki_3 value=$daibiki_3 size=5>円</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">100,000円以上<BR>300,000円未満の場合</TD><TD class=\"main2\"><input type=text name=daibiki_10 value=$daibiki_10 size=5>円</TD></TR>\n";
    print "-->\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">8,000円未満の場合</TD><TD class=\"main2\"><input type=text name=daibiki_0 value=$daibiki_0 size=5>円</TD></TR>\n";
    print "<TR BGCOLOR=FFF8DC><TD class=\"main2\">8,000円以上の場合</TD><TD class=\"main2\"><input type=text name=daibiki_1 value=$daibiki_1 size=5>円</TD></TR>\n";
    print "</TD>";
    print "</TR>";
    print "</TABLE>";
    print "</TABLE><BR>";
    print "<INPUT TYPE=\"hidden\"NAME=\"pass\"VALUE=\"$pass\">\n";
    print "<INPUT TYPE=\"hidden\"NAME=\"write\"VALUE=\"ok\">\n";
    print "<input type=\"submit\" value=\"決 定\">\n";
    print "</FORM>\n\n";
	
	
	
	
	
	#---------------------------------------------------------------------
	#
	#  その他設定
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
#  メール設定
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
	$retData = "受注確認メール＜前半＞<br><TEXTAREA rows=\"20\" cols=\"50\" name=\"comment1\">$retData1</TEXTAREA>";
	
	open TEMP,"<set/mail2.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			$retData2 .= $_;
		}
	close TEMP;
	$retData .= "<br><br><div align=\"center\"><font style=\"writing-mode: tb-rl\">～</font><br>前半部と後半部の間にご注文内容が自動的に挿入されます<br><font style=\"writing-mode: tb-rl\">～</font><br></center><br>受注確認メール＜後半＞<br><TEXTAREA rows=\"20\" cols=\"50\" name=\"comment2\">$retData2</TEXTAREA><br><br>";
	
	
	return($retData);
}





#---------------------------------------------------------------------
#
#  都道府県別送料設定
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
#  メイン設定 - 保存
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
#  都道府県別送料設定 - 保存
#
#---------------------------------------------------------------------
sub write2 {

open OUT,">set/$addfile" || die "Could not open the file";
    print OUT "$MYDATA{'kingaku1'}:=:北海道\n";
    print OUT "$MYDATA{'kingaku2'}:=:青森県\n";
    print OUT "$MYDATA{'kingaku3'}:=:岩手県\n";
    print OUT "$MYDATA{'kingaku4'}:=:宮城県\n";
    print OUT "$MYDATA{'kingaku5'}:=:秋田県\n";
    print OUT "$MYDATA{'kingaku6'}:=:山形県\n";
    print OUT "$MYDATA{'kingaku7'}:=:福島県\n";
    print OUT "$MYDATA{'kingaku8'}:=:茨城県\n";
    print OUT "$MYDATA{'kingaku9'}:=:栃木県\n";
    print OUT "$MYDATA{'kingaku10'}:=:群馬県\n";
    print OUT "$MYDATA{'kingaku11'}:=:埼玉県\n";
    print OUT "$MYDATA{'kingaku12'}:=:千葉県\n";
    print OUT "$MYDATA{'kingaku13'}:=:東京都\n";
    print OUT "$MYDATA{'kingaku14'}:=:神奈川県\n";
    print OUT "$MYDATA{'kingaku15'}:=:山梨県\n";
    print OUT "$MYDATA{'kingaku16'}:=:長野県\n";
    print OUT "$MYDATA{'kingaku17'}:=:新潟県\n";
    print OUT "$MYDATA{'kingaku18'}:=:富山県\n";
    print OUT "$MYDATA{'kingaku19'}:=:石川県\n";
    print OUT "$MYDATA{'kingaku20'}:=:福井県\n";
    print OUT "$MYDATA{'kingaku21'}:=:岐阜県\n";
    print OUT "$MYDATA{'kingaku22'}:=:静岡県\n";
    print OUT "$MYDATA{'kingaku23'}:=:愛知県\n";
    print OUT "$MYDATA{'kingaku24'}:=:三重県\n";
    print OUT "$MYDATA{'kingaku25'}:=:滋賀県\n";
    print OUT "$MYDATA{'kingaku26'}:=:京都府\n";
    print OUT "$MYDATA{'kingaku27'}:=:大阪府\n";
    print OUT "$MYDATA{'kingaku28'}:=:兵庫県\n";
    print OUT "$MYDATA{'kingaku29'}:=:奈良県\n";
    print OUT "$MYDATA{'kingaku30'}:=:和歌山県\n";
    print OUT "$MYDATA{'kingaku31'}:=:鳥取県\n";
    print OUT "$MYDATA{'kingaku32'}:=:島根県\n";
    print OUT "$MYDATA{'kingaku33'}:=:岡山県\n";
    print OUT "$MYDATA{'kingaku34'}:=:広島県\n";
    print OUT "$MYDATA{'kingaku35'}:=:山口県\n";
    print OUT "$MYDATA{'kingaku36'}:=:徳島県\n";
    print OUT "$MYDATA{'kingaku37'}:=:香川県\n";
    print OUT "$MYDATA{'kingaku38'}:=:愛媛県\n";
    print OUT "$MYDATA{'kingaku39'}:=:高知県\n";
    print OUT "$MYDATA{'kingaku40'}:=:福岡県\n";
    print OUT "$MYDATA{'kingaku41'}:=:佐賀県\n";
    print OUT "$MYDATA{'kingaku42'}:=:長崎県\n";
    print OUT "$MYDATA{'kingaku43'}:=:熊本県\n";
    print OUT "$MYDATA{'kingaku44'}:=:大分県\n";
    print OUT "$MYDATA{'kingaku45'}:=:宮崎県\n";
    print OUT "$MYDATA{'kingaku46'}:=:鹿児島県\n";
    print OUT "$MYDATA{'kingaku47'}:=:沖縄県\n";
    print OUT ":=:";
    close OUT;

	&disptop;
	
	exit;
}





#---------------------------------------------------------------------
#
#  メールコメント設定 - 保存
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
#  ヘッダー
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
#  フッター
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

$msg = '<br>パスワードを設定いたしました。<br>設定されたパスワードで再ログインしてください。';
&Disp_Html('set_login.html');

}






sub login {

       &Disp_Html('set_login.html');

exit;
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
&jcode'convert(*url_name, 'sjis');
}


__END__

