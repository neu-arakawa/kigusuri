{include file='../../template/ja/_header.html'}

{include file='../../template/ja/_menu.html'}

<div id="form">
	<h2>テンプレート編集</h2>
	<form action="./{$script}" method="post">
		<fieldset>
			<legend>編集フォーム</legend>
			<input type="hidden" name="mode" value="project_tmpl_modify_do" />
			<input type="hidden" name="id" value="{$app.id}" />
			<input type="hidden" name="index" value="{$app.index}" />
			<ul>
				<li>&lt;? ?&gt;で囲まれたPHPコードを消さない様に注意して下さい。</li>
				<li>テンプレートには「エラー画面」「完了画面」「確認画面」「フォーム画面」が含まれています。</li>
				<li>テンプレート「{$app.form_file}」をWEB作成ソフトで編集する事も可能です。</li>
				<li>関数を使ってフォームの初期値を設定できます。
					<a href="javascript:void(0);" onclick="toggle('howto');return false;" onkeypress="return;" title="設定方法">?</a></li>
			</ul>
			<dl id="howto" style="display: none;">
				<dt>各フォームの初期値の設定方法</dt>
					<dd>
						ソース2行目で末尾に_vの付いたフォーム変数を以下の関数に通すと初期値を設定できます。<br />
						チェックボックスとラジオボタンはcheckedを使って選択状態に、セレクトボックスはselectedを使って選択状態に、<br />
						入力フォームはtypedを使って文字を入力しておけます。<br />
						&lt;? checked(変数); selected(変数); typed(変数, &#039;好きな文字&#039;); ?&gt;
					</dd>
			</dl>
{include file='../../template/ja/_error.html'}
			<dl>
				<dt>ソース</dt>
					<dd><textarea id="source" name="source" cols="80" rows="20" style="width: 95%;height: 300px;ime-mode: inactive;">{$app.source}</textarea></dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./{$script}?mode=project_tmpl_publish&amp;id={$app.id}&amp;index={$app.index}';return false;" onkeypress="return;" />
				<input type="submit" value="保存する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

<div id="preview">
	<iframe src="{$app.form_file}" width="100%" height="400px" scrolling="yes">
		この部分は iframe 対応のブラウザで見て下さい。
	</iframe>
</div>

{include file='../../template/ja/_footer.html'}
