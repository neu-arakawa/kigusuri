<?php
namespace Controller\Front;

class Pharmacy_Question extends \Controller\Front
{
    public $auth_pages = array(
        'mypage','history','search','answer'
    );
    public function mypage()
    {
        $conditions = array(
          'approval'  => array('ok'),
          'show_flg'  => true,
          'dead_stat' => 0  //回答受付中
        );
        if( empty($this->params('search_type'))) {
            $this->set_params('search_type','no_answer');
        }
        if( $this->params('search_type') === 'all') {
            $conditions['answer'] = null;
        }else if( $this->params('search_type') === 'no_answer') {
            $conditions['answer'] = false;
        }else if( $this->params('search_type') === 'has_answer') {
            $conditions['answer'] = true;
        }

        list( $list, $pager )  
            = $this->model('Question')->find('list',
                array(
                    'conditions' => $conditions,
                    'page'       => array(
                        'current' => $this->app->request->get('page',1),
                        'limit'   => 10
                    )
                ) 
            );
        //回答件数がない質問件数        
        $this->stash['no_answers'] 
            = $this->model('Question')->get_no_answer_count();

        $this->stash['list']  = $list;
        $this->stash['pager'] = $pager;
        $this->stash['url']   = $this->app->urlFor(
            'pharmacy_mypage',
            array(
                'search_type' => $this->params('search_type')
            )
        );
        
        //マイページ通知
        $this->stash['notices'] 
            = $this->model('Question_Answer')->notices(array(
              'pharmacy_id' => $this->member_id
            ));
        
        $this->render();
    }
    public function search()
    {
        $conditions = array(
          'approval' => 'ok',
          'show_flg' => true,
          'dead_stat' => 0  //回答受付中
        );
        $question_categories 
            = array_flip($this->config('question_categories'));
        if(empty($category_id = $question_categories[$this->params('q')])){
            $this->app->notFound();
        }
        $conditions['category_id'] = $category_id;
        $conditions['answer'] 
            = $this->params('search_type') === 'all' ?
              null : 
              (($this->params('search_type') === 'has_answer') ? true : false);
        list( $list, $pager )  
            = $this->model('Question')->find('list',
                array(
                    'conditions' => $conditions,
                    'page'       => array(
                        'current' => $this->app->request->get('page',1),
                        'limit'   => 10
                    )
                ) 
            );
        $this->stash['list']  = $list;
        $this->stash['pager'] = $pager;
        $this->stash['url']   = $this->app->urlFor(
            'pharmacy_qa_search',
            array(
                'q' => urlencode($this->params('q'))
            )
        );
        $this->render();
    }
    public function history()
    {
        //デフォルトの検索条件
        $conditions = array(
          'approval'    => array('ok'),
          // 'show_flg'    => true,
          'pharmacy_id' => $this->member_id
        );
        
        //検索項目
        $search_fields = array(
            'category_id',
            'member_sex',
            'member_age',
            'member_pref',
            'keyword', 
            'dead_stat',
            'response'
        );
        $query_string = array();
        foreach($search_fields as $field){
            if(isset($this->query[$field])){
                $conditions[$field] = $this->query[$field];
                $query_string[] = $field.'='.urlencode($this->query[$field]);
            }
        }
        
        list( $list, $pager )  
            = $this->model('Question_Answer')->find('list',
                array(
                    'conditions' => $conditions,
                    'page'       => array(
                        'current' => $this->app->request->get('page',1),
                        'limit'   => 10
                    )
                ) 
            );

        //回答がない一覧
        if( empty($this->params('search_type'))) {
            $this->set_params('search_type','no_answer');
        }
        $this->stash['list']  = $list;
        $this->stash['pager'] = $pager;
        $this->stash['url']   = $this->app->urlFor(
            'answer_history',
            array(
                'search_type' => $this->params('search_type')
            )
        ).'?'.implode('&',$query_string);
        $this->render();
    }

    public function answer()
    {
        //質問内容を取得
        $question =
            $this->model('Question')->find('first',array(
                'conditions' => array(
                    'id'        => $this->params('question_id'),
                    'approval'  => array('ok'),
                )
            ));
        if(empty($question)){
            $this->app->notFound();
        }
        $this->stash['question'] = $question;
        
        //回答したかどうか
        $answers =
            $this->model('Question_Answer')->find('first',array(
                'conditions' => array(
                    'question_id' => $question['id'],
                    'pharmacy_id' => $this->member_id,
                )
            ));
        $this->stash['answers'] = $answers ;
        
        //マイページの既読
        if(!empty($this->app->request->get('c'))){
            $this->model('Question_Answer')
                ->notice_read($this->app->request->get('c'));
        }

        if($question['dead_stat'] != 0 or $answers){
            return $this->render();
        }
        //完了画面
        if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->model('Question_Answer')->add([
                    'question_id' => $this->params('question_id'),
                    'pharmacy_id' => $this->member_id,
                    'content'     => $this->query['content'],
                    'only_flg'    => $this->query['only_flg']
                ]);
                
                //メール送信
                $pharmacy = array(
                    'name' => $this->member['name'],
                    'url'  => $this->app->request->getUrl().
                        $this->app->urlFor('shop_detail',array(
                        'code'=> $this->member['code']))
                );
                
                //公開ページのURL
                $url = $this->app->request->getUrl().
                    $this->app->urlFor('qa_detail',
                    array('id'=> $question['id'] ));
                
                if(empty($question['member_withdrawal'])){
                    //相談者へ通知※退会者には送信しない
                    $this->send_mail('answer_notice',
                        array(
                            'To'   => $question['member_email'],
                            'args' => array(
                                'pharmacy' => $pharmacy,
                                'question' => $question,
                                'answer'   => $this->query,
                                'url'      => $url
                            )
                        )
                    );
                }

                //事務局へ通知
                $this->send_mail('answer_notice_for_admin',
                    array(
                        'args' => array(
                            'pharmacy' => $pharmacy,
                            'question' => $question,
                            'answer'   => $this->query,
                            'url'      => $url
                        )
                    )
                );

                $this->app->redirect($this->app->urlFor('qa_answer',array(
                    'question_id' => $this->params('question_id'),
                    'action'      => 'complete',
                    'category_id' => $question['category_id'] 
                )));
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
            $this->app_session_clear('question');
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
    public function complete()
    {
        $conditions = array(
          'approval' => 'ok',
          'show_flg' => true
        );
        $conditions['category_id'] = $this->params('category_id');
        $conditions['answer']      = false;
        $this->stash['list']  = $this->model('Question')->find('list',
                array(
                    'conditions' => $conditions,
                    'limit'      => 5
                ) 
            );
        $this->render();
    }
    public function check()
    {
        $errors = [];
        if(empty($this->query['content'])){
            $errors['content']     = '回答内容は必須項目です。';
        }
        elseif(mb_strlen($this->query['content']) < 10){
            $errors['content']     = '回答内容は10文字以上で入力してください。';
        }
        elseif(mb_strlen($this->query['content']) > 2000){
            $errors['content']     = '回答内容は2000文字以内で入力してください。';
        }
        return  $errors;
    }

    public function before()
    {
        if(!empty($this->query['content'])){
            //絵文字除去
            $this->query['content'] = delete4byte($this->query['content']);
        }
    }
    
    //回答したかどうか
    public function has_answer($question_id){
        if(empty($this->_pharmacy_answer_date)){
            $this->_pharmacy_answer_date =
                $this->model('Question_Answer')->pharmacy_answer_date(array(
                  'pharmacy_id' => $this->member_id
                ));
        } 
        return !empty($this->_pharmacy_answer_date[$question_id]);
    }
}


