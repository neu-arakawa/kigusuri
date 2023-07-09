<?php /* Smarty version 2.6.8, created on 2008-08-20 16:33:05
         compiled from ../../template/ja/_mail_js.html */ ?>
				<dt>オプション項目</dt>
					<dd>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■IPアドレス\n{$ip}');return false;" onkeypress="return;">[ IPアドレス ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■ホスト名\n{$host}');return false;" onkeypress="return;">[ ホスト名 ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■ブラウザ\n{$browser}');return false;" onkeypress="return;">[ ブラウザ ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■端末情報\n{$carrier}');return false;" onkeypress="return;">[ 端末情報 ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■リファラ\n{$referer}');return false;" onkeypress="return;" title="送信場所URL">[ リファラ ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■送信日付\n{$date}');return false;" onkeypress="return;">[ 送信日付 ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■送信元情報\nIPアドレス: {$ip}\nホスト名: {$host}\nブラウザ: {$browser}\n端末情報: {$carrier}\nリファラ: {$referer}\n送信日付: {$date}');return false;" onkeypress="return;"
							>[ 送信元情報一括 ]</a>
						<br />
						<a href="javascript:void(0);" onclick="paste_textarea('body',
							'■シリアル番号\n{$serial}');return false;" onkeypress="return;">[ シリアル番号 ]</a>
							（注文番号や問い合わせ番号として利用できる12桁の英数字）
					</dd>
				<dt>装飾枠</dt>
					<dd>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
'┌──────────────────────────────┐\n│　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　│\n└──────────────────────────────┘'
							);return false;" onkeypress="return;">[ 細枠 ]</a>
						<a href="javascript:void(0);" onclick="paste_textarea('body',
'┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓\n┃　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　┃\n┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛'
							);return false;" onkeypress="return;">[ 太枠 ]</a>
					</dd>
