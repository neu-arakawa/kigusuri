{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div>
	<h2>フォーム全体設定</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>フォーム全体設定</legend>
			<input type="hidden" name="mode" value="project_config_do" />
			<input type="hidden" name="id" value="{$app.id}" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>フォーム名</dt>
					<dd><input type="text" id="name" name="name" size="60" value="{$app.name}" style="ime-mode: active;" /></dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project&amp;index={$app.id}';return false;" onkeypress="return;" />
				<input type="submit" value="設定する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{include file='../../template/ja/_footer.html'}
