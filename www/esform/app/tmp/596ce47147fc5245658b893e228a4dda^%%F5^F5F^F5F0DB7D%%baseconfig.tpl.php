<?php /* Smarty version 2.6.8, created on 2008-08-20 16:40:57
         compiled from baseconfig.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'baseconfig.tpl', 19, false),)), $this); ?>
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

<div>
	<h2>基本設定</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend>基本設定</legend>
			<input type="hidden" name="mode" value="project_baseconfig_do" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<dl>
				<dt>フォーム内容の受信アドレス</dt>
					<dd>（カンマ区切りで複数指定可能、受信しない場合は空にする。）</dd>
					<dd><input type="text" id="admin_mail" name="admin_mail" size="60" value="<?php echo $this->_tpl_vars['app']['admin_mail']; ?>
" /></dd>
				<dt>PC用、携帯用の両テンプレート使用時の自動選択機能を有効にする</dt>
					<dd>（※有効にした場合、携帯用フォームの確認には携帯でアクセスする必要があります。）</dd>
					<dd>（※編集中のプレビュー画面に携帯用フォームが表示されなくなります。）</dd>
					<dd><?php echo smarty_function_html_radios(array('name' => 'auto_select','options' => $this->_tpl_vars['app']['options'],'selected' => $this->_tpl_vars['app']['auto_select']), $this);?>
</dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project';return false;" onkeypress="return;" />
				<input type="submit" value="設定する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>