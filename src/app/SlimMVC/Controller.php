<?php

namespace SlimMVC;

// use Illuminate\Database\Capsule\Manager as DB;
use HTML_FillInForm;

class Controller
{
    public $isOutput    = 999;
    protected $authpage    = false;
    protected $template_dir= false;
    protected $tpl         = null;

    public function __construct($args=array())
    {
        $this->app      = \Slim\Slim::getInstance();
        $this->request  = $this->app->request;
        $this->query    = $this->app->request->params();
        // $this->auth     = $this->auth_session();
        $this->response = $this->app->response();
        $this->stash    = array();
        
        $class  = explode("\\",get_class($this));
        array_splice($class, 0, 1);
        if($class[0] === 'Admin'){
            array_shift($class);
            $this->controller = join('_', $class);
            $this->template_dir('Admin/'.implode('/',$class));
        }else{
            $this->controller = $class[0];
            $this->template_dir(implode('/',$class));
        }
        
        //初期処理
        $this->before();
        
        //認証
        $this->auth();

        if(!empty($args['action'])){
            $this->action = $args['action'];
        }
    }
    public function init($args=array())
    {
    }
    public function get_action()
    {   
        return $this->action;
    }
    public function params($key=null)
    {   
        if(empty($key)){
            return $this->stash['params'];
        }
        if(empty($this->stash['params'][$key])){
            return false;
        }
        return $this->stash['params'][$key];
    }

    public function set_params($key, $value=null)
    {   
        if(!empty($key)){
            $this->stash['params'][$key] = $value;
        }
    }
    public function before()
    {}

    public function auth()
    {}
    
    public function __destruct()
    {
        //コミット 
        // DB::commit();
    }
    protected $models = array();
    public function model($name){
        if(empty($this->models[$name] )){
            $class = '\\Model\\'.$name;
            $this->models[$name] = new $class;
            $this->models[$name]->config = $this->app->container['settings'];
            $this->models[$name]->db     = $this->app->db;
        }
        return $this->models[$name];
    }
    // protected function query($data){
        // foreach($data as $k => $v){
            // $this->query[$k]  = $v;
        // }
    // }
    protected function redirect($url){
        $this->app->redirect($url);
    }
    protected function header($key,$value)
    {
        $this->response->header($key,$value);
    }
    function config($key=false)
    {
        return empty($key) ? 
            $this->app->config : 
            $this->app->config($key);
    }
    protected function template_dir($dir=null)
    {
        $this->template_dir = $dir;  
    }
    protected function str_render($tpl=null)
    {
        return $this->_render($tpl);
    }
    protected function render($tpl=null)
    {
        $this->app->response->setBody($this->_render($tpl));
    }
    protected function tpl($tpl=null)
    {
        $this->tpl = $tpl;
    }
    protected function template($dir=null, $tpl=null)
    {

        if($tpl){
            $tpl = $tpl.".twig";
        }
        elseif($this->tpl){
            $tpl = $this->tpl.".twig";
        }
        else {
            $tpl = $this->action.".twig";
        }
        $tpl = $dir .'/'. $tpl;

        $query_string = array();
        if(!empty($this->search_fields)){
            foreach($this->search_fields as $field){
                if(empty($this->query[$field])){
                    continue;
                }
                if(is_array($this->query[$field])){
                   foreach($this->query[$field] as $val){
                        $query_string[] = $field .'[]='. urlencode($val);
                   }
                }
                else {
                    $query_string[] = $field .'='. urlencode($this->query[$field]);
                }
            }
        }

        $data = array(
            'c'     => $this,
            'query' => $this->query,
            // 'stash' => $this->stash,
            'query_string' => implode('&',$query_string),
            'config'=> $this->app->container['settings'],
            'debug' => $this->app->container['settings']['debug']
        );
        $data = array_merge($data, $this->stash);
        $this->app->view->appendData($data);
        return $this->app->view->fetch($tpl);
    }
    protected function _render($tpl=null)
    {
        $this->before_render();
        $html = $this->template($this->template_dir, $tpl);    
        $fif = new HTML_FillInForm;
        $output = $fif->fill(array(
            'scalarref' => $html,
            'fdat'      => $this->query,
            'ignore_fields' => array('mode','next','csrf_token','files')
        ));
        return $this->after_render($output);
    }

    protected function before_render(){}
    protected function after_render($output){
        return $output;
    }

    function isAdmin(){
        if(!empty($this->auth['admin'])){
            return true;
        }
    }
    public function app_session($key, $value=null)
    {
        if( empty($key) )
            $key = $this->controller;
        if($data){
            $_SESSION['app'][$key] = $data;
        }
        // $this->app_session_files();
        return empty($_SESSION['app'][$key]) ? false : $_SESSION['app'][$key];
    }

    public function app_session_files(){
        $files = $_SESSION['files'][$this->controller];
        $key1 = $this->controller.'_'.$this->action;
        foreach($files as $key2 => $data){
            if($data['id'] == $_SESSION['app'][$key1]['id'] ){
                $_SESSION['app'][$key1]['files'][$key2] = $data;
                $this->query['files'][$key2] = $data;
            }
        }

    }
    protected function app_session_clear($key=null)
    {
        if( empty($key) ) $key = $this->controller;
        unset($_SESSION['app'][$key]);
    }
    // public function message($msg=null, $level='info'){
        // if(!empty($msg)){
            // $_SESSION['message'] = array();
            // $_SESSION['message']['msg'] = $msg;
            // $_SESSION['message']['class'] = $level;
            // return null;
        // }
        // if(empty($_SESSION['message'])){
            // return null;
        // }
            
        // $message = $_SESSION['message'];
        // $_SESSION['message'] = null;
        // return $message;
    // }
    public function message($level='info',$msg=null){
        if(empty($_SESSION['message'])){
            $_SESSION['message'] = array();
        }

        if(!empty($msg)){
            $_SESSION['message'][$level] = array();
            $_SESSION['message'][$level]['msg'] = $msg;
            return null;
        }

        if(empty($_SESSION['message'][$level])){
            return null;
        }
            
        $message = $_SESSION['message'][$level]['msg'];
        $_SESSION['message'][$level] = null;
        return $message;
    }

    protected function send_mail($key='default',$args=array())
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);
        $conf = $this->config('mail');
        if( $key !== 'default'){
            $conf = array_merge(
                $conf['default'],
                $conf[$key]
            );
        }
        

        $mail_prams = array_merge(
            $conf,
            $args
        );
        
        $body = '';
        if(empty($args['body'])){
            if(!empty($args['args'])){
                $data = $args['args'];
                $data['c'] = $this;
                $this->app->view->appendData($data);
            }
            $body = $this->app->view->fetch('Mail/'.$key.'.twig');
        }
        else {
            $body = $args['body'];
        }

        if ( !preg_match('/^(.*?)\n\n(.*)$/s', $body, $mbody)) {
            return false;
        }
        
        $mail_prams['Subject'] = $mbody[1];
        $mail_prams['Body']    = $mbody[2];

        $message = \Swift_Message::newInstance()
            ->setReturnPath($mail_prams['Return-path'])
            ->setSubject($mail_prams['Subject'])
            ->setTo($mail_prams['To'])
            ->setFrom($mail_prams['From'])
            ->setBody($mail_prams['Body']);
        $mailer->send($message);
    }
    protected function action($action=null){
        $this->action = $action;
        $this->$action();
    }
    protected function json($data){
        $this->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
    public function cache($process,$target, $args=array())
    {
        if($process === 'set'){
            return file_put_contents(CACHE_DIR.'/'.$target.'.cache', serialize($args));
        }
        else if($process === 'get'){
            return unserialize(file_get_contents(CACHE_DIR.'/'.$target.'.cache'));
        }
        else if($process === 'clear'){
            unlink(file_get_contents(CACHE_DIR.'/'.$target.'.cache'));
        }
    }
    public function resize_image($image,$dir = ".", $new_width=1024){
    	list($width,$height,$type) = getimagesize($image);
        
        if($width > $new_width ){
    	    $new_height = round($height*$new_width/$width);
        }
        else {
            $new_width  = $width;
            $new_height = $height;
        }
    
    	$emp_img = imagecreatetruecolor($new_width,$new_height);
    	switch($type){
    		case IMAGETYPE_JPEG:
    			$new_image = imagecreatefromjpeg($image);
    			break;
    		case IMAGETYPE_GIF:
    			$new_image = imagecreatefromgif($image);
    			break;
    		case IMAGETYPE_PNG:
    			imagealphablending($emp_img, false);
    			imagesavealpha($emp_img, true);
    			$new_image = imagecreatefrompng($image);
    			break;
    	}
    	imagecopyresampled($emp_img,$new_image,0,0,0,0,$new_width,$new_height,$width,$height);
    	switch($type){
    		case IMAGETYPE_JPEG:
    			imagejpeg($emp_img,$dir);
    			break;
    		case IMAGETYPE_GIF:
    			$bgcolor = imagecolorallocatealpha($new_image,0,0,0,127);
    			imagefill($emp_img, 0, 0, $bgcolor);
    			imagecolortransparent($emp_img,$bgcolor);
    			imagegif($emp_img,$dir);
    			break;
    		case IMAGETYPE_PNG:
    			imagepng($emp_img,$dir);
    			break;
    	}
    	imagedestroy($emp_img);
    	imagedestroy($new_image);
        return true;
    }
}
