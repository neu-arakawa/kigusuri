<?php
namespace Model;

class Base extends \SlimMVC\Model
{
    public function __construct()
    {
        $now = new \DateTime();
        $this->now = $now;

        // $now    = $this->now->format('Y-m-d H:i');
    }

    public function next_seq($name=null){
       
       $bind   = array();
       $bind[] = $name;
       $bind[] = $name;
       $this->db->query('
           insert into 
               sequences
               (
                   current_value,
                   name,
                   increment
               ) 
           values 
               (
                   1,
                   ?,
                   1
               ) 
           on duplicate key 
               update 
                   current_value = current_value + increment,
                   name = ?
           ',
           $bind
       );
       return  $this->db->read(
           'select current_value from sequences where name=?',array($name));

    }

}
