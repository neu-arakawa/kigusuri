<?php

require_once __DIR__.'/shell.php';

$app = new AppByShell();

$questions = $app->model('Question')->find('list',array(
    'conditions'   => array(
        'target_notice' => true,
    )
));

if( count($questions)){

    //公開している薬局のみ
    $app->model('Pharmacy')->public_mode = true;
    $pharmacies = $app->model('Pharmacy')->find('list',array(
        'conditions'   => array(
            'is_notice_email' => true
        )
    ));
    
    foreach($pharmacies as $pharmacy){
        if(empty($pharmacy['notice_email']))continue;

        //薬局会員へメール通知
        $app->send_mail('answer_request_for_pharmacy',
            array(
                'To' => explode(',',$pharmacy['notice_email'])
            ),
            array(
                'questions' => $questions,
                'pharmacy'  => array(
                    'name'  => $pharmacy['name'],
                ),
                'base_url'  => SITE_URL
            )
        );
        sleep(1);
    }

}

echo 'ok';

