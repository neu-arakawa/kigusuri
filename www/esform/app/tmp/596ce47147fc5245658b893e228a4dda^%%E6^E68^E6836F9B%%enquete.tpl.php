<?php /* Smarty version 2.6.8, created on 2008-08-20 17:25:08
         compiled from enquete.tpl */ ?>
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
	<h2><?php echo $this->_tpl_vars['app']['form_name']; ?>
集計結果（投票者<?php echo $this->_tpl_vars['app']['count']; ?>
名）</h2>
	<p>
		<input type="button" value="前へ戻る" class="button" onclick="location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project&amp;index=<?php echo $this->_tpl_vars['app']['id']; ?>
';return false;" onkeypress="return;" />
<?php if ($this->_tpl_vars['app']['list']): ?>
		<input type="button" value="削除する" class="button" onclick="if(confirm('この集計結果を本当に削除しますか？'))location.href='./<?php echo $this->_tpl_vars['script']; ?>
?mode=project_enquete_delete&amp;id=<?php echo $this->_tpl_vars['app']['id']; ?>
';return false;" onkeypress="return;" />
<?php endif; ?>
	</p>
<?php $_from = $this->_tpl_vars['app']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
	<table summary="<?php echo $this->_tpl_vars['item']['name']; ?>
" style="margin-top: 20px;">
		<tr>
			<th style="width: 5%;">順位</th>
			<th style="width: 40%;"><?php echo $this->_tpl_vars['item']['name']; ?>
（<?php echo $this->_tpl_vars['item']['type']; ?>
）</th>
			<th style="width: 9%;">投票数</th>
			<th style="width: auto;">投票率（計<?php echo $this->_tpl_vars['item']['total']; ?>
票）</th>
		</tr>
<?php $_from = $this->_tpl_vars['item']['vote']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['vote'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vote']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['vote']['iteration']++;
?>
		<tr>
			<td><?php echo $this->_foreach['vote']['iteration']; ?>
</td>
			<td><?php echo $this->_tpl_vars['key']; ?>
</td>
			<td><?php echo $this->_tpl_vars['item']['vote']; ?>
票</td>
			<td class="graph"><em style="width: <?php echo $this->_tpl_vars['item']['width']; ?>
%;"><span><?php echo $this->_tpl_vars['item']['rate']; ?>
%</span></em></td>
		</tr>
<?php endforeach; endif; unset($_from); ?>
	</table>
<?php endforeach; endif; unset($_from); ?>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../../template/ja/_footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>