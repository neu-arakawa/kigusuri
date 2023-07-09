{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div>
	<h2>パスワード変更</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>パスワード変更</legend>
			<input type="hidden" name="mode" value="project_pwd_do" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>現在のパスワード</dt>
					<dd><input type="password" id="oldpass" name="oldpass" size="16" value="" class="pass" /></dd>
				<dt>新しいパスワード</dt>
					<dd><input type="password" id="newpass" name="newpass" size="16" value="" class="pass" /></dd>
				<dt>確認の為に再入力</dt>
					<dd><input type="password" id="chkpass" name="chkpass" size="16" value="" class="pass" /></dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project';return false;" onkeypress="return;" />
				<input type="submit" value="変更する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{include file='../../template/ja/_footer.html'}
