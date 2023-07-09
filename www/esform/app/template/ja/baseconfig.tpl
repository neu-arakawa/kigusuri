{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div>
	<h2>基本設定</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>基本設定</legend>
			<input type="hidden" name="mode" value="project_baseconfig_do" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>フォーム内容の受信アドレス</dt>
					<dd>（カンマ区切りで複数指定可能、受信しない場合は空にする。）</dd>
					<dd><input type="text" id="admin_mail" name="admin_mail" size="60" value="{$app.admin_mail}" /></dd>
				<dt>PC用、携帯用の両テンプレート使用時の自動選択機能を有効にする</dt>
					<dd>（※有効にした場合、携帯用フォームの確認には携帯でアクセスする必要があります。）</dd>
					<dd>（※編集中のプレビュー画面に携帯用フォームが表示されなくなります。）</dd>
					<dd>{html_radios name="auto_select" options=$app.options selected=$app.auto_select"}</dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project';return false;" onkeypress="return;" />
				<input type="submit" value="設定する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{include file='../../template/ja/_footer.html'}
