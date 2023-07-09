<?php
namespace Model;

class Question_Request extends Base
{
    //削除依頼
    public function request($action ,$args=[]){
        if(empty($args['action'])){
            $args['action'] = $action;
        }
        $this->db->insert('requests',$args);
    }
    
    //削除依頼中かどうか
    public function has($action, $args){
            $has = $this->db->read(
               "select 
                    count(question_id)
                from 
                    requests 
                where 
                    action          = ? and 
                    question_id     = ? and 
                    status          = 0
                ",
                array(
                    $action,
                    $args['question_id'],
                )
            );
            return !empty($has) ?  true : false;
    }

    public function response($action, $args){
        $this->db->update('requests',
            array(
                'status'       => empty($args['ok']) ? 1 : 2,
            ),
            array(
                'question_id'  => $args['question_id'],
                'action'       => $action,
                'status'       => 0,
            )
        );
    }
    
    public function find_for_question_id($action){

            $questions = $this->db->read_many(
               "select 
                    question_id
                from 
                    requests 
                where 
                    action          = ? and 
                    status          = 0
                ",
                array(
                    $action
                )
            );
            
            var_dump($questions);

    }
}

