<?php
namespace Controller\Front;

class Member_Auth extends \Controller\Front
{
    public $non_member_pages = array('login');
    public function login()
    {
        if( $this->app->request->isPost() ){
            $member =  $this->model('Member')->login($this->query);
            if(empty($member)){
                $this->stash['errors']['email'] = 'ユーザ名もしくはパスワードが異なります。';
                $this->render();
            }
            else {
                $this->member_session_record($member);
                if(!empty($this->query['autologin'])){
                    $this->autologin(true);
                }
                session_regenerate_id();
                if(empty($this->query['location'])){
                    $this->app_session('login','ログインしました。');
                    $this->app->redirect($this->app->urlFor('qa_top'));
                }
                else if(preg_match('/^\/.+/',$this->query['location'])) {
                    $this->app->redirect($this->query['location']);
                }
            }
        }
        else {
            $this->render();
        }
    }
    public function logout()
    {
        // session_destroy();
        $this->session_logout();
        $this->app_session('logout',true);
        $this->app->redirect($this->app->urlFor('qa_top'));
    }

}
