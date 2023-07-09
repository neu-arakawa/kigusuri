<?php /* Smarty version 2.6.8, created on 2008-08-20 16:05:42
         compiled from ../../template/ja/_error.html */ ?>
<?php if ($this->_tpl_vars['errors']): ?>
			<ul class="error">
<?php $_from = $this->_tpl_vars['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['error']):
?>
				<li><?php echo $this->_tpl_vars['error']; ?>
。</li>
<?php endforeach; endif; unset($_from); ?>
			</ul>
<?php endif; ?>