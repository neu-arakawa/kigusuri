<?php
namespace Controller\Front;

class Member_Regist extends \Controller\Front
{
    public $auth_pages = array('edit');
    public $non_member_pages = array('add');

    public function add()
    {
        //初期表示
        if($this->app->request->isGet()){
            $this->query['birthday'] = 1970;
        }
        //完了画面
        else if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->member_id = $this->model('Member')->add($this->query);
                
                //ログインする
                $this->member_session_record( $this->member_id );

                //メール送信
                $this->send_mail('member_registered',
                    array(
                        'To'   => $this->query['email'],
                        'args' => array(
                            'member' => $this->member 
                        )
                    )
                );
                
                if(empty($this->app_session('question'))){
                    $this->app_session('member_join','会員登録完了');
                    $this->app->redirect($this->app->urlFor('qa_top'));
                }
                else {
                    $this->app->redirect($this->app->urlFor('qa_request'));
                }
            }
            else {
                $this->app->notFound();
            } 
        }
        //確認画面から戻る
        else if( !empty($this->query['_REF'] ) && 
                $this->query['_REF'] ==='confirm'){
            return $this->render('add');
        }
        //確認画面
        else if( $this->app->request->isPost() ){
            $error_list = $this->check();
            if (empty($error_list)) {
                return $this->render('confirm');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render('add');
            }
        }
        
        $this->render();
    }

    public function check()
    {
        $errors = [];
        if(empty($this->query['nickname'])){
            $errors['nickname'] = 'ニックネームは必須です';
        }
        else if((mb_strlen($this->query['nickname']) < 3)){
            $errors['nickname'] = 'ニックネームは3文字以上で入力してください';
        }
        else if((mb_strlen($this->query['nickname']) > 21)){
            $errors['nickname'] = 'ニックネームは20文字以内で入力してください';
        }
        else if(!safe_nickname($this->query['nickname'])){
            $errors['nickname'] = 'ニックネームに使用できない文字が含まれています';
        }
        else if($this->Model('Member')->has('nickname',$this->query['nickname'])) {
            $errors['nickname'] = 'ニックネームは既に使われています';
        }

        if(empty($this->query['email'])){
            $errors['email'] = 'メールアドレスは必須です';
        }
        else if($this->query['email'] !== $this->query['email_confirm']){
            $errors['email'] = 'メールアドレスが確認用と異なります';
        }
        else if($this->Model('Member')->has('email',$this->query['email'])) {
            $errors['email'] = 'メールアドレスは既に使われています';
        }
        else if(!is_email($this->query['email'])){
            $errors['email'] = 'メールアドレスを正しく入力してください';
        }
        if(empty($this->query['password'])){
            $errors['password'] = 'パスワードは必須です';
        }
        else if(mb_strlen($this->query['password']) < 6){
            $errors['password'] = 'パスワードは6文字以上を入力してください';
        }
        else if($this->query['password'] !== $this->query['password_confirm']){
            $errors['password'] = 'パスワードが確認用と異なります';
        }

        if(empty($this->query['birthday'])){
            $errors['birthday'] = '生年月日は必須です';
        }
        else if(!is_numeric($this->query['birthday']) || 
                1900 > $this->query['birthday'] || 
                $this->query['birthday'] > date('Y')  
                ){
            $errors['birthday'] = '生年月日を正しく入力してください';
        }
        if(empty($this->query['pref'])){
            $errors['pref'] = '都道府県を選択してください';
        }
        elseif(!in_array($this->query['pref'], $this->config('prefectures'))){
            $errors['pref'] = '都道府県を正しく選択してください。';
        }
        if(empty($this->query['sex']) || !preg_match('/^(男性|女性)$/',$this->query['sex'])){
            $errors['sex'] = '性別を選択してください';
        }
        if(!isset($this->query['dm_flg']) || !preg_match('/^(0|1)$/',$this->query['dm_flg'])){
            $errors['dm'] = 'DMを選択してください';
        }
        return  $errors;
    }
}
