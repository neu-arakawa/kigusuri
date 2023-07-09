<?php
// vim: foldmethod=marker
/**
 *  Esform_ActionForm.php
 *
 *  @author     {$author}
 *  @package    Esform
 *  @version    $Id: app.actionform.php 323 2006-08-22 15:52:26Z fujimoto $
 */

// {{{ Esform_ActionForm
/**
 *  アクションフォームクラス
 *
 *  @author     {$author}
 *  @package    Esform
 *  @access     public
 */
class Esform_ActionForm extends Ethna_ActionForm
{
    /**#@+
     *  @access private
     */

    /** @var    array   フォーム値定義(デフォルト) */
    var $form_template = array();

    /** @var    bool    バリデータにプラグインを使うフラグ */
    var $use_validator_plugin = true;

    /**#@-*/

    /**
     *  フォーム値検証のエラー処理を行う
     *
     *  @access public
     *  @param  string      $name   フォーム項目名
     *  @param  int         $code   エラーコード
     */
    function handleError($name, $code)
    {
        return parent::handleError($name, $code);
    }

    /**
     *  フォーム値定義テンプレートを設定する
     *
     *  @access protected
     *  @param  array   $form_template  フォーム値テンプレート
     *  @return array   フォーム値テンプレート
     */
    function _setFormTemplate($form_template)
    {
        return parent::_setFormTemplate($form_template);
    }

    /**
     *  フォーム値定義を設定する
     *
     *  @access protected
     */
    function _setFormDef()
    {
        return parent::_setFormDef();
    }

	function _filter_trim($value)
	{
		return preg_replace('/^[\s　]+|[\s　]+$/u', '', $value);
	}
	function _filter_gpc($value)
	{
		if (get_magic_quotes_gpc()) {
			return stripSlashes($value);
		}
		return $value;
	}
}
// }}}
?>
