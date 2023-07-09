<?php
namespace Controller\Front;

class Pharmacy_Edit extends \Controller\Front
{
    public $auth_pages = array('index');
    public $non_member_pages = array();

    public function index()
    {
        $data = $this->model('Pharmacy')->findById($this->member_id);
        foreach($data['image2'] as $key => $d){
            $key++;
            $data['image2_'.$key.'_caption']= $d['caption'];
            $data['image2_'.$key.'_path']   = $d['path'];
        }
        $this->stash['pharmacy'] = $data;
        //初期表示
        if($this->app->request->isGet()){
            $this->query = $data;
        }
        //完了画面
        else if($this->app->request->isPut()){
            $this->files_init();
            $error_list = $this->check();
            if (empty($error_list)) {
                $this->query['id'] = $this->member_id;
                $this->files_upload();
                $this->model('Pharmacy')->save_from_front($this->query);
                $this->app_session('pharmacy_edit', true);
                $this->app->redirect($this->app->urlFor('pharmacy_edit'));
            }
            else {
                $this->stash['errors'] = $error_list;
            } 
        }
        
        $this->render();
    }

    public function check()
    {
        $errors = [];
        if(empty($this->query['notice_email'])){
            $errors['notice_email'] = 'メールアドレスを入力してください。';
        }else{
            $emails = preg_split("/[\s,]+/", $this->query['notice_email']);
            foreach($emails as $notice_email){
                if(!is_valid_email($notice_email)){
                    $errors['notice_email'] = 'メールアドレスを正しく入力してください';
                    break;
                }
            }
        }

        if(empty($this->query['image1'])){
            $errors['image1'] = '店舗外観画像を登録してください';
        }
        else if(!empty($this->files['image1']) && empty($this->files['image1']['is_image'])){
            $errors['image1'] = '店舗外観画像を正しく登録してください';
        }

        return  $errors;
    }

    
    public $files = array();
    public function files_init()
    {
        foreach($_FILES as $key => $data){
            if(empty($data['tmp_name']))continue;
            $type = exif_imagetype($data['tmp_name']);
            $extension = false;
            switch($type){
                case IMAGETYPE_GIF:
                    $extension = 'gif';
                    break;
                case IMAGETYPE_JPEG:
                    $extension = 'jpg';
                    break;
                case IMAGETYPE_PNG:
                    $extension = 'png';
                    break;
            }
            
            if(!empty($extension)){
                $data['is_image'] = true;
            }
            else {
                $data['is_image'] = false;
            }
            $data['extension'] = $extension;

            //先頭の1文字カット
            $key = mb_substr( $key , 1 , mb_strlen($key) - 1 );
            $this->files[$key] = $data;

            $this->query[$key] = '/files/shop/'.$this->member_id.'/'.$key.'.'.$data['extension'];
        }

    }

    public function files_upload()
    {
        foreach($this->files as $key => $data){
            if(empty($data['is_image']))continue;
            $dir = WWW_ROOT.'/files/shop/'.$this->member_id.'/'; 
            if( !file_exists($dir)){
                mkdir($dir, 0777);
            }
            if (move_uploaded_file($data["tmp_name"], WWW_ROOT.$this->query[$key])) {
                chmod( WWW_ROOT.$this->query[$key], 0644);
            } else {
                trigger_error("ファイルアップロード中にエラーがありました",E_USER_ERROR);
            }
        }
    }
}
