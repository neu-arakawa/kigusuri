{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div id="form">
	<h2>フォーム管理</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>作成フォーム</legend>
			<input type="hidden" name="mode" value="project_add" />
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>フォーム名<em class="required">※</em></dt>
					<dd>（注文フォームなど）</dd>
					<dd><input type="text" id="name" name="name" size="40" value="{$app.name}" style="ime-mode: active;" /></dd>
				<dt>ファイル名</dt>
					<dd>（a～z_-）</dd>
					<dd><input type="text" id="file" name="file" size="40" value="{$app.file}" /></dd>
			</dl>
			<p>
				<input type="submit" value="作成する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

<div>
	<table summary="フォーム一覧">
		<tr>
			<th style="width: auto;">ﾌｫｰﾑ名</th>
			<th style="width: 6%;"><span class="help" title="送信ログの一覧">ﾛｸﾞ</span></th>
			<th style="width: 6%;"><span class="help" title="選択項目の集計結果">集計</span></th>
			<th style="width: 9%;">ﾃﾝﾌﾟﾚｰﾄ</th>
			<th style="width: 7%;">ﾒｰﾙ</th>
			<th style="width: 9%;">控えﾒｰﾙ</th>
			<th style="width: 7%;">設定</th>
			<th style="width: 7%;">削除</th>
			<th style="width: 5%;">要素</th>
			<th style="width: 15%;">更新時刻</th>
		</tr>
{foreach from=$app.projects key=key item=item}
		<tr id="list{$item.id}"{if $app.index == $item.id} class="modified"{/if}>
			<td style="line-height: 1.3em;">
				<a href="./{$script}?mode=project_config&amp;id={$item.id}" title="このフォームの全体設定">{$item.name}</a><br />
{if $item.published_p}
				<a href="{$item.form_file_p}" title="{$item.form_file_p}">PC用</a>
{else}
				<span style="color: #AAA;">PC用</span>
{/if}
|
{if $item.published_m}
				<a href="{$item.form_file_m}" title="{$item.form_file_m}">携帯用</a>
{else}
				<span style="color: #AAA;">携帯用</span>
{/if}
			</td>
			<td><a href="./{$script}?mode=project_log&amp;id={$item.id}">見る</a></td>
			<td><a href="./{$script}?mode=project_enquete&amp;id={$item.id}">見る</a></td>
			<td><a href="./{$script}?mode=project_tmpl_publish&amp;id={$item.id}">作成</a></td>
			<td><a href="./{$script}?mode=project_mail_modify&amp;id={$item.id}">編集</a></td>
			<td><a href="./{$script}?mode=project_receipt_modify&amp;id={$item.id}">編集</a></td>
			<td><a href="./{$script}?mode=project_form&amp;id={$item.id}">設定</a></td>
			<td><a href="./{$script}?mode=project_delete&amp;id={$item.id}" onclick="return confirm('このフォームを本当に削除しますか？');" onkeypress="return;">削除</a></td>
			<td>{$item.count}</td>
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
