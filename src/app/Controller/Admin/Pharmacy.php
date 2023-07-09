<?php
namespace Controller\Admin;

class Pharmacy extends \Controller\Admin
{

    public function __construct($args=array())
    {
        parent::__construct($args);

        $this->conditions = array(
            'code'    => $this->request->get('code', null),
            'name'    => $this->request->get('name', null),
            'q'       => $this->request->get('q', null),
            'pref'    => $this->request->get('pref', null),
        );

        $this->order = array(
            'sort'      => $this->request->get('sort', null),
            'direction' => $this->request->get('direction', null)
        );
        
        if(!empty($this->order['sort'])){
            //並び替えを記憶
            $this->app->setCookie( mb_strtolower($this->controller.'_order') , 
                json_encode($this->order), '5 years');
        }
    }
    public function index($args=array()){

        //最終の並び順を復元する
        if(empty($this->order['sort']) && 
            !empty($_COOKIE[mb_strtolower($this->controller.'_order')])){
           $this->order = json_decode($_COOKIE[mb_strtolower($this->controller.'_order')], true); 
        }
        list(
            $this->stash['list'],
            $this->stash['pager']
        )
        = $this->model('Pharmacy')->find('list',
            array(
              'conditions' => $this->conditions,
              'order'      => $this->order,
              'page'       => array(
                  'current' => $this->request->get('page',1),
                  'limit'   => 30
              )
          ));
        $this->stash['query_string'] 
            = http_build_query(array_merge($this->conditions, $this->order));

        $this->render();
    }

    public function add()
    {
        $this->edit();
    }
    public function edit($args=array())
    {
        if($this->app->request->isGet()){
            if( !empty($args['id'])){
                $data  = $this->model('Pharmacy')->findById($args['id']);
                if(!empty($data['websites'])){
                    foreach($data['websites'] as $key => $d){
                        $key++;
                        $data['website'.$key.'_label'] = $d['label'];
                        $data['website'.$key.'_url']   = $d['url'];
                    }
                }

                if(!empty($data['image2'])){
                    foreach($data['image2'] as $key => $d){
                        $key++;
                        $data['image2_'.$key.'_caption']= $d['caption'];
                        $data['image2_'.$key.'_path']   = $d['path'];
                    }
                }
                if(empty($data)){
                    $this->app->notFound();
                }
                
                $week = array('mon','tue','wed','thu','fri','sat','sun');

                foreach ($week as $_week) {
                    if (!empty($data[$_week])) {
                        $data[$_week] = implode("\n", $data[$_week]);
                    }
                }
                
                $data['password'] = '';
                $data['password_confirm'] = '';
                $this->query = $data;
            }
        }
        if($this->app->request->isPost()){
            $error_list = $this->check($this->query);
            if (empty($error_list)) {
                return $this->render('preview');
            }
            else {
                $this->stash['errors'] = $error_list;
                return $this->render('input');
            }
        }

        if($this->app->request->isPut()){
            $error_list = $this->check($this->query);
            if (!empty($error_list)) {
                return $this->render('input');
            }
            else {
                $query = $this->model('Pharmacy')->save($this->query);
                $this->message('info',$this->query['name'].'を更新しました。');

                if(!empty($this->query['referrer'])){
                    $this->app->redirect($this->query['referrer']);
                }else{
                    $this->app->redirect(
                        $this->app->urlFor('default',array('controller'=>'pharmacy'))
                    );
                }
            }
        }

        return $this->render('input');
    }
    public function check($query)
    {
        $errors = array();
        if(empty($query['code'])){
            $errors[] = 'IDを入力してください。';
        }
        elseif(!$this->model('Pharmacy')->check_unique_code($query['code'], $query['id'])){
            $errors[] = 'ID名が重複しています。他のIDを登録してください。';
        }


        if(empty($query['name'])){
            $errors[] = '薬局名を入力してください。';
        }
        if(empty($query['kana'])){
            $errors[] = '薬局名かなを入力してください。';
        }
        if(empty($query['zip'])){
            $errors[] = '郵便番号を入力してください。';
        }
        elseif(!preg_match("/^\d{3}\-\d{4}$/",$query['zip'] )){
            $errors[] = '郵便番号形式が間違っています。(数字3桁-数字4桁)';
        }
        if(empty($query['pref'])){
            $errors[] = '都道府県を入力してください。';
        }
        if(empty($query['addr1'])){
            $errors[] = '住所1を入力してください。';
        }
        if(empty($query['addr2'])){
            $errors[] = '住所2を入力してください。';
        }

        if(empty($query['tel'])){
            $errors[] = '電話番号を入力してください。';
        }
        // elseif(!is_valid_phone_number($query['tel'])){
            // $errors[] = '電話番号形式が間違っています。';
        // }

        $week = array('mon','tue','wed','thu','fri','sat','sun');

        $isOpenhours = false;
        foreach($week as $_week){
            $times = $query[$_week];
            if(empty($times))continue;
            $times = preg_split("/[\s,]+/", $times);
            foreach($times as $time){
                if(!is_openhours($time)){
                    $errors[] = '営業時間の時刻形式が間違っています。['.$_week.']';
                    break;
                }
                $isOpenhours = true;
            }
        }

        // if(empty($isOpenhours)){
            // $errors[] = '営業時間を入力してください。';
        // }

        if(!empty($query['email'])){
            $emails = preg_split("/[\s,]+/", $query['email']);
            foreach($emails as $email){
                if(!is_valid_email($email)){
                    $errors[] = 'メールアドレスを正しく入力してください。';
                    break;
                }
            }
        }
        // else {
            // $errors[] = 'メールアドレスを入力してください。';
        // }

        if(!empty($query['notice_email'])){
            $emails = preg_split("/[\s,]+/", $query['notice_email']);
            foreach($emails as $email){
                if(!is_valid_email($email)){
                    $errors[] = 'メールアドレスを正しく入力してください。';
                    break;
                }
            }
        }

        if(!empty($query['lat']) || !empty($query['lon'])){
            if(!preg_match('/^-?([0-8]?[0-9]|90)\.[0-9]{1,}$/',$query['lat'])){
                $errors[] = '緯度の形式が間違っています。';
            }
            else if(!preg_match('/^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,}$/',$query['lon'])){
                $errors[] = '経度の形式が間違っています。';
            }
        }

        if(!empty($query['reserve_shop_flg']) && $query['reserve_shop_flg'] == 2){
            if(empty($query['reserve_shop_url1']) && empty($query['reserve_shop_url2'])){
                $errors[] = '来店予約のURLを入力してください。';
            }
        }
        if(!isset($query['topics_flg']) || !preg_match('/^(0|1)$/',$query['topics_flg'])) {
            $errors[] = '健康トピックスをお選びください。';
        }

        if(!isset($query['join_flg']) || !preg_match('/^(0|1)$/',$query['join_flg'])) {
            $errors[] = '加盟ステータスをお選びください。';
        }

        if(!isset($query['show_flg']) || !preg_match('/^(0|1)$/',$query['show_flg'])) {
            $errors[] = '公開設定をお選びください。';
        }

        //パスワード
        if( empty($query['id']) || !empty($query['password']) ){
            if(empty($query['password'])){
                $errors['password'] = 'パスワードは必須です。';
            }
            else if($query['password'] !== $query['password_confirm']){
                $errors['password'] = 'パスワードが確認用と異なります。';
            }
        }

        return $errors;
    }

    public function del()
    {
        if($this->app->request->isDelete()){
            $pharmacy = $this->model('Pharmacy')->findById($this->query['id']);
            $this->model('Pharmacy')->delete($this->query['id']);
            $this->message('info',$pharmacy['name'].'を削除しました。');
            $this->app->redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function pager_sort($col, $url)
    {
        $order = array(
            'sort' => $col
        );

        if($col == $this->request->get('sort') && 
                $this->order['direction'] === 'asc'){
           $order['direction'] = 'desc'; 
        }
        else {
           $order['direction'] = 'asc'; 
        }

        $query_string = http_build_query(array_merge($this->conditions, $order));
        return $url.'?'.$query_string;
    }

    public function download(){

        $fields = array(
            'id',
            'code',
            'name',
            'kana',
            'zip',
            'pref',
            'addr1',
            'addr2',
            'addr3',
            'tel',
            'fax',
            'openhours_note', 
            'counseling_time', 
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
            'review_flg',
            'night_open_flg',
            'saturday_open_flg',
            'holiday_open_flg',
            'join_flg',
            'answer_cnt',
            'show_flg',
            'email',
            'notice_email',
            'serialize',
            'email_counseling_flg',
            'email_counseling_url1',
            'email_counseling_url2',
            'reserve_shop_flg',
            'reserve_shop_url1',
            'reserve_shop_url2',
            'topics_flg',
            'tags',
            'modified'
        );

        $data = $this->model('Pharmacy')->find('list',
            array(
              'fields'     => $fields,
              'conditions' => $this->conditions,
              'order'      => array(
			       'sort'      => 'created',
			       'direction' => 'asc' 
			   )

          ));

        foreach($data as $key => $val){

            $options = array_merge(unserialize($val['serialize']));
            if(is_array($options)){
                $data[$key] = array_merge($options,$data[$key]);
            }

            unset($data[$key]['serialize']);
            if(is_array($data[$key]['access'])){
                foreach($data[$key]['access'] as $i => $access){
                    $data[$key]['access'.($i+1)]  = $access;
                } 
                unset($data[$key]['access']);
            }

            $week = array('mon','tue','wed','thu','fri','sat','sun');
            foreach ($week as $_week) {
                if (!empty($data[$key][$_week])) {
                    $data[$key][$_week] = implode("\n", $data[$key][$_week]);
                }
            }

            foreach($data[$key]['websites'] as $i => $d){
                $data[$key]['website'.($i+1).'_label'] = $d['label'];
                $data[$key]['website'.($i+1).'_url']   = $d['url'];
            }
            unset($data[$key]['websites']);
            
            if(!empty($data[$key]['image2'])){
                foreach($data[$key]['image2'] as $i => $d){
                    $data[$key]['image2_'.($i+1).'_caption'] = $d['caption'];
                    $data[$key]['image2_'.($i+1).'_url']     = !empty($d['path']) ?
                        sprintf('http://%s%s', $_SERVER['HTTP_HOST'], $d['path']) :
                        '';
                }
                unset($data[$key]['image2']);
            }

            $data[$key]['image1'] = !empty($data[$key]['image1']) ?
                sprintf('http://%s%s', $_SERVER['HTTP_HOST'], $data[$key]['image1']) :
                '';
            
            $flgs = array(
                // 'reserve_shop_flg',
                'insurance_flg',
                'herb_flg',
                'girlstaff_flg',
                'goodinfo_flg',
                'parking_flg',
                'decoction_flg',
                'internetorder_flg',
                'review_flg',
                'topics_flg'
            );
            foreach($flgs as $flg){
                $data[$key][$flg] = !empty( $data[$key][$flg]  ) ? '有効' : '';
            }
            $data[$key]['reserve_shop_flg'] = !empty($data[$key]['reserve_shop_flg']) && $data[$key]['reserve_shop_flg'] == '2' ? '有効' : '';
            $data[$key]['join_flg'] = !empty( $data[$key]['join_flg']  ) ? '加盟する' : '加盟しない';
            $data[$key]['show_flg'] = !empty( $data[$key]['show_flg']  ) ? '公開する' : '非公開にする';
           	
			$data[$key]['line_no'] = $key + 1;
        } 

        $obj =  new \Util\CSV;
        $obj->filename = sprintf("pharmacy_%s.csv", date("YmdHi")); 
        $obj->header = array(
            'line_no' => 'No',
            'code'    => 'ID',
            'name'    => '薬局名',
            'kana'    => '薬局名かな',
            'image1'  => '画像',
            'zip'     => '郵便番号',
            'pref'    => '都道府県',
            'addr1'   => '住所1',
            'addr2'   => '住所2',
            'addr3'   => '住所3',
            'tel'     => '電話番号',
            'fax'     => 'FAX番号',
            'mon' => '[営業時間] 月', 
            'tue' => '[営業時間] 火', 
            'wed' => '[営業時間] 水', 
            'thu' => '[営業時間] 木', 
            'fri' => '[営業時間] 金', 
            'sat' => '[営業時間] 土', 
            'sun' => '[営業時間] 日', 
            'openhours_note' => '[営業時間] 補足', 
            'counseling_time' => '相談時間', 
            'access1' => '交通アクセス1', 
            'access2' => '交通アクセス2', 
            'access3' => '交通アクセス3', 
            'access4' => '交通アクセス4', 
            'access5' => '交通アクセス5', 
        );
        
        for ($i = 1; $i <= 20; $i++) {
            $obj->header['website'.$i.'_label'] = '[WEBサイト'.$i.'] ラベル';
            $obj->header['website'.$i.'_url']   = '[WEBサイト'.$i.'] URL';
		}
        $obj->header = array_merge( $obj->header ,array(
            'facebook' => 'Facebookページ', 
            'email' => 'メールアドレス', 
            'image2_1_caption' => '[店舗イメージ1] キャプション', 
            'image2_1_url'	   => '[店舗イメージ1] URL', 
            'image2_2_caption' => '[店舗イメージ2] キャプション', 
            'image2_2_url' 	   => '[店舗イメージ2] URL', 
            'image2_3_caption' => '[店舗イメージ3] キャプション', 
            'image2_3_url' 	   => '[店舗イメージ3] URL', 
            'goodinfo' => 'お得情報', 
            'word'     => 'ショップからの一言', 
            'lat'      => '緯度', 
            'lon'      => '経度', 
            'tags'     => '相談の多い分野・症状', 
            'email_counseling_url1' => '[メール相談] PC', 
            'email_counseling_url2' => '[メール相談] SP', 
            'reserve_shop_flg'  => '[来店予約]  完全予約制', 
            'reserve_shop_url1' => '[来店予約] PC', 
            'reserve_shop_url2' => '[来店予約] SP', 
            'topics_flg'        => '健康トピックス', 
            'insurance_flg'     => '保険調剤可',
            'herb_flg'          => 'ハーブの取扱い',
            'girlstaff_flg'     => '女性スタッフ常駐',
            'goodinfo_flg'      => 'お得な情報',
            'parking_flg'       => '駐車場',
            'decoction_flg'     => '煎じ薬取扱い',
            'internetorder_flg' => '通信販売',
            'review_flg'        => 'お客様の声',
            'join_flg'          => '加盟ステータス', 
            'notice_email'      => '通知用メールアドレス', 
            'show_flg'          => '公開設定', 
            'modified'          => '最終更新日', 
        ));
        $obj->rows = $data;
        $obj->dl();
        $this->app->config('debug', 0 );

    }
}
