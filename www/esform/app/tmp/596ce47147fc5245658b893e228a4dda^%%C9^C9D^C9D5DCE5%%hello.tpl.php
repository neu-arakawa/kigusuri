<?php /* Smarty version 2.6.8, created on 2008-08-20 16:05:40
         compiled from hello.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div>
	<h2>ES-FORMへようこそ</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend>初期設定</legend>
			<input type="hidden" name="mode" value="hello_do" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<dl>
				<dt>管理者パスワード<em class="required">※</em></dt>
					<dd>（半角英数字と記号_-+.*/の組み合わせ6～16文字）</dd>
					<dd>（※推測しにくい長めのパスワードにして下さい。）</dd>
					<dd><input type="password" id="admin_pass" name="admin_pass" size="16" value="" class="pass" /></dd>
				<dt>フォーム内容の受信アドレス</dt>
					<dd>（カンマ区切りで複数指定可能、受信しない場合は空にする。）</dd>
					<dd><input type="text" id="admin_mail" name="admin_mail" size="60" value="<?php echo $this->_tpl_vars['app']['admin_mail']; ?>
" /></dd>
			</dl>
			<p>
				<input type="submit" value="決定する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>