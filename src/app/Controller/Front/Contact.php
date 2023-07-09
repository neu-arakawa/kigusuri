<?php
namespace Controller\Front;

class Contact extends \Controller\Front
{
    public function shop()
    {
        //完了画面
        if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {

                //投稿者自動返信メール
                $this->send_mail('contact_shop',
                    array(
                        'To'   => $this->query['email'],
                        'args' => array(
                            'query' => $this->query
                        )
                    )
                );
                
                //事務局へ通知
                $this->send_mail('contact_shop_for_admin',
                    array(
                        'args' => array(
                            'query' => $this->query
                        )
                    )
                );
                return $this->render('Shop/complete');
            }
            else {
                $this->app->notFound();
            } 
        }
        //確認画面から戻る
        else if( !empty($this->query['_REF'] ) && 
                $this->query['_REF'] ==='confirm'){
            return $this->render('Shop/input');
        }
        //確認画面
        else if( $this->app->request->isPost() ){
            $error_list = $this->check();
            if (empty($error_list)) {
                return $this->render('Shop/confirm');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render('Shop/input');
            }
        }
        
        return $this->render('Shop/input');
    }

    public function check()
    {
        $errors = [];
        if(empty($this->query['pharmacy_name'])){
            $errors['pharmacy_name'] = '薬局・薬店名を入力してください';
        }
        if(empty($this->query['name'])){
            $errors['name'] = 'ご担当者様 氏名を入力してください';
        }
        if(empty($this->query['zip']) || !is_zip($this->query['zip'])){
            $errors['zip'] = '郵便番号を正しく入力してください';
        }
        if(empty($this->query['pref'])){
            $errors['pref'] = '都道府県を選択してください';
        }
        if(empty($this->query['addr1'])){
            $errors['addr1'] = '市区町村番地を入力してください';
        }

        if(empty($this->query['email'])){
            $errors['email'] = 'メールアドレスを入力してください';
        }
        else if(empty($this->query['email_confirm'])){
            $errors['email_confirm'] = 'メールアドレスを入力してください';
        }
        else if($this->query['email'] !== $this->query['email_confirm']){
            $errors['email'] = 'メールアドレスが確認用と異なります';
        }
        else if(!is_email($this->query['email'])){
            $errors['email'] = 'メールアドレスを正しく入力してください';
        }
        if(empty($this->query['tel'])){
            $errors['tel'] = '電話番号を入力してください';
        }
        if(empty($this->query['history'])){
            $errors['history'] = '漢方相談歴を選択してください';
        }
        return  $errors;
    }
}
