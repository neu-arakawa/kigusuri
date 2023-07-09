<?php
namespace Model;

class Pharmacy extends Base
{
    public $public_mode = false;
    
    //更新するフィールド
    public $update_fields = array(
        // 'id'                 
        'code',
        'image1',
        'name',
        'kana',
        'zip',
        'pref',
        'addr1',
        'addr2',
        'addr3',
        'tel',
        'fax',
        'counseling_time',
        'openhours_note',
        'latlon',
        'tags',
        'email_counseling_flg',
        'email_counseling_url1',
        'email_counseling_url2',
        'reserve_shop_flg',
        'reserve_shop_url1',
        'reserve_shop_url2',
        'topics_flg',
        'insurance_flg',
        'parking_flg',
        'herb_flg',
        'decoction_flg',
        'girlstaff_flg',
        'internetorder_flg',
        'goodinfo_flg',
        'review_flg',
        'join_flg',
        'saturday_open_flg',
        'holiday_open_flg',
        'night_open_flg',
        'del_flg',
        'show_flg',
        'keyword',
        'email',
        'notice_email',
        'serialize',
    );
    public function findById($id, $args=array())
    {
        if(empty($id)){
            return false;
        }
        $add_fields = implode(', ', $this->update_fields);
        $sql = "
            select
                id,
                $add_fields,
                x(latlon) as lat,
                y(latlon) as lon,
                serialize,
                (password is not null) as password_exist
            from
                pharmacies
            where
                id =? and
                del_flg = 0
        ";

        $data = $this->db->read($sql, array($id));
        $data = array_merge(unserialize($data['serialize']), $data);
        unset($data['serialize']);
        return $data;
    }

    public function findByCode($code, $args=array())
    {
        if(empty($code)){
            return false;
        }
        $add_fields = implode(', ', $this->update_fields);
        $sql = "
            select
                id,
                $add_fields,
                x(latlon) as lat,
                y(latlon) as lon,
                insurance_flg,
                parking_flg,
                herb_flg,
                decoction_flg,
                girlstaff_flg,
                internetorder_flg,
                goodinfo_flg,
                night_open_flg,
                saturday_open_flg,
                holiday_open_flg,
                email_counseling_flg,
                reserve_shop_flg,
                serialize
            from
                pharmacies
            where
                code =? and
                del_flg = 0
        ";

        if($this->public_mode){
            $sql .= ' and join_flg = true';
            $sql .= ' and show_flg = true';
        }

        $data = $this->db->read($sql, array($code));
        if(empty($data))return false;
        $data = array_merge(unserialize($data['serialize']), $data);
        unset($data['serialize']);
        return $data;
    }

    public function current_count($args=array())
    {
        $where  = array(
            'del_flg = 0'
        );
        $bind   = array();
        if($this->public_mode){
            $where[] = 'join_flg = true';
            $where[] = 'show_flg = true';
        }

        return $this->db->read(
           'select count(id) from pharmacies
               where '.(implode(' and ', $where)),
            $bind
        );
    }

    public function find($type, $args=array())
    {
        $where  = array(
            'del_flg = 0'
        );
        $fields = array();
        $bind   = array();
        $order  = array();
        if(empty($args['conditions']) || !count($args['conditions'])){
            $args['conditions'] = array();
        }
        //取得項目
        $fields = array(
            'id',
            'code',
            'name',
            'kana',
            'pref',
            'addr1',
            'image1',
            'x(latlon) as lat',
            'y(latlon) as lon',
            'insurance_flg',
            'parking_flg',
            'herb_flg',
            'decoction_flg',
            'girlstaff_flg',
            'internetorder_flg',
            'goodinfo_flg',
            'night_open_flg',
            'saturday_open_flg',
            'holiday_open_flg',
            'email_counseling_flg',
            'reserve_shop_flg',
            'join_flg',
            'answer_cnt',
            'show_flg',
            'email',
            'notice_email',
            'modified'
        );

        $conditions = $args['conditions'];

        //並び替え対応
        if(!empty($args['order']['sort']) && !empty($args['order']['direction'])){
            if($args['order']['sort'] === 'pref'){
                $order[] = ' field(pref,'. '\''.implode('\',\'',$this->config['prefectures']).'\') '.
                    $args['order']['direction'];
            }
            else {
                $order[] = $args['order']['sort'] .' '.$args['order']['direction'];
            }
        }

        if(!empty($conditions['lat']) && !empty($conditions['lon']) ){
            $conditions['latlon'][] = $conditions['lat'];
            $conditions['latlon'][] = $conditions['lon'];
            unset($conditions['lat']);
            unset($conditions['lon']);
        }

        foreach($conditions as $key => $val){
             if(empty($val))continue;
             if(preg_match('/_flg$/',$key)){
                $val = 1;
             }
             switch($key){
                // case 'code':
                    // $where[] = 'code like ?';
                    // $bind[]  = '%'.$val.'%';
                    // break;
                case 'password':
                    $where[] = ' password = ? ';
                    $bind[]  = password_encryption($conditions['password'], PASSWORD_SALT);
                    break;
                case 'name':
                    $where[] = '(name like ? or kana like ? )';
                    $bind[]  = '%'.$val.'%';
                    $bind[]  = '%'.$val.'%';
                    break;
                case 'q':

                    //キーワード 文字複数対応
                    $keywords = explode(' ', trim(mb_convert_kana($val, 's', 'UTF-8')));
                    foreach($keywords as $keyword){
                       $where[] = 'keyword like ? ';
                       $bind[]  = '%'.trim($keyword).'%';
                    }
                    
                    //現状
                    // $where[] = 'keyword like ? ';
                    // $bind[]  = '%'.$val.'%';

                    break;
                case 'latlon':
                    //現在検索(距離[m])
                    $fields[] = "GLength(
                        GeomFromText(CONCAT('LineString(".implode(' ',$conditions['latlon']).",', X(latlon), ' ', Y(latlon),')'))
                    ) *112.12 *1000  AS distance";
                    $order[] = 'distance is null asc ,distance  asc ';
                    break;
                case 'is_notice_email':
                    if( !empty($val) ){
                        $where[] = ' notice_email is not null ';
                    }
                    break;
                default:
                    $where[] = $key.' = ?';
                    $bind[]  = $val;
             }
        }

        //公開モード
        if($this->public_mode){
            $where[] = 'join_flg = true';
            $where[] = 'show_flg = true';
        }

        $sql = 'select '. implode(',', $fields).' from pharmacies';
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
                $sql .= ' order by ';
                $sql .= ' field(pref,'. '\''.implode('\',\'',$this->config['prefectures']).'\'),';
//                $sql .= ' zip asc, ord desc, id desc';
                $sql .= ' kana asc, ord desc, id desc';
            }

            if(!empty($args['page'])){
                $count = $this->db->read(
                   'select count(id) from pharmacies
                       where '.(implode(' and ', $where)),
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
                $result = $this->db->read_many($sql, $bind);
                return $result;
            }

        }
    }
    
    //ログイン
    public function login($args=array())
    {
    
        if(empty($args['loginid']) ||
           empty($args['password'])){
            return false;
        }
        
        return $this->find('first', array(
            'conditions' => array(
                'code'     => $args['loginid'],
                'password' => $args['password']
            )
        ));
    }

    public function save($query)
    {
        
        //更新対象のテーブルを取得
        $fields = $this->update_fields;
        
        //データ成形
        foreach($query as $key => $value){
            if(preg_match('/_flg$/',$key)){
                if(empty($value)){
                    $query[$key] = 0;
                }
                elseif(!preg_match("/^[0-9]{1}+$/", $query[$key])){
                    $query[$key] = 1;
                }
            }
        }

        if(empty($query['email_counseling_flg'])){
            if(!empty($query['email_counseling_url1']) ||
               !empty($query['email_counseling_url2'])){
                $query['email_counseling_flg'] = 1;
            }
        }
        if(empty($query['reserve_shop_flg'])){
            if(!empty($query['reserve_shop_url1']) ||
               !empty($query['reserve_shop_url2'])){
                $query['reserve_shop_flg'] = 1;
            }
        }

        //まとめてシリアル化
        if(empty($query['serialize'] )){
            if(empty($query['access'])){
                $query['access'] = array();
                for ($j = 1; $j <= 5; $j++) {
                    if(!empty($query['access'.$j])){
                        $query['access'][] = $query['access'.$j];
                    }
                    unset($query['access'.$j]);
                }
            }
            if(empty($query['websites'])){
                $query['websites'] = array();
                for ($j = 1; $j <= 20; $j++) {
                    if(!empty($query['website'.$j.'_label']) && !empty($query['website'.$j.'_url'])){
                        $query['websites'][] = array(
                            'label' => $query['website'.$j.'_label'],
                            'url'   => $query['website'.$j.'_url'],
                        );
                    }
                    unset($query['website'.$j.'_label']);
                    unset($query['website'.$j.'_url']);
                }
            }

            if(!isset($query['image2'])){
                $query['image2'] = array();
                for ($j = 1; $j <= 3; $j++) {
                    $query['image2'][] = array(
                        'caption' => $query['image2_'.$j.'_caption'],
                        'path'    => $query['image2_'.$j.'_path'],
                    );
                    unset($query['image2_'.$j.'_caption']);
                    unset($query['image2_'.$j.'_path']);
                }
            }

            foreach(array('mon','tue','wed','thu','fri','sat','sun') as $week ){
                if(!empty($query[$week]) and !is_array($query[$week])){
                    $query[$week] = preg_split("/[\s,]+/", $query[$week]);
                }
            }

            $serialize = array();
            foreach(array(
                'access',
                'websites',
                'mon','tue','wed','thu','fri','sat','sun',
                'image2',
                'goodinfo',
                'word',
                'facebook'
            ) as $key){
                $serialize[$key] = $query[$key];
            }
            $query['serialize'] = serialize($serialize);
        } 

        $id = parent::save(array(
            'table'  => 'pharmacies',
            'fields' =>  $fields,
            'query'  =>  $query,
        ));

        //緯度経度
        if(!empty($query['lat']) && !empty($query['lon'])){
            //緯度経度の登録
            $this->db->exec("
                update
                    pharmacies
                set
                    latlon = GeomFromText(?)
                where
                    id = ?
            ",array("POINT({$query['lat']} {$query['lon']})", $id));
        }

        //営業時間
        $this->db->exec('delete from pharmacy_openhours where pharmacy_id = ?', array($id));
        foreach(array('mon','tue','wed','thu','fri','sat','sun') as $week ){
            if(!empty($query[$week])){
                $seq=1;
                foreach($query[$week] as $_time){
                    $_time = explode('-', $_time);
                    if(count($_time) ===2 ){
                        $this->db->insert('pharmacy_openhours',
                            array(
                                'pharmacy_id'=> $id,
                                'week'       => $week,
                                'seq'        => $seq,
                                'st'         => $_time[0],
                                'ed'         => $_time[1],
                            )
                        );
                    }
                    $seq++;
                }
            }
        }
         
        $query['id'] = $id;

        //タグの関連付け
        $this->update_tags($query);
        
        //キーワードの更新
        $this->update_keyword($query);

        //営業時間フラグ更新
        $update = array();
        $update['id'] = $id;
        $update['saturday_open_flg'] = !empty($query['sat']) ? 1 : 0;
        $update['holiday_open_flg']  = !empty($query['sun']) ? 1 : 0;
        $count = $this->db->read("
            select count(*) from pharmacy_openhours where ed > '18:00' and pharmacy_id = ? ", array($id));
        $update['night_open_flg']    = !empty($count)? 1 : 0;

        $this->db->update('pharmacies', $update);
        
        //パスワード変更
        if(!empty($id) and  !empty($query['password'])){
            $this->db->exec('
                update 
                    pharmacies
                set
                    password = ?
                where
                    id = ?
                ' ,array(
                    password_encryption($query['password'], PASSWORD_SALT),
                    $id
                )
            );
        }

        //1ヶ月放置で関連されていない情報を削除
        $this->db->exec('
            delete from tags where id in (
                select *
                from (
                    select id from tags a
                    where not
                    exists (
                        select b.tag_id
                        from pharmacy_tags b
                        where a.id = b.tag_id
                        group by b.tag_id
                    )
                    and a.modified < subdate( curdate( ) , interval 1
                    month )
                ) as t
            )
        ');
    }
    public function update_tags($query){

        if(!empty($query['tags'])){
            $tags = text2tag_array($query['tags']);
            $this->db->exec('delete from pharmacy_tags where pharmacy_id = ?', array($query['id']));
            $tags = array_unique($tags);
            foreach($tags as $tag){
                $tag_id = $this->db->read('select id from tags where name=?', array($tag));
                if(empty($tag_id)){
                    $tag_id = $this->db->insert('tags', array('name'=> $tag));
                }
                $this->db->insert('pharmacy_tags', array('tag_id'=> $tag_id,'pharmacy_id'=>$query['id']));
            }
            $_query = array('id'=> $query['id'],'tags'=> implode(',', $tags));
            $this->db->update('pharmacies', $_query );

        }

    }

    public function update_keyword($query){

        //キーワードを設定する
        $keyword   = array();
        $keyword[] = $query['name'];
        $keyword[] = $query['kana'];
        $keyword[] = $query['zip'];
        $keyword[] = $query['pref'];
        $keyword[] = $query['addr1'];
        $keyword[] = $query['addr2'];
        $keyword[] = $query['addr3'];
        $keyword[] = $query['openhours_note'];  //営業時間
        $keyword[] = $query['counseling_time']; //相談時間
        foreach($query['access'] as $access){   //アクセス
            $keyword[] = $access;
        }
        foreach($query['websites'] as $site){  //WebSite
            $keyword[] = $site['label'];
        }
        foreach($query['image2'] as $img){  //WebSite
            $keyword[] = $img['caption'];
        }
        $keyword[] = $query['word'];    //ショップからの一言
        $keyword = array_merge(text2tag_array($query['tags']) , $keyword);    //相談の多い分野・症状
        $this->db->exec("
            update
                pharmacies
            set
                keyword = ?
            where
                id = ?
        ",array(implode("\n", $keyword), $query['id']));
    }

    //薬局会員からの更新
    public function save_from_front($query){
        if(empty($query['id'])){
            trigger_error("Pharmacy:save_from_front Error!",E_USER_ERROR);
        }
        
        if(!isset($query['access'])){
            $query['access'] = array();
            for ($j = 1; $j <= 5; $j++) {
                if(!empty($query['access'.$j])){
                    $query['access'][] = $query['access'.$j];
                }
                unset($query['access'.$j]);
            }
        }

        if(!isset($query['image2'])){
            $query['image2'] = array();
            for ($j = 1; $j <= 3; $j++) {
                $query['image2'][] = array(
                    'caption' => $query['image2_'.$j.'_caption'],
                    'path'    => $query['image2_'.$j.'_path'],
                );
                unset($query['image2_'.$j.'_caption']);
                unset($query['image2_'.$j.'_path']);
            }
        }

        $nowdata  = $this->findById($query['id']);
        $serialize = array();
        foreach(array(
            'access',
            'websites',
            'mon','tue','wed','thu','fri','sat','sun',
            'image2',
            'word',
            'facebook'
        ) as $key){
            if(isset($query[$key])){
                $serialize[$key] = $query[$key];
            }
            else {
                $serialize[$key] = $nowdata[$key];
            }
        }
        $query['serialize'] = serialize($serialize);
        //更新
        $this->db->update('pharmacies', array(
            'id'            => $query['id'],
            'image1'        => $query['image1'],
            'notice_email'  => $query['notice_email'],
            'serialize'     => $query['serialize'],
        ));
        //タグの更新
        $this->update_tags($query );
        foreach($query as $key => $value){
            $nowdata[$key] = $value;
        }
        //キーワードの更新
        $this->update_keyword($nowdata );
    }

    //論理削除
    public function delete($id)
    {
        $this->db->query("
            update
                pharmacies
            set
                del_flg = id
            where id = ?
            ",
            array($id)
        );
    }
    public function count_by_city($pref=null){

        $sql = '
            select addr1, pref, min( zip ) , count( id ) as cnt
            from pharmacies';

        if($this->public_mode){
            $sql .= ' where join_flg = true';
            $sql .= ' and show_flg = true';
        }

        $sql .= '
            group by pref, addr1
            order by min( zip ) asc';

        $_cities = $this->db->read_many($sql);

        $cities = array();
        foreach($_cities as $city){
            if(empty($cities[$city['pref']])){
                $cities[$city['pref']] = array();
            }
            $cities[$city['pref']][] = $city;
        }
        if(!empty($pref)){
            return $cities[$pref];
        }

        return $cities;

    }

    public function check_unique_code($code, $id=null){
        $bind = array();
        $bind[] = $code;
        $sql = '
            select
                count(id)
            from
                pharmacies
            where
                del_flg = 0 and
                code    = ?
        ';
        if(!empty($id)){
            $sql .= ' and
                id <> ?
            ';
            $bind[] = $id;
        }
        $count = $this->db->read($sql, $bind);
        return empty($count) ? true : false;
    }
}

/*
SELECT name,
GLength(GeomFromText(CONCAT('LineString(34.613977 135.490286,', X(latlon), ' ', Y(latlon),')'))) *112.12 *1000  AS len
FROM pharmacies
ORDER BY len
メートル

世界系→日本測地系
$conv_lat = ceil(($lat * 1.000106961 – $lon * 0.000017467 – 0.004602017) * 3600000);
$conv_lon = ceil(($lon * 1.000083049 + $lat * 0.000046047 – 0.010041046) * 3600000);

#市区町村のカウント
SELECT pref, addr1, MIN( zip ) , COUNT( id ) AS cnt
FROM pharmacies
GROUP BY pref, addr1
ORDER BY MIN( zip ) ASC
LIMIT 0 , 30

*/
