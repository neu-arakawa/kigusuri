#!/usr/bin/perl



require './topic_cnf.pl';
require './jcode.pl';
require './topic_util.pl';


$g_encoding = 'sjis';		#�����R�[�h


$msg = '';


$html_tdisp = './topic_request.html';
$html_pdisp = './topic_write.html';
$html_edisp = './topic_editview.html';

## �p�X���[�h�ǂݍ���
open PASS,"<../cart/set/pass.txt" || die "Could not open the file";
$passwd = <PASS>;
close PASS;

# ���͎�t
&parseInput($g_encoding);
if( ( $in{'mode'} ne 'fm' ) and ($method eq 'GET')){ &exitError("�f�d�s���\�b�h�͋�����Ă��܂���B");}


# ���[�h�Z���N�g
if   ($in{'mode'} eq 'temp2public')  { &Disp_T2P($in{'fnamer'}); }
elsif($in{'mode'} eq 'public2public'){ &Disp_P2P($in{'fnamer'}); }
elsif($in{'mode'} eq 'store2public') { &Disp_P2P($in{'fnamer'}); }
else                                 { &exitError(); }

exit;









#---------------------------------------
#   
#   Temp to Public�p �\��
#   
#---------------------------------------
sub Disp_T2P{
	
	my ($fnamer) = @_;
	my $readdata = "";
	
	
	open (FH,"$fnamer");
		$readdata = <FH>;
	close(FH);
	
	
	&Split_InstiDataTemp($readdata);
	$disp{'no'} = "[ �˗��ԍ� $irno ]";
	$tptext   = Unescape($tptext);
	
	$disp_sele{$tpg} = 'selected';
	
	&Global2In_InstiDataTemp();
	$in{'authe_id'} = $authe_id;
	&Disp_Html($html_pdisp);
	
}





#---------------------------------------
#   
#   Public to Public�p �\��
#   
#---------------------------------------
sub Disp_P2P{
	
	my ($fnamer) = @_;
	my $readdata = "";
	
	
	open (FH,"$fnamer");
		$readdata = <FH>;
	close(FH);
	
	
	&Split_InstiData($readdata);
	$disp{'no'} = "[ �L���ԍ� $kjno ]";
	$tptext = Unescape($tptext);
	
	$disp_sele{$tpg} = 'selected';
	
	&Global2In_InstiData();
	
	
	$DispTopData .= &Get_File($html_pdisp,1);
	&Disp_Html('./set_top.html');
	
}





