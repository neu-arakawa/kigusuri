<?php
// vim: foldmethod=marker
/**
 *  Ethna_Plugin_Validator_Max.php
 *
 *  @author     ICHII Takashi <ichii386@schweetheart.jp>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_Plugin_Validator_Max.php 313 2006-08-03 03:38:59Z ichii386 $
 */

// {{{ Ethna_Plugin_Validator_Max
/**
 *  最大値チェックプラグイン
 *
 *  @author     ICHII Takashi <ichii386@schweetheart.jp>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_Plugin_Validator_Max extends Ethna_Plugin_Validator
{
    /** @var    bool    配列を受け取るかフラグ */
    var $accept_array = false;

    /**
     *  最大値のチェックを行う
     *
     *  @access public
     *  @param  string  $name       フォームの名前
     *  @param  mixed   $var        フォームの値
     *  @param  array   $params     プラグインのパラメータ
     */
    function &validate($name, $var, $params)
    {
        $true = true;
        $type = $this->getFormType($name);
        if (isset($params['max']) == false || $this->isEmpty($var, $type)) {
            return $true;
        }

        switch ($type) {
            case VAR_TYPE_INT:
                if ($var > $params['max']) {
                    if (isset($params['error'])) {
                        $msg = $params['error'];
                    } else {
                        $msg = "{form}には%d以下の数字(整数)を入力して下さい";
                    }
                    return Ethna::raiseNotice($msg, E_FORM_MAX_INT, array($params['max']));
                }
                break;

            case VAR_TYPE_FLOAT:
                if ($var > $params['max']) {
                    if (isset($params['error'])) {
                        $msg = $params['error'];
                    } else {
                        $msg = "{form}には%f以下の数字(小数)を入力して下さい";
                    }
                    return Ethna::raiseNotice($msg, E_FORM_MAX_FLOAT, array($params['max']));
                }
                break;

            case VAR_TYPE_DATETIME:
                $t_max = strtotime($params['max']);
                $t_var = strtotime($var);
                if ($t_var > $t_max) {
                    if (isset($params['error'])) {
                        $msg = $params['error'];
                    } else {
                        $msg = "{form}には%s以前の日付を入力して下さい";
                    }
                    return Ethna::raiseNotice($msg, E_FORM_MAX_DATETIME, array($params['max']));
                }
                break;

            case VAR_TYPE_FILE:
                $st = stat($var['tmp_name']);
                if ($st[7] > $params['max'] * 1024) {
                    if (isset($params['error'])) {
                        $msg = $params['error'];
                    } else {
                        $msg = "{form}には%dKB以下のファイルを指定して下さい";
                    }
                    return Ethna::raiseNotice($msg, E_FORM_MAX_FILE, array($params['max']));
                }
                break;

            case VAR_TYPE_STRING:
                if (strlen($var) > $params['max']) {
                    if (isset($params['error'])) {
                        $msg = $params['error'];
                    } else {
                        $msg = "{form}は全角%d文字以下(半角%d文字以下)で入力して下さい";
                    }
                    return Ethna::raiseNotice($msg, E_FORM_MAX_STRING,
                            array(intval($params['max']/2), $params['max']));
                }
                break;
        }

        return $true;
    }
}
// }}}
?>
