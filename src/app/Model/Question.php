<?php
namespace Model;

class Question extends Base
{
    //質問する
    public function add($args){
        if(empty($args['member']) ||
           empty($args['category_id'])  ||
           empty($args['content'])){
            trigger_error("Question::add Error!",E_USER_ERROR);
        }

        $keyword = array();
        $keyword[] = $this->config['question_categories'][$args['category_id']];
        $keyword[] = $args['content'];
        
        $member = $args['member'];

        $id = $this->db->insert('questions',
            array(
                'category_id'         => $args['category_id'],
                'content'             => $args['content'],
                'keyword'             => implode('\t',$keyword),
                'member_id'           => $member['id'],
                'member_birthday'     => $member['birthday'],
                'member_age'          => year2era($member['birthday']),
                'member_sex'          => $member['sex'],
                'member_pref'         => $member['pref'],
                'response_limit_date' => date('Y-m-d H:i:s', strtotime('4 week', time()))
            )
        );
        $this->update_member_question_cnt($member['id']);

        return $id;
    }

    //利用者の質問件数を更新
    public function update_member_question_cnt($member_id){

        //質問数を更新
        $this->db->exec("
            update
               members a,
               (
                select 
                  count(id) as cnt,
                  member_id
                from
                  questions
                where
                  show_flg = 1 and
                  del_flg  = 0 and
                  approval_stat <> 2 and
                  member_id = ?
                ) b
            set    
                a.question_cnt  = b.cnt
            where
                a.id = ?
        ", array(
            $member_id,
            $member_id,
        ));
    }

    //承認する
    public function approval($result='ok', $args){
        if(!is_array($args['question_id'])){
            trigger_error("Question::approval Error!",E_USER_ERROR);
        }
        $where_id = implode(', ', array_fill(0, count($args['question_id']), '?'));
        $bind = $args['question_id'];
        array_unshift($bind, ($result === 'ok' ? 1 : 2), date('Y-m-d H:i:s'));
        $this->db->exec("
            update
                questions
            set    
                approval_stat  = ?,
                approval_date = ?
            where
                id in ($where_id) and
                approval_date is null and
                show_flg = 1 and
                del_flg  = 0
        ", $bind);

        if($result =='ng'){
            $bind = $args['question_id'];
            $list = $this->db->read_many("
                select
                    member_id
                from
                    questions
                 where
                    id in ($where_id)
            ",$bind);
            foreach($list as $member_id){
                $this->update_member_question_cnt($member_id);
            }
        }
    }
    //締切設定(解決済)
    public function resolved($args){
        $this->dead($args, 1);
    }
    //締切設定(自動)
    public function auto_dead($args){
        $this->dead($args, 2);
    }
    //締切設定(事務局)
    public function secretariat_dead($args){
        $this->dead($args, 3);
    }
    public function dead($args, $stat=0){
        if(!is_array($args['question_id'])){
            trigger_error("Question::dead Error!", E_USER_ERROR);
        }
        $where_id = implode(', ', array_fill(0, count($args['question_id']), '?'));
        $bind   = $args['question_id'];
        array_unshift($bind, $stat, date('Y-m-d H:i:s'));
        $this->db->exec("
            update
                questions
            set    
                dead_stat = ?,
                dead_date = ?
            where
                id in ($where_id) and
                dead_stat <> 1 and
                show_flg  = 1 and
                del_flg   = 0
        ", $bind);
    }


    public function find($type, $args=array())
    {
        $where  = array(
            'a.del_flg = 0'
        );
        $fields = array();
        $fields = empty($args['fields']) ?
            array() : $args['fields'];
        $bind   = array();
        $order  = empty($args['order']) ?
            array() : $args['order'];
        $conditions = empty($args['conditions']) ?
            array() : $args['conditions'];

        if(!empty($order) && !is_array($order)){
            if($order === 'answer_date'){
                $order = array('a.last_answer_date desc');
            }
            else if($order === 'like'){
                $order = array('a.like_cnt desc');
            }
        }

        $condition_approval = function($conditions){
            $_approval = !is_array($conditions['approval']) ?
                array($conditions['approval']) :
                $conditions['approval'];
            $_where = array();
            foreach($_approval as $val){
                switch($val){
                    case 'before':
                        $_where[] = ' a.approval_stat = 0';
                        break;
                    case 'ng':
                        $_where[] = ' a.approval_stat = 2';
                        break;
                    case 'ok':
                        $_where[] = ' a.approval_stat = 1';
                        break;
                }
            }
            return  '('. implode( ' or ', $_where ) . ')';
        };

        //会員ID(配列)
        if(!empty($conditions['member_id']) && is_array($conditions['member_id'])){
            $_where = array();
            $_where[] = $condition_approval($conditions['member_id']);
            $_where[] = ' a.member_id = ?';
            $_where[] = ' a.show_flg  = ?';

            //sql追加
            $bind[]  = $conditions['member_id']['member_id'];
            $bind[]  = $conditions['member_id']['show_flg'];

            if(!empty($conditions['approval'])){
                $where[] = ' ('.$condition_approval($conditions). 
                    ' or  ('. implode( ' and ', $_where ) . '))';
            }
            else {
                $where[] = ' ('. implode( ' and ', $_where ) . ')';
            }
        }
        //承認状態
        else if(!empty($conditions['approval'])){
            $where[] = $condition_approval($conditions);
        }

        //id
        if(isset($conditions['question_id'])){
            $conditions['id'] = $conditions['question_id'];
        }
        if(isset($conditions['id'])){
            if(is_array($conditions['id'])){
                $where[] = ' a.id in ('.implode(', ', 
                    array_fill(0, count($conditions['id']), '?')).')';
                foreach($conditions['id'] as $_id){
                    $bind[]  = $_id;
                }
            }
            else {
                $where[] = ' a.id = ?';
                $bind[]  = $conditions['id'];
            }
        }

        //not id
        if(!empty($conditions['queston_id:not'])){
            $where[] = ' a.id <> ?';
            $bind[]  = $conditions['queston_id:not'];
        }

        //公開状態
        if(isset($conditions['show_flg'])){
            $where[] = ' a.show_flg = ?';
            $bind[]  = !empty($conditions['show_flg']) ? 1 : 0;
        }

        //締切
        if(isset($conditions['dead_stat']) && is_numeric($conditions['dead_stat'])){
            if(!empty($conditions['dead_stat'])){
                $where[] = ' a.dead_stat = ?';
                $bind[]  = $conditions['dead_stat'];
            }
            else {
                $where[] = ' a.dead_stat = 0';
            }
        }

        //カテゴリ
        if(!empty($conditions['category_id'])){
            $where[] = ' a.category_id = ?';
            $bind[]  = $conditions['category_id'];
        }

        //キーワード
        if(!empty($conditions['keyword'])){
           $keywords = explode(' ', trim(mb_convert_kana($conditions['keyword'], 's', 'UTF-8')));
           foreach($keywords as $keyword){
              $where[] = 'a.keyword like ? ';
              $bind[]  = '%'.trim($keyword).'%';
           }
        }
        
        //回答有りか待ちか
        if(isset($conditions['answer'])){
            if(empty($conditions['answer'])){
                $where[] = ' a.answer_cnt = 0';
            }
            else {
                $where[] = ' a.answer_cnt > 0';
            }
        }
        
        //会員ID
        if(!empty($conditions['member_id'])){
            if(!is_array($conditions['member_id'])){
                $where[] = ' a.member_id = ?';
                $bind[]  = $conditions['member_id'];
            }
        }
        
        //通知対象(承認後、1時間以内)
        if(!empty($conditions['target_notice'])){
            $where[] = ' a.approval_stat = 1 and 
                         a.approval_date > current_timestamp + interval -1 hour';
        }

        //回答締切対象(投稿日から2週間)
        if(!empty($conditions['target_dead_answer'])){
            $where[] = ' a.dead_stat = 0 and 
                         a.created < current_timestamp + interval -2 week';
        }
        
        //薬局会員
        if(!empty($conditions['pharmacy_id'])){
            $_sql = 'a.id in ( 
                select 
                    question_id 
                from 
                    answers 
                where ';
            
            if(!empty($conditions['response'])){
                $_sql .= ' res_date is not null and ';
            }

            $_sql .= '
                    pharmacy_id = ?     and
                    show_flg    = true  and
                    del_flg     = false)';

            $where[] = $_sql;
            $bind[]  = $conditions['pharmacy_id'];
        }

        //相談者関連
        if(!empty($conditions['member_sex'])){
            $where[] = ' a.member_sex = ?';
            $bind[]  = $conditions['member_sex'];
        }

        if(isset($conditions['member_age']) && is_numeric($conditions['member_age'])){
            if(100 > $conditions['member_age']){
                $where[] = ' a.member_age = ?';
                $bind[]  = $conditions['member_age'];
            }
            else {
                $where[] = ' a.member_age >= ?';
                $bind[]  = $conditions['member_age'];
            }
        }
        if(!empty($conditions['member_pref'])){
            $where[] = ' a.member_pref = ?';
            $bind[]  = $conditions['member_pref'];
        }

        if(!count($fields)){
            $fields = array(
                'a.id',
                'a.category_id',
                'a.content',
                'a.answer_cnt',
                'a.like_cnt',
                'a.approval_stat',
                'a.dead_stat',
                'a.show_flg',
                'a.member_age',
                'a.member_sex',
                'a.member_pref',
                'a.response_limit_date < now() as response_limit',
                'b.id as member_id',
                'b.nickname as member_nickname',
                'b.email as member_email',
                'b.del_flg as member_withdrawal',
                'a.created',
            );
        }

        // SQL実行
        $sql = 'select '. implode(',', $fields).
               ' from questions a inner join members b on a.member_id = b.id ';
        if(count($where)){
            $sql .= ' where '.(implode(' and ', $where));
        }
        if($type == 'first'){
            return $this->db->read($sql, $bind);
        }
        elseif($type == 'list' ) {
            if(count($order)){
                $sql .= ' order by '.implode(', ', $order);
            }else {
                $sql .= ' order by a.created desc';
            }

            if(!empty($args['page'])){
                $page = $args['page'];
                $count = $this->db->read(
                   'select count(a.id) from questions a
                       where '.(join(' and ', $where)),
                    $bind
                );
                $pager = $this->pager($count, $page['current'], $page['limit']) ;
                $sql .= ' limit ?,?';
                $bind[] = $pager['offset'];
                $bind[] = $pager['limit'];
                $result = $this->db->read_many($sql, $bind);
                return array($result, $pager);
            }
            else {
                if(!empty($args['limit'])){
                    $sql   .= ' limit 0,?';
                    $bind[] = $args['limit'];
                }
                $result = $this->db->read_many($sql, $bind);
                return $result;
            }

        }

    }

    //save(更新のみ)
    public function save($args){
        if(empty($args['id'])){
            trigger_error('Question::save Error!',E_USER_ERROR);
        }
        
        if(isset($args['del_flg'])){
            $args['del_flg'] = !empty($args['del_flg'])? 1 : 0;
        }
        
        if(isset($args['show_flg'])){
            $args['show_flg'] = !empty($args['show_flg'])? 1 : 0;
        }

        $this->db->update('questions',$args);
    }

    public function delete_by_member($args){
        $this->db->update('questions',
            array('del_flg' => 1),
            array(
                'id'        => $args['question_id'],
                'member_id' => $args['member_id'],
                'del_flg'   => 0
            )
        );
    }
    
    //カテゴリ別の回答件数がない質問件数
    public function get_no_answer_count(){
        
        $sql = '
            select 
                category_id, 
                count(id) as cnt
            from 
                questions 
            where 
                answer_cnt = 0 and 
                approval_stat=1 and 
                dead_stat=0 and 
                show_flg=1 and 
                del_flg=0 
            group by 
                category_id
            order by 
                category_id
            ';

        return $this->db->read_many($sql);
    }

    public function delete($id)
    {
        $this->db->update('questions', array('del_flg'=> 1, 'id'=> $id ));
    }
    
    //回答締切設定
    public function dead_answer()
    {
        $this->db->exec("
            update
                questions
            set    
                dead_stat = 2,
                dead_date = current_timestamp
            where
                del_flg       = 0 and
                approval_stat = 1 and
                dead_stat     = 0 and
                created < current_timestamp + interval -2 week
        ");
    }

    //公開設定
    public function set_show($args=array())
    {
        //公開フラグを更新
        $this->save(array(
            'id'       => $args['id'],
            'show_flg' => $args['show_flg'],
        ));
        
        //相談者の件数を調整
        $list = $this->db->read_many("
            select
                member_id
            from
                questions
             where
                id in (?)
        ",array($args['id']));
        foreach($list as $member_id){
            $this->update_member_question_cnt($member_id);
        }

    }
}

