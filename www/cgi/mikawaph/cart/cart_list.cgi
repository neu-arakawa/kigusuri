#!/usr/bin/perl


$|=1;

require './lib/cgi-lib.pl';
require './lib/jcode.pl';
require './lib/pr_util.pl';
require './user_cnf.pl';


&ReadParse(*MYDATA);

$setting    = '<body bgcolor="#FFFFFF" text="#333333" link="#0000FF" vlink="#800080" alink="#ff0000">';  # �y�[�W�S�̂̃t�H���g�J���[���̐ݒ�
$page_title = '�y' . $UserDispName . '�z�@���i�ē�'; #�y�[�W�̃^�C�g��


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


@days   = ('��','��','��','��','��','��','�y');
@months = ('01','02','03','04','05','06','07','08','09','10','11','12');

($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
if ($mday < 10) { $mday = "0".$mday; }
if ($hour < 10) { $hour = "0".$hour; }
if ($min < 10) { $min = "0".$min; }
if ($sec < 10) { $sec = "0".$sec; }
$year = 1900 + $year;
$date = "$year�N$months[$mon]��$mday�� / $hour��$min��";


##  �󔒍폜
$email     =~ s/\s*//g;
$memo_mail =~ s/\s*//g;

$cgifile      = "cart_list.cgi"; #  ��CGI�t�@�C���̖��O
$datafile     = "db.txt";       #  �f�[�^�x�[�X�̃t�@�C���̖��O
$addfile      = "add.txt";      #  �f�[�^�x�[�X�̃t�@�C���̖��O
$mailfile     = "mail.txt";     #  ���q�l�ւ̕ԐM�p�̃R�����g�t�@�C���̖��O
$setfile      = "set.txt";      #  �e��ݒ�t�@�C���̖��O
$messfile     = 'mess.txt';
$headmessfile = 'headmess.txt';
$footmessfile = 'footmess.txt';


$style_font   = "12px";         #  �t�H���g�T�C�Y
$style_height = "16px";         #  �s��

$del_minute   = "30";           #  �Ō�ɏ��i���J�[�g�ɓ���Ă���̃J�[�g�f�[�^�̗L�������ݒ�(���P��)
$kbnMode = 0;					#  �敪����E�E�E�P�@�����E�E�E�O
	

$tab_color_top  = "#d9ac75";
$tab_color_main = "#ffeed8";
$tab_color_main2= "#ffeed8";




#-------------------------------------------------
#
# ��{�ݒ�Ǎ��ݏ���
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
# ���[���^�C�g�����{�ꉻ����
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
# �g�b�v�y�[�W # set.html��ǂݍ��݂܂��B
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
# ���O�t�@�C���쐬
#
#-------------------------------------------------
&delete_log;
if ($cart eq "���W�ɍs��" || $cart eq "����") {
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
# ���C�����[�`��
#
#-------------------------------------------------
if ($itemid ne '')										{ &Content_display;}
if ($cart eq '�J�e�S���I����ʂ�' || $cart eq '�� ��') 	{ &Shopping_is_continued; }
if ($cart eq '���e�m�F') 								{ &Contents_check; }
if ($cart eq 'disp') 									{ &Contents_check2; }
if ($cart eq '�J�[�g�ɓ����')							{ &It_puts_into_a_cart; }
if ($cart eq '�ύX')									{ &Quantity_change; }
if ($cart eq '�폜')									{ &Cancellation; }
if ($cart eq '�J�[�g����ɂ���')						{ &Empty; }
if ($cart eq '������ʂ֐i��')							{ &Payment; }
if ($cart eq reg_send)									{ &Send_Email; }
if ($disp eq 'on')										{ &Contents_are_displayed; }
if ($cart eq '���i����')								{ &Explanation; }


# &exitError('test');










#------------------------------------------------
#
# ���x��������
#
#------------------------------------------------
sub Payment {
	&check;
	
	&top("�������v�Z���܂��B");
	
	print "<table width=\"0\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"margin: 0 auto;\">\n";
	print "<tr>\n";
	print "<td><img src=\"img/send.gif\" width=\"220\" height=\"25\"></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"#808080\" align=\"center\">\n";
	print "<td>\n";
	print "<table width=\"218\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">\n";
	print "<tr align=\"center\">\n";
	print "<td>\n";
	print "<br>\n";
	print "<form method=post action=$cgifile>";
	&read;
	print "<br>\n";
	print "<br>\n";
	print "<input type=\"submit\" name=\"cart\" value=\"����\">\n";
	print "</td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr>\n";
	print "<td><IMG src=\"img/send2.gif\" width=\"220\" height=\"15\"></td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "<input type=\"hidden\" name=\"id\" value=\"$remo_add\">\n";
	print "<input type=\"hidden\" name=\"key\" value=\"on\">\n";
	print "</form><br>\n";
	
	&copy;
}






#-------------------------------------------------
#
# �������𑱂���
#
#-------------------------------------------------
sub Shopping_is_continued {
	&top("$page_title");
	&select;
	print "<br>\n";
	print "<img src=\"common/line_600.gif\" width=600 height=11 border=0>\n";
	print "<br>\n";
	print "<br>\n";
	print "<form Method=post action=\"$cgifile\">\n";
	if ($souryou == 3) {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	} else {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	}
	print "<input type=\"submit\" name=\"cart\" value=\"���e�m�F\">\n";
#	print "<input type=\"submit\" name=\"cart\" value=\"�J�[�g����ɂ���\">\n";
	&option;
	print "</form>\n";
	&copy;
	exit;
}






sub top_disp {
	&select;
	print "<br>\n";
	print "<img src=\"common/line_500.gif\" width=500 height=11 border=0>\n";
	print "<br>\n";
	print "<br>\n";
	print "<form method=post action=\"$cgifile\">\n";
	if ($souryou == 3) {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	} else {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	}
		print "input type=\"submit\" name=\"cart\" value=\"���e�m�F\">\n";
#		print "<input type=\"submit\" name=\"cart\" value=\"�J�[�g����ɂ���\">\n";
		&option;
		print "</form>\n\n";
		&copy;
		exit;
}





#------------------------------------------------
#
# ���e�m�F
#
#------------------------------------------------
sub Contents_check {
    &check;
	&top("���݂̃J�[�g�̒��g");
	&disptop;
	&copy;
}






#------------------------------------------------
#
# ���e�m�F
#
#------------------------------------------------
sub Contents_check2 {
	
	$id = $ENV{'REMOTE_ADDR'};
	
    &check;
	&top("���݂̃J�[�g�̒��g");
	&disptop;
	&copy;
}




#------------------------------------------------
#
# ���i�ǉ�
#
#------------------------------------------------
sub It_puts_into_a_cart {
	if ($MYDATA{'new_item'} eq "br") {
		&error("ERROR","���ʂ�I�����ĉ�����","�߁@��");
	}
	
	if ("$MYDATA{'new_item'}" =~ /(.*):=:(.*):=:(.*):=:(.*):=:(.*)/) {
		$page       = $1;
		$item_no    = $2;
		$item_name  = $3;
		$item_price = $4;
		$item_kazu  = $5;
	}
	
	
	## �݌ɏ���
	&zaiko;
	
	&step_1;
	
	&top("���Ȃ��̃J�[�g�̒��g");
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
#    �J�[�g�f�B�X�v���C
#
#--------------------------------------------------
sub Contents_are_displayed {
	
	&top("$page_title");
	open DB, "<db/db$page_id.txt" || die "Could not open the file";
	@templine = <DB>;
	foreach (@templine) {
		($page,$goods_id,$name,$price,$com,$picture,$limit,$comment) = split (/:=:/, $_);
		if($limit <= 0){ next; }
		
		
		$selectgoods= "<select name=\"new_item\">
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:1\">1</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:2\">2</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:3\">3</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:4\">4</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:5\">5</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:6\">6</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:7\">7</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:8\">8</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:9\">9</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:10\">10</option>
</select>";
		
		$price_disp = $price;
		&comma($price_disp);
		$limit2 = $limit +1;
		
		print "<form method=\"post\" action=\"$cgifile\">\n";
		print "<div class=\"box_sdw\">\n";
		print "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" class=\"lineup\">\n";
		print "<tr class=\"main_c box_name\"><td><h4>$name</h4></td></tr>\n";
		
		if(($comment ne '') or ($picture ne '')){
			print "<tr class=\"main_c box_item\"><td>\n";
			print "<table border=0 width=\"100%\"><tr>\n<td>";
			if($picture ne ''){ 
				$ImgSize = '';
				($Wi,$He) = &Get_JpegSize($picture);
				if($Wi > 200 or $He > 200){ $ImgSize= 'width="200"' }
				print "<div class=\"picture\"><a href=\"pic/$picture\" target=\"_blank\"><IMG SRC=\"pic/$picture\" border=\"0\" style=\"max-width:200px\"></a></div>\n";
			if($comment ne ''){ print "<div class=\"comment\">$comment</div>"; }
			}
			print "</td></tr></table></td></tr>\n";
		}
		
#		print "<tr><td rowspan=5 valign=top width= 135><img src=\"pic/$picture\" border=0>\n";
##		print "<td class=\"main2\"><b>$com</b>\n";
#		print "<tr class=\"main2\"><td>$name\n";
#		print "<tr class=\"main2\"><td><font color=\"#000000\"><b>�ō����i $price_disp �~</b></font>\n";
##		print "<tr class=\"main\"><td>$comment\n";
		
		if ($zaiko_disp eq "on") {
			if ($limit <= "0") {
				print "<tr class=\"main_c box_price\"><td><span style=\"color:#0000ff\"><b>�̔����i $price_disp �~</b></span> <span style=\"color:#ff0000\"><b>SOLD OUT</b></span> <input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">\n";
			}elsif($limit == "1" ||$limit < "10" ){
				print "<tr class=\"main_c box_price\"><td>";
				print "<span style=\"color:#000000\"><b>�ō����i $price_disp �~</b></span> <span style=\"color:#ff0000\"><b>�݌� $limit</b></span> <select name=\"new_item\">";
				for ($count = 1; $count < $limit2; ++$count) {
					print "<option value=\"$page:=:$goods_id:=:$name:=:$price:=:$count\">$count\n";	
				}
				print "</select> <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\"> \n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			} else {
				print "<tr class=\"main_c box_price\"><td>";
				print "<span style=\"color:#ff0000;\"><b>�݌ɂ���</b></span>";
				print "<span style=\"color:#000000\"><b>�ō����i $price_disp �~</b></span> $selectgoods <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\"> \n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			}
		} elsif ($zaiko_disp eq "off") {
			if ($limit <= "0") {
				print "<tr class=\"main_c box_price\"><td><span style=\"color:#ff0000;\"><b>SOLD OUT</b></span> <input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">\n";
			} else {
				print "<tr class=\"main_c box_price\"><td>";
				print "<div class=\"price\">�ō����i $price_disp �~</div> $selectgoods <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\">\n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			}
		}
		print "</td></tr></table></div>\n";
		&option;
		print "</form><br>\n\n";
	}
	close DB;
	
	&copy;
}




#--------------------------------------------------
#
#    �P�i�\��
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
		
		$selectgoods= "<select name=\"new_item\">
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:1\">1</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:2\">2</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:3\">3</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:4\">4</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:5\">5</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:6\">6</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:7\">7</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:8\">8</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:9\">9</option>
<option value=\"$page:=:$goods_id:=:$name:=:$price:=:10\">10</option>
</select>";
		
		$price_disp = $price;
		&comma($price_disp);
		$limit2 = $limit +1;
		
		print "<form method=\"post\" action=\"$cgifile\">\n";
		print "<div class=\"box_sdw\">\n";
		print "<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" class=\"lineup\">\n";
		print "<tr class=\"main_c box_name\"><td><h4>$name</h4></td></tr>\n";
		
		if(($comment ne '') or ($picture ne '')){
			print "<tr class=\"main_c box_item\"><td>\n";
			print "<table border=0 width=\"100%\"><tr>\n<td>";
			if($picture ne ''){ 
				$ImgSize = '';
				($Wi,$He) = &Get_JpegSize($picture);
				if($Wi > 200 or $He > 200){ $ImgSize= 'width="200"' }
				print "<div class=\"picture\"><a href=\"pic/$picture\" target=\"_blank\"><IMG SRC=\"pic/$picture\" border=\"0\" style=\"max-width:200px\"></a></div>\n";
			if($comment ne ''){ print "<div class=\"comment\">$comment</div>"; }
			}
			print "</td></tr></table></td></tr>\n";
		}
		
#		print "<tr><td rowspan=5 valign=top width= 135><img src=\"pic/$picture\" border=0>\n";
##		print "<td class=\"main2\"><b>$com</b>\n";
#		print "<tr class=\"main2\"><td>$name\n";
#		print "<tr class=\"main2\"><td><font color=\"#000000\"><b>�ō����i $price_disp �~</b></font>\n";
##		print "<tr class=\"main\"><td>$comment\n";
		
		if ($zaiko_disp eq "on") {
			if ($limit <= "0") {
				print "<tr class=\"main_c box_price\"><td><span style=\"color:#0000ff\"><b>�̔����i $price_disp �~</b></span> <span style=\"color:#ff0000\"><b>SOLD OUT</b></span> <input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">\n";
			}elsif($limit == "1" ||$limit < "10" ){
				print "<tr class=\"main_c\" bgcolor=\"ffffcc\"><td align=right>";
				print "<span style=\"color:#000000\"><b>�ō����i $price_disp �~</b></span> <span style=\"color:#ff0000\"><b>�݌� $limit</b></span> <select name=\"new_item\">";
				for ($count = 1; $count < $limit2; ++$count) {
					print "<option value=\"$page:=:$goods_id:=:$name:=:$price:=:$count\">$count\n";	
				}
				print "</select> <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\"> \n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			} else {
				print "<tr class=\"main_c box_price\"><td>";
				print "<span style=\"color:#ff0000;\"><b>�݌ɂ���</b></span>";
				print "<span style=\"color:#000000\"><b>�ō����i $price_disp �~</b></span> $selectgoods <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\"> \n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			}
		} elsif ($zaiko_disp eq "off") {
			if ($limit <= "0") {
				print "<tr class=\"main_c box_price\"><td><span style=\"color:#ff0000;\"><b>SOLD OUT</b></span> <input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">\n";
			} else {
				print "<tr class=\"main_c box_price\"><td align=right>";
				print "<div class=\"price\">�ō����i $price_disp �~</div> $selectgoods <input type=\"submit\" name=\"cart\" value=\"�J�[\�g�ɓ����\">\n";
				if($kbnMode == 1){ print "�@<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">"; }
			}
		}
		print "</td></tr></table></div>\n";
		&option;
		print "</form><br>\n\n";
	}
	close DB;
	
	&copy;
}




#--------------------------------------------------
#
# ������� ���i���ו\��
#
#--------------------------------------------------
sub reg2{
    &check;
	
	if ($add_name eq "br") {
		&error("ERROR","���͂���̓s���{�������I��������","�߁@��");
	}
	
	&top("���������i���e�Ƃ��x�������z");
	
	print "<h3>���������i���e�Ƃ��x�������z</h3>\n";
	print "<form method=POST action=$cgifile>";
	
	print "<table cellpadding=0 cellspacing=0 class=\"box_sdw\">\n";
	print "<tr>\n";
	print "<td>\n";
	print "<table cellpadding=2 cellspacing=1 class=\"orderf\" style=\"width:860px;\">\n";
	print "<tr align=center bgcolor=\"$tab_color_top\" class=\"main2\"><td align=center>���i�ԍ�</td><td>���i��</td><td>�P ��</td><td>�� ��</td><td>�� �v</td></tr>\n";
	
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
			
			print "<tr bgcolor=\"$tab_color_main\" class=\"main2\"><td>$name</td><td>$size</td><td align=right>$price</td><td align=right>$kazu</td><td align=right>$subtotal_�~</td></tr>\n";
				@plus = $subtotal;   #   ���i�v
				foreach $plus (@plus) {
					$total += $plus;
				}
		}
	}
	
	
	
	
	
	#-------------------------------------------------
	# �����
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
	
	print "<tr bgcolor=\"$tab_color_main\" align=right class=\"main2\"><td colspan=4 style=\"background-color:#fff;\">���i���v</td><td colspan=1>$total�~</td></tr>";
	
	
	
	#-------------------------------------------------
	#  ����ł̗L��
	#-------------------------------------------------
	&comma($tax);
	&comma($taxin);
	&comma($total2);
	&comma($total3);
	&comma($total4);
	&comma($daibiki_no);
	&comma($souryou_p);
	
	if ($souryou != 0) {
		print "<tr bgcolor=\"$tab_color_main\" align=right class=\"main2\"><td colspan=4 style=\"background-color:#fff;\">����</td><td colspan=1>$souryou_p�~</td></tr>";
	}
	
	print "<tr bgcolor=\"$tab_color_main\" align=right class=\"main2\"><td colspan=5><b>���x�������z���v�@�@ $total2�~</b></td></tr>";
	print "</table>";
	print "</td>\n";
	print "</tr>\n";
	print "</table><br><br>";
	
	
	print "\n<input type=\"hidden\" name=\"page_id\" value=\"ok\">";
	print "\n<input type=\"hidden\" name=\"id\" value=\"$id\">";
	print "\n<input type=\"hidden\" name=\"total\" value=\"$total\">";
	print "\n<input type=\"hidden\" name=\"souryou_p\" value=\"$souryou_p\">";
	print "\n<input type=\"hidden\" name=\"daibiki_no\" value=\"$daibiki_no\">";
	print "\n<input type=\"hidden\" name=\"total2\" value=\"$total2\">";
	print "\n<input type=\"hidden\" name=\"total3\" value=\"$total3\">";
	
	
	
	#-------------------------------------------------
	#   ���[�����M�l�����͗�
	#-------------------------------------------------
	if ($memory eq on) {
		print "<br><br>\n";
		print "<table class=\"box_sdw\" cellpadding=0 cellspacing=0 width=0 border=0>\n";
		print "<tr bgcolor=\"#333333\">\n";
		print "<td>\n";
		print "<table cellpadding=2 cellspacing=1 width=860 border=0 class=\"orderf\">\n";
		print "<tr bgcolor=\"$tab_color_top\" align=\"center\" class=\"main2\">\n";
		print "<td colspan=2>�u���w���ҘA����v��o�^���ꂽ��</td>\n";
		print "</tr>\n";
		print "<tr bgcolor=\"$tab_color_main\" align=\"center\" class=\"main2\">\n";
		print "<td colspan=2>�o�^���[���A�h���X <input type=\"text\" name=\"memo_mail\"size=40></td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</td>\n";
		print "</tr>\n";
		print "</table><br><br>\n";
	}
	
	print "<table cellpadding=0 cellspacing=0 width=860 border=0 class=\"orderf\">\n";
	print "<tr>\n";
	print "<td><b>�������̍ۂ́A���L���ڂ������͂̂������M�������B</b></td>\n";
	print "</tr>\n";
	print "</table>\n";
	
#	if ($memory eq on) {
#		print "<table bgcolor=\"#FFFFFF\" cellpadding=2 cellspacing=0 width=860 border=0>\n";
#		print "<tr class=\"main\">\n";
#		print "<td>����E-mail���͗����̃`�F�b�N�{�b�N�X��ON�ɂ��鎖�ŁA�l�����L�������邱�Ƃ��o���܂��B����ɂ��A����w�����ɂ́A��ɂ���o�^���[���A�h���X����E-mail�A�h���X����͂��邾�ł悭�A�l���̓��͌y���ɂȂ�܂��B</td>\n";
#		print "</tr>\n";
#		print "</table>\n";
#	}
	
	
	print "<table class=\"box_sdw\" cellpadding=0 cellspacing=0 width=0 border=0>\n";
	print "<tr>\n";
	print "<td><table cellpadding=2 cellspacing=1 width=860 border=0 class=\"orderf\">\n";
	print "<tr align=center class=\"main2\">\n";
	print "<td colspan=2 bgcolor=\"$tab_color_top\">���w���� �A����</td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>�� ��<span style=\"color:red;\"> *</span></td><td><input type=text name=regname size=30></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>�ӂ肪��<span style=\"color:red;\"> *</span></td><td><input type=text name=kana size=30>�@<span style=\"font-size:0.8em;\">���Ђ炪�Ȃł��L�����������B</span></td>\n";
	print "</tr>\n";
	if ($memory eq on) {
		print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
		print "<td>E-mail<span style=\"color:red;\"> *</span></td><td><input type=text name=email size=40> ";
		print "<p style=\"margin:0;padding:3px;\">�A�h���X��o�^����<input type=\"checkbox\" name=\"memo_check\"value=\"on\"><br>";
		print "<span style=\"font-size:0.8em;\">���A�h���X��o�^���Ă��������ƁA���񂲍w�����Ɂu���w���� �A����v�̂����͂��s�v�ɂȂ�܂��B</span></p>" . "</td>\n";
		print "</tr>\n";
	} else {
		print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
		print "<td>E-mail</td><td><input type=text name=email size=50></td>\n";
		print "</tr>\n";
	}
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>�X�֔ԍ�<span style=\"color:red;\"> *</span></td>\n";
	print "<td><input type=\"text\" name=\"zip\" value=\"��\" size=15></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>�Z��<span style=\"color:red;\"> *</span></td><td><input type=text name=add size=50></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>TEL<span style=\"color:red;\"> *</span></td><td><input type=text name=tel size=30></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main\" class=\"main2\">\n";
	print "<td>FAX</td><td><input type=text name=fax size=30></td>\n";
	print "</tr>\n";
	print "<tr align=center class=\"main2\">\n";
	print "<td colspan=2 bgcolor=\"$tab_color_top\">���͂��悪�قȂ�ꍇ�̂� ���L���������B</td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>���x�����l</td>\n";
	print "<td><input type=\"radio\" name=\"who_shiharai\" value=\"������\" checked>������\n";
	print "<input type=\"radio\" name=\"who_shiharai\" value=\"���͂���\">���͂���\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>�� ��</td><td><input type=text name=regname2 size=30></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>�X�֔ԍ�</td>\n";
	print "<td><input type=\"text\" name=\"zip2\" value=\"��\" size=15></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>�Z��</td><td><input type=text name=add2 size=50></td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>TEL</td><td><input type=text name=tel2 size=30></td>\n";
	print "</tr>\n";
	print "</td></tr></table></table>\n";
	print "<br><br>\n";
	
	
	
	
	
	print "<table class=\"box_sdw\" cellpadding=0 cellspacing=0 width=0 border=0>\n";
	print "<tr>\n";
	print "<td><table cellpadding=2 cellspacing=1 width=860 border=0 class=\"orderf\">\n";
	print "<tr align=center class=\"main2\">\n";
	print "<td colspan=2 bgcolor=\"$tab_color_top\">���x�����ɂ���</td>\n";
	print "</tr>\n";
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>�̎���</td>\n";
	print "<td><input type=\"radio\" name=\"ryoushu\" value=\"�s�v\" checked> �s�v�@�@\n";
	print "<input type=\"radio\" name=\"ryoushu\" value=\"�v\"> �v�@\n";
	print "<input type=\"text\" name=\"atena\" value=\"* �� ��\"></td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
	print "<td>���x�����@<span style=\"color:red;\"> *</span></td>\n";
	print "<td>\n";
	
	if ($ginkou_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\" value=\"��s�U��\">��s�U�� <br>\n";
	}
	if ($yuubin_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\" value=\"�X�֐U��\">�X�֐U�� <br>\n";
	}
	if ($daibiki_pay eq "on") {
		print "<input type=\"radio\" name=\"shiharai\" value=\"�������\">������� <span style=\"color:#ff0000\">���萔�� $daibiki_no�~�����Z����܂��@</span>\n";
	}
	print "</td>\n";
	print "</tr>\n";
	print "</td></tr></table></table>\n";
	print "<br><br>\n";
	
	
	
	
	
	
	print "<table class=\"box_sdw\" cellpadding=0 cellspacing=0 width=0 border=0>\n";
	print "<tr>\n";
	print "<td><table cellpadding=2 cellspacing=1 width=860 border=0 class=\"orderf\">\n";
	print "<tr align=center class=\"main2\">\n";
	print "<td colspan=2 bgcolor=\"$tab_color_top\">���̑��̏��</td>\n";
	print "</tr>\n";
	
	if ($day_appoint eq on) {
		print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
		print "<td nowrap style=\"width:6em;\">�z�B���w��</td><td><input type=text name=shitei2 size=30 value=\"�z�B�� �w��Ȃ�\"></td>\n";
		print "</tr>\n";
	}
	
	if ($time_appoint eq on) {
		print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\">\n";
		print "<td nowrap style=\"width:6em;\">���ԑюw��</td>\n";
		print "<td>\n";
		print "<input type=text name=shitei size=30 value=\"�z�B���ԑюw��Ȃ�\">\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�z�B���ԑ� �w��Ȃ�\" checked> �z�B���ԑюw��Ȃ�<br>\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�P�Q�`�P�S��\"> �P�Q�`�P�S���@�@\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�P�S�`�P�U��\"> �P�S�`�P�U���@�@\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�P�U�`�P�W��\"> �P�U�`�P�W��<br>\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�P�W�`�Q�O��\"> �P�W�`�Q�O���@�@\n";
#		print "<input type=\"radio\" name=\"shitei\" value=\"�Q�O�`�Q�P��\"> �Q�O�`�Q�P��\n";
#		print "<div style=\"font-size:0.8em; color:#666; padding-left:20px;\">�� ��ʎ���ɂ��A���͂����ԑт��O�シ��ꍇ���������܂��B<br>�@�@�\\�߂��������������܂�</div>\n";
		print "</td>\n";
		print "</tr>\n";
	}
	
	
	
	
	print "<tr bgcolor=\"$tab_color_main2\" class=\"main2\" class=\"orderf\">\n";
	print "<td>�ʐM��</td><td>�̂��A���b�s���O���̂��w��͉��L�ւ��L���������B<br><textarea name=comment rows=6 cols=50 WRAP=HARD></textarea></td>\n";
	print "</tr>\n";
	print "</td></tr></table></table>\n";
	print "<br><br>\n";
	
	if($mise_message ne ''){
		print "<table class=\"box_sdw\" bgcolor=\"#333333\" cellpadding=0 cellspacing=0 width=0 border=0>\n";
		print "<tr>\n";
		print "<td><table cellpadding=2 cellspacing=1 width=860 border=0 class=\"orderf\">\n";
		print "<tr align=center class=\"main2\">\n";
		print "<td colspan=2 bgcolor=\"$tab_color_top\">�X�܂���̂��m�点</td>\n";
		print "</tr>\n";
		print "<tr align=center class=\"main2\">\n";
		print "<td colspan=2 bgcolor=\"$tab_color_main2\" style=\"text-align:left;padding:5px;\">$mise_message</td>\n";
		print "</tr>\n";
		print "</td></tr></table></table><br><br>\n";
	}
	
	
	
	print "<div style=\"margin:0 0 0 180px;\"><input type=\"hidden\" name=\"cart\" value=\"reg\">\n";
	print "<input type=\"submit\" value=\"�m�F��ʂ�\">\n";
	print "<input type=\"reset\"  value=\"���͎��\">\n";
	print "<input type=\"button\" value=\"�� ��\" onClick=\"history.back()\" name=\"button\">";
	print "</form></div><br><br>\n";
	
	
	
	
	#-------------------------------------------------
	# ���菤����̕\��
	#-------------------------------------------------
	open TEMP,"<set/disp.html" || die "Could not open the file";
	@templine = <TEMP>;
	foreach $value (@templine) {
		$value =~ s/<!--edit-->\S.*<!--\/edit-->//;
		print "$value\n";
	}
	close TEMP;
	
	print "<br><br><br>\n";
	
	
	
	
	
	
#	&del_cart;
	
	&copy;
}





#-------------------------------------------------
#
# ���[���m�F
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
# �ŏI�m�F���
#
#-------------------------------------------------
sub pass {
	&check;
	&conv;
	# �L���R��`�F�b�N
	if ($regname eq "" || $email eq "" || $add eq "" || $tel eq ""  || $shiharai eq "") {
		&error("ERROR","\n<br><div style=\"text-align:center;\"><b style=\"color:red\">���x�����@�A�����O�A�ӂ肪�ȁAE-mail�A���Z���A���d�b�ԍ� �͕K�{���ڂł��B</b><br></div>\n","�߁@��");
		exit;
	}
	
	&top("�ŏI�m�F���");
	print "<form method=post action=$cgifile>";
	
	print "\n<input type=\"hidden\" name=\"id\" value=\"$id\">";
	print "\n<input type=\"hidden\" name=\"page_id\" value=\"ok\">";
	print "\n<input type=\"hidden\" name=\"total\" value=\"$total\">";
	print "\n<input type=\"hidden\" name=\"souryou_p\" value=\"$souryou_p\">";
	print "\n<input type=\"hidden\" name=\"daibiki_no\" value=\"$daibiki_no\">";
	print "\n<input type=\"hidden\" name=\"total2\" value=\"$total2\">";
	print "\n<input type=\"hidden\" name=\"total3\" value=\"$total3\">";
	
	
	print "\n<input type=\"hidden\" name=\"memo_mail\" value=\"$memo_mail\">";
	
	print "<table cellpadding=0 cellspacing=0 width=860 border=0>\n";
	print "<tr bgcolor=\"333333\">\n";
	print "<td>\n";
	print "<table cellpadding=2 cellspacing=1 width=100% border=0>\n";
	print "<tr align=center bgcolor=\"$tab_color_top\" class=\"main2\">\n";
	print "<td colspan=2>���q�l�̃f�[�^�E���͎��������m�F������</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>����</td>\n";
	print "<td><input type=\"hidden\"name=\"regname\" value=\"$regname\">$regname</td>\n";
	print "</tr>\n";

	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�ӂ肪��</td>\n";
	print "<td><input type=\"hidden\"name=\"kana\" value=\"$kana\">$kana</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>E-mail</td>\n";
	print "<td><input type=\"hidden\"name=\"email\" value=\"$email\">$email</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�X�֔ԍ�</td>\n";
	print "<td><input type=\"hidden\"name=\"zip\" value=\"$zip\">$zip</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�Z��</td>\n";
	print "<td><input type=\"hidden\"name=\"add\" value=\"$add\">$add</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>TEL</td>\n";
	print "<td><input type=\"hidden\"name=\"tel\" value=\"$tel\">$tel</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>FAX</td>\n";
	print "<td><input type=\"hidden\"name=\"fax\" value=\"$fax\">$fax</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�̎���</td>\n";
	print "<td><input type=\"hidden\"name=\"ryoushu\" value=\"$ryoushu\">$ryoushu</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>����</td>\n";
	print "<td><input type=\"hidden\"name=\"atena\" value=\"$atena\">$atena</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>���x�����@</td>\n";
	print "<td><input type=\"hidden\"name=\"shiharai\" value=\"$shiharai\">$shiharai</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�z�B���w��</td>\n";
	print "<td><input type=\"hidden\"name=\"shitei2\" value=\"$shitei2\">$shitei2</td>\n";
	print "</tr>\n";
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>���ԑюw��</td>\n";
	print "<td><input type=\"hidden\"name=\"shitei\" value=\"$shitei\">$shitei</td>\n";
	print "</tr>\n";
	
	if ($regname2 ne "") {
		print "<tr align=center bgcolor=\"$tab_color_top\" class=\"main\">\n";
		print "<td colspan=2><B>�z����w��</B></td>\n";
		print "</tr>\n";
		
		print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
		print "<td>���x���l</td>\n";
		print "<td><input type=\"hidden\"name=\"who_shiharai\" value=\"$who_shiharai\">$who_shiharai</td>\n";
		print "</tr>\n";
		
		print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
		print "<td>����</td>\n";
		print "<td><input type=\"hidden\"name=\"regname2\" value=\"$regname2\">$regname2</td>\n";
		print "</tr>\n";
		
		print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
		print "<td>�X�֔ԍ�</td>\n";
		print "<td><input type=\"hidden\"name=\"zip2\" value=\"$zip2\">$zip2</td>\n";
		print "</tr>\n";
		
		print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
		print "<td>�Z��</td>\n";
		print "<td><input type=\"hidden\"name=\"add2\" value=\"$add2\">$add2</td>\n";
		print "</tr>\n";
		
		print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
		print "<td>TEL</td>\n";
		print "<td><input type=\"hidden\"name=\"tel2\" value=\"$tel2\">$tel2</td>\n";
		print "</tr>\n";
	}
	
	print "<tr bgcolor=\"$tab_color_main\" class=\"main\">\n";
	print "<td>�ʐM��</td>\n";
	print "<td><input type=\"hidden\"name=\"comment\" value=\"$comment\">$comment</td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "</td>\n";
	print "</tr>\n";
	print "</table>\n";
	
	print "<br><br>\n";
	print "<input type=\"hidden\" name=\"cart\" value=\"reg_send\">\n";
	print "<input type=\"hidden\" name=\"memo_check\" value=\"$memo_check\">\n";
	
	print "<input type=\"submit\" value=\"�������m�肷��\">\n";
	print "<input type=\"button\" value=\"��O�ɖ߂�\" onClick=\"history.back()\" name=\"button\">";
	print "</FORM>\n";
	&copy;
}





#-------------------------------------------------
#
# ���[�����M
#
#-------------------------------------------------
sub Send_Email {
	
	# �L���R��`�F�b�N
	if ($regname eq "" || $email eq "" || $add eq "" || $tel eq "") {
		&error("ERROR","�L���R�ꂪ����܂�","�߁@��");
		exit;
	}
	
	unless ($email =~ /.+\@.+\..+/) {
		&error2("ERROR","���[���A�h���X�����m�F���������B","�߁@��");
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
							&error2 ("ERROR","���͂��ꂽE-mail�A�h���X�͓o�^�ς݂ł��B","�߁@��");
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
		
		print MAIL "�����N����\n";
		print MAIL "$date\n";
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        ��������̏��\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "�����O       $regname\n";
		print MAIL "�ӂ肪��     $kana\n";
		print MAIL "�d�q���[��   $email\n";
		print MAIL "�X�֔ԍ�     $zip\n";
		print MAIL "���Z��       $add\n";
		print MAIL "���d�b       $tel\n";
		print MAIL "FAX          $fax\n";
		
		if ($ryoushu eq "�v") {
			print MAIL "�̎��؈���   $atena\n";
		}
		print MAIL "���x�����@   $shiharai\n";
		
		if ($day_appoint eq on) {
			print MAIL "�z�B���w��   $shitei2\n";
		}
		
		if ($time_appoint eq on) {
			print MAIL "���ԑюw��   $shitei\n";
		}
		print MAIL "�ʐM��\n";
		print MAIL "$comment\n";
		
		if ($regname2 ne "") {
			print MAIL "---------------------------------------------------------------\n";
			print MAIL "                        ���͂���w��\n";
			print MAIL "---------------------------------------------------------------\n";
			
			print MAIL "���x���l     $who_shiharai\n";
			print MAIL "�����O       $regname2\n";
			print MAIL "�X�֔ԍ�     $zip2\n";
			print MAIL "���Z��       $add2\n";
			print MAIL "���d�b       $tel2\n";
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
				
				print MAIL "���i�� $cnt  $name $size $price X $kazu = $subtotal �~\n";
				print MAIL "---------------------------------------------------------------\n";
			}
		}
		print MAIL "���i���v     $total �~\n";
		
		&comma($souryou1);
		
		if ($souryou_p != 0) {
			print MAIL "����         $souryou_p �~\n";
		}
		
		if ($shiharai eq "�������") {
			print MAIL "����萔��   $daibiki_no �~\n";
			print MAIL "���x�������z $total3 �~\n";
		}
		
		if ($shiharai eq "��s�U��") {
			print MAIL "���x�������z $total2 �~\n";
		}
		
		if ($shiharai eq "�X�֐U��") {
			print MAIL "���x�������z $total2 �~\n";
		}
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "Remote addr: $ENV{'REMOTE_ADDR'}\n";
		print MAIL "Remote host: $ENV{'REMOTE_HOST'}\n";
		print MAIL "User Agent : $ENV{'HTTP_USER_AGENT'}\n";
		
		close(MAIL);
	
	}else{
		print "Content-type: text/html\n\n";
		print "<title>Request Rejected</title>\n";
		print "<h3>���[�����M�͎��s���܂����B</h3>\n";
	}
	
	if ($email ne "") {
		&next_mail;
	} else {
		unlink "cart_log/$id.txt";
		&errortop ("���M����","<div style=\"text-align:center;color:#d55b75;\">������������܂����B���肪�Ƃ��������܂����B</div>","TOP��");
	}
	
	exit;
}






##############################################
#
#  �����m�F�p���[��(������ւ̊m�F���[��)
#
##############################################
sub next_mail {
	
	unless ($email =~ /.+\@.+\..+/) {
		&error2("ERROR","���[���A�h���X�����m�F���������B","�߁@��");
	}
	
	if( open(MAIL,"| /$mail_pass1/$mail_pass2/sendmail $email")) {
		&conv;
		print MAIL "From: $user\n";
		print MAIL "To: $email\n";
		print MAIL "Subject: $subject\n";
		
		
		print MAIL "�����N����\n";
		print MAIL "$date\n";
		print MAIL "$regname �l\n\n";
		
#		print MAIL "���̓x�̓I�����C���V���b�v�������p�����������ɂ��肪�Ƃ��������܂��B\n";
#		print MAIL "���q�l�̂������͈ȉ��̓��e�ŏ���܂����̂ł��m�点�������܂��B\n";
		
		
		open TEMP,"<set/mail1.txt" || die "Could not open the file";
		@templine = <TEMP>;
		foreach (@templine) {
			print MAIL "$_";
		}
		close TEMP;
		
		print MAIL "\n\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        ���q�l�̏��\n";
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "�����O       $regname\n";
		print MAIL "�ӂ肪��     $kana\n";
		print MAIL "�d�q���[��   $email\n";
		print MAIL "�X�֔ԍ�     $zip\n";
		print MAIL "���Z��       $add\n";
		print MAIL "���d�b       $tel\n";
		print MAIL "FAX          $fax\n";
		
		if ($ryoushu eq "�v") {
			print MAIL "�̎��؈���   $atena\n";
		}
			print MAIL "���x�����@   $shiharai\n";
			
		if ($day_appoint eq on) {
			print MAIL "�z�B���w��   $shitei2\n";
		}
		
		if ($time_appoint eq on) {
			print MAIL "���ԑюw��   $shitei\n";
		}
		print MAIL "�ʐM��\n";
		print MAIL "$comment\n";
		
		if ($regname2 ne "") {
			print MAIL "---------------------------------------------------------------\n";
			print MAIL "                        ���͂���w��\n";
			print MAIL "---------------------------------------------------------------\n";
			
			print MAIL "���x���l     $who_shiharai\n";
			print MAIL "�����O       $regname2\n";
			print MAIL "�X�֔ԍ�     $zip2\n";
			print MAIL "���Z��       $add2\n";
			print MAIL "���d�b       $tel2\n";
		}
		
		print MAIL "---------------------------------------------------------------\n";
		print MAIL "                        ���������i���e\n";
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
				
				print MAIL "���i��$cnt2 : $size\n";
				print MAIL "�P�� : $price �~\n";
				print MAIL "���� : $kazu\n";
				print MAIL "���z : $subtotal �~\n";
				print MAIL "---------------------------------------------------------------\n";
			}
		}
		print MAIL "���v : $total�~\n";
		
		if ($souryou_p != 0) {
			print MAIL "���� : $souryou_p �~\n";
		}
		
		if ($shiharai eq "�������") {
			print MAIL "����萔�� : $daibiki_no �~\n";
			print MAIL "���x�������z : $total3 �~\n";
		}
		
		if ($shiharai eq "��s�U��" || $shiharai eq "�X�֐U��") {
			print MAIL "���x�������z : $total2 �~\n";
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
		
		&errortop ("���M����","<div style=\"text-align:center;color:#d55b75;\">������������܂����B���肪�Ƃ��������܂����B</div>","$url_name");
		exit;
	}
}





#------------------------------------------------
#
# ���ʕύX���s
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
	
	&top("���Ȃ��̃J�[�g�̒��g");
	&disptop;
	&copy;
}





#-------------------------------------------------
#
# �폜
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
		&top("���Ȃ��̃J�[�g�̒��g");
		&disptop;
		&copy;
	}
}





#-------------------------------------------------
#
# �J�[�g����ɂ���
#
#-------------------------------------------------
sub Empty {
	&zaiko4;
	
	if (-e "cart_log/$id.txt") {
		unlink "cart_log/$id.txt";   #  �폜�{�^���ɂ��J�[�g�t�@�C���폜
		
		&errortop("�J�[�g���폜���܂����B","�J�[�g���폜���܂����B","$url_name");
		
	} else {
		&error("Not Found","�w��t�@�C�������݂��܂���","�߁@��");
	}
}






#-------------------------------------------------
#
# �݌ɑ�������3
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
# �݌ɑ�������2
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
# �݌ɑ�������1
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
					&error("�݌ɂ�����܂���B","�����i�̍݌ɂ� $limit �ł��B","�߁@��");
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
# �݌Ɍ�����
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
					&reload_error("ERROR","�����i�̍݌ɂ� $limit �ł��B","");
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
		&error("ERROR","�����Ȑ��l�����͂���܂����B","�߁@��");
	}
}






sub reload_error {
	&top;
	print "$_[1]\n";
	print "<br>\n";
	print "<br>\n";
	print "<form method=post action=\"$cgifile\">\n";
	print "<input type=\"submit\" value=\"�������𑱂���\">\n";
	print "<input type=\"hidden\" name=\"page_id\" value=\"$page_id\">\n";
	print "<input type=\"hidden\" name=\"disp\" value=\"on\">\n";
	print "</form>\n\n";
	&copy;
	exit;
}







sub errortop {
	&top;
	print "$_[1]\n";
	print "<br>\n";
	print "<br>\n";
#	print "<form><input type=\"button\" value=\"$_[2]\" onClick=\"top.location.href='http://www.kigusuri.com/shop/$UserName/'\"></FORM>\n";
	&copy;
	exit;
}






sub error {
	&top;
	print "$_[1]\n";
	print "<br>\n";
	print "<br>\n";
	print "<form><input type=\"button\" value=\"$_[2]\" onClick=\"history.back()\" name=\"button\"></form>\n";
	&copy;
	exit;
}






sub error2 {
	&top;
	print "$_[1]\n";
	print "<br>\n";
	print "<br>\n";
	print "<form><input type=\"button\" value=\"$_[2]\" onClick=\"history.back()\" name=\"button\"></FORM>\n";
	&copy;
	exit;
}






sub cart_emp {
	&top;
	print "$_[1]\n";
	print "<br>\n";
	print "<br>\n";
	print "<form><input type=\"button\" value=\"$_[2]\" onClick=\"top.location.href='$cgifile'\"></form>\n";
	&copy;
	exit;
}







sub option {
	print "<input type=\"hidden\" name=\"id\" value=\"$remo_add\">\n";
	print "<input type=\"hidden\" name=\"page_id\" value=\"$page_id\">\n";
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
		&error ("ERROR","�J�[�g�͋�ł��B","�߁@��");
		exit;
	}
}





#-------------------------------------------------
#
# �t�@�C�����b�N
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
	$from = '[�O-�X]';
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
# ���i�f�B�X�v���C
#
#-------------------------------------------------
sub disptop {
	print "<table cellpadding=0 cellspacing=0 class=\"box_sdw conf\" width=\"860\">\n";
	print "<tr>\n";
	print "<td>\n";
	print "<table cellpadding=1 cellspacing=1 width=100%>\n";
	print "<tr align=center class=main bgcolor=\"$tab_color_top\"><td>���i��</td><td>�P��</td><td>����</td><td>���v</td><td>�ύX</td><td>�폜</td></tr>\n";
	
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
			
			print "<form method=POST action=\"$cgifile\">\n";
			print "<input type=hidden name=\"delid\" value=\"$delid\">\n";
			print "<input type=hidden name=\"kazu\" value=\"$kazu\">\n";
			print "<input type=hidden name=\"page\" value=\"$page\">\n";
			print "<input type=hidden name=\"id\" value=\"$id\">\n";
			print "<input type=hidden name=\"page_id\" value=\"$page_id\">\n";
			print "<tr class=main bgcolor=\"$tab_color_main\"><td>$name</td><td align=RIGHT>$price</td>\n";
			print "<td bgcolor=\"$tab_color_main\" align=RIGHT><input type=text size=4 name=\"change_kazu\" value=\"$kazu\"></td>\n";
			print "<td bgcolor=\"$tab_color_main\" align=right>$subtotal �~</td>\n";
			print "<td bgcolor=\"$tab_color_main\" align=center><input type=\"submit\" name=\"cart\" value=\"�ύX\"></td>\n";
			print "<td bgcolor=\"$tab_color_main\" align=center><input type=\"submit\" name=\"cart\" value=\"�폜\"></td>\n";
			print "</tr>\n";
			print "</form>\n";
			
		}
	}
	print "</td>";
	print "</tr>";
	print "</table>";
	print "</table><br>";
	
	if ($cart ne '���e�m�F'){
		print "<div class=\"clearfix\" style=\"margin:10px 0 50px 230px;\">\n<form method=POST action=\"$cgifile\" style=\"float:left;\">\n";
		print "<input type=\"submit\" value=\"�������𑱂���\">\n";
		print "<input type=\"hidden\" name=\"page_id\" value=\"$page_id\">\n";
		print "<input type=\"hidden\" name=\"disp\" value=\"on\">\n";
		print "</form>\n\n";
	}
	
	print "<form method=POST action=\"$cgifile\" style=\"float:left;margin:0 3em;\">\n";
	#   print "<input type=\"submit\" name=\"cart\" value=\"�J�e�S���I����ʂ�\">\n";
	#   print "<input type=\"submit\" name=\"cart\" value=\"���i�̏ڍא���\">\n";
	
	if ($souryou == 3) {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	} else {
		print "<input type=\"submit\" name=\"cart\" value=\"������ʂ֐i��\">\n";
	}
#	print "<input type=\"submit\" name=\"cart\"value=\"�J�[�g����ɂ���\">\n";
	&option;
	print "</form>\n</div>\n";
}





#------------------------------------------------
#
# �J�[�g�폜�{�^��
#
#------------------------------------------------
sub del_cart {
    print "<form method=POST action=\"$cgifile\">\n";
    print "<input type=\"SUBMIT\" name=\"cart\" value=\"�J�[�g����ɂ���\">\n";
	print "<input type=\"hidden\" name=\"page_id\" value=\"on\">\n";
	print "<input type=\"hidden\" name=\"id\" value=\"$remo_add\">\n";
    print "</form>\n\n";
}





#------------------------------------------------
#
# ���z�̋�؂菈��
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
# �s���{���ʑ����ݒ�
#
#------------------------------------------------
sub read {
	print "<select name=\"add_name\">\n";
	print "<option value=\"br\">���͂����I��\n";
	open TEMP,"<set/$addfile" || die "Could not open the file";
	@templine = <TEMP>;
	foreach (@templine) {
		($kingaku,$kenmei) = split (/:=:/, $_);
		print "<option value=\"$kingaku\">$kenmei\n";
	}
	print "</select>\n";
	close TEMP;
}









#-------------------------------------------------
#
# �w�b�^
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
# �t�b�^ 
#
#-------------------------------------------------
sub copy {
	$dispTemp = &Get_File('cart_footer.html',1);
	print $dispTemp;
	exit;
}






#
# ���O�t�@�C���폜
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
#&error("���O�t�@�C�����폜���܂����B","$del_day���ȏ�O�̃��O�t�@�C�����폜���܂����B","�߁@��");
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
