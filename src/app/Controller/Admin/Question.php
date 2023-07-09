<?php
namespace Controller\Admin;

class Question extends \Controller\Admin
{
    public function index($args=array()){
        // $args['id'] = null;
        $conditions = array_merge(
            array(),
            $args
        );
        
        //検索フォームから
        if(!empty($this->request->get('id'))){
            $conditions['id'] = $this->request->get('id');
        }
        $conditions['category_id'] 
            = $this->request->get('category_id', null);
        
        //会員IDでの絞り込み
        if(!empty($this->request->get('member_id'))){
            $conditions['member_id'] = $this->request->get('member_id');
            
            //会員情報
            $this->stash['member'] 
                = $this->model('Member')->find('first',
                  array(
                    'conditions' => $conditions,
                ));
        }

        list(
            $this->stash['list'],
            $this->stash['pager']
        )
        = $this->model('Question')->find('list',
            array(
              'conditions' => $conditions,
              'page'       => array(
                  'current' => $this->request->get('page',1),
                  'limit'   => 30
              )
          ));
        $this->stash['query_string'] = http_build_query($conditions);
        $this->render('index');
    }
    public function approval_before($args=array()){
        $this->index(array('approval'=> 'before'));
    }
    public function approval_ok($args=array()){
        $this->index(array('approval'=> 'ok'));
    }
    public function approval_ng($args=array()){
        $this->index(array('approval'=> 'ng'));
    }

    //承認設定
    public function approval_preference(){
        
        if(empty($this->query['question_id']) || 
           empty($this->query['result']) || 
           !preg_match('/^(ok|ng)$/',$this->query['result'])){
            echo '送信パラメータが不正です。';
            exit;
        }
        
        //承認する
        $this->model('Question')->approval($this->query['result'],array(
            'question_id' => array(
                $this->query['question_id']
            )
        ));

        if($this->query['result'] === 'ng'){
            
          $questions = $this->model('Question')->find('list',
            array(
              'conditions' => array(
                'question_id' => array(
                    $this->query['question_id']
                )
              )
            )
          );

          foreach($questions as $question){

              //承認NGメール
              $this->send_mail('question_ng',
                  array(
                      'To'   => $question['member_email'],
                      'args' => array(
                          'categories' => $this->config('question_categories'),
                          'question'   => $question 
                      )
                  )
              );
          }

        }

        header('Content-Type: application/json');
        echo json_encode(array('result'=>'ok'));     
    }

    //公開処理
    public function show_preference(){
        
        //公開フラグを更新
        $this->model('Question')->set_show(array(
            'id'       => $this->query['question_id'],
            'show_flg' => $this->query['result'],
        ));
        
        //利用者の件数を更新する
        

        header('Content-Type: application/json');
        echo json_encode(array('result'=>'ok'));     
    }

    //削除リクエスト
    public function del_requests(){

        
       $this->model('Question_Request')->find_for_question_id('delete');

    }


    public function add()
    {
        $this->edit();
    }
    public function edit($args=array())
    {
        if($this->app->request->isGet()){
            if( !empty($args['id'])){
                $data  = $this->model('Pharmacy')->findById($args['id']);
                foreach($data['websites'] as $key => $d){
                    $key++;
                    $data['website'.$key.'_label'] = $d['label'];
                    $data['website'.$key.'_url']   = $d['url'];
                }
                foreach($data['image2'] as $key => $d){
                    $key++;
                    $data['image2_'.$key.'_caption']= $d['caption'];
                    $data['image2_'.$key.'_path']   = $d['path'];
                }
                if(empty($data)){
                    $this->app->notFound();
                }

                $week = array('mon','tue','wed','thu','fri','sat','sun');

                foreach ($week as $_week) {
                    if (!empty($data[$_week])) {
                        $data[$_week] = implode("\n", $data[$_week]);
                    }
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
                $query = $this->model('Pharmacy')->save($this->query);
                $this->message('info',$this->query['name'].'を更新しました。');

                if(!empty($this->query['referrer'])){
                    $this->app->redirect($this->query['referrer']);
                }else{
                    $this->app->redirect(
                        $this->app->urlFor('default',array('controller'=>'pharmacy'))
                    );
                }
            }
        }

        return $this->render('input');
    }
    public function check($query)
    {
        $errors = array();
        if(empty($query['code'])){
            $errors[] = 'IDを入力してください。';
        }
        elseif(!$this->model('Pharmacy')->check_unique_code($query['code'], $query['id'])){
            $errors[] = 'ID名が重複しています。他のIDを登録してください。';
        }


        if(empty($query['name'])){
            $errors[] = '薬局名を入力してください。';
        }
        if(empty($query['kana'])){
            $errors[] = '薬局名かなを入力してください。';
        }
        if(empty($query['zip'])){
            $errors[] = '郵便番号を入力してください。';
        }
        elseif(!preg_match("/^\d{3}\-\d{4}$/",$query['zip'] )){
            $errors[] = '郵便番号形式が間違っています。(数字3桁-数字4桁)';
        }
        if(empty($query['pref'])){
            $errors[] = '都道府県を入力してください。';
        }
        if(empty($query['addr1'])){
            $errors[] = '住所1を入力してください。';
        }
        if(empty($query['addr2'])){
            $errors[] = '住所2を入力してください。';
        }

        if(empty($query['tel'])){
            $errors[] = '電話番号を入力してください。';
        }
        // elseif(!is_valid_phone_number($query['tel'])){
            // $errors[] = '電話番号形式が間違っています。';
        // }

        $week = array('mon','tue','wed','thu','fri','sat','sun');

        $isOpenhours = false;
        foreach($week as $_week){
            $times = $query[$_week];
            if(empty($times))continue;
            $times = preg_split("/[\s,]+/", $times);
            foreach($times as $time){
                if(!is_openhours($time)){
                    $errors[] = '営業時間の時刻形式が間違っています。['.$_week.']';
                    break;
                }
                $isOpenhours = true;
            }
        }

        // if(empty($isOpenhours)){
            // $errors[] = '営業時間を入力してください。';
        // }

        if(!empty($query['email'])){
            $emails = preg_split("/[\s,]+/", $query['email']);
            foreach($emails as $email){
                if(!is_valid_email($email)){
                    $errors[] = 'メールアドレスを正しく入力してください。';
                    break;
                }
            }
        }

        if(!empty($query['lat']) || !empty($query['lon'])){
            if(!preg_match('/^-?([0-8]?[0-9]|90)\.[0-9]{1,}$/',$query['lat'])){
                $errors[] = '緯度の形式が間違っています。';
            }
            else if(!preg_match('/^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,}$/',$query['lon'])){
                $errors[] = '経度の形式が間違っています。';
            }
        }

        if(!empty($query['reserve_shop_flg']) && $query['reserve_shop_flg'] == 2){
            if(empty($query['reserve_shop_url1']) && empty($query['reserve_shop_url2'])){
                $errors[] = '来店予約のURLを入力してください。';
            }
        }
        if(!isset($query['topics_flg']) || !preg_match('/^(0|1)$/',$query['topics_flg'])) {
            $errors[] = '健康トピックスをお選びください。';
        }

        if(!isset($query['join_flg']) || !preg_match('/^(0|1)$/',$query['join_flg'])) {
            $errors[] = '加盟ステータスをお選びください。';
        }

        if(!isset($query['show_flg']) || !preg_match('/^(0|1)$/',$query['show_flg'])) {
            $errors[] = '公開設定をお選びください。';
        }
        return $errors;
    }

    public function download(){
        $conditions = array();
        $conditions['id']          = $this->request->get('id', null);
        $conditions['category_id'] = $this->request->get('category_id', null);
        $conditions['approval']    = $this->request->get('approval', null);
        $data = $this->model('Question')->find('list',
          array(
            'conditions' => $conditions,
        ));
		
        $question_categories = $this->config('question_categories');

        $csv_data = array();
        foreach($data as $key => $val){
            $val['category'] = $question_categories[ $val['category_id'] ];
            $answers = $this->model('Question_Answer')->find('list',
              array(
                  'conditions' => array(
                    'question_id'  => $val['id']
                  )
            ));
            
            if( !empty($answers) ){ 
                foreach($answers as $i => $answer){
                    $_data = $val;
                    $_data['answer_date']   = $answer['answer_date'];
                    $_data['answer_detail'] = $answer['answer'];
                    $_data['answer_open']   = empty($answer['only_flg']) ? '全員' : '質問者';
                    $_data['answer_pharmacy']      = $answer['pharmacy_name'];
                    $_data['answer_pharmacy_code'] = $answer['pharmacy_code'];
                    $_data['answer_like']          = $answer['like_cnt'];
                    $_data['comment_date']   = $answer['response_date'];
                    $_data['comment_detail'] = $answer['response'];
                    $csv_data[] = $_data; 
                }
            }else{
                $csv_data[] = $val; 
            }
        }

        $obj =  new \Util\CSV;
        $obj->filename = sprintf("questions_%s.csv", date("YmdHi")); 
        $obj->header = array(
            'id'       => 'No',
            'created'  => '質問日',
            'category' => 'カテゴリ',
            'content'  => '質問内容',
            'member_nickname' => '[質問者] ニックネーム',
            'member_sex'      => '[質問者] 性別',
            'member_age'      => '[質問者] 年代 ',
            'member_pref'     => '[質問者] 都道府県',
            'answer_cnt'      => '回答数',
            'answer_date'     => '[回答] 回答日時',
            'answer_detail'   => '[回答] 内容',
            'answer_open'     => '[回答] 公開',
            'answer_pharmacy_code' => '[回答] 薬局コード',
            'answer_pharmacy' => '[回答] 薬局名',
            'answer_like'     => '[回答] 参考になった',
            'comment_date'    => '[コメント] 日時',
            'comment_detail'  => '[コメント] 内容',
        );
        $obj->rows = $csv_data;
        $obj->dl();
        $this->app->config('debug', 0 );
    }
}
