<?php
namespace Model;

class News extends Base 
{

    public function find($type, $args=array())
    {
        $now = $this->now->format('Y-m-d H:i');
        $where  = array(
            'del_flg = 0'
        );
        $fields = array();
        $bind   = array();
        $order  = array();
        if(empty($args['conditions']) || !count($args['conditions'])){
            $args['conditions'] = array();
        }
        $conditions = $args['conditions'];
        foreach($conditions as $key => $val){
             switch($key){
                case 'status':
                    $where[]  = "
                        date <= '$now'  and  show_flg = true
                    ";
                    break;
                default:
                    $where[] = $key.' = ?';
                    $bind[]  = $val;
             }
        }
        
        //取得項目
        $fields = array(
            'id',
            'date',
            'title',
            'url',
            'blank_flg',
            'show_flg',
            "CASE WHEN date > '$now' THEN 'before' WHEN show_flg = 0 THEN 'after'  ELSE 'now' END as pub"
        );

        $sql = 'select '. implode(',', $fields).' from news';
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
                $sql .= ' order by date desc, id desc';
            }

            if(!empty($args['page'])){
                $count = $this->db->read(
                            'select count(id) from news where '.(join(' and ', $where)), 
                             $bind
                         );
                $pager = $this->pager($count, $args['page']['current'], $args['page']['limit']) ;
                $sql .= ' limit ?,?';
                $bind[] = $pager['offset'];
                $bind[] = $pager['limit'];
                $result = $this->db->read_many($sql, $bind);
                return array($result, $pager);
            }
            else {
                if(!empty($args['limit'])){
                    $sql .= ' limit ?,?';
                    $bind[] = 0;
                    $bind[] = $args['limit'];
                }
                $result = $this->db->read_many($sql, $bind);
                return $result;
            }

        }
    }

    public function save($query)
    {
       $fields = array(
           'date',
           'title',
           'body',
           'url',
           'blank_flg',
           'show_flg',
       );
       $query['blank_flg'] = !empty($query['blank_flg']) ? 1 : 0;
       $query['show_flg']  = !empty($query['show_flg']) ? 1 : 0;
       parent::save(array(
           'table'  => 'news', 
           'fields' =>  $fields, 
           'query'  =>  $query, 
       ));
       
       return $query;
    }
    public function delete($id)
    {
        $this->db->update('news',array('del_flg'=>1, 'id'=> $id ));
    }
}
