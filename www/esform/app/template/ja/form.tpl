{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div id="form">
	<h2>{$app.form_name}</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>追加フォーム</legend>
			<input type="hidden" name="mode" value="project_form_add" />
			<input type="hidden" name="id" value="{$app.id}" />
{include file='../../template/ja/_error.html'}
			<p>
				{html_radios name="type" options=$app.type_list selected=$app.type onclick="this.form.submit();"}
			</p>
			<p>
				<select id="index" name="index">
					{html_options options=$app.index_list selected=$app.index}
				</select>
				行目に追加
			</p>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project&amp;index={$app.id}';return false;" onkeypress="return;" />
				<input type="submit" value="追加する" class="button" id="button" />
			</p>
		</fieldset>
	</form>
</div>

<script type="text/javascript">
// <![CDATA[
	document.getElementById('button').style.display = 'none';
// ]]>
</script>

<div>
	<table summary="フォーム要素一覧">
		<tr>
			<th style="width: 4%;"><span class="help" title="枠内クリックで行番号をセット">行</span></th>
			<th style="width: auto;">フォーム名</th>
			<th style="width: 13%;">タイプ</th>
			<th style="width: 12%;">削除</th>
			<th style="width: 12%;">設定</th>
			<th style="width: 12%;">下へ配置</th>
			<th style="width: 12%;">上へ配置</th>
		</tr>
{foreach name=list from=$app_ne.forms key=key item=item}
		<tr id="list{$key+1}"{if $app.index == $key+1} class="modified"{/if}>
			<td onclick="paste('index', {$key+1});" onkeydown="return;" style="cursor: pointer;">{$key+1}</td>
			<td>{if $item.group}└ {/if}{$item.name|escape:"html"}{if $item.required}<em class="required">※</em>{/if}</td>
			<td>{$item.form}</td>
			<td><a href="./{$script}?mode=project_form_delete&amp;id={$app.id}&amp;index={$key+1}">削除する</a></td>
			<td><a href="./{$script}?mode=project_form_modify&amp;id={$app.id}&amp;index={$key+1}">設定する</a></td>
{if $smarty.foreach.list.last}
			<td>下へ配置</td>
{else}
			<td><a href="./{$script}?mode=project_form_down&amp;id={$app.id}&amp;index={$key+1}">下へ配置</a></td>
{/if}
{if $smarty.foreach.list.first}
			<td>上へ配置</td>
{else}
			<td><a href="./{$script}?mode=project_form_up&amp;id={$app.id}&amp;index={$key+1}">上へ配置</a></td>
{/if}
		</tr>
{/foreach}
	</table>
</div>

{include file='../../template/ja/_footer.html'}
