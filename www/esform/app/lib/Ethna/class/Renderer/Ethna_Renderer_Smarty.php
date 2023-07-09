<?php
// vim: foldmethod=marker
/**
 *  Ethna_Renderer_Smarty.php
 *
 *  @author     Kazuhiro Hosoi <hosoi@gree.co.jp>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_Renderer_Smarty.php 453 2007-01-17 18:38:51Z ichii386 $
 */
require_once 'Smarty/Smarty.class.php';
require_once ETHNA_BASE . '/class/Ethna_SmartyPlugin.php';

// {{{ Ethna_Renderer_Smarty
/**
 *  Smartyレンダラクラス（Mojaviのまね）
 *
 *  @author     Kazuhiro Hosoi <hosoi@gree.co.jp>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_Renderer_Smarty extends Ethna_Renderer
{
    /** @var    string compile directory  */
    var $compile_dir;
    
    /**
     *  Ethna_Renderer_Smartyクラスのコンストラクタ
     *
     *  @access public
     */
    function Ethna_Renderer_Smarty(&$controller)
    {
        parent::Ethna_Renderer($controller);
        
        $this->engine =& new Smarty;
        
        $template_dir = $controller->getTemplatedir();
        $compile_dir = $controller->getDirectory('template_c');

        $this->setTemplateDir($template_dir);
        $this->compile_dir = $compile_dir;
        $this->engine->template_dir = $this->template_dir;
        $this->engine->compile_dir = $this->compile_dir;
        $this->engine->compile_id = md5($this->template_dir);

        // 一応がんばってみる
        if (is_dir($this->engine->compile_dir) === false) {
            Ethna_Util::mkdir($this->engine->compile_dir, 0755);
        }

        $this->engine->plugins_dir = array_merge(array(SMARTY_DIR . 'plugins'),
                                                 $controller->getDirectory('plugins'));

        $this->_setDefaultPlugin();
    }
    
    /**
     *  デフォルトの設定.
     *
     *  @access public
     */
    function _setDefaultPlugin()
    {
        // default modifiers
        $this->setPlugin('number_format','modifier','smarty_modifier_number_format');
        $this->setPlugin('strftime','modifier','smarty_modifier_strftime');
        $this->setPlugin('count','modifier','smarty_modifier_count');
        $this->setPlugin('join','modifier','smarty_modifier_join');
        $this->setPlugin('filter','modifier', 'smarty_modifier_filter');
        $this->setPlugin('unique','modifier','smarty_modifier_unique');
        $this->setPlugin('wordwrap_i18n','modifier','smarty_modifier_wordwrap_i18n');
        $this->setPlugin('truncate_i18n','modifier','smarty_modifier_truncate_i18n');
        $this->setPlugin('i18n','modifier','smarty_modifier_i18n');
        $this->setPlugin('checkbox','modifier','smarty_modifier_checkbox');
        $this->setPlugin('select','modifier','smarty_modifier_select');
        $this->setPlugin('form_value','modifier','smarty_modifier_form_value');

        // default functions
        $this->setPlugin('is_error','function','smarty_function_is_error');
        $this->setPlugin('message','function','smarty_function_message');
        $this->setPlugin('uniqid','function','smarty_function_uniqid');
        $this->setPlugin('select','function','smarty_function_select');
        $this->setPlugin('checkbox_list','function','smarty_function_checkbox_list');
        $this->setPlugin('form_name','function','smarty_function_form_name');
        $this->setPlugin('form_input','function','smarty_function_form_input');
        $this->setPlugin('form_submit','function','smarty_function_form_submit');
        $this->setPlugin('url','function','smarty_function_url');
        $this->setPlugin('csrfid','function','smarty_function_csrfid');

        // default blocks
        $this->setPlugin('form','block','smarty_block_form');       
    }

    /**
     *  ビューを出力する
     *
     *  @param  string  $template   テンプレート名
     *  @param  bool    $capture    true ならば出力を表示せずに返す
     *
     *  @access public
     */
    function perform($template = null, $capture = false)
    {
        if ($template === null && $this->template === null) {
            return Ethna::raiseWarning('template is not defined');
        }

        if ($template !== null) {
            $this->template = $template;
        }

        if ((is_absolute_path($this->template) && is_readable($this->template))
            || is_readable($this->template_dir . $this->template)) {
                if ($capture === true) {
                    $captured = $this->engine->fetch($this->template);
                    return $captured;
                } else {
                    $this->engine->display($this->template);
                }
        } else {
            return Ethna::raiseWarning('template not found ' . $this->template);
        }
    }
    
    /**
     * テンプレート変数を取得する
     * 
     *  @param string $name  変数名
     *
     *  @return mixed　変数
     *
     *  @access public
     */
    function &getProp($name = null)
    {
        $property =& $this->engine->get_template_vars($name);

        if ($property !== null) {
            return $property;
        }
        return null;
    }

    /**
     *  テンプレート変数を削除する
     * 
     *  @param name    変数名
     * 
     *  @access public
     */
    function removeProp($name)
    {
        $this->engine->clear_assign($name);
    }

    /**
     *  テンプレート変数に配列を割り当てる
     * 
     *  @param array $array
     * 
     *  @access public
     */
    function setPropArray($array)
    {
        $this->engine->assign($array);
    }

    /**
     *  テンプレート変数に配列を参照として割り当てる
     * 
     *  @param array $array
     * 
     *  @access public
     */
    function setPropArrayByRef(&$array)
    {
        $this->engine->assign_by_ref($array);
    }

    /**
     *  テンプレート変数を割り当てる
     * 
     *  @param string $name 変数名
     *  @param mixed $value 値
     * 
     *  @access public
     */
    function setProp($name, $value)
    {
        $this->engine->assign($name, $value);
    }

    /**
     *  テンプレート変数に参照を割り当てる
     * 
     *  @param string $name 変数名
     *  @param mixed $value 値
     * 
     *  @access public
     */
    function setPropByRef($name, &$value)
    {
        $this->engine->assign_by_ref($name, $value);
    }

    /**
     *  プラグインをセットする
     * 
     *  @param string $name　プラグイン名
     *  @param string $type プラグインタイプ
     *  @param mixed $plugin プラグイン本体
     * 
     *  @access public
     */
    function setPlugin($name, $type, $plugin) 
    {
        //プラグイン関数の有無をチェック
        if (is_callable($plugin) === false) {
            return Ethna::raiseWarning('Does not exists.');
        }

        //プラグインの種類をチェック
        $register_method = 'register_' . $type;
        if (method_exists($this->engine, $register_method) === false) {
            return Ethna::raiseWarning('This plugin type does not exist');
        }

        // フィルタは名前なしで登録
        if ($type === 'prefilter' || $type === 'postfilter' || $type === 'outputfilter') {
            parent::setPlugin($name, $type, $plugin);
            $this->engine->$register_method($plugin);
            return;
        }
        
        // プラグインの名前をチェック
        if ($name === '') {
            return Ethna::raiseWarning('Please set plugin name');
        }
       
        // プラグインを登録する
        parent::setPlugin($name, $type, $plugin);
        $this->engine->$register_method($name, $plugin);
    }
}
// }}}
?>
