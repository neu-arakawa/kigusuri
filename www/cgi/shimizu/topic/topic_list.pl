
#-------------------------------------------------------------------------------
#
#	質問ディレクトリのリスト
#
#-------------------------------------------------------------------------------
sub Event_List {
	
	local ($target_dir,$msg_html,$target_html,$now_kjnm,$cnt,$pflg,$l_mode) = @_;
	
	local ($readdata,$first_kjnm,$last_kjnm,$next_kjnm,$back_kjnm,$indx,$count,$bt_next,$bt_back,$fnamer,$maxindx,$bindx);
	local @filenames;
	local @newfilenames;
	
	
	
	# 対象ディレクトリの全ファイル名を取得 昇順
	if( index($in{'mode'},'temp') == -1 ){ @filenames = &Get_Dir($target_dir,0); }
								     else{ @filenames = &Get_Dir($target_dir,1); }
	$test .= @filenames;
	# 対象ＩＤでのフィルタリング
	#if($l_mode ne 'all'){ @filenames = &EventDataFilter($in{'authe_id'},\@filenames); }
	$test .= @filenames;
	
	
	$indx = @filenames;
	if($pflg eq 'back'){ 
		$first_kjnm = $filenames[$indx-1];
		$last_kjnm  = $filenames[0];
	}else{ 
		$first_kjnm = $filenames[0];
		$last_kjnm  = $filenames[$indx-1];
	}
	
	
	
	
	if($now_kjnm eq ''){ $now_kjnm = $first_kjnm; }
	if($cnt eq ''){ $cnt = 10; }
	
	$maxindx = $indx;
	$indx = 0;
	foreach(@filenames){
		if($now_kjnm eq $_){ last; }else{ $indx = $indx + 1; }
	}
	
	
	$count = $indx + $cnt -1;
	$bindx = $indx;
	while(1){
		if($pflg eq 'back'){
			push(@newfilenames,$filenames[$indx]);
			if($filenames[$indx] eq $first_kjnm){ last; }
		}else{ 
			push(@newfilenames,$filenames[$indx]); 
			if($filenames[$indx] eq $last_kjnm){ last; }
			
		}
		
		if(int($indx) == int($count)){ $count = $indx; last; }else{ $indx = $indx + 1; }
		
	}
	
	
	
	if($bindx >= $cnt){
		$back_kjnm = $filenames[$bindx-$cnt];
	}else{
		$back_kjnm = $filenames[0];
	}
	if($maxindx < ($indx+1) ){
		$next_kjnm = $filenames[@filenames];
	}else{
		$next_kjnm = $filenames[$indx+1];
	}
	if($newfilenames[0] eq $first_kjnm ){ $back_kjnm = ''; }
	if($newfilenames[0] eq $last_kjnm ){ $back_kjnm = ''; }

#	if($newfilenames[@newfilenames] eq $last_kjnm){ $next_kjnm = ''; }
	
	
	
	foreach(@newfilenames){
		
		$fnamer = "$target_dir$_";
		open (FH,"$fnamer");
			$readdata = <FH>;
		close(FH);
		
		
		
		# 画面表示用の処理と表示
		if( index($in{'mode'},'temp') == -1){ &Split_InstiData($readdata); }
		                                else{ &Split_InstiDataTemp($readdata); }
		
		unless($syozoku){ $disp{'syozoku'} = "　"; }
					else{ $disp{'syozoku'} = $syozoku; }
#		$disp{'kaisai'} = "$styy/$stmm/$stdd";
#		if( length("$edyy$edmm$eddd") == 8 ){ $disp{'kaisai'} .= " ～ " . "$edyy/$edmm/$eddd まで"; }
#		if( length("$meyy$memm$medd") == 8 ){ $disp{'moushi'} = "$meyy/$memm/$medd まで"; }
#										else{ $disp{'moushi'} = "　"; }
#		if( length("$msyy$msmm$msdd") == 8 ){ $disp{'moushi'} = "$msyy/$msmm/$msdd" . " ～ " . $disp{'moushi'}; }
		
		
		open(TEMP,"$target_html");
    		while (<TEMP>) {
	        s/(\$[\w\d\{\}\[\]\']+)/$1/eeg;
	        $msg .= $_;
	    }
		close(TEMP);
	}
	
	
	
	
	$bt_back = '';
	if( $back_kjnm ne ''){
		$bt_back .="<form method=\"post\" action=\"$selfURL\">\n";
		$bt_back .="<input type=\"submit\" value=\"　　前の記事　　\">\n";
		$bt_back .="<input type=\"hidden\" name=\"mode\" value=\"$in{mode}\">\n";
		$bt_back .="<input type=\"hidden\" name=\"authe_id\" value=\"$in{authe_id}\">\n";
		$bt_back .="<input type=\"hidden\" name=\"authe_pwd\" value=\"$in{authe_pwd}\">\n";
		$bt_back .="<input type=\"hidden\" name=\"cnt\" value=\"$cnt\">\n";
		$bt_back .="<input type=\"hidden\" name=\"pflg\" value=\"back\">\n";
		$bt_back .="<input type=\"hidden\" name=\"now_kjnm\" value=\"$back_kjnm\">\n";
		$bt_back .="</form>\n";
	}
	
	
	
	$bt_next = '';
	if( $next_kjnm ne ''){
		$bt_next .="<form method=\"post\" action=\"$selfURL\">\n";
		$bt_next .="<input type=\"submit\" value=\"　　次の記事　　\">\n";
		$bt_next .="<input type=\"hidden\" name=\"mode\" value=\"$in{mode}\">\n";
		$bt_next .="<input type=\"hidden\" name=\"authe_id\" value=\"$in{authe_id}\">\n";
		$bt_next .="<input type=\"hidden\" name=\"authe_pwd\" value=\"$in{authe_pwd}\">\n";
		$bt_next .="<input type=\"hidden\" name=\"cnt\" value=\"$cnt\">\n";
		$bt_next .="<input type=\"hidden\" name=\"pflg\" value=\"next\">\n";
		$bt_next .="<input type=\"hidden\" name=\"now_kjnm\" value=\"$next_kjnm\">\n";
		$bt_next .="</form>\n";
	}
	
	
	
	# 画面用HTMLを埋め込みたくないけども・・・スマソ
	if( ($bt_next ne '') or ($bt_back ne '')){
		$msg .= "<br><br>\n";
		$msg .= "	<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"\#5555aa\">\n";
  		$msg .= "	<tr align=\"center\" height=\"40\">\n";
    	$msg .= "		<td><br>$bt_back</td>\n";
		$msg .= "		<td><br>$bt_next</td>\n";
		$msg .= "	</tr>\n";
		$msg .= "	</table>\n";
	}
	
	
	my $filecnt = @filenames;
	if(int($filecnt) == 0){ 
		if($in{mode} eq 'tempall'){ $msg = "現在、データはありません。";}
							  else{ $msg = "該当するデータがありません。";}
	}
	$DispTopData = $msg;
	&Disp_Html('./set_top.html');
}



#---------------------------------------
#	
#   取得ファイルのＩＤフィルタリング
#	
#   EventDataFilter(\@data);
#---------------------------------------
sub	EventDataFilter{
	
	local ($id,$data) = @_;
	local @rdata;
	
	foreach (@$data){
		my ($no,$aid) = split(/-/,$_);
		if( index($aid,$id) != -1 ){ push(@rdata,$_); }
	}
	
	return(@rdata);
	
}




1;
