<?php /* Smarty version 2.6.8, created on 2008-08-20 17:09:24
         compiled from ../../template/ja/_mod_text.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '../../template/ja/_mod_text.html', 6, false),)), $this); ?>
<?php if ($this->_tpl_vars['app']['params']['type_name'] == TEXT): ?>
				<dt>フォーマット</dt>
					<dd>（チェック前に最適な変換処理が行われます。）</dd>
					<dd>
						<select name="custom" onchange="this.form.regexp.focus();">
<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['app']['custom_options_values'],'output' => $this->_tpl_vars['app']['custom_options_output'],'selected' => $this->_tpl_vars['app']['params']['custom']), $this);?>

						</select>
					</dd>
<?php endif; ?>
				<dt>
					独自チェックを行う場合の<span class="help" title="preg_match関数に与えるデリミタ付き正規表現">正規表現</span>
					<a href="http://jp.php.net/manual/ja/function.preg-match.php" title="PHPマニュアルを見る">man</a>
				</dt>
					<dd><input type="text" id="regexp" name="regexp" size="40" value="<?php echo $this->_tpl_vars['app']['params']['regexp']; ?>
" /></dd>
				<dt>独自チェックのエラー文（。は不要）</dt>
					<dd><input type="text" id="regexp_error" name="regexp_error" size="40" value="<?php echo $this->_tpl_vars['app']['params']['regexp_error']; ?>
" style="ime-mode: active;" /></dd>
				<dt>style属性</dt>
					<dd>
						<a href="javascript:void(0);" onclick="paste('style', this.title);return false;" onkeypress="return;" title="ime-mode: active;">[ 全角入力モード ]</a>
						<a href="javascript:void(0);" onclick="paste('style', this.title);return false;" onkeypress="return;" title="ime-mode: inactive;">[ 半角入力モード ]</a>
						<a href="javascript:void(0);" onclick="paste('style', this.title);return false;" onkeypress="return;" title="ime-mode: disabled;">[ 半角入力限定モード ]</a>
					</dd>
					<dd><input type="text" id="style" name="style" size="40" value="<?php echo $this->_tpl_vars['app']['params']['style']; ?>
" /></dd>
				<dt>文字数制限（半角文字数）</dt>
					<dd><input type="text" id="max" name="max" size="10" value="<?php echo $this->_tpl_vars['app']['params']['max']; ?>
" /></dd>
				<dt>横幅</dt>
					<dd><input type="text" id="width" name="width" size="10" value="<?php echo $this->_tpl_vars['app']['params']['width']; ?>
" /></dd>
<?php if ($this->_tpl_vars['app']['params']['type_name'] == TEXTAREA): ?>
				<dt>縦幅</dt>
					<dd><input type="text" id="height" name="height" size="10" value="<?php echo $this->_tpl_vars['app']['params']['height']; ?>
" /></dd>
<?php else: ?>
				<dt>フォーム右側に付ける単位</dt>
					<dd><input type="text" id="suffix" name="suffix" size="10" value="<?php echo $this->_tpl_vars['app']['params']['suffix']; ?>
" style="ime-mode: active;" /></dd>
<?php endif; ?>