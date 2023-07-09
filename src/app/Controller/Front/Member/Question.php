<?php
namespace Controller\Front;

class Member_Question extends \Controller\Front
{
    public $auth_pages = array(
        'add','history','complete','resolved',
        'delete','delete_complete',
        'response','response_complete',
    );
    
    //相談履歴
    public function history()
    {
        $conditions = array(
          'approval'  => array('before','ok'),
          'show_flg'  => true,
          'member_id' => $this->member_id
        );
        if( !empty($this->params('search_type'))) {
            $conditions['answer'] 
                = ($this->params('search_type') === 'has_answer') ?
                    true : false;
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
        
        $this->stash['list']  = $list;
        $this->stash['pager'] = $pager;
        $this->stash['url']   = $this->app->urlFor(
            'qa_history',
            array(
                'search_type' => $this->params('search_type')
            )
        );
        $this->render();
    }

    //質問依頼フォーム
    public function add()
    {
        //初期表示
        if($this->app->request->isGet()){
            if(empty($this->query['category_id']))
               $this->query = $this->app_session('question');
        }
        //完了画面
        else if($this->app->request->isPut()){
            $error_list = $this->check();
            if (empty($error_list)) {
                $id = $this->model('Question')->add([
                    'category_id' => $this->query['category_id'],
                    'content'     => $this->query['content'],
                    'member'      => $this->member,
                ]);

                $question = $this->model('Question')->find('first', array(
                    'conditions'  => array(
                        'id' => $id
                    )
                ));

                //事務局宛に承認依頼メールを通知する
                $this->send_mail('question_approval_request_for_admin',
                    array(
                        'args' => array(
                            'question' => $question
                        )
                    )
                );
                
                $this->app->redirect($this->app->urlFor('qa_request',array(
                    'action' => 'complete'
                )));
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
            $this->app_session_clear('question');
            $error_list = $this->check();
            if (empty($error_list)) {
                return $this->render('add_confirm');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render('add');
            }
        }

        $this->render();
    }

    //質問依頼フォーム完了
    public function complete()
    {
        $this->render('add_complete');
    }

    //質問依頼フォーム入力チェック
    public function check()
    {
        $errors = [];
        if(empty($this->query['category_id']) 
            || !in_array($this->query['category_id'], array_keys($this->config('question_categories')))){
            $errors['category_id'] = '部位・症状を選択してください。';
        }
        
        $content = preg_replace('/\s|　/', '', $this->query['content']);
        if(empty($content)){
            $errors['content']     = '内容は必須項目です。';
        }
        elseif(mb_strlen($content) < 10){
            $errors['content']     = '内容は10文字以上で入力してください。';
        }
        elseif(mb_strlen($content) > 1000){
            $errors['content']     = '内容は1000文字以内で入力してください。';
        }

        return  $errors;
    }

    public function before()
    {
        if(!empty($this->query['content'])){
            //絵文字除去
            $this->query['content'] = trim(delete4byte($this->query['content']));
        }
    }

    //レスポンス書き込み
    public function response()
    {
        $this->app->config('debug', 0 );
        
        $id = $this->model('Question_Response')->add('member', array(
           'question_id' => $this->query['question_id'],
           'answer_id'   => $this->query['answer_id'],
           'member_id'   => $this->member_id,
           'content'     => $this->query['content'],
        ));

        $data['msg'] = $this->model('Question_Response')->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        $data['msg']['answer_id'] = $this->query['answer_id'];
        $data['myself']  = true;
        $data['ajax']  = true;

        $this->app->view->appendData($data);
        $html = $this->app->view->fetch('/Front/Parts/member_response.twig');

        $this->app->response->headers->set('Access-Control-Allow-Origin','http://'.$_SERVER['HTTP_HOST']);
        $this->app->response->headers->set('Access-Control-Allow-Credentials','true');
        $this->app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('html'=>$html));
    }
    
    //レスポンス削除
    public function response_delete()
    {
        $this->app->config('debug', 0 );
        
        $this->model('Question_Response')->delete_by_member(array(
           'response_id' => $this->query['response_id'],
           'answer_id'   => $this->query['answer_id'],
           'member_id'   => $this->member_id,
        ));

        $data['input'] = array(
           'answer_id'   => $this->query['answer_id'],
        );
        $data['myself']  = true;

        $this->app->view->appendData($data);
        $html = $this->app->view->fetch('/Front/Parts/member_response.twig');

        $this->app->response->headers->set('Access-Control-Allow-Origin','http://'.$_SERVER['HTTP_HOST']);
        $this->app->response->headers->set('Access-Control-Allow-Credentials','true');
        $this->app->response->headers->set('Content-Type','application/json');
        echo json_encode(array('html'=>$html));
    }
    
    //削除処理
    public function delete()
    {
        $conditions = array(
            'id'        => $this->params('question_id'),
            'approval'  => array('before','ok'),
            'show_flg'  => true,
            'member_id' => $this->member_id
        );

        $question =
            $this->model('Question')->find('first',array(
                'conditions' => $conditions
            ));
        
        if(empty($question)){
            $this->app->notFound();
        }
        if( $question['answer_cnt'] > 0){
            $this->set_params('mode', 'request'); 
        }

        $this->stash['question'] = $question;

        if($this->app->request->isPut()){
            if($this->params('mode') === 'request'){
                
                $errors = [];
                if(empty($this->query['reason'])){
                    $errors['reason'] = '理由は必須です。';
                }
                elseif(mb_strlen($this->query['reason']) < 10){
                    $errors['reason'] = '内容は10文字以上で入力してください。';
                }
                elseif(mb_strlen($this->query['reason']) > 1000){
                    $errors['reason'] = '内容は1000文字以内で入力してください。';
                }
                if(!empty($errors)){
                    $this->stash['errors'] = $errors;
                    return $this->render();
                }


            }

            if($this->params('mode') === 'request'){
                //回答があるときには削除依頼
                $data = array(
                    'question_id' => $this->params('question_id'),
                    'reason'      => $this->query['reason']
                );
                //リクエストチェック
                // if($this->model('Question_Request')->has('delete', $data)){
                    // $this->app->notFound();
                // }
                //削除リクエスト
                $this->model('Question_Request')->request('delete', $data);
                
                //事務居へメール通知
                $this->send_mail('question_delete_request_for_admin',
                    array(
                        'args' => array(
                            'question' => $question,
                            'reason'   => $this->query['reason']
                        )
                    )
                );
                
                //利用者へのメール通知
                $this->send_mail('question_delete_request',
                    array(
                        'To'   => $this->member['email'],
                        'args' => array(
                            'question' => $question,
                            'reason'   => $this->query['reason']
                        )
                    )
                );

                $this->app->redirect($this->app->urlFor('qa_delete',array(
                    'question_id' => $this->params('question_id'),
                    'mode'        =>'complete'
                )));
            }
            else {
                //回答がないときは直接削除
                $this->model('Question')->delete_by_member(array(
                    'question_id' => $this->params('question_id'),
                    'member_id'   => $this->member_id,
                ));
                
                //質問件数を更新
                $this->model('Question')
                    ->update_member_question_cnt($this->member_id);
                $this->app->redirect($this->app->urlFor('qa_delete',array(
                    'question_id' => $this->params('question_id'),
                    'mode'        =>'complete'
                )));
            }
        }

        $this->render();
    }
    
    //削除完了画面
    public function delete_complete()
    {

        $conditions = array(
            'id'        => $this->params('question_id'),
            'approval'  => array('before','ok'),
            'show_flg'  => true,
            'member_id' => $this->member_id
        );

        $question =
            $this->model('Question')->find('first',array(
                'conditions' => $conditions
            ));
        
        $this->stash['question'] = $question;
        $this->render('delete_complete');
    }
    
    //解決済
    public function resolved(){

        //デバッグモード無効
        $this->app->config('debug', false );
        
        //締め切り前の情報
        $conditions = array(
            'id'        => $this->query['question_id'],
            'approval'  => array('ok'),
            'show_flg'  => true,
            // 'dead_stat' => 0,
            'member_id' => $this->member_id
        );
        $question =
            $this->model('Question')->find('first',array(
                'conditions' => $conditions
            ));
        if(empty($question)){
            $this->app->notFound();
        }

        $this->model('Question')->resolved(array(
            'question_id' => array(
                $this->query['question_id']
            )
        ));
        
        //メール送信
        $this->send_mail('question_resolved_for_admin',
            array(
                'args' => array(
                    'member'   => $this->member,
                    'question' => $question
                )
            )
        );
        
        $this->app->response->headers->set('Access-Control-Allow-Origin','http://'.$_SERVER['HTTP_HOST']);
        $this->app->response->headers->set('Access-Control-Allow-Credentials','true');
        echo 'ok';
    }
}


