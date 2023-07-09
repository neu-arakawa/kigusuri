<?php
namespace Model;

class Mail_Magazine extends Base 
{

    public $table = 'mail_magazines';
    public function get_list($args =[] ){

        $where  = [
            'active = 1' 
        ];
        $bind  = [];
        $order = [];
        if(!empty($args['conditions'])){
           $conditions = $args['conditions'];
           if(isset($conditions['reserve'])){
                if($conditions['reserve']){
                    $where[]  = 'st is null';
                }
                else {
                    $where[]  = 'st is not null';
                }
           }
           if(isset($conditions['over'])){
                if($conditions['over']){
                    $where[]  = 'reserve <= ?';
                }
                else {
                    $where[]  = 'reserve > ?';
                }
                $bind[] = $this->now->format('Y-m-d H:i');
           }
           if(!empty($conditions['retry'])){
                $where[]  = 'reserve <= ?';
                $bind[] = $this->now->format('Y-m-d H:i');
                $where[]  = 'st is not null';
                $where[]  = 'ed is null';
           }
           if(!empty($conditions['ed'])){
                $where[]  = 'ed is not null';
                $order[]  = 'ed desc';
           }
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
        if(count($order)){
            $sql .= ' order by '.(join(', ', $order));
        }
        $options = [
            'sql'           => $sql, 
            'bind'          => $bind,
        ];
        
        if(!empty($args['page'])){
            $options['page']  = $args['page'];
        }

        return parent::get_list($options);
        
    }

    public function get_data($args = array()){
        $where  = [];
        $bind = [];
        if(empty($args['conditions']) || !count($args['conditions'])){
            return false; 
        }
        $conditions = $args['conditions'];
        if(!empty($conditions['id'])){
             $where[]  = 'id = ?';
             $bind[] = $conditions['id'];
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

        $data = parent::get_data([
            'sql'   => $sql, 
            'bind'  => $bind,
        ]);

        if(!empty($data)){
            $data['reserve'] = substr($data['reserve'],0, -3); 
        }

        return $data;
    }

    public function save($query){
       $fields = [
           'subject',
           'body',
           'reserve',
           'title',
           'st',
           'ed',
       ];
       
       return parent::save([
        'table'  => 'mail_magazines', 
        'fields' =>  $fields, 
        'query'  =>  $query, 
       ]);
    }
    
    //ログ関連
    public function write_log($query){
       $fields = [
           'mail_magazine_id',
           'email',
       ];
       
       return parent::save([
        'table'  =>  'mail_logs', 
        'fields' =>  $fields, 
        'query'  =>  $query, 
       ]);
    }
    public function exist_log($query){

        $count = $this->db->read('
            select 
                count(id)
            from 
                mail_logs 
            where 
                mail_magazine_id = ? and 
                email = ?',
            [
                $query['mail_magazine_id'],
                $query['email']
            
            ]);

        return empty($count) ? false : true;
    }

    public function get_logs( $id ){

        $sql = '
            select 
                * 
            from
                mail_logs
            where 
                mail_magazine_id = ?
        ';

        $bind = [$id];
        $options = [
            'sql'           => $sql, 
            'bind'          => $bind,
        ];

        return parent::get_list($options);
    }
}
