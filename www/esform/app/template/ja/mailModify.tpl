{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div>
	<h2>メール文面編集</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>編集フォーム</legend>
			<input type="hidden" name="mode" value="project_mail_modify_do" />
			<input type="hidden" name="id" value="{$app.id}" />
{include file='../../template/ja/_error.html'}
			<dl>
{include file='../../template/ja/_mail_js.html'}
				<dt>メール文面</dt>
					<dd><textarea id="body" name="body" cols="80" rows="20" style="width: 95%;height: 300px;">{$app.body}</textarea></dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project&amp;index={$app.id}';return false;" onkeypress="return;" />
				<input type="submit" value="保存する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{include file='../../template/ja/_footer.html'}
