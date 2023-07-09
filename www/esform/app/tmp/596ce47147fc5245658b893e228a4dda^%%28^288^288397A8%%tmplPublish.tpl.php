<?php /* Smarty version 2.6.8, created on 2008-08-20 16:43:56
         compiled from tmplPublish.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'tmplPublish.tpl', 19, false),array('modifier', 'date_format', 'tmplPublish.tpl', 51, false),)), $this); ?>
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
	<h2>テンプレート作成</h2>
	<form action="./<?php echo $this->_tpl_vars['script']; ?>
" method="post">
		<fieldset>
			<legend>作成フォーム</legend>
			<input type="hidden" name="mode" value="project_tmpl_publish_do" />
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['app']['id']; ?>
" />
			<ul>
				<li>「<?php echo $this->_tpl_vars['app']['form_name']; ?>
」のメール文面とテンプレートファイルを作成します。</li>
				<li>作成済みの場合は、フォーム部分だけの再構築ができます。</li>
				<li>携帯用テンプレートは、「<?php echo @C_MOBILE_SUFFIX; ?>
」の付いたファイル名になります。</li>
			</ul>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_error.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<p>
				<?php echo smarty_function_html_radios(array('name' => 'type','options' => $this->_tpl_vars['app']['types'],'selected' => $this->_tpl_vars['app']['type'],'onclick' => "this.form.submit();"), $this);?>

			</p>
			<p>
				<input type="button" value="前へ戻る" class="button" onclick="location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project&amp;index=<?php echo $this->_tpl_vars['app']['id']; ?>
';return false;" onkeypress="return;" />
				<input type="submit" value="作成する" class="button" id="button" />
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
	<table>
		<tr>
			<th style="width: 18%;">テンプレート</th>
			<th style="width: auto;">ファイル</th>
			<th style="width: 14%;">編集</th>
			<th style="width: 14%;">削除</th>
			<th style="width: 19%;">更新時刻</th>
		</tr>
<?php $_from = $this->_tpl_vars['app']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['list']['iteration']++;
?>
		<tr<?php if ($this->_tpl_vars['item']['id'] == $this->_tpl_vars['app']['index']): ?> class="modified"<?php endif; ?>>
			<td><?php echo $this->_tpl_vars['item']['name']; ?>
テンプレート</td>
			<td><a href="<?php echo $this->_tpl_vars['item']['file']; ?>
"><?php echo $this->_tpl_vars['item']['file']; ?>
</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_tmpl_modify&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['item']['id']; ?>
">編集する</a></td>
			<td><a href="./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_tmpl_delete&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
&amp;index=<?php echo $this->_tpl_vars['item']['id']; ?>
" onclick="return confirm('<?php echo $this->_tpl_vars['item']['name']; ?>
テンプレートを削除しますか？');" onkeypress="return;">削除する</a></td>
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