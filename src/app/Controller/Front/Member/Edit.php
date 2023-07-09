<?php
namespace Controller\Front;

class Member_Edit extends \Controller\Front
{
    public $auth_pages = array('index','email','password');
    public $non_member_pages = array();

    public function index()
    {
        //初期表示
        if($this->app->request->isGet()){
            $this->query =  $this->member; 
        }
        //確認画面
        // else if( $this->app->request->isPost() ){
            // $error_list = $this->check();
            // if (empty($error_list)) {
                // return $this->render('confirm');
            // }
            // else {
                // $this->stash['errors'] = $error_list;
                // return $this->render();
            // }
        // }
        //完了画面
        else if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->model('Member')->update(array(
                    'id'       => $this->member_id,
                    // 'birthday' => $this->query['birthday'],
                    'pref'     => $this->query['pref'],
                    // 'sex'      => $this->query['sex'],
                    'dm_flg'   => $this->query['dm_flg']
                ));
                $this->app_session('member_msg','会員情報を変更しました。');
                $this->app->redirect($this->app->urlFor('member_edit'));
            }
            else {
                $this->stash['errors'] = $error_list;
            } 
        }
        //確認画面から戻る
        // else if( !empty($this->query['_REF'] ) && 
                // $this->query['_REF'] ==='confirm'){
            // return $this->render();
        // }
        
        $this->render();
    }

    public function email()
    {
        //完了画面
        if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->model('Member')->update(array(
                    'id'       => $this->member_id,
                    'email'    => $this->query['email'],
                ));
                $this->app_session('member_msg','メールアドレスを変更しました。');
                $this->app->redirect($this->app->urlFor('member_edit'));
            }
            else {
                $this->stash['errors'] = $error_list;
            } 
        }
        
        $this->render();
    }

    public function password()
    {
        //完了画面
        if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->model('Member')->update(array(
                    'id'       => $this->member_id,
                    'password' => $this->query['password'],
                ));
                $this->app_session('member_msg','パスワードを変更しました。');
                $this->app->redirect($this->app->urlFor('member_edit'));
            }
            else {
                $this->stash['errors'] = $error_list;
            } 
        }
        
        $this->render();
    }

    public function check()
    {
        $errors = [];

        if($this->action === 'email' ){
            if(empty($this->query['email'])){
                $errors['email'] = 'メールアドレスは必須です。';
            }
            else if($this->query['email'] !== $this->query['email_confirm']){
                $errors['email'] = 'メールアドレスが確認用と異なります。';
            }
            else if($this->Model('Member')->has('email',$this->query['email'] ,$this->member_id)) {
                $errors['email'] = 'メールアドレスは既に使われています。';
            }
            else if(!is_email($this->query['email'])){
                $errors['email'] = 'メールアドレスを正しく入力してください。';
            }
            
            if(empty($this->query['password'])){
                $errors['password'] = 'パスワードは必須です。';
            }
            else if(empty($this->Model('Member')->password_check( 
                        array('id' => $this->member_id,'password'=> $this->query['password'])))){
                $errors['password'] = 'パスワードが異なります。';
            }
        }

        else if($this->action === 'password' ){
            if(empty($this->query['password_current'])){
                $errors['password_current'] = '現在のパスワードは必須です。';
            }
            else if(empty($this->Model('Member')->password_check( 
                        array('id' => $this->member_id,'password'=> $this->query['password_current'])))){
                $errors['password_current'] = '現在のパスワードが異なります。';
            }
            if(empty($this->query['password'])){
                $errors['password'] = 'パスワードは必須です。';
            }
            else if(mb_strlen($this->query['password']) < 6){
                $errors['password'] = 'パスワードは6文字以上を入力してください。';
            }
            else if($this->query['password'] !== $this->query['password_confirm']){
                $errors['password'] = 'パスワードが確認用と異なります。';
            }
            else if($this->query['password_current'] == $this->query['password']){
                $errors['password'] = '現在のパスワードと新しいパスワードが同じです。';
            }
        }

        else if($this->action === 'index' ){
//            if(empty($this->query['birthday'])){
//                $errors['birthday'] = '生年月日は必須です。';
//            }
//            else if(!is_numeric($this->query['birthday']) || 
//                    1900 > $this->query['birthday'] || 
//                    $this->query['birthday'] > date('Y')  ){
//                $errors['birthday'] = '生年月日を正しく入力してください。';
//            }
            if(empty($this->query['pref'])){
                $errors['pref'] = '都道府県を選択してください。';
            }
            elseif(!in_array($this->query['pref'], $this->config('prefectures'))){
                $errors['pref'] = '都道府県を正しく選択してください。';
            }
            
//            if(empty($this->query['sex']) || !preg_match('/^(男性|女性)$/',$this->query['sex'])){
//                $errors['sex'] = '性別を選択してください。';
//            }
            if(!isset($this->query['dm_flg']) || !preg_match('/^(0|1)$/',$this->query['dm_flg'])){
                $errors['dm'] = 'DMを選択してください。';
            }
        }
        return  $errors;
    }
}
