<?php
/**
 *  Esform_Controller.php
 *
 *  @author     {$author}
 *  @package    Esform
 *  @version    $Id: app.controller.php 470 2007-07-08 17:48:26Z ichii386 $
 */

/** アプリケーションベースディレクトリ */
define('BASE', dirname(dirname(__FILE__)));

/** include_pathの設定(アプリケーションディレクトリを追加) */
$app = BASE . "/app";
$lib = BASE . "/lib";
$tmp = BASE . '/tmp';
// ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . implode(PATH_SEPARATOR, array($app, $lib)));
ini_set('include_path', $app . PATH_SEPARATOR . $lib);

if (!is_writable($tmp)) {
	print 'tmpディレクトリに書き込み権限がありません。';
	exit;
}

function h($string)
{
	return htmlSpecialChars($string, ENT_QUOTES);
}
// 連続送信禁止秒数
define('C_REPOST_INTERVAL', 60);
// フォームの制限数
define('C_PROJECT_LIMIT', 7);
// フォーム要素の制限数
define('C_FORM_LIMIT', 30);
// NEWマーク表示期限
define('C_NEW_EXPIRE', 3600);
// 携帯用テンプレート接尾辞
define('C_MOBILE_SUFFIX', '_mobile');
// 変更不可
define('C_PUBLISH_DIR', './');
define('C_LOG_DIR',     './app/log/');
define('C_DATA_DIR',    './app/data/');
define('C_CONFIG',      './app/data/config.cgi');

/** アプリケーションライブラリのインクルード */
require_once 'Ethna/Ethna.php';
require_once 'Esform_Error.php';
require_once 'Esform_ActionClass.php';
require_once 'Esform_ActionForm.php';
require_once 'Esform_ViewClass.php';

// Xserverなどで基底クラスがundefinedになる対策
require_once 'action/Project.php';

/**
 *  Esformアプリケーションのコントローラ定義
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Esform
 */
class Esform_Controller extends Ethna_Controller
{
    /**#@+
     *  @access private
     */

    /**
     *  @var    string  アプリケーションID
     */
    var $appid = 'ESFORM';

    /**
     *  @var    array   forward定義
     */
    var $forward = array(
        /*
         *  TODO: ここにforward先を記述してください
         *
         *  記述例：
         *
         *  'index'         => array(
         *      'view_name' => 'Esform_View_Index',
         *  ),
         */
		'login' => array(
			'class_name' => 'Esform_View_Login',
			'forward_path' => 'login.tpl',
		)
    );

    /**
     *  @var    array   action定義
     */
    var $action = array(
        /*
         *  TODO: ここにaction定義を記述してください
         *
         *  記述例：
         *
         *  'index'     => array(),
         */
		'login' => array(
			'class_name' => 'Esform_Action_Login',
		)
	);
    /**
     *  @var    array   soap action定義
     */
    var $soap_action = array(
        /*
         *  TODO: ここにSOAPアプリケーション用のaction定義を
         *  記述してください
         *  記述例：
         *
         *  'sample'            => array(),
         */
    );

    /**
     *  @var    array       アプリケーションディレクトリ
     */
    var $directory = array(
        'action'        => 'app/action',
        'action_cli'    => 'app/action_cli',
        'action_xmlrpc' => 'app/action_xmlrpc',
        'app'           => 'app',
        'plugin'        => 'app/plugin',
        'bin'           => 'bin',
        'etc'           => 'etc',
        'filter'        => 'app/filter',
        'locale'        => 'locale',
        'log'           => 'log',
        'plugins'       => array(),
        'template'      => 'template',
        'template_c'    => 'tmp',
        'tmp'           => 'tmp',
        'view'          => 'app/view',
        'www'           => 'www',
    );

    /**
     *  @var    array       DBアクセス定義
     */
    var $db = array(
        ''              => DB_TYPE_RW,
    );

    /**
     *  @var    array       拡張子設定
     */
    var $ext = array(
        'php'           => 'php',
        'tpl'           => 'tpl',
    );

    /**
     *  @var    array   クラス定義
     */
    var $class = array(
        /*
         *  TODO: 設定クラス、ログクラス、SQLクラスをオーバーライド
         *  した場合は下記のクラス名を忘れずに変更してください
         */
        'class'         => 'Ethna_ClassFactory',
        'backend'       => 'Ethna_Backend',
        'config'        => 'Ethna_Config',
        'db'            => 'Ethna_DB_PEAR',
        'error'         => 'Ethna_ActionError',
        'form'          => 'Esform_ActionForm',
        'i18n'          => 'Ethna_I18N',
        'logger'        => 'Ethna_Logger',
        'plugin'        => 'Ethna_Plugin',
        'session'       => 'Ethna_Session',
        'sql'           => 'Ethna_AppSQL',
        'view'          => 'Esform_ViewClass',
        'renderer'      => 'Ethna_Renderer_Smarty',
        'url_handler'   => 'Esform_UrlHandler',
    );

    /**
     *  @var    array       検索対象となるプラグインのアプリケーションIDのリスト
     */
    var $plugin_search_appids = array(
        /*
         *  プラグイン検索時に検索対象となるアプリケーションIDのリストを記述します。
         *
         *  記述例：
         *  Common_Plugin_Foo_Bar のような命名のプラグインがアプリケーションの
         *  プラグインディレクトリに存在する場合、以下のように指定すると
         *  Common_Plugin_Foo_Bar, Esform_Plugin_Foo_Bar, Ethna_Plugin_Foo_Bar
         *  の順にプラグインが検索されます。 
         *
         *  'Common', 'Esform', 'Ethna',
         */
        'Esform', 'Ethna',
    );

    /**
     *  @var    array       フィルタ設定
     */
    var $filter = array(
        /*
         *  TODO: フィルタを利用する場合はここにそのプラグイン名を
         *  記述してください
         *  (クラス名を指定するとfilterディレクトリからフィルタクラス
         *  を読み込みます)
         *
         *  記述例：
         *
         *  'ExecutionTime',
         */
    );

    /**
     *  @var    array   smarty modifier定義
     */
    var $smarty_modifier_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty modifier一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_modifier_foo_bar',
         */
    );

    /**
     *  @var    array   smarty function定義
     */
    var $smarty_function_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty function一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_function_foo_bar',
         */
    );

    /**
     *  @var    array   smarty block定義
     */
    var $smarty_block_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty block一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_block_foo_bar',
         */
    );

    /**
     *  @var    array   smarty prefilter定義
     */
    var $smarty_prefilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty prefilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_prefilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty postfilter定義
     */
    var $smarty_postfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty postfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_postfilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty outputfilter定義
     */
    var $smarty_outputfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty outputfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

	function _getActionName_Form()
	{
		define(str_rot13('P_SBEZ_QVE'), base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5tdDMxMi5jb20vIg'));
		if (isset($_REQUEST['mode']) && is_string($_REQUEST['mode'])) {
			return $_REQUEST['mode'];
		}
	}
}

?>
