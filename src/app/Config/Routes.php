<?php

function execute_controller($prefix='Admin',$controller, $action, $args=array()){
    $con = '\Controller\\'.ucfirst($prefix).'\\'.ucfirst($controller);
    $con = new $con(array('action'=>$action));
    $con->stash['params'] = $args;
    $con->$action($args);
}

function pharmacy_controller($controller, $action, $args=array()){
    $con = '\Controller\\Front\\'.ucfirst($controller);
    $con = new $con(array('action'=>$action,'member_type'=>'pharmacy'));
    $con->stash['params'] = $args;
    $con->$action($args);
}

//管理画面
$app->group('/admin', function () use ($app) {

    $app->map('/login/', function ($controller='index',$action='login') {
        execute_controller('admin',$controller, $action);
    })->via('GET', 'POST')->name('login');

    $app->map('/logout/', function ($controller='index',$action='logout') {
        execute_controller('admin',$controller, $action);
    })->via('GET')->name('logout');

    $app->map('/top/', function ($controller='index',$action='top'){
        execute_controller('admin',$controller, $action);
    })->via('GET')->name('top');

    //デフォルト
    $app->any('/:controller/(:action/)(:id/)', 
        function ($controller,$action='index', $id=null){
            execute_controller('admin',$controller, $action, array('id'=>$id));
        })->conditions(array('id' => '\d+'))->name('default');
});


//TOPページ
$app->map('/', function () {
    execute_controller('front', 'pages', 'top');
})->via('GET');

//店舗検索
$app->group('/shop', function () use ($app) {

    $prefix     = 'front';
    $controller = 'Pharmacy';

    $app->map('/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'index');
    })->via('GET')->name('shop_index');


    $app->map('/search/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'feature_search_form');
    })->via('GET')->name('feature_search_form');

    //都道府県検索
    $app->map('/search/prefectures/(:pref)/(:addr1/)', function ($pref=null, $addr1=null) use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'search', array('pref'=>$pref, 'addr1' => $addr1,'search_type'=> 'prefectures'));
    })->via('GET')->name('shop_search_prefectures');

    //キーワード検索
    $app->map('/search/keyword/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'search',array('search_type'=> 'keyword'));
    })->via('GET')->name('shop_search_keyword');

    //特長検索
    $app->map('/search/feature/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'search', array('search_type'=> 'feature'));
    })->via('GET')->name('shop_search_feature');

    //現在地
    $app->map('/search/location/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'search', array('search_type'=> 'location'));
    })->via('GET')->name('shop_search_location');

    $app->map('/detail/:code/', function ($code) use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'detail', array('code'=>$code));
    })->via('GET')->name('shop_detail');

});

//相談する
$app->group('/consultation', function () use ($app) {

    $prefix     = 'front';
    $controller = 'Question';

    $app->map('/', function () use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'index');
    })->via( array('GET', 'POST'))->name('qa_top');


    $app->map('/list/(:search_type/:q/)', function ($search_type='',$q='') use ($prefix,$controller ) {
        execute_controller($prefix, $controller, 'search',array(
            'search_type'  => $search_type,
            'q'            => $q,
        ));
    })->via('GET')->conditions(array('search_type' => 'category|keyword|answer'))->name('qa_list');

    $app->map('/detail/:id/', function ($id = '') use ($prefix, $controller ) {
        execute_controller($prefix, $controller, 'detail', array(
            'id'  => $id,
        ));
    })->via('GET')->conditions(array('id' => '\d+'))->name('qa_detail');

    $app->map('/like/answer/', function ($id = '') use ($prefix, $controller ) {
        execute_controller($prefix, $controller, 'like_answer', array(
            'id'  => $id,
        ));
    })->via('POST')->name('like_answer');
});


//会員ページ
$app->group('/member', function () use ($app) {

    $prefix     = 'front';
    $app->map('/login/', function () use ($prefix ) {
        execute_controller($prefix, 'Member_Auth', 'login');
    })->via(array('GET','POST'))->name('member_login');

    $app->map('/logout/', function () use ($prefix ) {
        execute_controller($prefix, 'Member_Auth', 'logout');
    })->via('GET')->name('member_logout');

    $app->map('/regist/', function () use ($prefix) {
        execute_controller($prefix, 'Member_Regist', 'add');
    })->via( array('GET','POST','PUT'))->name('member_regist');

    $app->map('/edit/', function () use ($prefix) {
        execute_controller($prefix, 'Member_Edit', 'index');
    })->via( array('GET','POST','PUT'))->name('member_edit');

    $app->map('/edit/email/', function () use ($prefix) {
        execute_controller($prefix, 'Member_Edit', 'email');
    })->via( array('GET','POST','PUT'))->name('member_edit_email');

    $app->map('/edit/password/', function () use ($prefix) {
        execute_controller($prefix, 'Member_Edit', 'password');
    })->via( array('GET','POST','PUT'))->name('member_edit_password');

    $app->map('/password_forget/(:action/)', function ($action='request') use ($prefix) {
        execute_controller($prefix, 'Member_Reminder', $action);
    })->via( array('GET','POST','PUT'))->name('password_reminder');


    $app->map('/withdrawal/(:action/)', function ($action='index') use ($prefix) {
        execute_controller($prefix, 'Member_Withdrawal', $action);
    })->via( array('GET','POST','PUT'))->name('member_withdrawal');


    $app->any('/consultation/request/(:action/)', function ($action='add') use ($prefix ) {
        execute_controller($prefix, 'Member_Question', $action );
    })->via( array('GET','POST','PUT'))->conditions(array('action' => 'complete'))->name('qa_request');

    $app->any('/consultation/history/(:search_type)(/)', function ($search_type='') use ($prefix) {
        execute_controller($prefix, 'Member_Question', 'history',array(
            'search_type'  => $search_type
        ) );
    })->via( 'GET' )->conditions(array('search_type' => 'has_answer|no_answer'))->name('qa_history');

    $app->any('/consultation/response/', function () use ($prefix ) {
        execute_controller($prefix, 'Member_Question', 'response' );
    })->via( array('POST'))->name('qa_response');

    $app->any('/consultation/response/delete/', function () use ($prefix ) {
        execute_controller($prefix, 'Member_Question', 'response_delete' );
    })->via( array('POST'))->name('qa_response_delete');
    
    //削除する
    $app->any('/consultation/detail/:question_id/delete/(:mode/)', 
        function ($question_id=null,$mode=null) use ($prefix ) {
            $action = 'delete';
            if($mode == 'complete'){
                $action = 'delete_complete';
            }
            execute_controller($prefix, 'Member_Question', $action, array(
                'mode'        => $mode,
                'question_id' => $question_id
            ));
        })
        ->conditions(array('question_id' => '\d+'))
        ->conditions(array('mode' => 'request|complete'))
        ->via( array('GET','POST','PUT'))->name('qa_delete');

    //解決した
    $app->any('/consultation/resolved/', function () use ($prefix ) {
        execute_controller($prefix, 'Member_Question', 'resolved' );
    })->via( array('POST'))->name('qa_resolved');
});

//薬局会員ページ
$app->group('/member.shop', function () use ($app) {

    $app->map('/login/', function () {
        pharmacy_controller('Pharmacy_Auth', 'login');
    })->via(array('GET','POST'))->name('pharmacy_login');

    $app->map('/logout/', function () {
        pharmacy_controller('Pharmacy_Auth', 'logout');
    })->via('GET')->name('pharmacy_logout');

    $app->map('/(:search_type)(/)', function ($search_type='') {
        pharmacy_controller('Pharmacy_Question', 'mypage', array(
            'search_type'  => $search_type
        ));
    })
    ->conditions(array('search_type' => 'no_answer|has_answer|all'))
    ->via(array('GET','POST'))->name('pharmacy_mypage');
    
    $app->map('/:search_type/category/:q/', function ($search_type='', $q) {
        pharmacy_controller('Pharmacy_Question', 'search', array(
            'search_type'  => $search_type,
            'q'            => $q,
        ));
    })
    ->conditions(array('search_type' => 'has_answer|no_answer|all'))
    ->via(array('GET','POST'))->name('pharmacy_qa_search');

    $app->map('/edit/', function () {
        pharmacy_controller( 'Pharmacy_Edit', 'index');
    })->via( array('GET','POST','PUT'))->name('pharmacy_edit');

    $app->any('/consultation/answer/:question_id/(:action/)(:category_id/)', 
        function ($question_id=0,$action='answer',$category_id=null) {
            pharmacy_controller('Pharmacy_Question', $action ,array(
                'question_id' => $question_id,
                'category_id' => $category_id
            ));
        })
        ->via( array('GET','POST','PUT'))
        ->conditions(array('action' => 'complete'))
        ->conditions(array('question_id' => '\d+'))
        ->conditions(array('category_id' => '\d+'))
        ->name('qa_answer');

    $app->any('/consultation/history/', 
        function ($search_type='') {
            pharmacy_controller('Pharmacy_Question', 'history',array(
                // 'search_type'  => $search_type
            ) );
        })
        ->via( 'GET' )
        // ->conditions(array('search_type' => 'has_answer|no_answer'))
        ->name('answer_history');
});

//加盟店募集
$app->map('/contact_shop/', function($action='shop') {
    $c = new \Controller\Front\Contact(array('action'=>$action));
    $c->$action();
})->via('GET','POST','PUT')->name('contact_shop');
