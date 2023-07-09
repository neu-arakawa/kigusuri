<?php
namespace Controller\Front;

class Question extends \Controller\Front
{

    public function index()
    {
        if($this->app->request->isPost()){
            if( empty($this->query['content']) or mb_strlen($this->query['content']) < 10 ){
                $this->stash['errors']['content'] = '質問内容は10文字以上入力してください。';
            }
            else {
                $this->app_session('question', array(
                    'content' => $this->query['content']
                ));
                //質問フォームへgo
                $this->app->redirect($this->app->urlFor('qa_request'));
            }
        }
        
        $list = $this->model('Question')->find('list',
            array(
                'conditions' => array(
                    'approval' => 'ok',
                    'show_flg' => true
                ),
                'limit' => 5
            ) 
        );

        $this->stash['list']  = $list;
        $this->render();
    }

    public function search()
    {
        $conditions = array(
          'approval' => 'ok',
          'show_flg' => true
        );
        if( !empty($this->params('search_type'))) {
            if($this->params('search_type') === 'category' ){
                $question_categories 
                    = array_flip($this->config('question_categories'));
                if(empty($category_id = $question_categories[$this->params('q')])){
                    $this->app->notFound();
                }
                $conditions[ 'category_id' ]  = $category_id;
                $this->stash[ 'category_id' ] = $category_id;
                $conditions[$this->params('search_type')] = $this->params('q');
            }
            else if($this->params('search_type') === 'keyword' ){
                if(empty($this->params('q')) || 30 < mb_strlen($this->params('q')) ){
                    $this->app->notFound();
                }
                $conditions[$this->params('search_type')] = $this->params('q');
            }
            else if($this->params('search_type') === 'answer'){
                $this->stash['pharmacy'] =
                    $this->model('Pharmacy')->findByCode($this->params('q'));
                if(empty($this->stash['pharmacy'])){
                    $this->app->notFound();
                }
                $conditions['pharmacy_id'] = $this->stash['pharmacy']['id'];
            }
        }

        if(!empty($this->query['filter'])){
            if($this->query['filter'] === 'has_answer'){
                $conditions['answer'] = true;
            }
            else if($this->query['filter'] === 'resolved'){
                $conditions['dead_stat'] = 1; //解決済み
            }
        }

        $order = false;
        if(!empty($this->query['order'])){
            $order = $this->query['order'];
        }

        list( $list, $pager )  
            = $this->model('Question')->find('list',
                array(
                    'conditions' => $conditions,
                    'order'      => $order,
                    'page'       => array(
                        'current' => $this->app->request->get('page',1),
                        'limit'   => 10
                    )
                ) 
            );

        if($this->params('search_type') === 'answer' && empty($pager['total_count'])){
            //一件も回答していない薬局は404
            $this->app->notFound();
        }

        $this->stash['list']  = $list;
        $this->stash['pager'] = $pager;
        
        $args = array();
        if(!empty($this->params('search_type'))){
            $args = array(
                'search_type' => $this->params('search_type'),
                'q'           => urlencode($this->params('q'))
            );
        }
        $this->stash['url']   = $this->app->urlFor('qa_list', $args );

        $this->stash['query_string'] = http_build_query(array(
            'filter' => $this->app->request->get('filter', null),
            'order'  => $this->app->request->get('order', null)
        ));
        $this->render();
    }

    public function detail($args=array())
    {
        
        $conditions = array(
            'id' => $args['id'],
            'approval'  => array('ok'),
            'show_flg'  => true
        );
        if($this->is_member){
            $conditions['member_id'] = array( 
                'approval'  => array('before','ok'),
                'show_flg'  => true,
                'member_id' => $this->member_id
            );
            
            //削除リスト済かどうか
            $this->stash['delete_request'] =
                $this->model('Question_Request')->has('delete', array(
                    'question_id' => $args['id']
                ));
        }

        $question =
            $this->model('Question')->find('first',array(
                'conditions' => $conditions
            ));
        if(empty($question)){
            $this->app->notFound();
        }
        $this->stash['question'] = $question;

        $answers =
            $this->model('Question_Answer')->find('list',array(
                'conditions' => array(
                    'question_id' => $args['id'],
                    'show_flg'    => true,
                )
            ));
        $this->stash['answers'] = $answers ;
        //自分が投稿した質問か
        $this->stash['myself']  = ($question['member_id'] == $this->member_id) ?
                                  true:
                                  false;
        
        //同じカテゴリの最新2件
        $list = $this->model('Question')->find('list',
            array(
                'conditions' => array(
                    'approval'       => 'ok',
                    'show_flg'       => true,
                    'category_id'    => $question['category_id'],
                    'queston_id:not' => $question['id']
                ),
                'limit' => 2
            ) 
        );
        $this->stash['list']  = $list;
        $this->render();
    }

    public function category_pages()
    {
       $categoris = $this->config('question_categories'); 
       $pages = array();
       foreach($categoris as $key => $val){
        $pages[ $val ] = $this->app->urlFor('qa_list', array(
            'search_type' => 'category',
            'q'           => urlencode($val)
        ));
       }
       return $pages;

    }
    
    //参考になったカウント
    public function like_answer()
    {
        $this->app->config('debug', 0 );
        $cookie_name = $this->action .'_' .$this->query['answer_id'];
        if(empty($_COOKIE[$cookie_name])){
            $this->model('Question_Answer')->do_like([
                'answer_id' => $this->query['answer_id']
            ]);
            $this->app->setCookie( $cookie_name, true, '5 years');
            echo 'ok';
        }
        // echo 'ng';
    }
    public function like_answer_clicked($answer_id)
    {
        return !empty($_COOKIE['like_answer_' .$answer_id]);
    }
}


