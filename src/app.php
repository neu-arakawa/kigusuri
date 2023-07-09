<?php

//共通関数
require __DIR__.'/lib/functions.php';

// ライブラリ読み込み
require __DIR__.'/vendor/autoload.php';

date_default_timezone_set('Asia/Tokyo');

error_reporting( E_ALL & ~E_STRICT);

define('CACHE_DIR', dirname(__FILE__).'/cache');

// if(!empty($argv) && count($argv)){
    // require dirname(__FILE__).'/app/Cron.php';
// }

class App {
     
    function __construct($config_file='example')
    {

        umask(0);
        
        // 設定ファイル読み込み
        $config = include __DIR__."/app/Config/".$config_file.".php";
        
        //Sim
        $app = new \Slim\Slim(array(
            'templates.path' => __DIR__.'/app/View/',
            'view' => new \Slim\Views\Twig(),
            'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
                'path' => __DIR__.'/tmp/logs',
                'name_format' => 'Y-m-d',
                'message_format' => '[%label%] %date% : %message%'
            ))
        ));

        //Sim config
        $app->config($config);
        
        $this->app = $app;
        
        // $app->error($message, $trace=[]) use ($app) {
            // echo $message;
        // });

        $this->database();
        $this->session();
        $this->plugins();
        $this->template();
        // pr($this->app->environment());

    }
    function database(){
        $app  = $this->app;
        try {
            $conn =  new SlimMVC\DB();
            $app->container->singleton('db', function ()use($conn) { 
                return $conn;
            });
        } catch(PDOException $e) {
            //ここはエラーにする
            echo 'Can not connect to database. :' . $e->getMessage();
            exit;
        }
        // return $conn; 
        $app->hook('slim.before', function() use ($app){
            $app->db->beginTransaction();
        });
        $app->hook('slim.after', function() use ($app){

            if($app->response->status() === 200){

                if($app->config('debug')){
                    $logs = $app->db->getQueryLog();
                    $no = 1;
                    foreach($logs as $sql){
                        echo '<div>';
                        echo $no.'.';
                        foreach($sql as $k => $v){
                            echo '['.$k .']: ';
                            if(is_array($v)){
                                echo join(',',$v);
                            }
                            else {
                                echo empty($v) ? 'none' : $v;
                            }
                            echo ' ';
                        }
                        echo '</div>';
                        $no++;
                    }
                }

            }
            if(preg_match('/^(200|302)$/',$app->response->status())){
                $app->db->commit();
            }
            else {
                $app->db->rollback();
            }

            if ($app->response()->status() == 400 ) { 
                if ( $app->response()->body() === 'Invalid or missing CSRF token.' ) { 
                    include '404.php';
                } 
            }
        });

    }
    function session(){
        //Slim session
        // ini_set('session.cookie_httponly', 1);
        // ini_set('session.gc_maxlifetime', 60*60*24*14);
        // session_set_cookie_params(60*60*24*14);
        session_cache_limiter(false);
        // session_save_path(HOME.'/src/tmp/session');
        session_save_path('2;'.HOME.'/src/tmp/session');
        
        // session_start();

        // $session = new Session($this->app->db);
        // session_set_save_handler(
          // array($session, "open"),
          // array($session, "close"),
          // array($session, "read"),
          // array($session, "write"),
          // array($session, "destroy"),
          // array($session, "gc")
        // );

        session_start();

        // if( !empty($config['session']) ){
            // $app->add(
                // new \Slim\Middleware\SessionCookie($config['session'])
            // );
        // }
    }
    function template(){
        $app = $this->app;
        $app->view->parserExtensions = array(
            new Twig_Extension_Debug()
        );
        //Slim template setting
        $app->view->parserOptions = array(
            'debug'            => $app->config('debug'),
            'charset'          => 'utf-8',
            'cache'            => "/tmp/".$_SERVER['HTTP_HOST'],//realpath("/tmp/cache"),
            'auto_reload'      => true,
            'strict_variables' => false,
            'autoescape'       => true,
        );
        $twig = $app->view->getInstance();
        $twig->addFilter('php', new Twig_Filter_Function('TwigController::twigPhpFilter'));
        $twig->addTokenParser(new Twig_TokenParserPHP());
        $twig->addExtension(new Twig_Filters());
    }
    function plugins(){
        // CSRF対策
        $this->app->add(new \Slim\Extras\Middleware\CsrfGuard());
    }
    function run($args=array()){
        $app = $this->app;
        
        include __DIR__."/app/Config/Routes.php";

        $app->notFound(function () use ($app) {
            $path = $app->request->getPath();
            if(preg_match('/\.htm$/',$path)){
                $app->redirect($path.'l'); 
            }
            else {
                include '404.php';
            }
        });
        $app->error(function (\Exception $e) use ($app) {
            include '404.php';
        });
        $app->run();
    }
    
}


/**
 * Session
 */
class Session {
 
  /**
   * Db Object
   */
  private $db;
  public function __construct($pdo){
   // Instantiate new Database object
   $this->db = $pdo;
    }
    /**
     * Open
     */
    public function open(){
      // If successful
      if($this->db){
        // Return True
        return true;
      }
      // Return False
      return false;
    }
    /**
     * Close
     */
    public function close(){
      // Close the database connection
      // If successful
      if($this->db->close()){
        // Return True
        return true;
      }
      // Return False
      return false;
    }
    /**
     * Read
     */
    public function read($id){
      // Set query
      $data = $this->db->read('SELECT data FROM sessions WHERE id = ?', array($id));
      return empty($data) ? null : $data;
    }
    /**
     * Write
     */
    public function write($id, $data){
         
      // Set query  
      $this->db->replace('sessions',array(
        'id'     => $id,
        'expire' => time(),
        'data'   => $data
      ));
    }
    /**
     * Destroy
     */
    public function destroy($id){
      // Set query
      if($this->db->query('DELETE FROM sessions WHERE id = ?', array($id))){
        // Return True
        return true;
      }
     
      // Return False
      return false;
    } 
    /**
     * Garbage Collection
     */
    public function gc($max){
      // Calculate what is to be deemed old
      $old = time() - $max;
     
      // Attempt execution
      if($this->db->query('DELETE * FROM sessions WHERE access < ?', array($old))){
        // Return True
        return true;
      }
     
      // Return False
      return false;
    }
}
