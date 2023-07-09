<?php
// vim: foldmethod=marker
/**
 *  Esform_ActionClass.php
 *
 *  @author     {$author}
 *  @package    Esform
 *  @version    $Id: app.actionclass.php 323 2006-08-22 15:52:26Z fujimoto $
 */

// {{{ Esform_ActionClass
/**
 *  action実行クラス
 *
 *  @author     {$author}
 *  @package    Esform
 *  @access     public
 */
class Esform_ActionClass extends Ethna_ActionClass
{
    /**
     *  アクション実行前の認証処理を行う
     *
     *  @access public
     *  @return string  遷移名(nullなら正常終了, falseなら処理終了)
     */
    function authenticate()
    {
    	return parent::authenticate();
    }

    /**
     *  アクション実行前の処理(フォーム値チェック等)を行う
     *
     *  @access public
     *  @return string  遷移名(nullなら正常終了, falseなら処理終了)
     */
    function prepare()
    {
        return parent::prepare();
    }

    /**
     *  アクション実行
     *
     *  @access public
     *  @return string  遷移名(nullなら遷移は行わない)
     */
    function perform()
    {
        return parent::perform();
    }
}
// }}}
?>
