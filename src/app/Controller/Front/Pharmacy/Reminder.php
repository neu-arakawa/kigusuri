<?php
namespace Controller\Front;

class Member_Reminder extends \Controller\Front
{
    public $non_member_pages = array('request','execution');
    public function request()
    {
        if( $this->app->request->isPost() ){
            $member = $this->Model('Member')->find('first', array(
                'conditions' => array(
                    'email'    => $this->query['email'],
                    'birthday' => $this->query['birthday']
                )
            ));
            if(empty($member)){
                $this->stash['errors'][] = '会員情報が見つかりません。';
                $this->render('step1');
            }
            else {
                $data = array($member['id'], $member['email'],$member['birthday']);
                $key  = $this->model('Cert')->add('password_forget',
                    $data, md5(implode('',$data)));
                
                $this->send_mail('password_forget',
                    array(
                        'To'=>$this->query['email'],
                        'args' => array(
                            'url' => 'http://'.$_SERVER['HTTP_HOST'].'/member/password_forget/execution/?cert='.$key
                        )
                    )
                );

                $this->render('step2');
            }
        }
        else {
            $this->query['birth_y'] = 1980;
            $this->render('step1');
        }
    }

    public function execution()
    {
        //certチェック
        $data =  $this->Model('Cert')->get('password_forget',
            $this->app->request->get('cert', ''), false);
        if(empty($data)){
            $this->app->notFound();
        }
        if( $this->app->request->isPost() ){
            $errors = [];
            if(empty($this->query['password']) && empty($this->query['password_confirm'])){
                $errors['password'] = 'パスワードは必須です。';
            }
            else if(mb_strlen($this->query['password']) < 6){
                $errors['password'] = 'パスワードは6文字以上を入力してください。';
            }
            else if($this->query['password'] !== $this->query['password_confirm']){
                $errors['password'] = 'パスワードが確認用と異なります。';
            }
            if(empty($errors)){
                $row['id'] = $data[0];
                $row['password']  = $this->query['password'];
                $this->model('Member')->update($row);
                $this->model('Cert')->clear('password_forget',$this->app->request->get('cert', ''));
                $this->app_session('change_password','パスワードを変更しました。');
                $this->app->redirect($this->app->urlFor('member_login'));
            }
            else {
                $this->stash['errors'] = $errors;
                $this->render('step3');
            }
        }
        else {
            $this->render('step3');
        }
    }
}
