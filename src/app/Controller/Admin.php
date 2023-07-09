<?php

namespace Controller;

class Admin extends \SlimMVC\Controller
{
    public $user_info     = array();
    public $upload_files  = array();
    public $search_fields = array();
    public $default_model = '';
    public function __construct($args=array())
    {
        parent::__construct($args);

        $view = $this->app->view();
        $path = $view->getTemplatesDirectory();
        $view->setTemplatesDirectory($path.'/Admin/');
        if(empty($_SESSION['admin'])){
            $_SESSION['admin'] = array();
        }
        if( $this->controller !== 'Index' and $this->action !=='login' and 
          (empty($_SESSION['admin']['logined'] ) or $_SESSION['admin']['logined'] !== true )){
            $this->location($_SERVER['REQUEST_URI']);
            $this->redirect('/admin/login');
        }

        $this->user_info = $_SESSION['admin'];
    }
    public function location($url=null){
        if(empty($url) && !empty($_SESSION['location'])){
            $url = $_SESSION['location'];
            $_SESSION['location'] = null;
            return $url;
        }
        $_SESSION['location'] = $url;
    }
    //ファイル読み込み
    public function read_files($fields , $data=array('file_img1'=>'file_img1.png')){
        
        $files = array();
        $id  = $data['id'];
        $dir = !empty($this->upload_dir) ? $this->upload_dir : $this->controller;  
        foreach($fields as $field) {
            if(empty($data[$field]))continue;
            $file             = array();
            $file['name']     = $data[$field];
            $file['tmp_name'] = '/files/'.$dir.'/'.$id.'/'.$data[$field];
            $file['mode']     = 'current';
            $file['id']       = $id;
            $files[$field] = $file;
        }

        return $files;
    }

    //アップロード処理(一時)
    public function tmp_upload($fields){
        $files = json_decode($this->query['files'],true);
        $id = null;
        if(!empty($this->query['id'])){
            $id = $this->query['id'];
        }
            
        foreach($fields as $field) {
            if(empty($files[$field]))
                $files[$field] = array();

            if(!empty($this->query[$field.'_delete']) && !empty($files[$field]))
                $files[$field]['mode']     = 'delete';
            
            if(empty($_FILES[$field]['size'])){
                continue;
            }
           
            //アップロードされたファイルの情報を取得
            $fileinfo = pathinfo($_FILES[$field]["name"]);
            //ファイルの拡張子を取得して大文字に変換
            $fileext = $fileinfo["extension"];

            if (!preg_match('/\.pdf$|\.gif$|\.png$|\.jpg$|\.jpeg$|\.bmp$/i', $_FILES[$field]["name"])) {
                $files[$field]['error']  = 'no_type';
                continue;
            }

            if($_FILES[$field]['size'] > (1024*1024*2)){
                $files[$field]['error']  = '2Mを超えるファイルをアップロードできません。';
                continue;
            }

            $temp_file = '/files/tmp/'.time().session_id().'_'.$field.'.'.$fileext;

            $files[$field]  = $_FILES[$field];
            $files[$field]['tmp_name'] = $temp_file;
            $files[$field]['mode']     = 'add';
            $files[$field]['id']       = $id;

            if (!move_uploaded_file($_FILES[$field]['tmp_name'], ROOT.$temp_file )) {
                $files[$field]['error']  = '画像ファイルの一時ファイルを作成できませんでした。';
            }
        }
        return $files;
    }
    
    //アップロード処理
    public function upload( $data, $fields , $model){
        
        $id    = $data['id'];
        $files = $data['files'];

        $dir = !empty($this->upload_dir) ? $this->upload_dir : $this->controller;  
        $update = array();
        foreach($fields as $field) {
            if(empty($files[$field])){
                continue;
            }

            //アップロードされたファイルの情報を取得
            if($files[$field]['mode'] == 'add'){
                //アップロードされたファイルの情報を取得
                $fileinfo = pathinfo($files[$field]["name"]);
                //ファイルの拡張子を取得して大文字に変換
                $fileext = $fileinfo["extension"];

                mkdirr(ROOT.'/files/'.$dir.'/'.$id.'/');
                copy(ROOT.$files[$field]['tmp_name'], ROOT.'/files/'.$dir.'/'.$id.'/'.$field.'.'.$fileext);
                $update[$field] = $field.'.'.$fileext;
            }
            else if($files[$field]['mode'] == 'delete'){
                $update[$field] = '';
            }
        }

        if(count($update)){
            $update['id'] = $data['id'];
            $model->files_save($update);
        }
        
    }


    public function index()
    {
        if(!empty($this->default_model)){
            $model = $this->default_model;
        }
        else {
            $model = $this->controller;
        }
        if(!empty($this->query['clear'])){
            $this->app_session_clear();
        }

        $conditions = array();
        $page = empty($this->query['page']) ? 1 : $this->query['page'];
//        $this->query = $this->app_session();
        
        foreach($this->search_fields as $field){
            if(!empty($this->query[$field])){
                $conditions[$field] = $this->query[$field];
            }
        }
        $this->query['page'] = $page;

        list($this->stash['list'],
            $this->stash['pager']) 
            = $this->model($model)->get_list(array(
                    'conditions' => $conditions,
                    'page'       => array(
                        'current' => $page,
                        'limit'   => 50,
                        'range'   => 10
                    )
                ));
        
        $this->render();
    }
    public function search()
    {
       $this->action = 'index';
       $this->app_session($this->query);
       $this->action('index'); 
    }
    public function del()
    {
        $this->model(ucfirst($this->controller))->delete($this->query['id']);
        $this->message('info','削除しました。');
        $this->app->redirect($_SERVER["HTTP_REFERER"]);
    }

    public function edit($id=null)
    {
        if(!empty($this->default_model)){
            $model = $this->default_model;
        }
        else {
            $model = $this->controller;
        }

        $app   = $this->app;
        $query = $this->query;
        if($app->request->getMethod() === 'GET'){
            if( !empty($id)){
                $data  = $this->model($model)->get_data(array('conditions'=>array( 'id'=> $id ) ));
                if(empty($data)){
                    $app->notFound();
                }
                $this->query = $data;
                $this->query['files'] = $this->read_files($this->upload_files, $data);
            }
        }

        if(empty($query['next'])){
            $this->render('input');
        }
        else if($query['next'] === 'back'){
            $this->query = $this->app_session();
            if(empty($query)){
                $this->message('error','データが失われました');
                $app->redirect(ADMIN_URL.'/'.mb_strtolower($this->controller));
            }
            $this->render('input');
        }
        else if($query['next'] === 'preview'){
            $this->query['files'] = $this->tmp_upload( $this->upload_files );
            $error_list = $this->check($this->query);
            if (empty($error_list)) {
                $this->app_session($this->query);
                $this->render('preview');
            }
            else {
                $this->stash['errors'] = $error_list;
                $this->render('input');
            }
        }
        else if($query['next'] === 'complete'){
            $this->complete();
        }
    }

    public function redirect_index_page(){
        if(empty($this->url_list['list'])){
            $this->app->redirect(ADMIN_URL.'/'.
                mb_strtolower($this->controller));
        }
        else {
            $this->app->redirect(ADMIN_URL.'/'.$this->url_list['list']);
        }
    }

    public function default_model($model){
        $this->default_model = $model;
    }

    public function complete(){
        if(!empty($this->default_model)){
            $model = $this->default_model;
        }
        else {
            $model = $this->controller;
        }
        $query = $this->app_session();
        if(empty($query)){
            $this->message('error','データが失われました');
            $this->redirect_index_page();
        }
        $query = $this->model($model)->save($query);
        // $this->upload( $query, $this->upload_files, $this->model($model) );
        $this->app_session_clear();
        $this->message('info','登録しました。');
        $this->redirect_index_page();
    }

    //キャッシュを作成する
    // public function update_cache(){
        // $daily  = $this->cache('get', 'daily');
        // $data = array(
            // 'rank'  => array()
        // );
        // $data['rank']['courtesy_dividend'] = $this->model('Stockholder_Rank')->get(array(
            // 'conditions' => array(
                // 'target' => 'courtesy_dividend'
            // )
        // )); 
        // $data['rank']['obtain_amount'] = $this->model('Stockholder_Rank')->get([
            // 'conditions' => [
                // 'target' => 'obtain_amount'
            // ]
        // ]); 
        // $data['calendar']= $this->model('Stockholder_Calendar')->get([
            // 'conditions'    =>  [
                // 'month' => date('n').'月'
            // ]
        // ]);
        // $data['updated_on'] = $daily['updated_on'];
        // umask(0);
        // file_put_contents(CACHE_DIR.'/daily.cache', serialize($data));
    // }

}
