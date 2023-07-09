<?php
namespace Model;

class Cert extends Base 
{
    public function add($action, $src=array(), $uni_key=null)
    {
        $token = md5(mt_rand());
        if(!empty($uni_key)){
            $this->db->exec('
                update 
                    certs
                set 
                    del_flg = 1
                where
                    del_flg = 0 and 
                    action  = ? and 
                    uni_key = ?
            ', [$action,$uni_key]);
        }
        $this->db->insert('certs' ,
            [
                'action'    => $action, 
                'token'     => $token,
                'serialize' => serialize($src), 
                'uni_key'   => $uni_key
            ]);
        return $token;
    }

    public function get($action, $token, $delete=true)
    {
        //24時間以内
        $sql = '
            select 
                id,
                serialize
            from
                certs 
            where 
                action  = ? and
                token   = ? and
                del_flg = 0 and
                created > current_timestamp + interval -1 day;
        ';

        $cert = $this->db->read($sql, [$action, $token]);
        if(empty($cert)){
            return false;
        }
        
        if($delete){
            $this->db->exec('
                update 
                    certs
                set 
                    del_flg = 1
                where
                    id = ?
            ', [$cert['id']]);
        }

        return unserialize($cert['serialize']);
    }

    public function clear($action, $token)
    {
        $this->db->exec('
            update 
                certs
            set 
                del_flg = 1
            where
                action  = ? and
                token   = ?
        ',  [$action, $token]);

        return true;
    }
}
