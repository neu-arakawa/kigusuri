<?php
/**
 *  {$app_path}
 *
 *  @author     {$author}
 *  @package    {$project_id}
 *  @version    $Id: skel.app_object.php 387 2006-11-06 14:31:24Z cocoitiban $
 */

/**
 *  {$app_object}Manager
 *
 *  @author     {$author}
 *  @access     public
 *  @package    {$project_id}
 */
class {$app_object}Manager extends Ethna_AppManager
{
}

/**
 *  {$app_object}
 *
 *  @author     {$author}
 *  @access     public
 *  @package    {$project_id}
 */
class {$app_object} extends Ethna_AppObject
{
    /**
     *  プロパティの表示名を取得する
     *
     *  @access public
     */
    function getName($key)
    {
        return $this->get($key);
    }
}
?>
