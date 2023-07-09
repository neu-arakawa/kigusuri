{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div>
	<h2>送信ログ管理</h2>
	<p>
		<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project&amp;index={$app.id}';return false;" onkeypress="return;" />
{if $app.logs}
		<input type="button" value="削除する" class="button" onclick="if(confirm('このフォームの送信ログをすべて削除しますか？'))location.href='./{$script}?mode=project_log_clear&amp;id={$app.id}';return false;" onkeypress="return;" />
{/if}
	</p>
	<table summary="送信ログ一覧">
		<tr>
			<th style="width: 4%;">行</th>
			<th style="width: auto;">ファイル名</th>
			<th style="width: 17%;">ダウンロード</th>
			<th style="width: 17%;">削除</th>
			<th style="width: 24%;">送信時間</th>
		</tr>
{foreach from=$app.logs key=key item=item}
		<tr id="list{$key+1}">
			<td>{$key+1}</td>
			<td>{$item.file}</td>
			<td><a href="./{$script}?mode=project_log_obtain&amp;file={$item.file}">ダウンロードする</a></td>
			<td><a href="./{$script}?mode=project_log_delete&amp;file={$item.file}">削除する</a></td>
			<td>
				{$item.time|date_format:"%Y&#24180;%m&#26376;%d&#26085; %H:%M"}
{if $item.time gt ($smarty.now - 86400)}
				<em class="new">new!</em>
{/if}
			</td>
		</tr>
{/foreach}
	</table>
</div>

{include file='../../template/ja/_footer.html'}
