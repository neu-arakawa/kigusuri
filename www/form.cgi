#!/usr/bin/perl


##############################################################################
#
#	FormMailerDX 
#	v 1.01 
#	(C)CGI-LAND
#	happy@honesto.net
#	http://happy.honesto.net/cgi/
#
#	�E���̃X�N���v�g���g�p�������Ƃɂ�邢���Ȃ鑹�Q�ɂ��Ă���҂͂��̐ӂ�
#	�@�����܂���
#	�E�L��/�����A�����̗L���Ɋւ�炸�A���̃X�N���v�g�̖��f�ł̍Ĕz�z��
#�@�@�@���ړI�c�����p�i�����^���E��s�ݒu�Ȃǁj�͋֎~���܂�
#	�E�X�N���v�g�̉����͎��R�ł������쌠�\���������Ȃ�����s�ׂ͋֎~���܂�
#   �E�X�N���v�g�̐ݒu���Ɋւ��郁�[���ł̂��₢���킹�͂��������������B
#	�E�K����LURL�̗��p�K�������m�F�̏�ł����p���������B
##############################################################################


#jcode.pl�̃p�X
require'./jcode.pl';


#mimew.pl�̃p�X
require'./mimew.pl';


#�Ǘ��҃p�X���[�h
$admin = 'regGSNindoor';


#���M�O�̊m�F���
#0:�\�����Ȃ�
#1:�\������
$confirm_key = 1;


#���͕K�{����
#�쐬����HTML�t�H�[����name�������w�肵�܂�
#�Y�t�t�@�C�����K�{�Ɏw��\
#�u'�v�ň͂��āu,�v�ŋ�؂�
@req = ('��','��','�s���{��','�Z��','�d�b�ԍ�','MAIL','����','�N��','�����k���e');


#�m�F��ʃe���v���[�g�t�@�C��
$template2 = './temp2.txt';


#���[�����M��
#�ԈႦ��ƃ��[�����͂��܂���I
$mailto = 'support@kigusuri.com';


#�L�����ꂽ���[���A�h���X�ɂ�
#�������[�����T���Ƃ��đ��M�icc�ő��M���܂��j
#0:���Ȃ�
#1:����
$copy_key = 0;


#���[���̍ő啶����(bytes)
#�����Ŏw�肵���������𒴂��郁�[���͑��M�ł��Ȃ��Ȃ�܂��B
#�S�p�����P������2bytes
#���p����1������1byte
$max_length = 50000;


#�ő�t�@�C���T�C�Y(bytes)
#�t�@�C���T�C�Y�̍��v���A�����Ŏw�肵���l�𒴂���Ƒ��M�ł��܂���
$max_file_size = 3000000;


#sendmail�̃p�X
$sendmail = '/usr/sbin/sendmail';


#���̃X�N���v�g�̃t�@�C����
$scriptname = 'form.cgi';


#���[���e���v���[�g�t�@�C��
$template = './temp.txt';


#�Y�t�t�@�C���ꎞ�ۑ��f�B���N�g��
#�Ō��/�͕t���Ȃ�
$file_dir = './tmp';


#body�^�O
#�G���[�\����ʂ�Ǘ��҃��[�h�ŗL��
$body_tag = '<body bg="#ffffff" text="#000000">';


#���M������ɕ\������HTML��URL�ihttp����L���j
$jumpto = "http://www.kigusuri.com/";


#���M�������HTML�ւ̃W�����v
#0:Location�w�b�_�ňړ��i�ʏ�͂�����j
#1:META�^�O�ňړ��iLocation�w�b�_�ł��܂������Ȃ��ꍇ�͂�����j
$jump_header = 0;


#���͍��ڂ��^�u��؂�e�L�X�g�t�@�C���iTSV�t�@�C���j�ɋL�^
#0:���Ȃ�
#1:����
#�t�@�C���ɂ͓��͂��ꂽ�l�����L�^����܂��B
#��舵���ɂ͏[�������ӂ��������B
$tsv_key = 0;


#TSV�t�@�C���쐬�f�B���N�g��
#�@�����̍�������Z���E�d�b�ԍ����̏d�v�Ȍl��񓙂�
#�L�^�����\��������ꍇ�́Apublic_html�̊O�Ȃ�
#�u���E�U���璼�ڃA�N�Z�X�ł��Ȃ��ꏊ�ɍ쐬����Ȃǂ�
#�Z�L�����e�B�΍���s�����Ƃ������߂��܂��B
$tsv_dir = './tsv';


#TSV�t�@�C����
#0:�����ɍ쐬(�쐬�����t�@�C�����Fyymmdd)
#1:�����ɍ쐬(yymm)
$tsv_name = 0;


#TSV�t�@�C���ɋL�����鍀��
#�t�H�[����name�����Ŏw��
#�����Ŏw�肵�������ŋL�^����܂��B
@tsv_items = ('���O','�{��');


#TSV�t�@�C���̊g���q
#�u.�v�͕t���Ȃ�
$tsv_ext = "txt";



#�����ݒ肱���܂�

&get_form;

if($mode eq ''){
	if($confirm_key){
		&confirm;
	}else{
		&send;
	}
}elsif($mode eq 'admin'){&admin_top;}
elsif($mode eq 'edit_form'){&edit_form;}
elsif($mode eq 'edit'){&edit;}
elsif($mode eq 'confirm'){&confirm;}
elsif($mode eq 'send'){&send;}
exit;


#-------------------------------------
#----�Ǘ��҃��[�h�g�b�v
#-------------------------------------
sub admin_top{
	&header;
	print<<"HTML";
<center>
<form action="$scriptname" method="POST">
�Ǘ��҃p�X���[�h�F<input type="password" name="pass" size="8">
<input type="submit" value="OK">
<input type="hidden" name="mode" value="edit_form">
</form>
</center>
HTML
	&footer;
}


#-------------------------------------
#----���[���ҏW�t�H�[��
#-------------------------------------
sub edit_form{
	if($in{pass} ne $admin){&error("�Ǘ��҃p�X���[�h������������܂���");}
	open(TEMPLATE,$template) || &error("�e���v���[�g�t�@�C�����J���܂���");
	@allbody = <TEMPLATE>;
	close(TEMPLATE);
	
	$subject = shift @allbody;
	$body = join("",@allbody);
	
	$subject =~ s/\n//g;
	
	&header;
	print<<"HTML";
<center>
<form action="$scriptname" method="POST">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="pass" value="$in{pass}">
<table border="1" width="80%">
<tr>
<th colspan="2">���[���̕ҏW</th>
</tr>
<tr>
<td align="right">�����F</td>
<td><input type="text" name="subject" size="40" value="$subject"></td>
</tr>
<tr>
<td align="right" valign="top">�{���F</td>
<td>���M����郁�[���̖{�����L�q���Ă��������B<br>
�E�t�H�[���ɋL�����ꂽ���ڂ�{�����ɑ}������ɂ�&lt;#name����#&gt;�ƋL�q���܂��B<br>
�@��j�t�H�[����&lt;input type="text" name="�Z��" size="20"&gt;<br>
�@�Ƃ������ڂ�����A�����ɓ��͂��ꂽ�l��{�����ɕ\\���������ꍇ<br>
�@&lt;#�Z��#&gt;�ƋL�����܂��B<br>
�E���M�҂̃z�X�g�EIP��\\���������ꍇ�͂��ꂼ��&lt;#host#&gt;�A&lt#ip#&gt�Ƃ��Ă��������B<br>
<br>
<textarea cols="80" rows="20" name="body">$body</textarea></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="OK"></td>
</tr>
</table>
</form>
</center>
HTML

	&footer;
}


#-------------------------------------
#----���[���ҏW
#-------------------------------------
sub edit{
	if($in{pass} ne $admin){&error("�Ǘ��҃p�X���[�h������������܂���");}
	
	if($in{subject} eq ''){&error("�������L������Ă��܂���");}
	if($in{body} eq ''){&error("�{�����L������Ă��܂���");}
	
	$in{subject} =~ s/\r\n//g;
	$in{subject} =~ s/\r//g;
	$in{subject} =~ s/\n//g;
	$in{subject} = &repair_tag($in{subject});
	
	$in{body} =~ s/\r\n/\n/g;
	$in{body} =~ s/\r/\n/g;
	$in{body} = &repair_tag($in{body});
	
	open(TEMPLATE,">$template") || &error("�e���v���[�g�t�@�C���ɏ������߂܂���");
	print TEMPLATE $in{subject}."\n";
	print TEMPLATE $in{body};
	close(TEMPLATE);
	
	&header;
	print"<center>\n";
	print"�X�V���܂���<br>";
	print"<a href=\"$scriptname?mode=admin\">�߂�</a>\n";
	print"</center>\n";
	&footer;
}


#-------------------------------------
#----���M
#-------------------------------------
sub send{

	if($confirm_key){#�m�F��ʂ���̏ꍇ�̓Y�t�t�@�C������
		foreach $file (grep(/^file\d+$/,(keys %in))){
			$FILE_PATH{$file} = $in{"path_$file"};
			$FILE_NAME{$file} = $in{"name_$file"};
			binmode(FILE);
			open(FILE,$FILE_PATH{$file}) || &error("�t�@�C�����J���܂���");
			while(<FILE>){
				$FILE_DATA{$file} .= $_;
			}
			close(FILE);
		}
	}
	
	&chk_req;#�K�{���ڃ`�F�b�N	
	
	&send_mail;#���[�����M
	
	if($tsv_key){&add_to_tsv;}#tsv�t�@�C���ɉ�����
	
	&delete_old_file;
	
	&jump;#���M������ʂ�
}


#-------------------------------------
#----���[�����M
#-------------------------------------
sub send_mail{
	open(TEMPLATE,$template) || &error("�e���v���[�g�t�@�C�����J���܂���");
	@allbody = <TEMPLATE>;
	close(TEMPLATE);
	
	my $boundary = "boundary";#��؂�
	
	my $subject = shift(@allbody);
	my $body = join("",@allbody);
	
	foreach $key (keys %in){#�e���v���[�g�Ɠ��͍��ڂ���{���쐬
		if($confirm_key){#�m�F��ʂ�URL�G�X�P�[�v�𕜌�
			$in{$key} =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		}
		$in{$key} =~ s/\r\n/\n/g;
		$in{$key} =~ s/\r/\n/g;

		$body =~ s/\Q<#$key#>\E/$in{$key}/g;
	}
	
	if($copy_key){#�R�s�[�𑗐M����ꍇ�̓��[���A�h���X�`�F�b�N��
		&chk_mail($in{MAIL});
	}
	
	#�z�X�g�EIP�\��	
	my($host,$ip) = &get_host;
	$body =~ s/\<\#host\#\>/$host/g;
	$body =~ s/\<\#ip\#\>/$ip/g;
	
	#�����͍��ڂ̃^�O�폜
	$body =~ s/\<\#.*\#\>//g;
	
	#�����`�F�b�N
	if(length($body) > $max_length){&error("�ő啶�����𒴂��Ă��܂�");}
	
	
	#�����G���R�[�h
	$subject = &mimeencode(jcode'jis($subject));
	$subject =~ s/\n//g;
	
	#�{���G���R�[�h
	$body = jcode'jis($body);

	undef(@allbody);
	
	my $file_flag = 0;
	my $file_size = 0;
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#�Y�t�t�@�C���̗L������
		if($FILE_DATA{$file}){
			$file_flag++;
			$file_size += length($FILE_DATA{$file});#�t�@�C���T�C�Y���v���Z
		}
	}
	
	if($file_size > $max_file_size){&error("�t�@�C���T�C�Y�̍��v���傫�����܂�");}

	open(MAIL,"|$sendmail -t -i") || &error("SENDMAIL���J���܂���");
	
	if($file_flag){#�Y�t�t�@�C���L��
		
		#�w�b�_
		print MAIL "MIME-Version: 1.0\n";
		print MAIL "Content-Type: Multipart/Mixed; boundary=\"$boundary\"\n";
		print MAIL "Content-Transfer-Encoding:Base64\n";
		print MAIL 'X-Mailer: FormMailerDX (C)CGI-LAND' ."\n";#�폜���ϕs��
		print MAIL "From: $in{MAIL}\n";
		print MAIL "To: $mailto\n";
		if($copy_key){
			print MAIL "Cc: $in{MAIL}\n";
		}
		print MAIL "Subject: $subject\n";
	
		
		#�{��
		print MAIL "--$boundary\n";
		print MAIL "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
		print MAIL "\n";
		print MAIL "$body\n";
	
	
		#�Y�t�t�@�C��
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){ 	
			if($FILE_DATA{$file}){
				#$path = &save_file($FILE_DATA{$file},$FILE_NAME{$file});
				
				#�Y�t�t�@�C���G���R�[�h
				$FILE_DATA{$file} = &bodyencode($FILE_DATA{$file});
				$FILE_DATA{$file} .= &benflush;
				
				print MAIL "--$boundary\n";
				print MAIL "Content-Type: application/octet-stream; name=\"$FILE_NAME{$file}\"\n";
				print MAIL "Content-Transfer-Encoding: base64\n";
				print MAIL "Content-Disposition: attachment; filename=\"$FILE_NAME{$file}\"\n";
				print MAIL "\n";
				print MAIL "$FILE_DATA{$file}\n";
				print MAIL "\n";
	
			}
		}
	
	
		#�}���`�p�[�g�I��
		print MAIL "--$boundary--\n";
		
		#�ꎞ�ۑ��Y�t�t�@�C���폜
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#�Y�t�t�@�C���̗L������
			if(-e $FILE_PATH{$file}){unlink $FILE_PATH{$file};}
		}
		
	}else{#�Y�t�t�@�C������
		
		#�w�b�_
		print MAIL "Mime-Version: 1.0\n";
		print MAIL "Content-Type: text/plain; charset=ISO-2022-JP\n";		
		print MAIL "Content-Transfer-Encoding: 7bit\n";
		print MAIL 'X-Mailer: FormMailerDX (C)CGI-LAND' ."\n";#�폜���ϕs��
		print MAIL "To: $mailto\n";	
		print MAIL "From: $in{MAIL}\n";
		if($copy_key){
			print MAIL "Cc: $in{MAIL}\n";
		}
		print MAIL "Subject: $subject\n";
		print MAIL "\n";
		
		#�{��
		print MAIL $body."\n";
		
	}
	close MAIL;

}


#-------------------------------------
#----TSV�t�@�C���ɋL�^
#-------------------------------------
sub add_to_tsv{

	foreach $n (@tsv_items){
		push(@tsv,$in{$n}); 
	}
	my $line = join("\t",@tsv);#���͍��ڂ�A��
	$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;
	
	my($y,$m,$d) = &get_date;
	
	if(!$tsv_name){#TSV�t�@�C�����ݒ�
		$tsv = "$y$m$d.$tsv_ext";
	}else{
		$tsv = "$y$m.$tsv_ext";
	}
	
	if($confirm_key){#�m�F��ʂ�URL�G�X�P�[�v�𕜌�
		$line =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
	}
	$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;
	open(TSV,">>$tsv_dir/$tsv") || &error("$tsv_dir/$tsv.$tsv_ext:TSV�t�@�C���ɏ������߂܂���");
	print TSV $line."\n";
	close(TSV);
	
}


#-------------------------------------
#----�Â��ꎞ�ۑ��t�@�C�����폜
#-------------------------------------
sub delete_old_file{
	my $file;
	opendir(DIR,$file_dir);
	while($file = readdir(DIR)){
		if($file eq '.' || $file eq '..'){next;}
		if(time - (stat("$file_dir/$file"))[9] > 24*60*60){
			unlink("$file_dir/$file") || &error("$file_dir/$file���폜�ł��܂���");
		}
		
	}
	closedir(DIR);
}


#-------------------------------------
#----�m�F���
#-------------------------------------
sub confirm{

	&chk_req;#�K�{���ڃ`�F�b�N
	if($copy_key){#�R�s�[�𑗐M����ꍇ�̓��[���A�h���X�`�F�b�N��
		&chk_mail($in{MAIL});
	}
	
	my @files;
	my $file_flag = 0;
	
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){
		if($FILE_DATA{$file} ne ''){
			$path = &save_file($FILE_DATA{$file},$FILE_NAME{$file});
			$FILE_PATH{$file} = $path;
			$file_flag++;
		}
	}
	
	print"Content-type: text/html\n\n";
	
	open(TEMP2,"$template2") || &error("�m�F��ʃe���v���[�g���J���܂���");
	$body = join("",<TEMP2>);
	close(TEMP2);
		
	#�e����
	foreach $key (keys %in){
		$escaped{$key} = $in{$key};
		
		$in{$key} = &del_tag($in{$key});#�^�O����
		$in{$key} =~ s/\r\n/<br>/g;
		$in{$key} =~ s/\r/<br>/g;
		$in{$key} =~ s/\n/<br>/g;
		
		$escaped{$key} =~ s/(\W)/'%'.unpack('H2',$1)/eg;#URL�G�X�P�[�v		
		$hidden = "<input type=\"hidden\" name=\"$key\" value=\"$escaped{$key}\">";
		$body =~ s/\Q<#$key#>\E/$in{$key}$hidden/g;
	}
	
	#�Y�t�t�@�C��
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){
		if($FILE_DATA{$file} eq ''){next;}
		$file_hidden .= "<a href=\"$FILE_PATH{$file}\">$FILE_NAME{$file}</a> ";
		$file_hidden .= "<input type=\"hidden\" name=\"$file\" value=\"$FILE_NAME{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"path_$file\" value=\"$FILE_PATH{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"name_$file\" value=\"$FILE_NAME{$file}\">";
	}
	$body =~ s/\<\#file\#\>/$file_hidden/g;
	
	$body =~ s/\<\#.*\#\>//g;#�����͍��ڂ̃^�O�폜
	

	$body =~ s/\<\/body\>//g;
	$body =~ s/\<\/html\>//g;
	print $body;
	&footer;


}


#-------------------------------------
#----�t�H�[�����͎擾
#-------------------------------------
sub get_form{
	if($ENV{'REQUEST_METHOD'} eq "POST"){
	    if($ENV{'CONTENT_TYPE'} =~ m|multipart/form-data; boundary=([^\r\n]*)$|io){
			&decode_form_multipart($1);
		}else{
			read(STDIN,$buffer,$ENV{'CONTENT_LENGTH'});
			&decode_form;
		}
	}else { 
		$buffer = $ENV{'QUERY_STRING'}; 
		&decode_form;
	}
	$mode = $in{'mode'};
}


#-------------------------------------
#----�t�H�[�����̓f�R�[�h
#-------------------------------------
sub decode_form{
	@pairs = split(/&/,$buffer);
	foreach $pair (@pairs) {
		($name,$value) = split(/=/, $pair);
		$value =~ tr/+/ /;
		$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		$name =~ tr/+/ /;
		$name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		&jcode'convert(*value,'sjis');
		
		#$value = &del_tag($value);
		
		#����name�����̏ꍇ
		if($in{$name} ne ''){
			$in{$name} .= " ".$value;
		}else{
			$in{$name} = $value;
		}
	}
}


#-------------------------------------
#----�}���`�p�[�g�t�H�[���f�R�[�h
#-------------------------------------
sub decode_form_multipart{
	my($bound) = @_;
 	my($que,$remain,$tmp,@arr);
	$CRLF = "\r\n";
	$que = "$CRLF";
	$remain = $ENV{'CONTENT_LENGTH'};

	binmode(STDIN);
	while($remain){
		$remain -= sysread(STDIN,$tmp,$remain) || &error($!);
		$que .= $tmp;
	}

	@arr = split(/$CRLF-*$bound-*$CRLF/,$que);
	shift(@arr);
	foreach(@arr){
		$tmp = $_;

		if(/^Content-Disposition: [^;]*; name="[^;]*"; filename="[^;]*"/io){
			$tmp =~ s/^Content-Disposition: ([^;]*); name="([^;]*)"; filename="([^;]*)"($CRLF)Content-Type: ([^;]*)$CRLF$CRLF//io;
			$FILE_DATA{$2} = $tmp;
			$FILE_NAME{$2} = $3;
			$FILE_TYPE{$2} = $4;

		}elsif(/^Content-Disposition: [^;]*; name="[^;]*"/io){
			$tmp =~ s/^Content-Disposition: [^;]*; name="([^;]*)"$CRLF$CRLF//io;
			&jcode::convert(\$tmp,'sjis');
			&jcode::convert(\$1,'sjis');

			#$tmp = &del_tag($tmp);
			
			#����name�����̏ꍇ
			if($in{$1} ne ''){
				$in{$1} .= " ".$tmp;
			}else{
				$in{$1} = $tmp;
			}
		}
	}
	
	foreach $k (keys %FILE_NAME){
		my @path = split(/\\/,$FILE_NAME{$k});
		$FILE_NAME{$k} = @path[$#path];
	}
}


#-------------------------------------
#----�t�@�C���ۑ�
#-------------------------------------
sub save_file{
	my($file_data,$file_name) = @_;

	open(IMG,">$file_dir/$file_name") || &error("�t�@�C����ۑ��ł��܂���");
	print IMG $file_data;
	close(IMG);
	chmod 0666,"$file_dir/$file_name";

	return "$file_dir/$file_name";
}


#-------------------------------------
#----���M������ړ�
#-------------------------------------
sub jump{
	if(!$jump_header){#Location
		print"Location:$jumpto\n\n";
	}else{#META
		print"Content-type :text/html\n\n";
		print"<html>\n";
		print"<head>\n";
		print"<meta http-equiv=\"Refresh\" content=\"0;URL=$jumpto\">\n";
		print"</head>\n";
		print"<body bgcolor=\"#ffffff\">\n";
		print"�X�V���E�E�E<Br>";
		print"���΂炭�҂��Ă��ړ����Ȃ��ꍇ��<br>\n";
		print"<a href=\"$jumpto\">������</a>���N���b�N���Ă�������\n";
		print"</body>\n";
		print"</html>\n";
	}
}


#-------------------------------------
#----�K�{���ڃ`�F�b�N
#-------------------------------------
sub chk_req{
	foreach $req (@req){
		if($req =~ /^file\d+$/ && $FILE_DATA{$req} eq ''){#�t�@�C���̏ꍇ
			&error("$req���w�肳��Ă��܂���");
		}
		if($req !~ /^file\d+$/ && $in{$req} eq ''){#�t�@�C���ȊO�̍���
			&error("$req�����͂���Ă��܂���");
		}
	}
}


#-------------------------------------
#----�w�b�_�o��
#-------------------------------------
sub header{
	print"Content-Type:text/html\n\n";
	print<<"eof";
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=Shift_JIS">
<STYLE type="text/css">
<!--
body,tr,td,th,blockquote{font-size:$font_size}
-->
</STYLE>
<title>$title</title>
</head>
$body_tag
<br>
eof
}


#-------------------------------------
#----�t�b�^���쌠�\���i�폜�E�ύX�s�j
#-------------------------------------
sub footer{
	print"<hr>\n";
	print"<div align=\"right\"><small><a href=\"http://happy.honesto.net/cgi/\">FORMMAILER DX&nbsp;CGI-LAND</a></small></div>\n";
	print"</body>\n";
	print"</html>\n";
}


#-------------------------------------
#----���[���A�h���X�`�F�b�N
#-------------------------------------
sub chk_mail{
	my $chk = $_[0];
	if($chk eq ''){&error("���[���A�h���X���L������Ă��܂���");}
	if($chk =~ /\,/){ &error("���[���A�h���X�ɃR���}�i,�j���܂܂�Ă��܂�"); }
	if($chk !~ /[\w\.\-]+\@[\w\.\-]+\.[a-zA-Z]{2,3}/){
		&error("���[���A�h���X���S�p�ŋL������Ă��邩�A����������������܂���");
	}
}


#-------------------------------------
#---�����擾
#-------------------------------------
sub get_date{
	my($sec,$min,$hour,$mday,$mon,$year,$wdy,$yday,$isdst) = localtime(time);
	$mon++;
	$year += 1900;
	$year = substr($year,-2,2);
	if($mon < 10){$mon = "0$mon";}
	if($mday < 10){$mday = "0$mday";}
	if($hour < 10){$hour = "0$hour";}
	if($min < 10){$min = "0$min";}
	if($sec < 10){$sec = "0$sec";}
	$youbi = ('(Sun)','(Mon)','(Tue)','(Wed)','(Thu)','(Fri)','(Sat)')[$wdy];
	return($year,$mon,$mday,$hour,$min,$sec,$youbi);
}


#-------------------------------------
#----�z�X�g�EIP�擾
#-------------------------------------
sub get_host{
	my ($ip,$host);
	$ip = $ENV{'REMOTE_ADDR'};
	$host = $ENV{'REMOTE_HOST'};
	if($host eq '' || $host eq $ip){$host = gethostbyaddr(pack("C4",split(/\./,$ip)),2);}
	if($host eq ''){$host = $ip;}
	return($host,$ip);
}


#-------------------------------------
#----�^�O����
#-------------------------------------
sub del_tag{
	my $str = $_[0];
	$str =~ s/&/&amp;/g;
	$str =~ s/,/&#44;/g;
	$str =~ s/</&lt;/g;
	$str =~ s/>/&gt;/g;
	$str =~ s/\"/&quot;/g;
	return $str;
}


#-------------------------------------
#----�^�O�C��
#-------------------------------------
sub repair_tag{
	my $str = $_[0];
	$str =~ s/&lt;/</g;
	$str =~ s/&gt;/>/g;
	$str =~ s/&quot;/"/g;#"
	return $str;
}


#-------------------------------------
#----�G���[�\��
#-------------------------------------
sub error{
	if($_[1]){&unlock;}
	&header;
	print "<br>$_[0]";
	print"<br>�u���E�U��[Back(�߂�)]�{�^���Ŗ߂��Ă�������";
	&footer;
	exit;
}

