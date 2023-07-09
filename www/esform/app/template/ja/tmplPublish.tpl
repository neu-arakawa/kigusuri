{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div id="form">
	<h2>テンプレート作成</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>作成フォーム</legend>
			<input type="hidden" name="mode" value="project_tmpl_publish_do" />
			<input type="hidden" name="id" value="{$app.id}" />
			<ul>
				<li>「{$app.form_name}」のメール文面とテンプレートファイルを作成します。</li>
				<li>作成済みの場合は、フォーム部分だけの再構築ができます。</li>
				<li>携帯用テンプレートは、「{$smarty.const.C_MOBILE_SUFFIX}」の付いたファイル名になります。</li>
			</ul>
{include file='../../template/ja/_error.html'}
			<p>
				{html_radios name="type" options=$app.types selected=$app.type onclick="this.form.submit();"}
			</p>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project&amp;index={$app.id}';return false;" onkeypress="return;" />
				<input type="submit" value="作成する" class="button" id="button" />
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
	<table>
		<tr>
			<th style="width: 18%;">テンプレート</th>
			<th style="width: auto;">ファイル</th>
			<th style="width: 14%;">編集</th>
			<th style="width: 14%;">削除</th>
			<th style="width: 19%;">更新時刻</th>
		</tr>
{foreach name=list from=$app.list key=key item=item}
		<tr{if $item.id == $app.index} class="modified"{/if}>
			<td>{$item.name}テンプレート</td>
			<td><a href="{$item.file}">{$item.file}</a></td>
			<td><a href="./{$script}?mode=project_tmpl_modify&amp;id={$app.id}&amp;index={$item.id}">編集する</a></td>
			<td><a href="./{$script}?mode=project_tmpl_delete&amp;id={$app.id}&amp;index={$item.id}" onclick="return confirm('{$item.name}テンプレートを削除しますか？');" onkeypress="return;">削除する</a></td>
			<td>
				{$item.time|date_format:"%m&#26376;%d&#26085; %H:%M"}
{if $item.time gt ($smarty.now - $smarty.const.C_NEW_EXPIRE)}
				<em class="new">new!</em>
{/if}
			</td>
		</tr>
{/foreach}
	</table>
</div>

{include file='../../template/ja/_footer.html'}
