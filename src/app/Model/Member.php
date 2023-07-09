<?php
namespace Model;

class Member extends Base 
{
    //会員を追加
    public function add($args){
        if(empty($args['nickname']) ||
           empty($args['email'])  ||
           empty($args['password'])  ||
           empty($args['sex'])  ||
           empty($args['pref'])){
            trigger_error("Member::add Error!",E_USER_ERROR);
        }

        return $this->db->insert('members',
            array(
                'nickname' => $args['nickname'],
                'email'    => $args['email'],
                'password' => password_encryption($args['password'], PASSWORD_SALT),
                'birthday' => $args['birthday'],
                'sex'      => $args['sex'],
                'pref'     => $args['pref'],
                'dm_flg'   => (!empty($args['dm_flg']) ? 1 : 0),
            )
        );
    }

    public function update($args){
        if(empty($args['id'])){
            trigger_error("Member::edit Error!",E_USER_ERROR);
        }

       $data = array(
            'id' => $args['id']
       );
       $update_columns 
        = array(
            'nickname',
            'email',   
            'password',
            'birthday',
            'sex',     
            'pref',   
            'dm_flg'  
         );

       foreach($update_columns as $colum){
            if(!isset($args[ $colum ]))continue;
            if( $colum === 'password'){
                $data[ $colum ] = password_encryption( $args[ $colum ], PASSWORD_SALT);
            }
            else if(preg_match('/_flg$/', $colum)){
                $data[ $colum ] =  !empty( $args[ $colum ] ) ? 1 : 0;
            }
            else {
                $data[ $colum ] =  $args[ $colum ];
            }
       }
       $this->db->update('members', $data);
    }
    
    public function has($name, $value, $member_id=0){
      if($name === 'email'){
        $data = $this->db->read(
            'select count(id) from members where email=? and id<>? and del_flg=0', 
            array($value, $member_id)
        );
        return !empty($data) ? true : false;
      }

      if($name === 'nickname'){
        $data = $this->db->read(
            'select count(id) from members where nickname=? and id<>?', 
            array($value, $member_id)
        );
        return !empty($data) ? true : false;
      }
        
    }

    public function login($args=array())
    {
    
        if(empty($args['email']) ||
           empty($args['password'])){
            return false;
        }
        
        return $this->find('first', array(
            'conditions' => $args
        ));
    }

    public function password_check($args=array())
    {
    
        if(empty($args['id']) ||
           empty($args['password'])){
            return false;
        }
        
        return $this->find('first', array(
            'conditions' => $args
        ));
    }
    public function find($type, $args=array())
    {
        $where  = array( '1=1' );
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
                'a.question_cnt',
                'a.del_flg',
            );
        }

        //削除フラグ
        if(!isset($conditions['del_flg'])){
            $where[] = ' a.del_flg = 0';
        }
        else {
           if($conditions['del_flg'] == 0 and $conditions['del_flg'] != ''){
                $where[] = ' a.del_flg = 0';
           }
           else if($conditions['del_flg'] == 1){
                $where[] = ' a.del_flg > 0';
           }
        }
        //会員ID
        if(!empty($conditions['member_id'])){
            $where[] = ' a.id = ?';
            $bind[]  = $conditions['member_id'];
        }
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

        //Birthday
        if(isset($conditions['birthday'])){
            $where[] = ' a.birthday = ?';
            $bind[]  = $conditions['birthday'];
        }

        //Email
        if(isset($conditions['email'])){
            $where[] = ' a.email = ?';
            $bind[]  = $conditions['email'];
        }
        //Password
        if(isset($conditions['password'])){
            $where[] = ' a.password = ?';
            $bind[]  = password_encryption($conditions['password'], PASSWORD_SALT);
        }

        //管理画面からの検索
        if(!empty($conditions['keyword'])){
            $keywords = explode(' ', trim(mb_convert_kana($conditions['keyword'], 's', 'UTF-8')));
            foreach($keywords as $keyword){
                $where[] = ' concat(id,"\t",nickname,"\t",birthday,"\t",sex,"\t",pref,"\t",email)  like ? ';
                $bind[]  = '%'.trim($keyword).'%';
            }
        }

        // SQL実行
        $sql = '';;

        if($type === 'list' ) {
            //退会情報を結合
            $fields[] = 'a.created';
            $fields[] = 'b.updater as withdrawal_updater';
            $fields[] = 'b.reasons as withdrawal_reasons';
            $fields[] = 'b.message as withdrawal_message';
            $fields[] = 'b.created as withdrawal_date';
            $sql = 'select '. implode(',', $fields).
                   ' from members a 
                    left join withdrawals b on
                     a.id = b.member_id 
                   ';
        } else {
            $sql = 'select '. implode(',', $fields).
                   ' from members a ';
        }

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
                   'select count(a.id) from members a
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
    
    //削除
    public function delete($id)
    {
        $this->db->update('members', array('del_flg'=> 1, 'id'=> $id ));
    }

}
