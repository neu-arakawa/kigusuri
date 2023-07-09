<?php /* Smarty version 2.6.8, created on 2008-08-20 16:45:40
         compiled from ../../template/ja/_mod_radio.html */ ?>
				<dt>選択項目</dt>
					<dd>（空行を挟むと改行されます。）</dd>
					<dd>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_list_js.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</dd>
					<dd><textarea id="values" name="values" cols="40" rows="8"><?php echo $this->_tpl_vars['app']['params']['values']; ?>
</textarea></dd>