<?php
namespace Model;

class Question_Response extends Base
{
    //レスする
    public function add($type=null, $args){

        if(empty($args['question_id']) ||
           empty($args['answer_id']) ||
           empty($args['content'])){
            trigger_error("Question_Reponse ErrorCode:01", E_USER_ERROR);
        }
        
        if($type === 'member'){
            $has = $this->db->read(
               "select 
                    a.id 
                from 
                    questions a
                inner join
                    answers b on
                    a.id = b.question_id and
                    b.show_flg      = 1 and
                    b.del_flg       = 0
                where 
                    a.id            = ? and 
                    a.member_id     = ? and 
                    a.show_flg      = 1 and
                    a.del_flg       = 0 and
                    a.approval_stat = 1 and
                    a.dead_stat     = 0 and
                    a.response_limit_date < now()  = false and
                    b.res_possible  = 'member' and
                    b.id        = ?
                ",
                array(
                    $args['question_id'],
                    $args['member_id'],
                    $args['answer_id'],
                )
            );
            if(empty($has)){
                trigger_error("Question_Reponse ErrorCode:02", E_USER_ERROR);
            }

            $count = $this->db->read(
               "select count(id) from responses where answer_id = ?",
               array($args['answer_id'])
            );
            $seq = empty($count) ? 1 : $count + 1;

            $id = $this->db->insert('responses',
                array(
                    'answer_id' => $args['answer_id'],
                    'member_id' => $args['member_id'],
                    'content'   => $args['content'],
                    'seq'       => $seq
                )
            );
            
            //レスポンスできる区分を変更
            $this->db->update('answers',
                array(
                    'id'            => $args['answer_id'],
                    'res_date'      => $this->now->format('Y-m-d H:i:s'),
                    'res_possible'  => 'pharmacy',
                )
            );
            return $id;

        }
        else if($type === 'pharmacy'){

            $has = $this->db->read(
               "select 
                    a.id 
                from 
                    questions a
                inner join
                    answers b on
                    a.id = b.question_id and 
                    b.show_flg      = 1 and
                    b.del_flg       = 0
                where 
                    a.id            = ? and 
                    a.show_flg      = 1 and
                    a.del_flg       = 0 and
                    a.approval_stat = 1 and
                    a.dead_stat     = 0 and
                    b.pharmacy_id   = ? and
                    b.res_possible  = 'pharmacy' and
                    b.id            = ?
                ",
                array(
                    $args['question_id'],
                    $args['pharmacy_id'],
                    $args['answer_id'],
                )
            );
            if(empty($has)){
                trigger_error("Question_Reponse ErrorCode:03", E_USER_ERROR);
            }

            $count = $this->db->read(
               "select count(id) from responses where answer_id = ?",
               array($args['answer_id'])
            );
            $seq = empty($count) ? 1 : $count + 1;

            $id = $this->db->insert('responses',
                array(
                    'answer_id'   => $args['answer_id'],
                    'pharmacy_id' => $args['pharmacy_id'],
                    'content'     => $args['content'],
                    'seq'         => $seq
                )
            );

            //レスポンスできる区分を変更
            $this->db->update('answers',
                array(
                    'id'            => $args['answer_id'],
                    'res_date'      => $this->now->format('Y-m-d H:i:s'),
                    'res_possible'  => 'member',
                )
            );

            return $id;
        }
        else {
            trigger_error("Question_Reponse ErrorCode:04", E_USER_ERROR);
        }

        

    }

    //レスする
    public function delete_by_member($args){

        if(empty($args['response_id']) || 
           empty($args['answer_id']) || 
           empty($args['member_id'])){
            trigger_error("Question_Reponse ErrorCode:01", E_USER_ERROR);
        }
        
        // if($type === 'member'){
        $has = $count = $this->db->read(
           "select 
                a.id 
            from 
                questions a
            inner join
                answers b on
                a.id = b.question_id and
                b.show_flg      = 1 and
                b.del_flg       = 0
            inner join
                responses c on
                b.id = c.answer_id and
                c.show_flg      = 1 and
                c.del_flg       = 0
            where 
                c.id            = ? and 
                a.member_id     = ? and 
                a.show_flg      = 1 and
                a.del_flg       = 0 and
                a.approval_stat = 1 and
                a.dead_stat     = 0 and
                b.res_possible  = 'pharmacy' and
                b.id        = ?
            ",
            array(
                $args['response_id'],
                $args['member_id'],
                $args['answer_id']
            )
        );
        if(empty($has)){
            trigger_error("Question_Reponse ErrorCode:02", E_USER_ERROR);
        }

        $this->db->update('responses',
            array(
                'del_flg' => 1,
            ),
            array(
                'id' => $args['response_id']
            )
        );
        
        //レスポンスできる区分を変更
        $this->db->update('answers',
            array(
                'id'            => $args['answer_id'],
                'res_possible'  => 'member',
            )
        );
        // }
        // else if($type === 'pharmacy'){
            // //MEMO：将来的につかうかも？

        // }
        // else {
            // trigger_error("Question_Reponse ErrorCode:04", E_USER_ERROR);
        // }


    }

    public function find($type, $args=array())
    {
        $where  = array(
            'del_flg = 0'
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
            $where[] = ' a.show_flg = ?';
            $bind[]  = !empty($conditions['show_flg']) ? 1 : 0;
        }
        
        //質問ID
        if(!empty($conditions['id'])){
            $where[] = 'a.id = ?';
            $bind[]  = $conditions['id'];
        }

        if(!count($fields)){
            $fields = array(
                'a.id as response_id',
                'a.content as response',
                'a.created as response_date',
            );
        }

        // SQL実行
        $sql = 'select '. 
               implode(',', $fields).
               ' from 
                    responses a
               ';

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
                $sql .= ' order by a.id desc';
            }
            $result = $this->db->read_many($sql, $bind);
            return $result;
        }

    }
    
}

