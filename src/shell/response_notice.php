<?php

require_once __DIR__.'/shell.php';

$app = new AppByShell();

$answers = $app->model('Question_Answer')->find('list',array(
    'conditions'   => array(
        'target_notice' => true,
    )
));

if( count($answers)){
    
    foreach($answers as $answer){
        
        $args =  array(
            'question' => array(
                'id'       =>  $answer['question_id'],
                'response' =>  $answer['response']
            ),
            'pharmacy'  => array(
                'name' => $answer['pharmacy_name']
            ),
            'member'  => array(
                'nickname' => $answer['member_nickname']
            ),
            'base_url'  => SITE_URL
        );

        //事務局へ通知
        $app->send_mail('response_notice_for_admin',
            array(),
            $args
        );
        
        if(empty($answer['pharmacy_email']))continue;
        if(empty($answer['pharmacy_active']))continue;

        //回答者へ通知
        $app->send_mail('response_notice_for_pharmacy',
            array(
                'To' => explode(',',$answer['pharmacy_email'])
            ),
            $args
        );
        
        sleep(1);
    }

}

echo 'ok';

