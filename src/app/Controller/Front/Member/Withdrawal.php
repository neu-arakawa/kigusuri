<?php
namespace Controller\Front;

class Member_Withdrawal extends \Controller\Front
{
    public $auth_pages = array('index');
    public $non_member_pages = array('complete');

    protected function before_render(){
        $this->stash['reasons'] 
            = $this->model('Member_Withdrawal')->reasons; 
    }
    public function index()
    {
        //会員情報
        $this->stash['member'] =  $this->member; 

        //初期表示
        if($this->app->request->isGet()){}
        //完了画面
        else if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->model('Member_Withdrawal')->add(
                    array(
                        'member_id' => $this->member_id,
                        'reasons'   => $this->query['reasons'],
                        'message'   => $this->query['message'],
                    )
                );
                $this->send_mail('member_withdrawn',
                    array('To'=>$this->member['email']));
                //ログアウト
                $this->session_logout();
                $this->app->redirect( 
                    $this->app->urlFor('member_withdrawal',
                        array('action'=>'complete' ))
                );
            }
            else {
                $this->app->notFound();
            } 
        }
        //確認画面から戻る
        else if( !empty($this->query['_REF'] ) && 
                $this->query['_REF'] ==='confirm'){
            return $this->render();
        }
        //確認画面
        else if( $this->app->request->isPost() ){
            $error_list = $this->check();
            if (empty($error_list)) {
                return $this->render('confirm');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render();
            }
        }
        
        $this->render();
    }
    public function complete(){
        $this->render();
    }
    public function check()
    {
        $errors = [];
        if(!empty($this->query['reasons'])){
            if( !is_array($this->query['reasons'])){
                $errors['reasons'] = '退会理由が不明です。';
            }
            else {
                foreach($this->query['reasons'] as $reason){
                    if(empty($reason))continue;
                    if(!in_array($reason, array_keys($this->model('Member_Withdrawal')->reasons))){
                        $errors['reasons'] = '退会理由が不明です。';
                        break;
                    }
                }
            }
        }

        return  $errors;
    }
}
