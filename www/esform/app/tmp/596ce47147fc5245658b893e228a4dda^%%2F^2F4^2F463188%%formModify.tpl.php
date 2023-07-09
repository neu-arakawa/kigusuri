<?php /* Smarty version 2.6.8, created on 2008-08-20 16:45:40
         compiled from formModify.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'formModify.tpl', 18, false),)), $this); ?>
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
	<h2>フォーム個別設定</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend><?php echo $this->_tpl_vars['app']['params']['name']; ?>
</legend>
			<input type="hidden" name="mode" value="project_form_modify_do" />
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['app']['params']['id']; ?>
" />
			<input type="hidden" name="index" value="<?php echo $this->_tpl_vars['app']['params']['index']; ?>
" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<dl>
				<dt>名称</dt>
					<dd><input type="text" id="name" name="name" size="40" value="<?php echo $this->_tpl_vars['app']['params']['name']; ?>
" style="ime-mode: active;" /></dd>
				<dt>必須にする</dt>
					<dd><?php echo smarty_function_html_radios(array('name' => 'required','options' => $this->_tpl_vars['app']['radios'],'selected' => $this->_tpl_vars['app']['params']['required']), $this);?>
</dd>
<?php if ($this->_tpl_vars['app']['params']['type_name'] == TEXT):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_text.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['app']['params']['type_name'] == TEXTAREA):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_text.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['app']['params']['type_name'] == SELECT):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_select.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['app']['params']['type_name'] == RADIO):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_radio.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['app']['params']['type_name'] == CHECKBOX):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_checkbox.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['app']['params']['type_name'] == FILE):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_mod_file.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

				<dt>記入例や注意点</dt>
					<dd><input type="text" id="example" name="example" size="40" value="<?php echo $this->_tpl_vars['app']['params']['example']; ?>
" style="ime-mode: auto;" /></dd>
				<dt>上段のフォームと結合（グループ化）して構築する</dt>
					<dd><?php echo smarty_function_html_radios(array('name' => 'group','options' => $this->_tpl_vars['app']['radios'],'selected' => $this->_tpl_vars['app']['params']['group']), $this);?>
</dd>
			</dl>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form&amp;id=<?php echo $this->_tpl_vars['app']['params']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['app']['params']['index']; ?>
';return false;" onkeypress="return;" />
				<input type="submit" value="適用する" class="button" />
			</p>
		</fieldset>
	</form>
</div>

<?php if ($this->_tpl_vars['app_ne']['form']): ?>
<div id="preview">
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<dl>
			<dt><?php echo $this->_tpl_vars['app']['params']['name'];  if ($this->_tpl_vars['app']['params']['required']): ?><em class="required">※</em><?php endif; ?></dt>
				<dd><?php echo $this->_tpl_vars['app_ne']['form'];  echo $this->_tpl_vars['app']['params']['suffix']; ?>
</dd>
<?php if ($this->_tpl_vars['app']['params']['example']): ?>
				<dd><?php echo $this->_tpl_vars['app']['params']['example']; ?>
</dd>
<?php endif; ?>
		</dl>
	</form>
</div>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>