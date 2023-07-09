<?php
namespace Model;

class Member_Withdrawal extends Base 
{
    public $reasons =  [
        '2'  => '悩みや疑問が解決した',
        '4'  => '満足したサービスを得られなかった',
        '8'  => '他の情報サービスを利用するようになった',
        '16' => 'その他',
    ];

    //退会処理
    public function add($args=[])
    {
        if(empty($args['member_id'])){
            trigger_error("Member_Withdrawal Error!",E_USER_ERROR);
        }
        if(!empty($args['reasons']) && !is_array($args['reasons'])){
            trigger_error("Member_Withdrawal Error!",E_USER_ERROR);
        }

        $has = $this->db->read("
            select count(id) from members where id =? and del_flg = 0
        ", $args['member_id']);
        if(!$has){
            trigger_error("Member_Withdrawal Error!",E_USER_ERROR);
        }

        //ログイン情報を無効化
        $this->db->exec("
            update members set del_flg = id
            where id =?
        ", $args['member_id']);

        //既に退会理由がある場合は削除
        $this->db->exec("
            delete from withdrawals where member_id =?
        ", $args['member_id']);

        $bind = []; 
        
        //bit演算
        $args['reasons'] = !empty($args['reasons']) ? 
                           array_sum($args['reasons']) :
                           0;
        foreach(array('member_id','reasons', 'message', 'updater') as $field){
            if(isset($args[$field]))$bind[$field] = $args[$field];
        }
        $this->db->insert( 'withdrawals',  $bind);
    }

    public function find($type, $args=array())
    {
        $where  = array(
            'a.del_flg > 0'
        );
        $fields = array();
        $fields = empty($args['fields']) ?
            array() : $args['fields'];
        $bind   = array();
        $order  = array();
        $conditions = empty($args['conditions']) ?
            array() : $args['conditions'];

        if(!count($fields)){
            $fields = array(
                'a.id',
                'a.nickname',
                'a.email',
                'a.password',
                'a.birthday',
                'a.sex',
                'a.pref',
                'a.dm_flg',
                'b.created_on as withdrawal_date',
            );
        }

        //会員ID
        if(!empty($conditions['id'])){
            $where[] = ' a.id = ?';
            $bind[]  = $conditions['id'];
        }

        //都道府県
        if(!empty($conditions['pref'])){
            $where[] = ' a.pref = ?';
            $bind[]  = $conditions['pref'];
        }

        //DM
        if(isset($conditions['dm_flg'])){
            $where[] = ' a.dm_flg = ?';
            $bind[]  = !empty($conditions['dm_flg']) ? true : false;
        }

        // SQL実行
        $sql = 'select '. implode(',', $fields).
               ' from members a inner join withdrawals b on a.id = b.member_id';
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
                $sql .= ' order by a.id asc';
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

}
