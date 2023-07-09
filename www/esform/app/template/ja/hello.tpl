{include file='../../template/ja/_header.html'}

<div>
	<h2>ES-FORMへようこそ</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>初期設定</legend>
			<input type="hidden" name="mode" value="hello_do" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>管理者パスワード<em class="required">※</em></dt>
					<dd>（半角英数字と記号_-+.*/の組み合わせ6～16文字）</dd>
					<dd>（※推測しにくい長めのパスワードにして下さい。）</dd>
					<dd><input type="password" id="admin_pass" name="admin_pass" size="16" value="" class="pass" /></dd>
				<dt>フォーム内容の受信アドレス</dt>
					<dd>（カンマ区切りで複数指定可能、受信しない場合は空にする。）</dd>
					<dd><input type="text" id="admin_mail" name="admin_mail" size="60" value="{$app.admin_mail}" /></dd>
			</dl>
			<p>
				<input type="submit" value="決定する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{include file='../../template/ja/_footer.html'}
