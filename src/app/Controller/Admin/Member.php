<?php
namespace Controller\Admin;

class Member extends \Controller\Admin
{
    public function index($args=array()){
        $conditions = array(
            'id'      => $this->request->get('id', null),
            'del_flg' => $this->request->get('del_flg', ''),
            'keyword' => $this->request->get('keyword', null),
        );

        list(
            $this->stash['list'],
            $this->stash['pager']
        )
        = $this->model('Member')->find('list',
            array(
              'conditions' => $conditions,
              'page'       => array(
                  'current' => $this->request->get('page',1),
                  'limit'   => 30
              )
          ));
        $this->stash['query_string'] = http_build_query($conditions);
        $this->render();
    }


    public function delete()
    {
        if($this->app->request->isPost()){
            //退会登録
            $this->model('Member_Withdrawal')->add(
                array(
                    'member_id' => intval($this->query['id']),
                    'message'   => '事務局の判断で退会処分になりました',
                    'updater'   => 'admin',
                )
            );

            header('Content-Type: application/json');
            echo json_encode(array('result'=>'ok'));     
        }
        else {
            echo 'Bad Request!';     
        }
       
    }

    public function download(){
        
        $conditions = array(
            'id'      => $this->request->get('id', null),
            'del_flg' => $this->request->get('del_flg', ''),
            'keyword' => $this->request->get('keyword', null),
        );
        $members = $this->model('Member')->find('list',
          array(
            'conditions' => $conditions
          )
        );
        
        $reasons = $this->model('Member_Withdrawal')->reasons;
        foreach($members as $i => $member){
            $members[$i]['dm_flg'] = !empty($member['dm_flg']) ? '受け取る' : '受け取らない';
            $members[$i]['status'] = empty($member['del_flg']) ? '会員'     : '退会';
            
            if($member['withdrawal_updater'] === 'admin'){
                $members[$i]['withdrawal_reasons'] = '事務局による強制退会';
            }
            else if(empty($member['withdrawal_reasons'])){
                $members[$i]['withdrawal_reasons'] = '';
            }
            else {
                $_reason = array();
                foreach($reasons as $key => $val){
                    if($members[$i]['withdrawal_reasons'] & $key){
                        $_reason[] = $val;       
                    }
                }
                $members[$i]['withdrawal_reasons'] = $_reason;
            }
        }

        $obj =  new \Util\CSV;
        $obj->filename = sprintf("members_%s.csv", date("Ymd")); 
        $obj->header = array(
            'status'              => 'ステータス',
            'id'                  => 'ID',
            'nickname'            => 'ニックネーム',
            'email'               => 'メールアドレス',
            'birthday'            => '生年（西暦）',
            'sex'                 => '性別',
            'pref'                => '都道府県',
            'dm_flg'              => 'DM',
            'question_cnt'        => '質問数',
            'created'             => '登録日',
            'withdrawal_date'     => '退会日',
            'withdrawal_reasons'  => '退会理由',
            'withdrawal_message'  => 'その他ご意見',
        );
        $obj->rows = $members;
        $obj->dl();
    }
}
