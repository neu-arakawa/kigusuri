#!/usr/bin/perl


$|=1;

require './lib/cgi-lib.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';
require './user_cnf.pl';


&ReadParse(*MYDATA);

$setting    = '<BODY BGCOLOR="#FFFFFF" TEXT="#333333" LINK="#0000FF" VLINK="#800080" ALINK="#FF0000">';  # ページ全体のフォントカラー等の設定
$page_title = '【' . $UserDispName . '】　商品案内'; #ページのタイトル


$memo_mail    = $MYDATA{'memo_mail'};    $memo_check  = $MYDATA{'memo_check'};
$remo_add     = $ENV{'REMOTE_ADDR'};     $disp        = $MYDATA{'disp'};
$key          = $MYDATA{'key'};          
$cart         = $MYDATA{'cart'};         $kazu        = $MYDATA{'kazu'};
$id           = $MYDATA{'id'};           $name        = $MYDATA{'name'};
$page_id      = $MYDATA{'page_id'};      $price       = $MYDATA{'price'};
$submit       = $MYDATA{'submit'};       $size        = $MYDATA{'size'};
$goods        = $MYDATA{'goods'};        $change      = $MYDATA{'change'};
$delid        = $MYDATA{'delid'};        $change_kazu = $MYDATA{'change_kazu'};
$change2      = $MYDATA{'change2'};      $add_name    = $MYDATA{'add_name'};
$regname      = $MYDATA{'regname'};      $kana        = $MYDATA{'kana'};
$regname2    = $MYDATA{'regname2'};
$tel          = $MYDATA{'tel'};          $tel2        = $MYDATA{'tel2'};
$add          = $MYDATA{'add'};          $add2        = $MYDATA{'add2'};
$zip          = $MYDATA{'zip'};          $zip2        = $MYDATA{'zip2'};
$email        = $MYDATA{'email'};        $fax         = $MYDATA{'fax'};
$ryoushu      = $MYDATA{'ryoushu'};      $taxin       = $MYDATA{'taxin'};
$shiharai     = $MYDATA{'shiharai'};     $tax         = $MYDATA{'tax'};
$atena        = $MYDATA{'atena'};        $souryou_p   = $MYDATA{'souryou_p'};
$comment      = $MYDATA{'comment'};      $page        = $MYDATA{'page'};
$total        = $MYDATA{'total'};        $total2      = $MYDATA{'total2'};
$total3       = $MYDATA{'total3'};       $total4      = $MYDATA{'total4'};
$daibiki_no  = $MYDATA{'daibiki_no'};
$shitei       = $MYDATA{'shitei'};       $shitei2     = $MYDATA{'shitei2'};
$who_shiharai = $MYDATA{'who_shiharai'};
$itemid       = $MYDATA{'itemid'};


@days   = ('日','月','火','水','木','金','土');
@months = ('01','02','03','04','05','06','07','08','09','10','11','12');

($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
if ($mday < 10) { $mday = "0".$mday; }
if ($hour < 10) { $hour = "0".$hour; }
if ($min < 10) { $min = "0".$min; }
if ($sec < 10) { $sec = "0".$sec; }
$year = 1900 + $year;
$date = "$year年$months[$mon]月$mday日 / $hour時$min分";


##  空白削除
$email     =~ s/\s*//g;
$memo_mail =~ s/\s*//g;

$cgifile      = "cart_list.cgi"; #  当CGIファイルの名前
$datafile     = "db.txt";       #  データベースのファイルの名前
$addfile      = "add.txt";      #  データベースのファイルの名前
$mailfile     = "mail.txt";     #  お客様への返信用のコメントファイルの名前
$setfile      = "set.txt";      #  各種設定ファイルの名前
$messfile     = 'mess.txt';
$headmessfile = 'headmess.txt';
$footmessfile = 'footmess.txt';


$style_font   = "12px";         #  フォントサイズ
$style_height = "16px";         #  行間

$del_minute   = "30";           #  最後に商品をカートに入れてからのカートデータの有効期限設定(分単位)
$kbnMode = 0;					#  区分あり・・・１　無し・・・０
	

$tab_color_top  = "#CAED54";
$tab_color_main = "#FFFFCC";
$tab_color_main2= "#FFFFCC";




#-------------------------------------------------
#
# 基本設定読込み処理
#
#-------------------------------------------------
open TEMP,"<set/$setfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		($user,$subject,$memory,$f_lock,$souryou,$souryou1,$souryou2,$daibiki_0,$daibiki_1,$daibiki_3,$daibiki_10,$ginkou_pay,$yuubin_pay,$daibiki_pay,$time_appoint,$zaiko_disp,$mail_pass1,$mail_pass2,$free,$day_appoint,$url_name,$url_jump,$del_minute) = split (/:=:/, $_);
	}
close TEMP;


open TEMP,"<set/$messfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$mise_message .= $_;
	}
	$mise_message =~ s/\n/<br>/g;
close TEMP;



open TEMP,"<set/$headmessfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$HeaderMessage .= $_;
	}
	$HeaderMessage =~ s/\n/<br>/g;
close TEMP;



open TEMP,"<set/$footmessfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		$FooterMessage .= $_;
	}
	$FooterMessage =~ s/\n/<br>/g;
close TEMP;


if($kbnMode == 0){
	$disp = 'on';
	$page_id = 0;
}






#-------------------------------------------------
#
# メールタイトル日本語化処理
#
#-------------------------------------------------
$subject = &mail64encode($subject);sub mail64encode {
	local($subject) = $_[0];
	&jcode'convert(*subject, "jis");
	$subject =~ s/\x1b\x28\x42/\x1b\x28\x4a/g;
	$subject = &base64encode($subject);
	return("=?iso-2022-jp?B?$subject?=\n");
}
sub base64encode {
	local($base) = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" . "abcdefghijklmnopqrstuvwxyz" . "0123456789+/";
	local($subject, $yy, $zz, $i);
	$subject = unpack("B*", $_[0]);
	for ($i = 0; $yy = substr($subject, $i, 6); $i += 6) {
		$zz .= substr($base, ord(pack("B*", "00" . $yy)), 1);
		if (length($yy) == 2) {
			$zz .= "==";
		} elsif (length($yy) == 4) {
			$zz .= "=";
		}
	}
	return($zz);
}





#-------------------------------------------------
#
# トップページ # set.htmlを読み込みます。
#
#-------------------------------------------------
sub select {
	&delete_log;
	open TEMP,"<set.html" || die "Could not open the file";
	@templine = <TEMP>;
	foreach $value (@templine) {
		$value =~ s/<!--edit-->\S.*<!--\/edit-->//;
		print "$value\n";
	}
	close TEMP;
}








#-------------------------------------------------
#
# ログファイル作成
#
#-------------------------------------------------
&delete_log;
if ($cart eq "レジに行く" || $cart eq "決定") {
	&reg2;
}
if ($cart eq reg) {
	&reg;
}
if ($page_id eq ""){
	&top("$page_title");
	&select;
	&copy;
}
unless (-e "cart_log/$remo_add.txt") {
	open FILE, ">cart_log/$remo_add.txt" || die "Could not open the file";
	close FILE;
}





#-------------------------------------------------
#
# メインルーチン
#
#-------------------------------------------------
if ($itemid ne '')										{ &Content_display;}
if ($cart eq 'カテゴリ選択画面へ' || $cart eq '戻 る') 	{ &Shopping_is_continued; }
if ($cart eq '内容確認') 								{ &Contents_check; }
if ($cart eq 'disp') 									{ &Contents_check2; }
if ($cart eq 'カートに入れる')							{ &It_puts_into_a_cart; }
if ($cart eq '変更')									{ &Quantity_change; }
if ($cart eq '削除')									{ &Cancellation; }
if ($cart eq 'カートを空にする')						{ &Empty; }
if ($cart eq 'お支払い')								{ &Payment; }
if ($cart eq reg_send)									{ &Send_Email; }
if ($disp eq 'on')										{ &Contents_are_displayed; }
if ($cart eq '商品説明')								{ &Explanation; }


# &exitError('test');










#------------------------------------------------
#
# お支払い処理
#
#------------------------------------------------
sub Payment {
	&check;
	
	&top("送料を計算します。");
	
	print "<TABLE width=\"0\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	print "<TR>\n";
	print "<TD><IMG src=\"img/send.gif\" width=\"220\" height=\"25\"></TD>\n";
	print "</TR>\n";
	print "<TR bgcolor=\"#808080\" align=\"center\">\n";
	print "<TD>\n";
	print "<TABLE width=\"218\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"FFFFFF\">\n";
	print "<TR align=\"center\">\n";
	print "<TD>\n";
	print "<BR>\n";
	print "<FORM METHOD=POST ACTION=$cgifile>";
	&read;
	print "<BR>\n";
	print "<BR>\n";
	print "<INPUT TYPE=\"SUBMIT\" NAME=\"cart\" VALUE=\"決定\">\n";
	print "</TD>\n";
	print "</TR>\n";
	print "</TABLE>\n";
	print "</TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "<TD><IMG src=\"img/send2.gif\" width=\"220\" height=\"15\"></TD>\n";
	print "</TR>\n";
	print "</TABLE>\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"id\" VALUE=\"$remo_add\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"key\" VALUE=\"on\">\n";
	print "</FORM><BR>\n";
	
	&copy;
}






#-------------------------------------------------
#
# 買い物を続ける
#
#-------------------------------------------------
sub Shopping_is_continued {
	&top("$page_title");
	&select;
	print "<BR>\n";
	print "<IMG SRC=\"common/line_600.gif\" WIDTH=600 HEIGHT=11 BORDER=0>\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
	if ($souryou == 3) {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"お支払い\">\n";
	} else {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"レジに行く\">\n";
	}
	print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"内容確認\">\n";
#	print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カートを空にする\">\n";
	&option;
	print "</FORM>\n";
	&copy;
	exit;
}






sub top_disp {
	&select;
	print "<BR>\n";
	print "<IMG SRC=\"common/line_500.gif\" WIDTH=500 HEIGHT=11 BORDER=0>\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
	if ($souryou == 3) {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"お支払い\">\n";
	} else {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"レジに行く\">\n";
	}
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"内容確認\">\n";
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カートを空にする\">\n";
		&option;
		print "</FORM>\n\n";
		&copy;
		exit;
}





#------------------------------------------------
#
# 内容確認
#
#------------------------------------------------
sub Contents_check {
    &check;
	&top("現在のカートの中身");
	&disptop;
	&copy;
}






#------------------------------------------------
#
# 内容確認
#
#------------------------------------------------
sub Contents_check2 {
	
	$id = $ENV{'REMOTE_ADDR'};
	
    &check;
	&top("現在のカートの中身");
	&disptop;
	&copy;
}




#------------------------------------------------
#
# 商品追加
#
#------------------------------------------------
sub It_puts_into_a_cart {
	if ($MYDATA{'new_item'} eq "br") {
		&error("ERROR","数量を選択して下さい","BACK");
	}
	
	if ("$MYDATA{'new_item'}" =~ /(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
		$page       = $1;
		$item_no    = $2;
		$item_name  = $3;
		$item_price = $4;
		$item_kazu  = $5;
	}
	
	
	## 在庫処理
	&zaiko;
	
	&step_1;
	
	&top("あなたのカートの中身");
	&disptop;
	&copy;
	
	sub step_1{
		open READ,"<cart_log/$id.txt" || die "Could not open the file";
		@read = <READ>;
		foreach (@read) {
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*)/){
			$page2  = $1;
			$num2   = $2;
			$name2  = $3;
			$price2 = $4;
			$kazu2  = $5;
			if ($item_no eq $num2){ $how = re; }
			if (-z "cart_log/$id.txt"){ $how = ""; }
		}
		}
		close (READ);
		&new_write;
	}
	
	
	sub step_2 {
		open READ2,"<cart_log/$id.txt" || die "Could not open the file";
		@read2 = <READ2>;
		close (READ2);
		
		open WRITE,">cart_log/$id.txt" || die "Could not open the file";
		foreach (@read2) {
			if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*)/){
				$pages  = $1;
				$num    = $2;
				$name   = $3;
				$price  = $4;
				$kazu   = $5;
				$kazu_x = $kazu + $item_kazu;
				if ($item_no eq $num){
					&f_lock("WRITE");
					print WRITE"$page:=:$num:=:$name:=:$price:=:$kazu_x\n";
					&f_lock2("WRITE");
				}else{
					&f_lock("WRITE");
					print WRITE"$page:=:$num:=:$name:=:$price:=:$kazu\n";
					&f_lock2("WRITE");
				}
			}
		}
		close (WRITE);
	}
	
	sub new_write {
		open READ,"<cart_log/$id.txt" || die "Could not open the file";
		@read = <READ>;
		close READ;
		if ($how eq ""){
			open (OUT, ">>cart_log/$id.txt") or die "Could not open the file";
		}else{
			&step_2;	
		}
		
		&f_lock("OUT");
		print OUT "$page:=:$item_no:=:$item_name:=:$item_price:=:$item_kazu\n";
		&f_lock2("OUT");
		
		close (OUT);
	}
}






#--------------------------------------------------
#
#    カートディスプレイ
#
#--------------------------------------------------
sub Contents_are_displayed {
	
	&top("$page_title");
	open DB, "<db/db$page_id.txt" || die "Could not open the file";
	@templine = <DB>;
	foreach (@templine) {
		($page,$goods_id,$name,$price,$com,$picture,$limit,$comment) = split (/:=:/, $_);
		if($limit <= 0){ next; }
		
		
		$selectgoods= "<SELECT NAME=\"new_item\">
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:1\">1</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:2\">2</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:3\">3</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:4\">4</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:5\">5</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:6\">6</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:7\">7</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:8\">8</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:9\">9</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:10\">10</OPTION>
</SELECT>";
		
		$price_disp = $price;
		&comma($price_disp);
		$limit2 = $limit +1;
		
		print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
		print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=560 BORDER=0>\n";
		print "<TR><TD>\n";
		print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=560 BORDER=0>\n";
		print "<TR class=main2 BGCOLOR=CAED54><TD><b>$name</b></td></tr>\n";
		
		if(($comment ne '') or ($picture ne '')){
			print "<TR class=main2 BGCOLOR=ffffcc><TD>\n";
			print "<table border=0><tr>\n";
			if($comment ne ''){ print "<td width=400>$comment</td>"; }
			if($picture ne ''){ 
				$ImgSize = '';
				($Wi,$He) = &Get_JpegSize($picture);
				if($Wi > 200 or $He > 200){ $ImgSize= 'width="200" height="200"' }
				print "<TD VALIGN=TOP align=center><IMG SRC=\"pic/$picture\" BORDER=0 $ImgSize></td>\n";
			}
			print "</tr></table></td></tr>\n";
		}
		
#		print "<TR><TD ROWSPAN=5 VALIGN=TOP width= 135><IMG SRC=\"pic/$picture\" BORDER=0>\n";
##		print "<TD class=\"main2\"><B>$com</B>\n";
#		print "<TR class=\"main2\"><TD>$name\n";
#		print "<TR class=\"main2\"><TD><FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT>\n";
##		print "<TR class=\"main\"><TD>$comment\n";
		
		if ($zaiko_disp eq "on") {
			if ($limit <= "0") {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right><FONT COLOR=\"#0000FF\"><B>販売価格 $price_disp 円</B></FONT> <FONT SIZE=3 COLOR=\"#FF0000\"><B>SOLD OUT</B></FONT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">\n";
			}elsif($limit == "1" ||$limit < "10" ){
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
				print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> <FONT COLOR=\"#FF0000\"><B>在庫 $limit</B></FONT> <SELECT NAME=\"new_item\">";
				for ($count = 1; $count < $limit2; ++$count) {
					print "<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:$count\">$count\n";	
				}
				print "</SELECT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カー\トに入れる\"> \n";
				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			} else {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
				print "<FONT COLOR=\"#FF0000\"><B>在庫あり</B></FONT>";
				print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> $selectgoods <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カー\トに入れる\"> \n";
				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			}
		} elsif ($zaiko_disp eq "off") {
			if ($limit <= "0") {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right><FONT COLOR=\"#FF0000\"><B>SOLD OUT</B></FONT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">\n";
			} else {
# カートボタンに加え、価格も非表示に
#				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
#	#カートボタンを非表示に
#	#			print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> $selectgoods <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カー\トに入れる\">\n";
#				print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> \n";
#				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			}
		}
# カートボタンに加え、価格も非表示に
#		print "</td></tr></TABLE></TD></TR></TABLE>\n";
		print "</TABLE></TD></TR></TABLE>\n";
		&option;
		print "</FORM><br>\n\n";
	}
	close DB;
	
	&copy;
}




#--------------------------------------------------
#
#    単品表示
#
#--------------------------------------------------
sub Content_display {
	
	&top("$page_title");
	open DB, "<db/db$page_id.txt" || die "Could not open the file";
	@templine = <DB>;
	foreach (@templine) {
		($page,$goods_id,$name,$price,$com,$picture,$limit,$comment) = split (/:=:/, $_);
		if($limit <= 0){ next; }
		if($itemid ne $goods_id){ next; }
		
		$selectgoods= "<SELECT NAME=\"new_item\">
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:1\">1</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:2\">2</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:3\">3</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:4\">4</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:5\">5</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:6\">6</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:7\">7</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:8\">8</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:9\">9</OPTION>
<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:10\">10</OPTION>
</SELECT>";
		
		$price_disp = $price;
		&comma($price_disp);
		$limit2 = $limit +1;
		
		print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
		print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=560 BORDER=0>\n";
		print "<TR><TD>\n";
		print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=560 BORDER=0>\n";
		print "<TR class=main2 BGCOLOR=CAED54><TD><b>$name</b></td></tr>\n";
		
		if(($comment ne '') or ($picture ne '')){
			print "<TR class=main2 BGCOLOR=ffffcc><TD>\n";
			print "<table border=0><tr>\n";
			if($comment ne ''){ print "<td width=400>$comment</td>"; }
			if($picture ne ''){ 
				$ImgSize = '';
				($Wi,$He) = &Get_JpegSize($picture);
				if($Wi > 200 or $He > 200){ $ImgSize= 'width="200" height="200"' }
				print "<TD VALIGN=TOP align=center><IMG SRC=\"pic/$picture\" BORDER=0 $ImgSize></td>\n";
			}
			print "</tr></table></td></tr>\n";
		}
		
#		print "<TR><TD ROWSPAN=5 VALIGN=TOP width= 135><IMG SRC=\"pic/$picture\" BORDER=0>\n";
##		print "<TD class=\"main2\"><B>$com</B>\n";
#		print "<TR class=\"main2\"><TD>$name\n";
#		print "<TR class=\"main2\"><TD><FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT>\n";
##		print "<TR class=\"main\"><TD>$comment\n";
		
		if ($zaiko_disp eq "on") {
			if ($limit <= "0") {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right><FONT COLOR=\"#0000FF\"><B>販売価格 $price_disp 円</B></FONT> <FONT SIZE=3 COLOR=\"#FF0000\"><B>SOLD OUT</B></FONT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">\n";
			}elsif($limit == "1" ||$limit < "10" ){
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
				print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> <FONT COLOR=\"#FF0000\"><B>在庫 $limit</B></FONT> <SELECT NAME=\"new_item\">";
				for ($count = 1; $count < $limit2; ++$count) {
					print "<OPTION VALUE=\"$page:=:$goods_id:=:$name:=:$price:=:$count\">$count\n";	
				}
				print "</SELECT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カー\トに入れる\"> \n";
				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			} else {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
				print "<FONT COLOR=\"#FF0000\"><B>在庫あり</B></FONT>";
				print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT> $selectgoods <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カー\トに入れる\"> \n";
				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			}
		} elsif ($zaiko_disp eq "off") {
			if ($limit <= "0") {
				print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right><FONT COLOR=\"#FF0000\"><B>SOLD OUT</B></FONT> <INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">\n";
			} else {
# カートボタンに加え、価格も非表示に
			#	print "<TR class=\"main2\" BGCOLOR=ffffcc><TD align=right>";
			#	print "<FONT COLOR=\"#000000\"><B>税込価格 $price_disp 円</B></FONT>\n"; 
				if($kbnMode == 1){ print "　<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">"; }
			}
		}
		print "</td></tr></TABLE></TD></TR></TABLE>\n";
		&option;
		print "</FORM><br>\n\n";
	}
	close DB;
	
	&copy;
}





#--------------------------------------------------
#
# 注文画面 商品明細表示
#
#--------------------------------------------------
sub reg2{
    &check;
	
	if ($add_name eq "br") {
		&error("ERROR","都道府県を選択して下さい","BACK");
	}
	
	&top("ご注文商品内容とお支払い金額");
	
	print "<FONT SIZE=3><B>ご注文商品内容とお支払い金額</B></FONT>\n";
	print "<BR><BR>";
	print "<FORM METHOD=POST ACTION=$cgifile>";
	
	print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
	print "<TR BGCOLOR=\"#333333\">\n";
	print "<TD>\n";
	print "<TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
	print "<TR ALIGN=CENTER BGCOLOR=\"$tab_color_top\" class=\"main2\"><TD ALIGN=CENTER>商品番号</TD><TD>商品名</TD><TD>単 価</TD><TD>数 量</TD><TD>小 計</TD></TR>\n";
	
	open DISP,"<cart_log/$remo_add.txt" || die "Could not open the file";
	
	
	@lines = <DISP>;
	close DISP;
	
	foreach (@lines) {
		if (/(.*):=:(\w*):=:(.*):=:(.*):=:(.*)/) {
			$page     = $1;
			$name     = $2;
			$size     = $3;
			$price    = $4;
			$kazu     = $5;
			
			$price =~ s/,//g;
			$subtotal = $price * $kazu;
			$subtotal_ = $price * $kazu;
			
			&comma($subtotal_);
			&comma($price);
			
			print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\"><TD>$name</TD><TD>$size</TD><TD ALIGN=RIGHT>$price</TD><TD ALIGN=RIGHT>$kazu</TD><TD ALIGN=RIGHT>$subtotal_円</TD></TR>\n";
				@plus = $subtotal;   #   商品計
				foreach $plus (@plus) {
					$total += $plus;
				}
		}
	}
	
	
	
	
	
	#-------------------------------------------------
	# 代引き
	#-------------------------------------------------
	
	if ($total < 10000) {
		$daibiki_no = $daibiki_0;
	} elsif ($total >= 10000 and $total < 30000) {
		$daibiki_no = $daibiki_1;
	} elsif ($total >= 30000 and $total < 100000) {
		$daibiki_no = $daibiki_3;
	} elsif ($total >= 100000 and $total < 300000) {
		$daibiki_no = $daibiki_10;
	}
	
	
	if ($souryou == 1) {
		&souryou1;
	} elsif ($souryou == 2) {
		&souryou2;
	} elsif ($souryou == 3) {
		&souryou3;
	}
	
	if ($souryou == 1 || $souryou == 2 || $souryou == 3 || $souryou == 0) {
		$total  = int($total + 0.5) ;
		$total2 = int($total + $souryou_p + 0.5) ;
		$total3 = int($total + $souryou_p + $daibiki_no + 0.5) ;
	}
	
	&comma($total);
	
	print "<TR BGCOLOR=\"$tab_color_main\" ALIGN=RIGHT class=\"main2\"><TD COLSPAN=4>商品合計</TD><TD COLSPAN=1>$total円</TD></TR>";
	
	
	
	#-------------------------------------------------
	#  消費税の有無
	#-------------------------------------------------
	&comma($tax);
	&comma($taxin);
	&comma($total2);
	&comma($total3);
	&comma($total4);
	&comma($daibiki_no);
	&comma($souryou_p);
	
	if ($souryou != 0) {
		print "<TR BGCOLOR=\"$tab_color_main\" ALIGN=RIGHT class=\"main2\"><TD COLSPAN=4>送料</TD><TD COLSPAN=1>$souryou_p円</TD></TR>";
	}
	
	print "<TR BGCOLOR=\"$tab_color_main\" ALIGN=RIGHT class=\"main2\"><TD COLSPAN=5><b>お支払い金額合計　　 $total2円</b></TD></TR>";
	print "</TABLE>";
	print "</TD>\n";
	print "</TR>\n";
	print "</TABLE><BR><BR>";
	
	
	print "\n<INPUT TYPE=\"hidden\"NAME=\"page_id\"VALUE=\"ok\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"id\"VALUE=\"$id\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total\"VALUE=\"$total\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"souryou_p\"VALUE=\"$souryou_p\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"daibiki_no\"VALUE=\"$daibiki_no\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total2\"VALUE=\"$total2\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total3\"VALUE=\"$total3\">";
	
	
	
	#-------------------------------------------------
	#   メール送信個人情報入力欄
	#-------------------------------------------------
	if ($memory eq on) {
		print "<BR><BR>\n";
		print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
		print "<TR BGCOLOR=\"#333333\">\n";
		print "<TD>\n";
		print "<TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
		print "<TR BGCOLOR=\"$tab_color_top\" ALIGN=\"CENTER\" class=\"main2\">\n";
		print "<TD COLSPAN=2>「ご購入者連絡先」を登録された方は、E-mailアドレスをご入力下さい。</TD>\n";
		print "</TR>\n";
		print "<TR BGCOLOR=\"$tab_color_main\" ALIGN=\"CENTER\" class=\"main2\">\n";
		print "<TD COLSPAN=2>登録メールアドレス <input type=\"text\" name=\"memo_mail\"size=40></TD>\n";
		print "</TR>\n";
		print "</TABLE>\n";
		print "</TD>\n";
		print "</TR>\n";
		print "</TABLE><BR><BR>\n";
	}
	
	print "<TABLE BGCOLOR=\"#FFFFFF\" CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
	print "<TR class=\"main2\">\n";
	print "<TD><B>ご注文の際は、下記項目をご入力のうえ送信下さい。</B></TD>\n";
	print "</TR>\n";
	print "</TABLE>\n";
	
#	if ($memory eq on) {
#		print "<TABLE BGCOLOR=\"#FFFFFF\" CELLPADDING=2 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
#		print "<TR class=\"main\">\n";
#		print "<TD>下のE-mail入力欄横のチェックボックスをONにする事で、個人情報を記憶させることが出来ます。それにより、次回購入時には、上にある登録メールアドレス欄にE-mailアドレスを入力するだでよく、個人情報の入力軽減になります。</TD>\n";
#		print "</TR>\n";
#		print "</TABLE>\n";
#	}
	
	
	print "<TABLE  BGCOLOR=\"#333333\" CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
	print "<TR>\n";
	print "<TD><TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
	print "<TR ALIGN=CENTER class=\"main2\">\n";
	print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_top\">ご購入者連絡先</TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>氏 名</TD><TD><input type=text name=regname size=30></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>氏名カナ</TD><TD><input type=text name=kana size=30>　<font size=2>※カタカナでご記入ください。</font></TD>\n";
	print "</TR>\n";
	if ($memory eq on) {
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
		print "<TD>E-mail</TD><TD><input type=text name=email size=40> ";
		print "E-mail登録<input type=\"checkbox\" name=\"memo_check\"value=\"on\"><br>";
		print '<font size=2>※E-mail登録のチェックボックスをＯＮにしますとご購入者連絡先の情報を記憶させることができます。次回購入時には上部の【登録メールアドレス】入力欄に、登録して頂いたメールアドレスを入力するだけでご購入者連絡先の入力を済ませます。</font>' . "</TD>\n";
		print "</TR>\n";
	} else {
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
		print "<TD>E-mail</TD><TD><input type=text name=email size=50></TD>\n";
		print "</TR>\n";
	}
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>郵便番号</TD>\n";
	print "<TD><input type=\"text\" name=\"zip\"VALUE=\"〒\"size=15></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>住所</TD><TD><input type=text name=add size=50></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>TEL</TD><TD><input type=text name=tel size=30></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main2\">\n";
	print "<TD>FAX</TD><TD><input type=text name=fax size=30></TD>\n";
	print "</TR>\n";
	print "<TR ALIGN=CENTER class=\"main2\">\n";
	print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_top\">お届け先が違う場合のみご記入下さい。</TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>お支払い人</TD>\n";
	print "<TD><input type=\"radio\" name=\"who_shiharai\"VALUE=\"注文主\" checked>注文主\n";
	print "<input type=\"radio\" name=\"who_shiharai\"VALUE=\"お届け先\">お届け先\n";
	print "</TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>氏 名</TD><TD><input type=text name=regname2 size=30></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>郵便番号</TD>\n";
	print "<TD><input type=\"text\" name=\"zip2\"VALUE=\"〒\"size=15></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>住所</TD><TD><input type=text name=add2 size=50></TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>TEL</TD><TD><input type=text name=tel2 size=30></TD>\n";
	print "</TR>\n";
	print "</TD></TR></TABLE></TABLE>\n";
	print "<BR><BR>\n";
	
	
	
	
	
	print "<TABLE  BGCOLOR=\"#333333\" CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
	print "<TR>\n";
	print "<TD><TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
	print "<TR ALIGN=CENTER class=\"main2\">\n";
	print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_top\">お支払いについて</TD>\n";
	print "</TR>\n";
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>領収証</TD>\n";
	print "<TD><input type=\"radio\" name=\"ryoushu\"VALUE=\"要\">要 \n";
	print "<input type=\"radio\" name=\"ryoushu\"VALUE=\"不要\"checked>不要 \n";
	print "<input type=\"text\" name=\"atena\"VALUE=\"* 宛 名\"></TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>お支払い方法</TD>\n";
	print "<TD>\n";
	
	if ($ginkou_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\" VALUE=\"銀行振込\">銀行振込 \n";
	}
	if ($yuubin_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\" VALUE=\"郵便振込\">郵便振込 \n";
	}
	if ($daibiki_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\"VALUE=\"代金引換\">代金引換 <FONT color=\"red\">■手数料$daibiki_no円が加算されます</FONT>\n";
	}
	print "</TD>\n";
	print "</TR>\n";
	print "</TD></TR></TABLE></TABLE>\n";
	print "<BR><BR>\n";
	
	
	
	
	
	
	print "<TABLE  BGCOLOR=\"#333333\" CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
	print "<TR>\n";
	print "<TD><TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
	print "<TR ALIGN=CENTER class=\"main2\">\n";
	print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_top\">その他の情報</TD>\n";
	print "</TR>\n";
	
	if ($day_appoint eq on) {
		print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
		print "<TD>配達日指定</TD><TD><input type=text name=shitei2 size=30 value=\"配達日指定なし\"></TD>\n";
		print "</TR>\n";
	}
	
	if ($time_appoint eq on) {
		print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
		print "<TD>時間帯指定</TD>\n";
		print "<TD>\n";
		print "<input type=text name=shitei size=30 value=\"配達時間帯指定なし\">\n";
		print "</TD>\n";
		print "</TR>\n";
	}
	
	
	
	
	print "<TR BGCOLOR=\"$tab_color_main2\" class=\"main2\">\n";
	print "<TD>通信欄</TD><TD>のし、ラッピング等のご指定は下記へご記入下さい。<br><textarea name=comment rows=6 cols=50 WRAP=HARD></textarea></TD>\n";
	print "</TR>\n";
	print "</TD></TR></TABLE></TABLE>\n";
	print "<BR><BR>\n";
	
	if($mise_message ne ''){
		print "<TABLE  BGCOLOR=\"#333333\" CELLPADDING=0 CELLSPACING=0 WIDTH=0 BORDER=0>\n";
		print "<TR>\n";
		print "<TD><TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=500 BORDER=0>\n";
		print "<TR ALIGN=CENTER class=\"main2\">\n";
		print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_top\">店舗からのお知らせ</TD>\n";
		print "</TR>\n";
		print "<TR ALIGN=CENTER class=\"main2\">\n";
		print "<TD COLSPAN=2 BGCOLOR=\"$tab_color_main2\">$mise_message</TD>\n";
		print "</TR>\n";
		print "</TD></TR></TABLE></TABLE><br><br>\n";
	}
	
	
	
	print "<INPUT TYPE=\"hidden\"NAME=\"cart\"VALUE=\"reg\">\n";
	print "<input type=\"submit\" value=\"入力内容確認画面へ\">\n";
	print "<input type=\"reset\"  value=\"入力取消\">\n";
	print "<INPUT TYPE=\"button\" VALUE=\"戻 る\" onClick=\"history.go(-1)\" NAME=\"button\">";
	print "</FORM><BR><BR>\n";
	
	
	
	
	#-------------------------------------------------
	# 特定商取引の表示
	#-------------------------------------------------
	open TEMP,"<set/disp.html" || die "Could not open the file";
	@templine = <TEMP>;
	foreach $value (@templine) {
		$value =~ s/<!--edit-->\S.*<!--\/edit-->//;
		print "$value\n";
	}
	close TEMP;
	
	print "<BR><BR><BR>\n";
	
	
	
	
	
	
#	&del_cart;
	
	&copy;
}





#-------------------------------------------------
#
# メール確認
#
#-------------------------------------------------
sub reg{
	
	open READ,"<members/members.pl" || die "Could not open the file";
	@lines = <READ>;
	close READ;
	foreach (@lines) {
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$email_c   = $1;
			
			if ($memo_mail eq $email_c) {
				$email     = $1;
				$regname   = $2;
				$zip       = $3;
				$add       = $4;
				$tel       = $5;
				$fax       = $6;
			}
		}
	}
	&pass;
}





#-------------------------------------------------
#
# 最終確認画面
#
#-------------------------------------------------
sub pass {
	&check;
	&conv;
	# 記入漏れチェック
	if ($regname eq "" || $kana eq "" || $email eq "" || $add eq "" || $tel eq ""  || $shiharai eq "") {
		&error("ERROR","お支払方法、お名前、フリガナ、e-mail、ご住所、お電話番号、支払い方法は必須項目です。","BACK");
		exit;
	}
	
	&top("最終確認画面");
	print "<FORM METHOD=POST ACTION=$cgifile>";
	
	print "\n<INPUT TYPE=\"hidden\"NAME=\"id\"VALUE=\"$id\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"page_id\"VALUE=\"ok\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total\"VALUE=\"$total\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"souryou_p\"VALUE=\"$souryou_p\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"daibiki_no\"VALUE=\"$daibiki_no\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total2\"VALUE=\"$total2\">";
	print "\n<INPUT TYPE=\"hidden\"NAME=\"total3\"VALUE=\"$total3\">";
	
	
	print "\n<INPUT TYPE=\"hidden\"NAME=\"memo_mail\"VALUE=\"$memo_mail\">";
	
	print "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=500 BORDER=0>\n";
	print "<TR BGCOLOR=\"333333\">\n";
	print "<TD>\n";
	print "<TABLE CELLPADDING=2 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
	print "<TR ALIGN=CENTER BGCOLOR=\"$tab_color_top\" class=\"main2\">\n";
	print "<TD COLSPAN=2>お客様のデータ・入力事項をご確認下さい</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>氏名</TD>\n";
	print "<TD><input type=\"hidden\"name=\"regname\"VALUE=\"$regname\">$regname</TD>\n";
	print "</TR>\n";

	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>氏名カナ</TD>\n";
	print "<TD><input type=\"hidden\"name=\"kana\"VALUE=\"$kana\">$kana</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>E-mail</TD>\n";
	print "<TD><input type=\"hidden\"name=\"email\"VALUE=\"$email\">$email</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>郵便番号</TD>\n";
	print "<TD><input type=\"hidden\"name=\"zip\"VALUE=\"$zip\">$zip</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>住所</TD>\n";
	print "<TD><input type=\"hidden\"name=\"add\"VALUE=\"$add\">$add</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>TEL</TD>\n";
	print "<TD><input type=\"hidden\"name=\"tel\"VALUE=\"$tel\">$tel</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>FAX</TD>\n";
	print "<TD><input type=\"hidden\"name=\"fax\"VALUE=\"$fax\">$fax</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>領収証</TD>\n";
	print "<TD><input type=\"hidden\"name=\"ryoushu\"VALUE=\"$ryoushu\">$ryoushu</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>宛名</TD>\n";
	print "<TD><input type=\"hidden\"name=\"atena\"VALUE=\"$atena\">$atena</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>支払方法</TD>\n";
	print "<TD><input type=\"hidden\"name=\"shiharai\"VALUE=\"$shiharai\">$shiharai</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>配達日指定</TD>\n";
	print "<TD><input type=\"hidden\"name=\"shitei2\"VALUE=\"$shitei2\">$shitei2</TD>\n";
	print "</TR>\n";
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>時間帯指定</TD>\n";
	print "<TD><input type=\"hidden\"name=\"shitei\"VALUE=\"$shitei\">$shitei</TD>\n";
	print "</TR>\n";
	
	if ($regname2 ne "") {
		print "<TR ALIGN=CENTER BGCOLOR=\"$tab_color_top\" class=\"main\">\n";
		print "<TD COLSPAN=2><B>配送先指定</B></TD>\n";
		print "</TR>\n";
		
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
		print "<TD>お支払人</TD>\n";
		print "<TD><input type=\"hidden\"name=\"who_shiharai\"VALUE=\"$who_shiharai\">$who_shiharai</TD>\n";
		print "</TR>\n";
		
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
		print "<TD>氏名</TD>\n";
		print "<TD><input type=\"hidden\"name=\"regname2\"VALUE=\"$regname2\">$regname2</TD>\n";
		print "</TR>\n";
		
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
		print "<TD>郵便番号</TD>\n";
		print "<TD><input type=\"hidden\"name=\"zip2\"VALUE=\"$zip2\">$zip2</TD>\n";
		print "</TR>\n";
		
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
		print "<TD>住所</TD>\n";
		print "<TD><input type=\"hidden\"name=\"add2\"VALUE=\"$add2\">$add2</TD>\n";
		print "</TR>\n";
		
		print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
		print "<TD>TEL</TD>\n";
		print "<TD><input type=\"hidden\"name=\"tel2\"VALUE=\"$tel2\">$tel2</TD>\n";
		print "</TR>\n";
	}
	
	print "<TR BGCOLOR=\"$tab_color_main\" class=\"main\">\n";
	print "<TD>通信欄</TD>\n";
	print "<TD><input type=\"hidden\"name=\"comment\"VALUE=\"$comment\">$comment</TD>\n";
	print "</TR>\n";
	print "</TABLE>\n";
	print "</TD>\n";
	print "</TR>\n";
	print "</TABLE>\n";
	
	print "<BR><BR>\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"cart\"VALUE=\"reg_send\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"memo_check\"VALUE=\"$memo_check\">\n";
	
	print "<input type=\"submit\" value=\"注文を確定する\">\n";
	print "<INPUT TYPE=\"button\" VALUE=\"一つ前に戻る\" onClick=\"history.go(-1)\" NAME=\"button\">";
	print "</FORM>\n";
	&copy;
}





#-------------------------------------------------
#
# メール送信
#
#-------------------------------------------------
sub Send_Email {
	
	# 記入漏れチェック
	if ($regname eq "" || $kana eq "" || $email eq "" || $add eq "" || $tel eq "") {
		&error("ERROR","記入漏れがあります","BACK");
		exit;
	}
	
	unless ($email =~ /.+\@.+\..+/) {
		&error2("ERROR","メールアドレスをご確認ください。","BACK");
	}
	
	if( open(MAIL,"| /$mail_pass1/$mail_pass2/sendmail $user")) {
		&conv;
		
		if ($memo_check eq "on") {
			if ($memo_mail eq "") {
				
				open READ,"<members/members.pl" || die "Could not open the file";
				
				@lines = <READ>;
				close READ;
				foreach (@lines) {
					if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
						$email_c   = $1;
						if ($email eq $email_c) {
							&error2 ("ERROR","入力されたE-mailアドレスは登録済みです。","BACK");
							exit;
						}
					}
				}
				open IN, ">>members/members.pl" || die "Could not open the file";
				
				&f_lock("IN");
				print IN"$email:=:$regname:=:$zip:=:$add:=:$tel:=:$fax\n";
				&f_lock2("IN");
				close IN;
			}
		}
		
		print MAIL "From: $email\n";
		print MAIL "To: $user\n";
		print MAIL "Subject: $subject\n";
		
		print MAIL "注文年月日\n";
		print MAIL "$date\n";
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        ご注文主の情報\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "お名前       $regname\n";
		print MAIL "フリガナ     $kana\n";
		print MAIL "電子メール   $email\n";
		print MAIL "郵便番号     $zip\n";
		print MAIL "ご住所       $add\n";
		print MAIL "お電話       $tel\n";
		print MAIL "FAX          $fax\n";
		
		if ($ryoushu eq "要") {
			print MAIL "領収証宛名   $atena\n";
		}
		print MAIL "お支払方法   $shiharai\n";
		
		if ($day_appoint eq on) {
			print MAIL "配達日指定   $shitei2\n";
		}
		
		if ($time_appoint eq on) {
			print MAIL "時間帯指定   $shitei\n";
		}
		print MAIL "通信欄\n";
		print MAIL "$comment\n";
		
		if ($regname2 ne "") {
			print MAIL "---------------------------------------------------------------\n";
			print MAIL "                        お届け先指定\n";
			print MAIL "---------------------------------------------------------------\n";
			
			print MAIL "お支払人     $who_shiharai\n";
			print MAIL "お名前       $regname2\n";
			print MAIL "郵便番号     $zip2\n";
			print MAIL "ご住所       $add2\n";
			print MAIL "お電話       $tel2\n";
		}
		print MAIL "---------------------------------------------------------------\n";
		
		open IN, "<cart_log/$id.txt" || die "Could not open the file";
		
		@lines    = <IN>;
		close IN;
		
		foreach (@lines) {
			if (/(.*):=:(\w*):=:(.*):=:(.*):=:(.*)/) {
				$page     = $1;
				$name     = $2;
				$size     = $3;
				$price    = $4;
				$kazu     = $5;
				$subtotal = $price * $kazu;
				++$cnt;
				
				print MAIL "商品名 $cnt  $name $size $price X $kazu = $subtotal 円\n";
				print MAIL "---------------------------------------------------------------\n";
			}
		}
		print MAIL "商品合計     $total 円\n";
		
		&comma($souryou1);
		
		if ($souryou_p != 0) {
			print MAIL "送料         $souryou_p 円\n";
		}
		
		if ($shiharai eq "代金引換") {
			print MAIL "代引手数料   $daibiki_no 円\n";
			print MAIL "お支払い金額 $total3 円\n";
		}
		
		if ($shiharai eq "銀行振込") {
			print MAIL "お支払い金額 $total2 円\n";
		}
		
		if ($shiharai eq "郵便振込") {
			print MAIL "お支払い金額 $total2 円\n";
		}
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "Remote addr: $ENV{'REMOTE_ADDR'}\n";
		print MAIL "Remote host: $ENV{'REMOTE_HOST'}\n";
		print MAIL "User Agent : $ENV{'HTTP_USER_AGENT'}\n";
		
		close(MAIL);
	
	}else{
		print "Content-type: text/html\n\n";
		print "<title>Request Rejected</title>\n";
		print "<h3>メール送信は失敗しました。</h3>\n";
	}
	
	if ($email ne "") {
		&next_mail;
	} else {
		unlink "cart_log/$id.txt";
		&errortop ("送信完了","送信完了いたしました。","TOPへ");
	}
	
	exit;
}






##############################################
#
#  注文確認用メール(注文主への確認メール)
#
##############################################
sub next_mail {
	
	unless ($email =~ /.+\@.+\..+/) {
		&error2("ERROR","メールアドレスをご確認ください。","BACK");
	}
	
	if( open(MAIL,"| /$mail_pass1/$mail_pass2/sendmail $email")) {
		&conv;
		print MAIL "From: $user\n";
		print MAIL "To: $email\n";
		print MAIL "Subject: $subject\n";
		
		
		print MAIL "注文年月日\n";
		print MAIL "$date\n";
		print MAIL "$regname 様\n\n";
		
#		print MAIL "この度はオンラインショップをご利用いただき誠にありがとうございます。\n";
#		print MAIL "お客様のご注文は以下の内容で承りましたのでお知らせいたします。\n";
		
		
		open TEMP,"<set/mail1.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			print MAIL "$_";
		}
		close TEMP;
		
		print MAIL "\n\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        お客様の情報\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "お名前       $regname\n";
		print MAIL "フリガナ     $kana\n";
		print MAIL "電子メール   $email\n";
		print MAIL "郵便番号     $zip\n";
		print MAIL "ご住所       $add\n";
		print MAIL "お電話       $tel\n";
		print MAIL "FAX          $fax\n";
		
		if ($ryoushu eq "要") {
			print MAIL "領収証宛名   $atena\n";
		}
			print MAIL "お支払方法   $shiharai\n";
			
		if ($day_appoint eq on) {
			print MAIL "配達日指定   $shitei2\n";
		}
		
		if ($time_appoint eq on) {
			print MAIL "時間帯指定   $shitei\n";
		}
		print MAIL "通信欄\n";
		print MAIL "$comment\n";
		
		if ($regname2 ne "") {
			print MAIL "---------------------------------------------------------------\n";
			print MAIL "                        お届け先指定\n";
			print MAIL "---------------------------------------------------------------\n";
			
			print MAIL "お支払人     $who_shiharai\n";
			print MAIL "お名前       $regname2\n";
			print MAIL "郵便番号     $zip2\n";
			print MAIL "ご住所       $add2\n";
			print MAIL "お電話       $tel2\n";
		}
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        ご注文商品内容\n";
		print MAIL "---------------------------------------------------------------\n";
		
		open IN, "<cart_log/$id.txt" || die "Could not open the file";
		
		@lines    = <IN>;
		close IN;
		
		foreach (@lines) {
			if (/(.*):=:(\w*):=:(.*):=:(.*):=:(.*)/) {
				$page     = $1;
				$name     = $2;
				$size     = $3;
				$price    = $4;
				$kazu     = $5;
				$subtotal = $price * $kazu;
				++$cnt2;
				
				print MAIL "商品名$cnt2 : $size\n";
				print MAIL "単価 : $price 円\n";
				print MAIL "数量 : $kazu\n";
				print MAIL "金額 : $subtotal 円\n";
				print MAIL "---------------------------------------------------------------\n";
			}
		}
		print MAIL "小計 : $total円\n";
		
		if ($souryou_p != 0) {
			print MAIL "送料 : $souryou_p 円\n";
		}
		
		if ($shiharai eq "代金引換") {
			print MAIL "代引手数料 : $daibiki_no 円\n";
			print MAIL "お支払い金額 : $total3 円\n";
		}
		
		if ($shiharai eq "銀行振込" || $shiharai eq "郵便振込") {
			print MAIL "お支払い金額 : $total2 円\n";
		}
		
		print MAIL "---------------------------------------------------------------\n";
		
#		open TEMP,"<set/$mailfile2" || die "Could not open the file";
#		@templine = <TEMP>;
#		foreach (@templine) {
#			++$count2;
#			($mail_comment) = split (/\n/, $_);
#			
#			print MAIL "$mail_comment\n";
#		}
#		close TEMP;
		
		print MAIL "\n\n";
		open TEMP,"<set/mail2.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			print MAIL "$_";
		}
		close TEMP;
		print MAIL "\n\n";
		
		
		close(MAIL);
		
		unlink "cart_log/$id.txt";
		
		&errortop ("送信完了","送信完了いたしました。","$url_name");
		exit;
	}
}





#------------------------------------------------
#
# 数量変更実行
#
#------------------------------------------------
sub Quantity_change {
	
	&check_kazu;	
	&zaiko2;
	$id_file = "cart_log/$id.txt";
	
	open (ITEM, "<$id_file") || die "Could not open the file";
		@lines = <ITEM>;
		close ITEM;
		
	open (OUTITEM, ">$id_file") || die "Could not open the file";
	
	foreach (@lines) {
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$page_old   = $1;
			$num_old    = $2;
			$name_old   = $3;
			$price_old  = $4;
			$kazu_old   = $5;
			
			if ($num_old eq "$delid") {
				print OUTITEM "$page_old:=:$num_old:=:$name_old:=:$price_old:=:$change_kazu\n";
			} else {
				print OUTITEM "$page_old:=:$num_old:=:$name_old:=:$price_old:=:$kazu_old\n";
			}
		}
	}
	
	
	close OUTITEM;
	
	&top("あなたのカートの中身");
	&disptop;
	&copy;
}





#-------------------------------------------------
#
# 削除
#
#-------------------------------------------------
sub Cancellation {
	
	$id_file = "cart_log/$id.txt";
	
	&zaiko3;
	
	open (ITEM, "<$id_file") || die "Could not open the file";
	@lines = <ITEM>;
	close ITEM;
	
	open (OUTITEM, ">$id_file") || die "Could not open the file";
	
	foreach (@lines) {
		if (/(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$page_old   = $1;
			$num_old    = $2;
			$name_old   = $3;
			$price_old  = $4;
			$kazu_old   = $5;
			
			if ($num_old eq "$delid") {
				print OUTITEM "";
			} else {
				print OUTITEM "$page_old:=:$num_old:=:$name_old:=:$price_old:=:$kazu_old\n";
			}
		}
	}
	
	
	close OUTITEM;
	
	if (-z $id_file) {
		&Contents_are_displayed;
	} else {
		&top("あなたのカートの中身");
		&disptop;
		&copy;
	}
}





#-------------------------------------------------
#
# カートを空にする
#
#-------------------------------------------------
sub Empty {
	&zaiko4;
	
	if (-e "cart_log/$id.txt") {
		unlink "cart_log/$id.txt";   #  削除ボタンによるカートファイル削除
		
		&errortop("カートを削除しました。","カートを削除しました。","$url_name");
		
	} else {
		&error("Not Found","指定ファイルが存在しません","BACK");
	}
}






#-------------------------------------------------
#
# 在庫増減処理3
#
#-------------------------------------------------
sub zaiko4 {
	
	if ($zaiko_disp eq "on"){
		
		$id_file = "cart_log/$id.txt";
		
		open (IN, "<$id_file") or die "Could not open the file";
		foreach (<IN>) {
			($d_page,$d_id,$d_name,$d_price,$d_kazu) = split (/:=:/, $_);
			
			close (IN);
			
			$db_file = "db/db$d_page.txt";
			
			open TEMP2,"<$db_file" || die "Could not open the file";
			@templine2 = <TEMP2>;
			close TEMP2;
			
			open (OUT, ">$db_file") or die "Could not open the file";
			foreach (@templine2) {
				($g_page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
				
				if ($d_id eq "$g_id") { $limit = $limit + $d_kazu; }
				
				
				&f_lock("OUT");
				print OUT "$g_page:=:$g_id:=:$g_name:=:$g_price:=:$g_comp:=:$g_pic:=:$limit:=:$g_comme";
				&f_lock2("OUT");
			}
			close (OUT);
		}
	}
}





#-------------------------------------------------
#
# 在庫増減処理2
#
#-------------------------------------------------
sub zaiko3 {
	
	if ($zaiko_disp eq "on"){
		
		$db_file = "db/db$page.txt";
		
		open TEMP,"<$db_file" || die "Could not open the file";
		@templine = <TEMP>;
		close TEMP;
		
		open (OUT, ">$db_file") or die "Could not open the file";
		foreach (@templine) {
			($g_page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
			
			if ($g_id eq "$delid") {
				$limit = $limit + $kazu;
			}
			
			&f_lock("OUT");
			print OUT "$g_page:=:$g_id:=:$g_name:=:$g_price:=:$g_comp:=:$g_pic:=:$limit:=:$g_comme";
			&f_lock2("OUT");
		}
		close (OUT);
	}
}





#-------------------------------------------------
#
# 在庫増減処理1
#
#-------------------------------------------------
sub zaiko2 {
	if ($zaiko_disp eq "on"){
		
		&conv1;
		
		$db_file = "db/db$page.txt";
		
		open TEMP,"<$db_file" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			($g_page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
			if ($g_id eq "$delid") {
				$limit = $limit + $kazu;
				if ($limit < $change_kazu){
					&error("在庫が足りません。","当商品の在庫は $limit 個です。","BACK");
				}
			}
		}
		close TEMP;
		
		open (OUT, ">$db_file") or die "Could not open the file";
		foreach (@templine) {
			($g_page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
			
			
			if ($g_id eq "$delid") { $limit = $limit + ($kazu - $change_kazu); }
			
			&f_lock("OUT");
			print OUT "$g_page:=:$g_id:=:$g_name:=:$g_price:=:$g_comp:=:$g_pic:=:$limit:=:$g_comme";
			&f_lock2("OUT");
		}
		close (OUT);
	}
}






#-------------------------------------------------
#
# 在庫減処理
#
#-------------------------------------------------
sub zaiko {
	if ($zaiko_disp eq "on"){
		
		$db_file = "db/db$page_id.txt";
		
		if ("$MYDATA{'new_item'}" =~ /(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
			$item_no   = $2;
			$item_kazu = $5;
		}
		
		open TEMP,"<$db_file" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			($g_page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
			if ($g_id eq "$item_no") {
				if ($limit < $item_kazu){
					&reload_error("ERROR","当商品の在庫は $limit 個です。","");
				}
			}
		}
		close TEMP;
		
		
		open (OUT, ">$db_file") or die "Could not open the file";
		foreach (@templine) {
			($page,$g_id,$g_name,$g_price,$g_comp,$g_pic,$limit,$g_comme) = split (/:=:/, $_);
			
			if ($g_id eq "$item_no") { $limit = $limit - $item_kazu; }
			
			&f_lock("OUT");
			print OUT "$page:=:$g_id:=:$g_name:=:$g_price:=:$g_comp:=:$g_pic:=:$limit:=:$g_comme";
			&f_lock2("OUT");
		}
		close (OUT);
	}
}






sub check_kazu {
	&conv1;
    $change_kazu  = int($change_kazu + 0.5) ;
	if($change_kazu	<= 0){
		&error("ERROR","無効な数値が入力されました。","BACK");
	}
}






sub reload_error {
	&top;
	print "$_[1]\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
	print "<INPUT TYPE=\"SUBMIT\" VALUE=\"買い物を続ける\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"page_id\" VALUE=\"$page_id\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"disp\" VALUE=\"on\">\n";
	print "</FORM>\n\n";
	&copy;
	exit;
}







sub errortop {
	&top;
	print "$_[1]\n";
	print "<BR>\n";
	print "<BR>\n";
#	print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"top.location.href='http://www.kigusuri.com/shop/$UserName/'\"></FORM>\n";
	&copy;
	exit;
}






sub error {
	&top;
	print "$_[1]\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"history.go(-1)\" NAME=\"button\"></FORM>\n";
	&copy;
	exit;
}






sub error2 {
	&top;
	print "$_[1]\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"history.go(-2)\" NAME=\"button\"></FORM>\n";
	&copy;
	exit;
}






sub cart_emp {
	&top;
	print "$_[1]\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<FORM><INPUT TYPE=\"button\" VALUE=\"$_[2]\" onClick=\"top.location.href='$cgifile'\"></FORM>\n";
	&copy;
	exit;
}







sub option {
	print "<INPUT TYPE=\"hidden\"NAME=\"id\" VALUE=\"$remo_add\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"page_id\" VALUE=\"$page_id\">\n";
}







sub souryou1 {
	if ($total >= $souryou2) {
		$souryou_p = 0 ;
	} else {
		$souryou_p = $souryou1 ;
	}
}





sub souryou2 {
	$souryou_p = $souryou1 ;
}





sub souryou3 {
	if ($free eq "on"){
		if ($total >= $souryou2) {
			$souryou_p = 0 ;
		}else{ 
			$souryou_p = $add_name ;
		}
	}
	if ($free eq "off"){
		$souryou_p = $add_name ;
	}
}







sub check {
	
	open IN, "<cart_log/$id.txt" || die "Could not open the file";
	
	@lines    = <IN>;
	$cartdisp = @lines;
	close IN;
	foreach (@lines) {
		if (/\d+/) { $item = ok; }
	}
	if ($item ne ok) {
		&error ("ERROR","カートは空です。","BACK");
		exit;
	}
}





#-------------------------------------------------
#
# ファイルロック
#
#-------------------------------------------------
sub f_lock {
	if ($f_lock eq on) {
		flock($_[0], 2);
		seek($_[0], 0, 2);
	}
}
sub f_lock2 {
	if ($f_lock eq on) { flock($_[0], 8); }
}
sub conv1 {
	$from = '[０-９]';
	$to   = '[0-9]';
	&jcode'convert(*change_kazu, 'euc');
	&jcode'convert(*from, 'euc');
	&jcode'convert(*to, 'euc');
	&jcode'tr(*change_kazu, $from, $to);
	&jcode'convert(*change_kazu, 'sjis');
}
sub conv {
	&jcode'convert(*shitei, 'sjis');
	&jcode'convert(*regname, 'sjis');
	&jcode'convert(*kana, 'sjis');
	&jcode'convert(*add, 'sjis');
	&jcode'convert(*atena, 'sjis');
	&jcode'convert(*comment, 'sjis');
	&jcode'convert(*regname2, 'sjis');
	&jcode'convert(*ryoushu, 'sjis');
	&jcode'convert(*shiharai, 'sjis');
	&jcode'convert(*who_shiharai, 'sjis');
	&jcode'convert(*zip, 'sjis');
	&jcode'convert(*zip2, 'sjis');
	&jcode'convert(*name, 'sjis');
	&jcode'convert(*add2, 'sjis');
}





#-------------------------------------------------
#
# 商品ディスプレイ
#
#-------------------------------------------------
sub disptop {
	print "<TABLE BGCOLOR=333333 CELLPADDING=0 CELLSPACING=0 WIDTH=560 BORDER=0>\n";
	print "<TR>\n";
	print "<TD>\n";
	print "<TABLE CELLPADDING=1 CELLSPACING=1 WIDTH=100% BORDER=0>\n";
	print "<TR BGCOLOR=\"$tab_color_top\" ALIGN=center class=main><TD>商品名</TD><TD>単価</TD><TD>数量</TD><TD>小計</TD><TD>変更</TD><TD>削除</TD></TR>\n";
	
	open CART, "<cart_log/$id.txt" || die "Could not open the file";
	
	@lines = <CART>;
	close CART;
	foreach (@lines) {
		if (/(.*):=:(\w*):=:(.*):=:(.*):=:(.*)/) {
			$page     = $1;
			$delid    = $2;
			$name     = $3;
			$price    = $4;
			$kazu     = $5;
			$subtotal = $price * $kazu;
			
			
			&comma($subtotal);
			&comma($price);
			
			print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
			print "<INPUT TYPE=hidden NAME=\"delid\" value=\"$delid\">\n";
			print "<INPUT TYPE=hidden NAME=\"kazu\" value=\"$kazu\">\n";
			print "<INPUT TYPE=hidden NAME=\"page\" value=\"$page\">\n";
			print "<INPUT TYPE=hidden NAME=\"id\" value=\"$id\">\n";
			print "<INPUT TYPE=hidden NAME=\"page_id\" value=\"$page_id\">\n";
			print "<TR class=main><TD BGCOLOR=\"$tab_color_main\">$name</TD><TD BGCOLOR=\"$tab_color_main\" ALIGN=RIGHT>$price</TD>\n";
			print "<TD BGCOLOR=\"$tab_color_main\" ALIGN=RIGHT><INPUT TYPE=text SIZE=4 NAME=\"change_kazu\" value=\"$kazu\"></TD>\n";
			print "<TD BGCOLOR=FFF8DC ALIGN=RIGHT>$subtotal 円</TD>\n";
			print "<TD BGCOLOR=\"$tab_color_main\" ALIGN=CENTER><INPUT TYPE=\"submit\" NAME=\"cart\" value=\"変更\"></TD>\n";
			print "<TD BGCOLOR=\"$tab_color_main\" ALIGN=CENTER><INPUT TYPE=\"submit\" NAME=\"cart\" value=\"削除\"></TD>\n";
			print "</TR>\n";
			print "</FORM>\n";
			
		}
	}
	print "</TD>";
	print "</TR>";
	print "</TABLE>";
	print "</TABLE><BR>";
	
	if ($cart ne '内容確認'){
		print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
		print "<INPUT TYPE=\"SUBMIT\" VALUE=\"買い物を続ける\">\n";
		print "<INPUT TYPE=\"hidden\"NAME=\"page_id\" VALUE=\"$page_id\">\n";
		print "<INPUT TYPE=\"hidden\"NAME=\"disp\" VALUE=\"on\">\n";
		print "</FORM>\n\n";
	}
	
	print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
	#   print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カテゴリ選択画面へ\">\n";
	#   print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"商品の詳細説明\">\n";
	
	if ($souryou == 3) {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"お支払い\">\n";
	} else {
		print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"レジに行く\">\n";
	}
#	print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カートを空にする\">\n";
	&option;
	print "</FORM>\n\n";
}





#------------------------------------------------
#
# カート削除ボタン
#
#------------------------------------------------
sub del_cart {
    print "<FORM METHOD=POST ACTION=\"$cgifile\">\n";
    print "<INPUT TYPE=\"SUBMIT\"NAME=\"cart\"VALUE=\"カートを空にする\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"page_id\" VALUE=\"on\">\n";
	print "<INPUT TYPE=\"hidden\"NAME=\"id\" VALUE=\"$remo_add\">\n";
    print "</FORM>\n\n";
}





#------------------------------------------------
#
# 金額の区切り処理
#
#------------------------------------------------
sub comma {
	$len=length($_[0])-3;
	for($i=$len;$i>0;$i=$i-3){
		$tmp=substr($_[0],0,$i);
		substr($_[0],0,$i)="$tmp,";
	}
}





#------------------------------------------------
#
# 都道府県別送料設定
#
#------------------------------------------------
sub read {
	print "<SELECT NAME=\"add_name\">\n";
	print "<OPTION value=\"br\">お届け先を選択\n";
	open TEMP,"<set/$addfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		($kingaku,$kenmei) = split (/:=:/, $_);
		print "<OPTION value=\"$kingaku\">$kenmei\n";
	}
	print "</SELECT>\n";
	close TEMP;
}









#-------------------------------------------------
#
# ヘッタ
#
#-------------------------------------------------
sub top {
	$page_title = $_[0];
	$dispTemp = &Get_File('cart_header.html',1);
	print "Content-type: text/html\n\n";
	print $dispTemp;
}




#-------------------------------------------------
#
# フッタ 
#
#-------------------------------------------------
sub copy {
	$dispTemp = &Get_File('cart_footer.html',1);
	print $dispTemp;
	exit;
}






#
# ログファイル削除
#
sub delete_log{
		opendir DIR , "./cart_log/";
		for ( grep /.+\.\w/, readdir DIR ) {
			++$count;

		$last_access = (-M "cart_log/$_");
		if ($last_access >= ($del_minute * 0.00069444)){
		&del;
		unlink "cart_log/$_";
	}
		}
		closedir DIR;
#&error("ログファイルを削除しました。","$del_day日以上前のログファイルを削除しました。","BACK");
}
sub del{
open (IN, "<cart_log/$_") or die "Could not open the file";
     foreach (<IN>) {
     ($g_page,$g_num,$g_name,$g_price,$g_kazu) = split (/:=:/, $_);
close (IN);


open TEMP2,"<db/db$g_page.txt" || die "Could not open the file";
	@lines = <TEMP2>;
	close TEMP2;	 

open (DB_WRITE, ">db/db$g_page.txt") || die "Could not open the file";
     foreach (@lines) {
     ($page_old,$num_old,$name_old,$price_old,$company_old,$pic_old,$zaiko_old,$comment_old) = split (/:=:/, $_);
		 
if ($num_old eq  $g_num) {
	$zaiko_old = $zaiko_old + $g_kazu;
}
	&f_lock("DB_WRITE");
	print DB_WRITE "$page_old:=:$num_old:=:$name_old:=:$price_old:=:$company_old:=:$pic_old:=:$zaiko_old:=:$comment_old";
	&f_lock2("DB_WRITE");
}
close DB_WRITE;
      }
}
