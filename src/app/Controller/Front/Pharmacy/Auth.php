<?php
namespace Controller\Front;

class Pharmacy_Auth extends \Controller\Front
{
    public $non_member_pages = array('login');
    public function login()
    {
        if( $this->app->request->isPost() ){
            $member =  $this->model('Pharmacy')->login($this->query);
            if(empty($member)){
                $this->stash['errors']['email'] = 'ユーザ名もしくはパスワードが異なります。';
                $this->render();
            }
            else {
                $this->member_session_record($member);
                $this->autologin(true);
                if(empty($this->query['location'])){
                    $this->app_session('login','ログインしました。');
                    $this->app->redirect($this->app->urlFor('pharmacy_mypage'));
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
        $this->session_logout();
        $this->app->redirect($this->app->urlFor('pharmacy_login'));
    }

}
