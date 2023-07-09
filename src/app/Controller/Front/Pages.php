<?php
namespace Controller\Front;

class Pages extends \Controller\Front
{
    public function top()
    {

        $conditions = array();
        $conditions['status'] = 'public';
        $this->stash['news'] = $this->model('News')->find('list',
            array(
              'conditions' => $conditions,
               'limit'     => 20
            )
        );
        
        //質問一覧
        $this->stash['questions']  = $this->model('Question')->find('list',
            array(
                'conditions' => array(
                    'approval' => 'ok',
                    'answer'   => true,
                    'show_flg' => true
                ),
                'order' => 'answer_date',
                'limit' => 3
            ) 
        );

        $this->stash['pharmacy_count'] = $this->model('Pharmacy')->current_count();
        $this->stash['pharmacy_news'] = $this->model('Pharmacy_Topic')->get();
        $this->render();
    }
}
