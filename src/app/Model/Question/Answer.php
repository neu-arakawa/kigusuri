<?php
namespace Model;

class Question_Answer extends Base
{
    //回答する
    public function add($args){

        if(empty($args['question_id']) ||
           empty($args['pharmacy_id'])  ||
           empty($args['content'])  ||
           !isset($args['only_flg'])){
            trigger_error("Question::answer Error!",E_USER_ERROR);
        }
        
        //公開フラグ
        $args['only_flg'] = !empty($args['only_flg'])? 1 : 0;

        $has = $count = $this->db->read(
           "select 
                id 
            from 
                questions
            where 
                id            = ? and 
                show_flg      = 1 and
                del_flg       = 0 and
                approval_stat = 1 and
                dead_stat     = 0
            ",
            array(
                $args['question_id'],
            )
        );
        if(empty($has)){
            trigger_error("Question_Reponse ErrorCode:02", E_USER_ERROR);
        }

        $this->db->insert('answers',
            array(
                'question_id' => $args['question_id'],
                'pharmacy_id' => $args['pharmacy_id'],
                'content'     => trim($args['content']),
                'only_flg'    => $args['only_flg'],
            )
        );
        
        $count = $this->db->read(
           "select count(id) from answers where question_id = ?",
           array($args['question_id'])
        );

        $this->db->update('questions',
            array(
                'id'               => $args['question_id'],
                'last_answer_date' => $this->now->format('Y-m-d H:i:s'),
                'answer_cnt'       => $count,
            )
        );
        
        //薬局の回答数を更新
        $this->update_answer_cnt($args['pharmacy_id']);

    }
    
    //回答済チェック
    public function answered_check($data=array()){
        $sql = '
            select 
            	count(a.id)
            from
                answers a
            where
             a.question_id = ? and
             a.pharmacy_id = ?
        ';
        $bind = array(
            $data['question_id'],
            $data['pharmacy_id']
        );

        $data = $this->db->read($sql, $bind);
        if(!empty($data)){
            return true; 
        }
        return false;
    }
    
    //いいね
    public function do_like($args){
        if(empty($args['answer_id'])){
            trigger_error("Question::answer Error!",E_USER_ERROR);
        }
        
        $value =  
            array(
                'date'      => $this->now->format('Y-m-d H:i:s'),
                'answer_id' => $args['answer_id'],
                'ip'        => @$_SERVER['REMOTE_ADDR'],
                'agent'     => @$_SERVER['HTTP_USER_AGENT'],
                'referer'   => @$_SERVER["HTTP_REFERER"],
            );
        
        if(!empty($args['member_id'])){
            $value['member_id'] = $args['member_id'];
        }
        if(!empty($args['pharmacy_id'])){
            $value['pharmacy_id'] = $args['pharmacy_id'];
        }

        $this->db->insert('answer_reactions',$value);

        //カウント結果を保存
        $count = $this->db->exec("
            update
                answers a,
                (select 
                    answer_id as id,
                    sum(value) as cnt
                 from 
                    answer_reactions
                 where 
                    kbn       = 'like' and
                    answer_id = ?
                ) b
            set    
                a.like_cnt = b.cnt
            where
                a.id = b.id and
                a.show_flg = 1 and
                a.del_flg  = 0
        ", array($args['answer_id']));

        if(empty($count)){
            $this->db->rollback(); 
            trigger_error("Question::answer Error!",E_USER_ERROR);
        }

        $count = $this->db->exec("
            update
                questions a,
                (select 
                    question_id as id,
                    sum(like_cnt) as cnt
                 from 
                    answers
                 where 
                    question_id = (
                        select 
                            question_id
                        from 
                            answers
                        where
                            id = ?
                    )
                ) b
            set    
                a.like_cnt = b.cnt
            where
                a.id = b.id and
                a.show_flg = 1 and
                a.del_flg  = 0

        ", array($args['answer_id']));

        if(empty($count)){
            $this->db->rollback(); 
            trigger_error("Question::answer Error!",E_USER_ERROR);
        }
    }

    public function find($type, $args=array())
    {
        $where  = array(
            'a.del_flg  = 0',
            'a.show_flg = 1'
        );
        $fields = array();
        $fields = empty($args['fields']) ?
            array() : $args['fields'];
        $bind   = array();
        $order  = array();
        $conditions = empty($args['conditions']) ?
            array() : $args['conditions'];

        //公開状態
        if(isset($conditions['show_flg'])){
            $where[] = ' d.show_flg = ?';
            $bind[]  = !empty($conditions['show_flg']) ? 1 : 0;
        }
        
        //質問ID
        if(!empty($conditions['question_id'])){
            $where[] = 'a.question_id = ?';
            $bind[]  = $conditions['question_id'];
        }

        //薬局会員
        if(!empty($conditions['pharmacy_id'])){
            $where[] = 'a.pharmacy_id = ?';
            $bind[]  = $conditions['pharmacy_id'];
        }

        /*
            相談履歴の検索条件
        */

        //相談者関連
        if(isset($conditions['dead_stat']) && is_numeric($conditions['dead_stat'])){
            if(!empty($conditions['dead_stat'])){
                $where[] = ' d.dead_stat = ?';
                $bind[]  = $conditions['dead_stat'];
            }
            else {
                $where[] = ' d.dead_stat = 0';
            }
        }

        //カテゴリ
        if(!empty($conditions['category_id'])){
            $where[] = ' d.category_id = ?';
            $bind[]  = $conditions['category_id'];
        }

        //キーワード
        if(!empty($conditions['keyword'])){
           $keywords = explode(' ', trim(mb_convert_kana($conditions['keyword'], 's', 'UTF-8')));
           foreach($keywords as $keyword){
              $where[] = 'd.keyword like ? ';
              $bind[]  = '%'.trim($keyword).'%';
           }
        }
        if(!empty($conditions['member_sex'])){
            $where[] = ' d.member_sex = ?';
            $bind[]  = $conditions['member_sex'];
        }

        if(isset($conditions['member_age']) && is_numeric($conditions['member_age'])){
            if(100 > $conditions['member_age']){
                $where[] = ' d.member_age = ?';
                $bind[]  = $conditions['member_age'];
            }
            else {
                $where[] = ' d.member_age >= ?';
                $bind[]  = $conditions['member_age'];
            }
        }
        if(!empty($conditions['member_pref'])){
            $where[] = ' d.member_pref = ?';
            $bind[]  = $conditions['member_pref'];
        }

        if(!empty($conditions['response'])){
            $where[] = ' a.res_date is not null';
        }
        
        //通知対象
        if(!empty($conditions['target_notice'])){
            $where[] = ' c.created > current_timestamp + interval -1 hour';
        }

        if(!count($fields)){
            $fields = array(
                'a.id as answer_id',
                'a.content as answer',
                'a.like_cnt as like_cnt',
                'b.code as pharmacy_code',
                'b.name as pharmacy_name',
                'b.image1 as pharmacy_image',
                'b.notice_email as pharmacy_email',
                '(b.show_flg && b.del_flg = 0 && b.join_flg) as pharmacy_active',
                'a.created as answer_date',
                'a.only_flg',
                'c.id as response_id',
                'c.content as response',
                'c.created as response_date',
                'd.id as question_id',
                'd.id',
                'd.category_id',
                'd.content',
                'd.answer_cnt',
                'd.approval_stat',
                'd.dead_stat',
                'd.show_flg',
                'd.member_age',
                'd.member_sex',
                'd.member_pref',
                'd.show_flg',
                'd.like_cnt as all_like_cnt',
                'e.id as member_id',
                'e.nickname as member_nickname',
                'e.email as member_email',
                'e.del_flg as member_withdrawal',
                'd.created',
            );
        }

        // SQL実行
        $created_sql = function($fields){
            return 'select '. 
               implode(',', $fields).
               ' from 
                    answers a
                 inner join
                    pharmacies b on
                    b.id = a.pharmacy_id
                 left join
                    responses c on
                    a.id = c.answer_id and
                    c.show_flg = 1 and
                    c.del_flg  = 0
                 inner join
                    questions d on
                    a.question_id = d.id and
                    d.del_flg  = 0
                 inner join
                    members e on
                    d.member_id = e.id
               ';

        };
        $sql = $created_sql( $fields );
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
                    $created_sql( array('count(a.id)') ).'
                       where '.(implode(' and ', $where)),
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

    public function update_answer_cnt($pharmacy_id){

        //回答数を更新
        $this->db->exec("
            update
               pharmacies a,
               (
                select 
                  count(id) as cnt,
                  pharmacy_id
                from
                 answers 
                where
                  show_flg = 1 and
                  del_flg  = 0 and
                  pharmacy_id = ?
                ) b
            set    
                a.answer_cnt  = b.cnt
            where
                a.id = b.pharmacy_id
        ", array(
            $pharmacy_id
        ));
    }
    
    //通知既読
    public function notice_read($cert){
        $this->db->exec("
            insert into
                notice_reads(cert) values(?)
            on duplicate key 
                update cert = ?",
            array(
                $cert,
                $cert
            )
        );
    }
    
    //通知一覧
    public function notices($args){
        if(empty($args['pharmacy_id'])){
            trigger_error("Question::answer Error!",E_USER_ERROR);
        }

        return  $this->db->read_many(
            "select 
             a.id as answer_id,
             a.question_id,
             c.nickname,
             'resolved' as stat,
             b.dead_date as date
            from 
             answers a 
            inner join
             questions b on 
             a.question_id = b.id and
             b.dead_stat = 1 and
             b.dead_date > current_timestamp + interval -14 day and
             b.del_flg = 0 and 
             b.show_flg = 1 
            inner join 
             members c on
             b.member_id = c.id
            where
             a.pharmacy_id = ? and 
             a.del_flg = 0 and 
             a.show_flg = 1 and
             not exists(select cert from notice_reads where cert = md5(concat(a.id, a.question_id,'resolved')))
            
            union
            
            select 
             a.id as answer_id,
             a.question_id,
             c.nickname,
             'response' as stat,
             a.res_date  as date
            from 
             answers a 
            inner join
             questions b on 
             a.question_id = b.id and
             b.del_flg = 0 and 
             b.show_flg = 1 
            inner join 
             members c on
             b.member_id = c.id
            where
             a.pharmacy_id = ? and 
             a.res_date > current_timestamp + interval -14 day and
             a.del_flg = 0 and 
             a.show_flg = 1 and
             not exists(select cert from notice_reads where cert = md5(concat(a.id, a.question_id,'response')))
            order by 
              date desc
            limit 30",array(
                $args['pharmacy_id'],
                $args['pharmacy_id']
            )
        );

    }
    
    //指定の薬局会員のすべての回答日を返す
    public function pharmacy_answer_date($args=array())
    {
        $list = $this->db->read_many(
            'select
                question_id,
                created
             from
                answers
             where
                pharmacy_id = ? and
                del_flg = 0
            
            ', array($args['pharmacy_id']));

        $result = array();
        foreach($list as $data){
            $result[ $data['question_id']  ] = $data['created'];
        }
        return $result;

    }
}

