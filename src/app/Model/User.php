<?php
namespace Model;

class User extends Base 
{
    public function data($condition=array())
    {
        $where  = array();
        $params = array();
        if(empty($condition['loginid'])){
            return false;
        }
        $where[]  = ' loginid =? ';
        // $where[]  = ' password =? ';
        $params[] = $condition['loginid'];
        // $params[] = $condition['password'];
        
        $sql = 'select * from users where';
        $sql .= join(' and ', $where);

        $data =  $this->db->read($sql, $params);
        if(!$data){
            return false;
        }
        $data['auth'] = json_decode($data['auth']);
        return $data;
    }
}
