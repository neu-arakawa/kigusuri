package FileUpload;


#　＊利用方法
#　・ファイルアップロード
#　１．まずアップロード用フォームのＨＴＭＬ内に記述されるFORM
#　　　タグでは METHOD="POST" と ENCTYPE="multipart/form-data"
#　　　が必ず設定されていなければいけません。
#　　　これ以外が指定されている場合や、これらが指定されている
#　　　ことが確認できなかった場合(*1)、fileupload.plは エラーを
#　　　出力します。
#　　（*1：環境変数REQUEST_METHOD,CONTENT_TYPEで確認しています）
#      <input type="file" name="">
#
#
#　２．使用例
#　　　require'file_upload.pl';
#　　　$alimit = '500';
#　　　$elimit = '100';
#　　　$dir    = './savefile/';
#　　　$allow  = 'gif,jpeg,jpg';
#　　　$savef  = '-ow';
#
#　　　($flag,$msg,$buffer,$savefile) = &FileUpload::receive($alimit,$elimit,$allow,$savef);
#
#　　　＊fileupload.plに渡すデータ
#
#　　　$alimit： フォームから送られてくるデータ全体のサイズについて、
#　　　　　　　　制限をかけたい場合に数値を指定します。[単位=byte]
#　　　　　　　　例えば 500を指定した場合、環境変数CONTENT_LENGTH
#　　　　　　　　の値が 500以上ならエラーが出力されます。
#　　　　　　　　制限をかけたくない場合は空にします。
#
#　　　$elimit： フォームから送られてくる、アップロードするファイル
#　　　　　　　　のサイズについて制限をかけたい場合に数値を指定しま
#　　　　　　　　す。[単位=byte]
#　　　　　　　　例えば 100を指定した場合、アップロードされるファイ
#　　　　　　　　ルのうち１つでも100バイトを越えているとエラーが出力
#　　　　　　　　されます。
#　　　　　　　　制限をかけたくない場合は空にします。
#
#　　　$dir　 ： アップロードするファイルの保存先ディレクトリを指定
#　　　　　　　　します。/ で終わっている必要があります。保存先ディ
#　　　　　　　　レクトリのパーミッションはＣＧＩがファイルを生成で
#　　　　　　　　きる値にしておいてください。
#
#　　　$allow ： アップロードを許可するファイル拡張子を指定します。
#　　　　　　　　ここで指定した拡張子以外のファイルが１つでもあると、
#　　　　　　　　エラーが出力されます。
#　　　　　　　　なお、複数指定可能ですが、その場合は半角カンマで区
#　　　　　　　　切ってください。
#　　　　　　　（複数指定の場合、必ず上記の例のように 一度 変数に入
#　　　　　　　　れたものをbfupload.plに渡すようにし、直接 ()の中に
#　　　　　　　　書かないでください）
#
#　　　$savef ： ファイルを保存するタイプの指定です。
#　　　　　　　　-ow：アップロードされたファイルのファイル名を使用
#　　　　　　　　-tm：アップロードの際の日時をファイル名に使用
#　　　　　　　　-fn：フォームのname=で指定された文字をファイルに使用
#　　　　　　　　注）同じファイル名が存在した場合は上書きされます。
#
#
#　　　＊fileupload.plから返されるデータ
#
#　　　$flag　　： なんらかの原因でエラーになりアップロードできなかっ
#　　　　　　　　　た場合、ここに f が返されます。
#
#　　　$msg　 　： エラーだった場合など、ここにメッセージが返されます。
#
#　　　$buffer　： 通常のフォーム送信と同様にＵＲＬエンコードされた状
#　　　　　　　　　態でフォームの内容が返されます。
#
#　　　$savefile： 保存したファイルが、name=保存ファイル名の形で返さ
#　　　　　　　　　れます。name部分はフォームのname=で指定された文字
#　　　　　　　　　です。複数ある場合は&で結合したものが返されます。
#　　　　　　　　　例）name1=file1.txt&name2=file2.txt
#
#＊備考
#
#・アップロードするファイルタイプのチェックは拡張子のみで行っています。
#
#・ファイル保存タイプが -owの場合、受信したファイルパスの末尾にあるフ
#　ァイル名を使用します。
#
#・ファイル保存タイプが -ow以外の場合、適切なファイル名＋.＋拡張子 と
#　いう形になり、このときの拡張子は受信したファイルパスの末尾にあるフ
#　ァイル名についた拡張子が使用されます。（ファイル保存タイプが -ow以
#　外の場合アップロードするファイルは必ず拡張子つきのファイル名になっ
#　ている必要があります）
#
#・受信したデータは加工せずそのまま保存しています。
#
#・複数のファイルをアップロードする場合、いずれかのファイルでサイズ制
#　限やファイルタイプのためエラーになると、ファイルは１つもアップロー
#　ドされません。
#　どのファイルもエラーにならなかった場合のみ、すべてのファイルがアッ
#　プロードされます。
#


# 追加予定
# マックバイナリ対策
#	if ($_ =~ /application\/x-macbinary/i) { $macbin=1; }
#	if ($macbin) {
#		$length = substr($upfile,83,4);
#		$length = unpack("%N",$length);
#		$upfile = substr($upfile,128,$length);
#	}

sub receive {
	
	local @set = @_;
	local ($time,$buffer,$errflag,$errmsg,$boundary,$head,$body,$j);
    local ($fname,$name,$file,$filename,$formdata,$ctype,$allow,$savefile);
	local @allows;
	local @ar_file;
	local @ar_body;
	local @ar_errmsg;
	
	$errflag = '';
    $errmsg = '';
	
	#get_time
	local($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
	$year = ($year + 1900);
	$mon++;
	if ($sec < 10)	{$sec = "0$sec";}
	if ($min < 10)	{$min = "0$min";}
	if ($hour < 10)	{$hour = "0$hour";}
	if ($mon < 10)	{$mon = "0$mon";}
	if ($mday < 10)	{$mday = "0$mday";}
	$time = "$year$mon$mday$hour$min$sec";
	
	#check_all_data_size
	if($set[0] ne '' && $ENV{'CONTENT_LENGTH'} > $set[0]){
		push(@ar_errmsg,'データ容量が制限値を越えています。');
		$errflag = 'f';
	}
	
	#check_form_type
	if($ENV{'REQUEST_METHOD'} eq "POST" && $ENV{'CONTENT_TYPE'} =~ /^multipart\/form-data/){
		binmode(STDIN);
		read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
	}
	else{
		push(@ar_errmsg,'フォームの設定に誤りがあります。');
		$errflag = 'f';
	}
	
	
	#get_boundary
	if($ENV{'CONTENT_TYPE'} =~ /boundary.*?=.*?(-+\w*)$/){
		$boundary = $1;
	}
	
	@buffer = split(/$boundary/,$buffer);
	$j = 0;
	foreach $buffer(@buffer){
		if($buffer eq '' || $buffer =~ /^-+$/){
			next;
		}
		$buffer =~ s/^-+(\r\n)//;
		$buffer =~ s/(\r\n)-+$//;
		$buffer =~ s/^(\r\n)+|(\r\n)+$//g;
		($head,$body) = split(/\r\n\r\n/,$buffer,2);
		$head .= "\r\n";
		$head =~ m/name\s*?=\s*?"(.*?)"/;
		$fname = $name = $1;

		$name =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
		$name =~ s/ /+/g;

		if($head =~ /filename\s*?=\s*?"?(.*?)"?\r\n/){
			$file = $filename = $1;
			$file =~ m/.*[\/\\\:](.*)$/;
			$file = $1;
			if($set[4] eq '-tm'){
				$file =~ s/.*(\..*)/$time$1/;
			}
			elsif($set[4] eq '-fn'){
				$file =~ s/.*(\..*)/$fname$1/g;
			}
			$filename =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
			$filename =~ s/ /+/g;
			$formdata .= '&'.$name.'='.$filename;

			#check_each_file_size
			if($set[1] ne '' && length($body) ne '' && length($body) > $set[1]){
				push(@ar_errmsg, "$file のファイルサイズが制限値を越えています。");
				$errflag = 'f';
			}

			#check_file_type
			$head =~ m/Content\-Type\:\s*(.*?)\r\n/i;
			$ctype = $1;
			@allows = split(/,/,$set[2]);
			$i = 0;
			foreach $allow(@allows){
				if($filename eq '' || $filename =~ /\.$allow$/){
					$i++;
				}
			}
			if($i == 0){
				push(@ar_errmsg, "$file は扱えないファイルタイプです。");
				$errflag = 'f';
			}
			
			#ready_save
			push(@ar_file,$file);
			push(@ar_body,$body);
			$j++;
			$savefile .= '&'.$name.'='.$file;
		}
		else{
			$body =~ s/([^\.\*\-_a-zA-Z0-9 ])/sprintf("%%%02lX",unpack("C",$1))/eg;
			$body =~ s/ /+/g;
			$formdata .= '&'.$name.'='.$body;
		}
	}
	
	
	$formdata =~ s/^\&//;
	$savefile =~ s/^\&//;
	
	if($#ar_errmsg+1 > 0){ $errmsg = join("\t",@ar_errmsg); }
	
	return($errflag,$errmsg,$formdata,$savefile,$j,\@ar_file,\@ar_body);
}




#-----------------------------------------------------------------------
#	書込み
#	
#   $errmsg = &FileUpload::write($dir,$alimit,$cnt,\@file_name,\@file_data);
#	
#-----------------------------------------------------------------------
sub write {
	
	local ($dir,$alimit,$j,$file,$body) = @_;
	local ($i,$f,$b,$errmsg,$cnt,$limit);
	
	
	#ディレクトリチェック
	if($dir !~ /\/$/){
		$dir .= '/';
	}
	if($dir eq '' || ! -d $dir || ! -w $dir){
		$errmsg = '保存ディレクトリの設定に誤りがあります。';
		return('1',$errmsg);
	}
	
	
	#ファイルの容量
	if($j != 0){
		$i = 0;
		while($i <= $j){
			$f = @$file[$i];
			$b = @$body[$i];
			if($f){	$limit = $limit + length($b); }
			$i++;
		}
	}
	if($limit > $alimit){
		$errmsg = '使用可能領域を超えています。';
		return('1',$errmsg);
	}
	
	
	$cnt = 0;
	#ファイルの保存
	if($j != 0){
		$i = 0;
		while($i <= $j){
			$f = @$file[$i];
			$b = @$body[$i];
			if($f){
				open (FILE,">$dir$f");
				binmode (FILE);
				print FILE $b;
				close (FILE);
				$cnt++;
			}
			$i++;
		}
	}
	return('',$cnt);
	
}


1;
