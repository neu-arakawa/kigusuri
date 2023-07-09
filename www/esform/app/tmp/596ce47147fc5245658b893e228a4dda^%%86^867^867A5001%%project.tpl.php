<?php /* Smarty version 2.6.8, created on 2008-08-20 16:32:24
         compiled from project.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'project.tpl', 66, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_menu.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="form">
	<h2>フォーム管理</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend>作成フォーム</legend>
			<input type="hidden" name="mode" value="project_add" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<dl>
				<dt>フォーム名<em class="required">※</em></dt>
					<dd>（注文フォームなど）</dd>
					<dd><input type="text" id="name" name="name" size="40" value="<?php echo $this->_tpl_vars['app']['name']; ?>
" style="ime-mode: active;" /></dd>
				<dt>ファイル名</dt>
					<dd>（a～z_-）</dd>
					<dd><input type="text" id="file" name="file" size="40" value="<?php echo $this->_tpl_vars['app']['file']; ?>
" /></dd>
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
<?php $_from = $this->_tpl_vars['app']['projects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
		<tr id="list<?php echo $this->_tpl_vars['item']['id']; ?>
"<?php if ($this->_tpl_vars['app']['index'] == $this->_tpl_vars['item']['id']): ?> class="modified"<?php endif; ?>>
			<td style="line-height: 1.3em;">
				<a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_config&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
" title="このフォームの全体設定"><?php echo $this->_tpl_vars['item']['name']; ?>
</a><br />
<?php if ($this->_tpl_vars['item']['published_p']): ?>
				<a href="<?php echo $this->_tpl_vars['item']['form_file_p']; ?>
" title="<?php echo $this->_tpl_vars['item']['form_file_p']; ?>
">PC用</a>
<?php else: ?>
				<span style="color: #AAA;">PC用</span>
<?php endif; ?>
|
<?php if ($this->_tpl_vars['item']['published_m']): ?>
				<a href="<?php echo $this->_tpl_vars['item']['form_file_m']; ?>
" title="<?php echo $this->_tpl_vars['item']['form_file_m']; ?>
">携帯用</a>
<?php else: ?>
				<span style="color: #AAA;">携帯用</span>
<?php endif; ?>
			</td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_log&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">見る</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_enquete&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">見る</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_tmpl_publish&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">作成</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_mail_modify&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">編集</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_receipt_modify&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">編集</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
">設定</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_delete&amp;id=<?php echo $this->_tpl_vars['item']['id']; ?>
" onclick="return confirm('このフォームを本当に削除しますか？');" onkeypress="return;">削除</a></td>
			<td><?php echo $this->_tpl_vars['item']['count']; ?>
</td>
			<td>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m&#26376;%d&#26085; %H:%M") : smarty_modifier_date_format($_tmp, "%m&#26376;%d&#26085; %H:%M")); ?>

<?php if ($this->_tpl_vars['item']['time'] > ( time() - @C_NEW_EXPIRE )): ?>
				<em class="new">new!</em>
<?php endif; ?>
			</td>
		</tr>
<?php endforeach; endif; unset($_from); ?>
	</table>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>