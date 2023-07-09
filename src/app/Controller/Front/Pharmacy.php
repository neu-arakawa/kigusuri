<?php
namespace Controller\Front;

class Pharmacy extends \Controller\Front
{
    //インデックスページ
    public function index($args=array()){
        $this->render();
    }
    //検索フォーム
    public function feature_search_form($args=array()){
        $this->stash['cities'] = $this->model('Pharmacy')->count_by_city();
        $this->render();
    }
    //検索
    public function search($args=array()){
        $conditions = array();
        if($args['search_type'] == 'keyword'){
            $conditions['q'] =  $this->request->get('q');
        }else if($args['search_type'] == 'feature'){
            $pharmacy_feature = array_keys($this->app->config('pharmacy_feature'));
            $get_params = $this->request->get();
            foreach($get_params as $key => $val){
                //特長
                if(in_array($key, $pharmacy_feature) && !empty($val)){
                    $conditions[$key] =  $val;
                }
                //都道府県
                else if(in_array($key, array('pref', 'addr1')) && !empty($val)){
                    $conditions[$key] =  $val;
                }
            }
        }else if($args['search_type'] == 'prefectures'){
            if(empty($args['pref'])){
                $this->redirect('/shop/search/feature/');
            }
            if(!in_array($args['pref'],$this->config('prefectures'))){
                $this->app->notFound();
            }
            $conditions['pref'] = $args['pref'];
            if(!empty($args['addr1'])){
                $conditions['addr1'] = $args['addr1'];
            }

            $this->query  = $args;
        }else if($args['search_type'] == 'location'){
            if(empty($this->query['lat']) || empty($this->query['lon'])){
                $this->app->notFound();
            }
            $conditions['lat'] = $this->query['lat'];
            $conditions['lon'] = $this->query['lon'];
        }

        list(
            $this->stash['list'],
            $this->stash['pager']
        )
        = $this->model('Pharmacy')->find('list',
            array(
              'conditions' => $conditions,
              'page'       => array(
                  'current' => $this->request->get('page',1),
                  'limit'   => 50
              )
          ));


        if($args['search_type'] == 'prefectures'){
            if(!empty($args['addr1']) && empty($this->stash['pager']['total_count'])){
                $this->app->notFound();
            }
        }

        //検索フォーム用
        $this->stash['cities'] = $this->model('Pharmacy')->count_by_city();
        $this->stash['query_string']     = http_build_query($conditions);
        // $this->stash['searching_string'] = '"'.implode('" "', array_values($conditions)).'"';
        $this->render();
    }

    //詳細
    public function detail($args=array()){
        $this->stash['data'] =
            $this->model('Pharmacy')->findByCode($args['code']);
        if(empty($this->stash['data'])){
            $this->app->notFound();
        }
        if(!empty($this->stash['data']['facebook'] )){
            $this->stash['data']['websites'][] = array(
                'label' => $this->stash['data']['name'].'のFacebookページ',
                'url'   => $this->stash['data']['facebook'],
            );
        }
        if(!empty( $this->stash['data']['email'] )){
            $emails = preg_split("/[\s,]+/", $this->stash['data']['email']);
            if(count($emails) > 1){
                $this->stash['data']['email'] = $emails[0];
            }
        }

        $this->stash['questions'] 
            = $this->model('Question')->find('list',
                array(
                    'conditions' => array(
                        'pharmacy_id' => $this->stash['data']['id'],
                        'show_flg'    => true
                    ),
                    'limit'   => 5
                ) 
            );

        $this->render();
    }
}
