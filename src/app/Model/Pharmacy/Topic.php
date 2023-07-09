<?php
namespace Model;

class Pharmacy_Topic extends Base
{
    public function get($args=array())
    {
        $sql = '
            select
            a.name as pharmacy_name,
            a.pref,
            b.date,
            b.title,
            b.link
            from pharmacies a
            inner join pharmacy_topices b on
            a.id = b.pharmacy_id
            where date <= ?
            order by b.date desc limit 0,50
        ';
        return $this->db->read_many($sql, array(date('Y-m-d 23:59:59')));
    }

    public function save($query)
    {
       $data = $this->db->read('select ident from pharmacy_topices where ident=?', array($query['ident']));
       if(!empty($data)){
           //update
           $this->db->update('pharmacy_topices', $query, 'ident');
       }
       else {
           //insert
           $this->db->insert('pharmacy_topices', $query);
       }
    }
}
