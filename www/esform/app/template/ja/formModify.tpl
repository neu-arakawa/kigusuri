{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div id="form">
	<h2>フォーム個別設定</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>{$app.params.name}</legend>
			<input type="hidden" name="mode" value="project_form_modify_do" />
			<input type="hidden" name="id" value="{$app.params.id}" />
			<input type="hidden" name="index" value="{$app.params.index}" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>名称</dt>
					<dd><input type="text" id="name" name="name" size="40" value="{$app.params.name}" style="ime-mode: active;" /></dd>
				<dt>必須にする</dt>
					<dd>{html_radios name="required" options=$app.radios selected=$app.params.required}</dd>
{if $app.params.type_name == TEXT}
{include file='../../template/ja/_mod_text.html'}
{elseif $app.params.type_name == TEXTAREA}
{include file='../../template/ja/_mod_text.html'}
{elseif $app.params.type_name == SELECT}
{include file='../../template/ja/_mod_select.html'}
{elseif $app.params.type_name == RADIO}
{include file='../../template/ja/_mod_radio.html'}
{elseif $app.params.type_name == CHECKBOX}
{include file='../../template/ja/_mod_checkbox.html'}
{elseif $app.params.type_name == FILE}
{include file='../../template/ja/_mod_file.html'}
{/if}

				<dt>記入例や注意点</dt>
					<dd><input type="text" id="example" name="example" size="40" value="{$app.params.example}" style="ime-mode: auto;" /></dd>
				<dt>上段のフォームと結合（グループ化）して構築する</dt>
					<dd>{html_radios name="group" options=$app.radios selected=$app.params.group}</dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project_form&amp;id={$app.params.id}&amp;index={$app.params.index}';return false;" onkeypress="return;" />
				<input type="submit" value="適用する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

{if $app_ne.form}
<div id="preview">
	<form action="./{$script}" method="post">
		<dl>
			<dt>{$app.params.name}{if $app.params.required}<em class="required">※</em>{/if}</dt>
				<dd>{$app_ne.form}{$app.params.suffix}</dd>
{if $app.params.example}
				<dd>{$app.params.example}</dd>
{/if}
		</dl>
	</form>
</div>
{/if}

{include file='../../template/ja/_footer.html'}
