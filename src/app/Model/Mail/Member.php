<?php
namespace Model;

class Mail_Member extends Base 
{


    public function get_list($args = []){
        
        $bind = [];
        $where  = [
            'active = 1' 
        ];
        $where[]  = 'email is not null';
        $where[]  = 'delivery = 1';
        $where[]  = 'active   = 1';
    
        if(!empty($args['conditions']['email'])){
            $where[] = 'email like ?';
            $bind[]  = '%'.$args['conditions']['email'].'%';
        }


        $sql = '
            select 
                max(id) as id,
                email
            from
                members
        ';
        
        $count_sql = 'select count( distinct email ) from members';
        if(count($where)){
            $sql .= ' where '.(join(' and ', $where));
            $count_sql .= ' where '.(join(' and ', $where));
        }
        
        $sql .=  ' group by email';

        if(!empty($args['page'])){
            $count = $this->db->read(
                $count_sql,
                $bind);
            $pager = $this->pager($count, $args['page']['current'], $args['page']['limit']) ;
        }
        if(!empty($pager)){
            $sql .= ' limit ?,?';
            $bind[] = $pager['offset'];
            $bind[] = $pager['limit'];
        }
        $result = $this->db->read_many($sql, $bind);
        return empty($pager) ? 
            $result :[$result, $pager];
        
    }
    // public function get_list($args = []){

        // $where  = [
            // 'active = 1' 
        // ];
        // $bind = [];
        // if(!empty($args['conditions'])){
           // $conditions = $args['conditions'];
           // if(!empty($conditions['email'])){
                // $where[]  = 'email like ?';
                // $bind[]   = '%'.$conditions['email'].'%';
           // }
        // }
        // $sql = '
            // select 
                // * 
            // from
                // mail_members
        // ';

        // if(count($where)){
            // $sql .= ' where '.(join(' and ', $where));
        // }

        // $options = [
            // 'sql'           => $sql, 
            // 'bind'          => $bind,
        // ];
        
        // if(!empty($args['page'])){
            // $options['page']  = $args['page'];
        // }

        // return parent::get_list($options);
        
    // }

    public function get_data($args = array()){
        $where= [];
        $bind = [];
        if(empty($args['conditions']) || !count($args['conditions'])){
            return false; 
        }
        $conditions = $args['conditions'];
        if(!empty($conditions['id'])){
             $where[]  = 'id = ?';
             $bind[] = $conditions['id'];
        }
        if(!empty($conditions['member_id'])){
             $where[]  = 'member_id = ?';
             $bind[] = $conditions['member_id'];
        }

        $sql = '
            select 
                * 
            from
                mail_magazines 
        ';
        if(count($where)){
            $sql .= ' where '.(join(' and ', $where));
        }
        return parent::get_data([
            'sql'   => $sql, 
            'bind'  => $bind,
        ]);
    }
    public function invalid($args){
        $bind  = explode(',',$args['id']);
        $where = implode(', ', array_fill(0, count($bind), '?'));
        $this->db->query("
            update 
                mail_members
                set active = - id
                where id in($where)
            ",
            $bind
        );
    }
    // public function save($query){
       // $fields = [
           // 'title',
           // 'body',
           // 'reserve',
           // 'title',
           // 'st',
           // 'ed',
       // ];
       
       // return parent::save([
        // 'table'  => 'mail_magazines', 
        // 'fields' =>  $fields, 
        // 'query'  =>  $query, 
       // ]);
    // }
}
