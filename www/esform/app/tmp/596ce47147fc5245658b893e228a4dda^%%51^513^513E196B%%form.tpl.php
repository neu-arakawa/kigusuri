<?php /* Smarty version 2.6.8, created on 2008-08-20 16:44:47
         compiled from form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'form.tpl', 14, false),array('function', 'html_options', 'form.tpl', 18, false),array('modifier', 'escape', 'form.tpl', 50, false),)), $this); ?>
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
	<h2><?php echo $this->_tpl_vars['app']['form_name']; ?>
</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend>追加フォーム</legend>
			<input type="hidden" name="mode" value="project_form_add" />
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['app']['id']; ?>
" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<p>
				<?php echo smarty_function_html_radios(array('name' => 'type','options' => $this->_tpl_vars['app']['type_list'],'selected' => $this->_tpl_vars['app']['type'],'onclick' => "this.form.submit();"), $this);?>

			</p>
			<p>
				<select id="index" name="index">
					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['app']['index_list'],'selected' => $this->_tpl_vars['app']['index']), $this);?>

				</select>
				行目に追加
			</p>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project&amp;index=<?php echo $this->_tpl_vars['app']['id']; ?>
';return false;" onkeypress="return;" />
				<input type="submit" value="追加する" class="button" id="button" />
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
	<table summary="フォーム要素一覧">
		<tr>
			<th style="width: 4%;"><span class="help" title="枠内クリックで行番号をセット">行</span></th>
			<th style="width: auto;">フォーム名</th>
			<th style="width: 13%;">タイプ</th>
			<th style="width: 12%;">削除</th>
			<th style="width: 12%;">設定</th>
			<th style="width: 12%;">下へ配置</th>
			<th style="width: 12%;">上へ配置</th>
		</tr>
<?php $_from = $this->_tpl_vars['app_ne']['forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['list']['iteration']++;
?>
		<tr id="list<?php echo $this->_tpl_vars['key']+1; ?>
"<?php if ($this->_tpl_vars['app']['index'] == $this->_tpl_vars['key']+1): ?> class="modified"<?php endif; ?>>
			<td onclick="paste('index', <?php echo $this->_tpl_vars['key']+1; ?>
);" onkeydown="return;" style="cursor: pointer;"><?php echo $this->_tpl_vars['key']+1; ?>
</td>
			<td><?php if ($this->_tpl_vars['item']['group']): ?>└ <?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  if ($this->_tpl_vars['item']['required']): ?><em class="required">※</em><?php endif; ?></td>
			<td><?php echo $this->_tpl_vars['item']['form']; ?>
</td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form_delete&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['key']+1; ?>
">削除する</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form_modify&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['key']+1; ?>
">設定する</a></td>
<?php if (($this->_foreach['list']['iteration'] == $this->_foreach['list']['total'])): ?>
			<td>下へ配置</td>
<?php else: ?>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form_down&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['key']+1; ?>
">下へ配置</a></td>
<?php endif;  if (($this->_foreach['list']['iteration'] <= 1)): ?>
			<td>上へ配置</td>
<?php else: ?>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_form_up&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['key']+1; ?>
">上へ配置</a></td>
<?php endif; ?>
		</tr>
<?php endforeach; endif; unset($_from); ?>
	</table>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>