<?php
// vim: foldmethod=marker
/**
 *  Ethna_Plugin_Generator_Template.php
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_Plugin_Generator_Template.php 411 2006-11-17 02:32:32Z ichii386 $
 */

// {{{ Ethna_Plugin_Generator_Template
/**
 *  スケルトン生成クラス
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_Plugin_Generator_Template extends Ethna_Plugin_Generator
{
    /**
     *  テンプレートのスケルトンを生成する
     *
     *  @access public
     *  @param  string  $forward_name   テンプレート名
     *  @param  string  $skelton        スケルトンファイル名
     *  @return true|Ethna_Error        true:成功 Ethna_Error:失敗
     */
    function &generate($forward_name, $skelton = null)
    {
        $tpl_dir = $this->ctl->getTemplatedir();
        if ($tpl_dir{strlen($tpl_dir)-1} != '/') {
            $tpl_dir .= '/';
        }
        $tpl_path = $this->ctl->getDefaultForwardPath($forward_name);

        // entity
        $entity = $tpl_dir . $tpl_path;
        Ethna_Util::mkdir(dirname($entity), 0755);

        // skelton
        if ($skelton === null) {
            $skelton = 'skel.template.tpl';
        }

        // macro
        $macro = array();
        // add '_' for tpl and no user macro for tpl
        $macro['_project_id'] = $this->ctl->getAppId();


        // generate
        if (file_exists($entity)) {
            printf("file [%s] already exists -> skip\n", $entity);
        } else if ($this->_generateFile($skelton, $entity, $macro) == false) {
            printf("[warning] file creation failed [%s]\n", $entity);
        } else {
            printf("template file(s) successfully created [%s]\n", $entity);
        }

        $true = true;
        return $true;
    }
}
// }}}
?>
