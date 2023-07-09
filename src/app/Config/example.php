<?php

# php 5.4 > php current version
# [] 配列短縮構文

define('PASSWORD_SALT', 'KyYzMffH2u7k847L9xT94bu3Cy7dqM');
define('SITE_URL', 'http://kigusuri_php74.example.test');

return array(
    'debug' => false,
    'database' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'port'      => '3316',
        'database'  => 'kigusuri_php74',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci'
    ),
    'session' => array(
        // 'expires' => '900 minutes',
        // 'path'      => '/',
        // 'secure'    => false,
        // 'httponly'  => true,
        // 'name' => 'neu-worq-sesson',
    ),

    'prefectures'
        => array(
            '北海道',
            '青森県',
            '岩手県',
            '宮城県',
            '秋田県',
            '山形県',
            '福島県',
            '東京都',
            '神奈川県',
            '埼玉県',
            '千葉県',
            '茨城県',
            '栃木県',
            '群馬県',
            '山梨県',
            '新潟県',
            '長野県',
            '富山県',
            '石川県',
            '福井県',
            '愛知県',
            '岐阜県',
            '静岡県',
            '三重県',
            '大阪府',
            '兵庫県',
            '京都府',
            '滋賀県',
            '奈良県',
            '和歌山県',
            '鳥取県',
            '島根県',
            '岡山県',
            '広島県',
            '山口県',
            '徳島県',
            '香川県',
            '愛媛県',
            '高知県',
            '福岡県',
            '佐賀県',
            '長崎県',
            '熊本県',
            '大分県',
            '宮崎県',
            '鹿児島県',
            '沖縄県',
        ),

    'pharmacy_feature'
        => array(
            'insurance_flg' => '保険調剤可',
            'parking_flg'   => '駐車場あり',
            'herb_flg'      => 'ハーブ取扱いあり',
            'decoction_flg' => '煎じ薬取扱いあり',
            'girlstaff_flg' => '女性スタッフ常駐',
            'internetorder_flg' => '通信販売あり',
            'goodinfo_flg'      => 'お得情報あり',
            'night_open_flg'    => '18時以降も対応',
            'saturday_open_flg' => '土曜営業あり',
            'holiday_open_flg'  => '日曜営業あり',
            'email_counseling_flg'  => 'メール相談受付可',
            'reserve_shop_flg'      => '来店予約受付可',
        ),
    //メール送信
    'mail' => array(
        //デフォルト
        'default' => array(
            'From'        => array('info@kigusuri.com' => 'きぐすり.com運営事務局'),
            'Return-path' => 'soudan@kigusuri.com',
            // 'Bcc'         => 'arakawa@n-e-u.co.jp',
        ),

        //会員登録後に送信されるメール
        'member_registered' => array(),

        //パスワード忘れ依頼後に送信されるメール
        'password_forget' => array(),

        //一般会員者の退会後に送信されるメール
        'member_withdrawn' => array(),

        //新規相談登録後の承認依頼メール
        'question_approval_request_for_admin' => array(
            'To' =>  array('soudan@kigusuri.com')  
        ),

        //質問に対して、回答があったときのメール
        'answer_notice' => array(),
        
        //承認NGにした時に相談者へ通知するメール
        'question_ng'   => array(),

        //新着質問に対して、回答を促すメール
        'answer_request_for_pharmacy' => array(),

        //相談者からコメントがあったときのメール
        'response_notice_for_pharmacy' => array(),
        
        //監視(薬局会員の回答)
        'answer_notice_for_admin' => array(
            'To' =>  array('soudan@kigusuri.com')  
        ),

        //監視(相談者コメント)
        'response_notice_for_admin' => array(
            'To' =>  array('soudan@kigusuri.com')  
        ),

        //監視(解決済)
        'question_resolved_for_admin' => array(
            'To' =>  array('soudan@kigusuri.com')  
        ),

        //削除依頼メール
        'question_delete_request' => array(),
        'question_delete_request_for_admin' => array(
            'To' =>  array('soudan@kigusuri.com')  
        ),

        //加盟店募集のお問合せ
        'contact_shop' => array(),
        'contact_shop_for_admin' => array(
            'To' =>  array('support@kigusuri.com')  
        ),

    ),
    //分野一覧
    'question_categories' 
        => array(
            '1' => '頭',
            '2' => '耳・鼻',
            '3' => '目',
            '4' => '口内',
            '5' => 'のど',
            '6' => '首・肩',
            '7' => '胸',
            '8' => 'こころ',
            '9' => '皮膚',
            '10' => '手',
            '11' => '腹',
            '12' => '生殖器(女性)',
            '13' => '生殖器(男性)',
            '14' => '腰',
            '15' => '排便・肛門',
            '16' => '排尿',
            '17' => '胃腸',
            '18' => '関節',
            '19' => '足',
            '20' => '冷え',
            '21' => 'ダイエット',
            '22' => '不妊',
            '23' => 'アレルギー',
            '24' => '神経',
            '25' => '生活習慣',
            '26' => 'アンチエイジング(老化)',
            '27' => '子供',
            '28' => '赤ちゃん',
            '29' => '全身症状',
            '30' => '血液',
            '31' => 'その他',
           ),
    //Google Map
    'google_map' => array(
        'key' => 'AIzaSyCu5td_KOXJ2e9lmzNKv7vYcwGo06CJqA4'
    )
);