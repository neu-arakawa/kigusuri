<?php
namespace Controller\Admin;

class Index extends \Controller\Admin
{
    public function login()
    {
        $this->stash['errors'] = array();
        if( $this->app->request->isPost() ){
            //ログインチェック
            $check = false;
            
            $condition = array(
                'loginid'  => $this->query['loginid'],
            );

            $user_info = $this->model('User')->data($condition);
            if (!empty($user_info) && password_verify($this->query['password'], $user_info['password'])) {
                $check = true;
            }
            if($check){
               //管理ツール TOP 
               $_SESSION['admin'] = $user_info;
               $_SESSION['admin']['logined'] = true;
               $url = $this->location();
               if(empty($url)){
                    $url = '/admin/top';
               }
               session_regenerate_id();
               $this->redirect($url);
            }
            $this->stash['errors'][] = 'ユーザ名もしくはパスワードが異なります。';
        }

        //ログイン済の場合
        if(!empty($_SESSION['admin']['logined'] )) {
           $this->redirect('/admin/top');
        }

        $this->render();
    }
    public function top()
    {
        $this->render();
    }
    public function logout()
    {
        $_SESSION['admin'] = array();
        $this->redirect('/admin/login');
    }
}
