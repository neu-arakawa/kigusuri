<?php
namespace SlimMVC;

class Model //extends \Illuminate\Database\Eloquent\Model
{
    public $date   = null;
    public $config = null;
    public $db     = null;

    public function __construct()
    {
        $this->date = date("Y-m-d");
    }

    function date ($date){
        return $this->date = $date; 
    }

    //pager
    function pager($total_count = NULL, $curpage = 1, $perpage = 50, $range = 10) {

        if(!is_numeric($curpage)){
            $curpage = 1;
        }
        
        if(empty($perpage)){
            $perpage = 50;
        }
        if(empty($range)){
            $range = 5;
        }

        $total_count = ceil($total_count);
        //total_pages
        $total_pages = ceil($total_count / $perpage);
        
        //range
        $range = ($total_pages < $range)? $total_pages : $range;
        
        //start_page
        $start_page = 1;
        if ($curpage >= ceil($range / 2)) {
            $start_page = $curpage - floor($range / 2);
        }
        $start_page = ($start_page < 1)? 1 : $start_page;   // １未満は１にする
        
        //end_page
        $end_page   = $start_page + $range - 1;
        if ($curpage > $total_pages - ceil($range / 2)) {    //最後らへんのページとか
            $end_page   = $total_pages;
            $start_page = $end_page - $range + 1;
        }
        
        //start_cnt
        $start_cnt = ceil($curpage - 1) * $perpage + 1;
        
        //end_cnt
        $end_cnt = ceil($curpage) * $perpage <  $total_count ? ceil($curpage) * $perpage : $total_count;
        
        //prev
        if ($curpage > $start_page) {
            $prev = $curpage - 1;
        } else {
            $prev = NULL;
        }
        
        //next
        if ($curpage < $end_page) {
            $next = $curpage + 1;
        } else {
            $next = NULL;
        }
        
        //offset
        $offset = ceil($curpage - 1) * $perpage;
        
        //limit
        $limit = ($total_count < $perpage)? $total_count : $perpage;
        
        return array(
                    'total_count' => $total_count,
                    'total_pages' => $total_pages,
                    'curpage'     => $curpage,
                    'range'       => $range,
                    'start_page'  => $start_page,
                    'end_page'    => $end_page,
                    'start_cnt'   => $start_cnt,
                    'end_cnt'     => $end_cnt,
                    'prev'        => $prev,
                    'next'        => $next,
                    'offset'      => $offset,
                    'limit'       => $limit
        );
    }

    public function data($args=array())
    {
        return $this->db->read('select * from '.$this->table.' where id=? and active=1', array( $args['id'] ));
    }
    public function delete($id)
    {
        $this->db->update($this->table, array('del_flg'=> $id, 'id'=> $id ));
    }
    public function get_data($args = array()){
        $sql    = $args['sql'];
        $params = $args['bind'];
        
        return $this->db->read($sql, $params);
    }
    public function get_list($args = array()){
        
        $sql    = $args['sql'];
        if(!empty($args['bind'])){
            $bind   = $args['bind'];
        }
        else {
            $bind   = array();
        }
        if(!empty($args['page'])){
            $count = $this->db->read(
                preg_replace('/.+from(.+)/i', 
                             'select count(*) from ${1}', 
                             preg_replace('/\n|\r|\r\n/', '', $sql)),
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
            $result : array($result, $pager);
    }

    public function save($args){
       $table  = $args['table'];
       $fields = $args['fields'];
       $query  = $args['query'];
       $params = array();
       foreach($fields as $field){
            if(isset($query[$field])){
                if(is_array($query[$field])){
                    $params[$field] = implode(',',$query[$field]) ;
                }
                else{
                    $params[$field] = $query[$field] ;
                }

            }
       }
       if(empty($query['id']) ){
            $params['created'] = date('Y-m-d H:i:s');
            $query['id'] = $this->db->insert( $table,  $params);
       }else {
            $params['id'] = $query['id'];
            $this->db->update($table, $params);
       }
       
       return $query['id'];
    }
    public function files_save($query)
    {
        $this->db->update($this->table, $query);
    }
    public function model($name){
        $class = '\\Model\\'.$name;
        $model = new $class;
        $model->config = $this->config;
        $model->db     = $this->db;
        return $model;
    }
}
