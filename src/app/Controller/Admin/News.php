<?php
namespace Controller\Admin;

class News extends \Controller\Admin
{
    public function index($args=array()){
        $conditions = array();
        list(
            $this->stash['list'],
            $this->stash['pager']
        ) 
        = $this->model('News')->find('list',
            array(
              'conditions' => $conditions,
              'page'       => array(
                  'current' => $this->request->get('page',1),
                  'limit'   => 30
              )
          ));
        $this->render();
    }

    public function add()
    {
        $this->edit();
    }
    public function edit($args=array())
    {
        if($this->app->request->isGet()){
            if( !empty($args['id'])){
                $data  = $this->model('News')->find('first', array('conditions'=>array( 'id'=> $args['id'])));
                if(empty($data)){
                    $this->app->notFound();
                }
                $this->query = $data;
            }
        }
        if($this->app->request->isPost()){
            $error_list = $this->check($this->query);
            if (empty($error_list)) {
                return $this->render('preview');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render('input');
            }
        }

        if($this->app->request->isPut()){
            $error_list = $this->check($this->query);
            if (!empty($error_list)) {
                return $this->render('input');
            }
            else {
                $query = $this->model('News')->save($this->query);
                $this->message('info','登録しました。');
                $this->app->redirect(
                    $this->app->urlFor('default',array('controller'=>'news'))
                );
            }
        }

        return $this->render('input');
    }
    public function check($query)
    {
        $errors = array();
        if(empty($query['date'])){
            $errors[] = '公開日を入力してください。';
        }
        if(empty($query['title'])){
            $errors[] = 'タイトルを入力してください。';
        }
        // if(empty($query['type'])){
            // $errors[] = 'タイプを選択してください。';
        // }
        // if(!v::notEmpty()->validate($query['title'])){
            // $error_list[] = 'タイトルは必須です。';
        // }
        // if(!v::notEmpty()->validate($query['date'])){
            // $error_list[] = '日付は必須です。';
        // }
        // if(!v::notEmpty()->validate($query['st'])){
            // $error_list[] = '掲載期間(開始日)は必須です。';
        // }
        // // if(!v::notEmpty()->validate($query['ed'])){
            // // $error_list[] = '掲載期間(終了日)は必須です。';
        // // }

        // if(empty($query['type'])){
            // $error_list[] = 'タイプは必須です。';
        // }
        // else {
            // if($query['type'] == 1 && empty($query['body'])){
                // $error_list[] = '本文は必須です。';
            // }
            // if($query['type'] == 2 && empty($query['url'])){
                // $error_list[] = 'URLは必須です。';
            // }
        // }

        // if(!v::date()->validate($this->query['st'])){
         // $error_list[] = '開始日に日付以外の文字が入力されています。';
        // }
        // else if(v::notEmpty()->validate($query['ed']) and !v::date()->validate($this->query['ed'])){
         // $error_list[] = '開始日に日付以外の文字が入力されています。';
        // }
        // else if(v::notEmpty()->validate($query['ed']) and $this->query['st'] > $this->query['ed']) {
         // $error_list[] = '終了日は開始日以降の日付を入力してください。';
        // }

        if(!isset($query['show_flg']) || !preg_match('/^(0|1)$/',$query['show_flg'])) {
            $errors[] = '表示対象かどうかをお選びください。';
        }

        return $errors;
    }

    public function del()
    {
        if($this->app->request->isDelete()){
            $this->model('News')->delete($this->query['id']);
            $this->message('info','削除しました。');
            $this->app->redirect($_SERVER["HTTP_REFERER"]);
        }
    }
}
