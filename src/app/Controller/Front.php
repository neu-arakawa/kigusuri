<?php

namespace Controller;

class Front extends \SlimMVC\Controller
{
    public $auth_pages = array();
    public $non_member_pages = array();
    public $is_member        = false;
    public $is_pharmacy      = false;
    public $session_group    = 'front';
    public $member_type      = 'member';
    public $member_id        = null;
    public function __construct($args=array())
    {
        parent::__construct($args);
        
        //薬局は公開モードへ
        $this->model('Pharmacy')->public_mode  = true;

        if(!empty($args['member_type'])){
            $this->session_group = mb_strtolower($args['member_type']);
            $this->member_type   = $args['member_type'];
        }

        if(empty($_SESSION[$this->session_group])){
            $_SESSION[$this->session_group] = array();
        }
        
        if(!empty($_SESSION[$this->session_group]['member']['id'])){
            $this->is_member = true;
            $this->member_id = $_SESSION[$this->session_group]['member']['id'];

            //ユーザ情報を更新
            $this->member_session_record($this->member_id);
        }

        if(in_array($this->action, $this->auth_pages) && empty($this->is_member)){
            if($this->member_type == 'member'){
                $this->app_session('member_auth', true);
                $this->app->redirect( $this->app->urlFor('member_login') . 
                    '?location='.$this->app->request->getResourceUri() );
            }else {
                $this->app->redirect( $this->app->urlFor('pharmacy_login') . 
                    '?location='.$this->app->request->getResourceUri() );
            }
        }

        if(in_array($this->action, $this->non_member_pages ) && !empty($this->is_member)){
            if($this->member_type == 'member'){
                $this->app->redirect( $this->app->urlFor('qa_top') );
            }
            else {
                $this->app->redirect( $this->app->urlFor('pharmacy_mypage') );
            }
        }

        $autologin = $this->autologin();
        if(!empty($autologin)){
            //ログイン維持(30日)
            setcookie(session_name(), session_id(), time() + 60*60*24*30, '/');
        }

    }

    protected function template_dir($dir=null)
    {
        $dir = str_replace('_','/', $dir);
        $this->template_dir = $dir;  
    }
    
    protected function member_session_record($data){
        if(is_array($data)){
            $_SESSION[$this->session_group]['member'] = $data;
        }
        else if(is_numeric($data)){
            //member_idから
            $_SESSION[$this->session_group]['member'] 
                = $this->model( ucfirst($this->member_type) )->find('first',
                    array('conditions' => array('id'=> $data)));
        }
        if(!empty($_SESSION[$this->session_group]['member'])){
            $_SESSION[$this->session_group]['logined'] = true;
        }
        else {
            $_SESSION[$this->session_group]['logined'] = false;
        }

        //会員ページのリンク
        $_SESSION[$this->session_group]['pages'] = array(
            'qa_request'      => $this->app->urlFor('qa_request'),
            'qa_history'      => $this->app->urlFor('qa_history'),
            'member_edit'     => $this->app->urlFor('member_edit'),
            'member_logout'   => $this->app->urlFor('member_logout'),
            'pharmacy_mypage' => $this->app->urlFor('pharmacy_mypage'),
            'pharmacy_logout' => $this->app->urlFor('pharmacy_logout'),
            'pharmacy_edit'   => $this->app->urlFor('pharmacy_edit'),
            'answer_history'  => $this->app->urlFor('answer_history'),
        );

        $this->member  = $_SESSION[$this->session_group]['member'];
    }
    
    public function app_session($key, $value=null){
        if(empty($_SESSION[$this->session_group]['app'])){
            $_SESSION[$this->session_group]['app'] = array();
        }
        if(!is_null($value)){
            $_SESSION[$this->session_group]['app'][$key] = $value;
        }
        return empty($_SESSION[$this->session_group]['app'][$key]) ? 
               null : $_SESSION[$this->session_group]['app'][$key];
    }

    public function app_session_clear($key=null){
        if( empty($key) ) $key = $this->controller;
        unset($_SESSION[$this->session_group]['app'][$key]);
    }

    public function session_logout(){
        unset($_SESSION[$this->session_group]);
    }

    public function autologin($flg=null){
        if(!is_null($flg)){
            $_SESSION[$this->session_group]['autologin'] = $flg;
        }
        return isset($_SESSION[$this->session_group]['autologin']) ?
            $_SESSION[$this->session_group]['autologin']: false;
    }

    protected function after_render($output)
    {
        if(!$this->app->config('debug')){
            $output = trim(preg_replace('/>\s+</', '><', $output));
        }
        return $output;
    }
    
    protected function setFlash($message, $level='info'){
        $_SESSION[$this->session_group]['flash'] = array(
            'message' => $message,
            'level'   => $level
        );
    }

    protected function getFlash(){
        if(empty($_SESSION[$this->session_group]['flash'])){
            return array();
        }
        $_tmp = $_SESSION[$this->session_group]['flash'];
        unset($_SESSION[$this->session_group]['flash']); 
        return $_tmp;
    }

    public function question_category($category_id){
        $categories = $this->config('question_categories');
        if(empty($categories[$category_id])){
            return '';
        }
        return $categories[$category_id];
    }
}
