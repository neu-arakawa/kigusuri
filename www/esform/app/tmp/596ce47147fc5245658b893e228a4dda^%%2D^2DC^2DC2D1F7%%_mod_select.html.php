<?php /* Smarty version 2.6.8, created on 2008-08-20 17:03:52
         compiled from ../../template/ja/_mod_select.html */ ?>
				<dt>選択項目</dt>
					<dd>（「-」で始まる行は空の値がセットされます。「選択して下さい」などに使用。）</dd>
					<dd>（「+」で始まる行はグループ名になり、以降のリストがグループ化されます。）</dd>
					<dd>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_list_js.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</dd>
					<dd><textarea id="values" name="values" cols="40" rows="8"><?php echo $this->_tpl_vars['app']['params']['values']; ?>
</textarea></dd>
				<dt>フォーム右側に付ける単位</dt>
					<dd><input type="text" id="suffix" name="suffix" size="10" value="<?php echo $this->_tpl_vars['app']['params']['suffix']; ?>
" style="ime-mode: active;" /></dd>