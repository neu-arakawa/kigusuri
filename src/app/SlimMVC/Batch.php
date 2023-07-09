<?php

namespace SlimMVC;

class Batch
{
    public function __construct($config)
    {
        $this->config = $config; 

        $now = new \DateTime();
        $this->now = $now;
    }

    public function __destruct()
    {
    }
    protected $models = array();
    protected function model($name){
        if(empty($this->models[$name] )){
            $class = '\\Model\\'.$name;
            $this->models[$name] = new $class;
            $this->models[$name]->config = $this->config;
            $this->models[$name]->db     = $this->db;
            $this->models[$name]->now    = $this->now;
        }
        return $this->models[$name];
    }
    function config($key=false)
    {
        return empty($key) ? 
            $this->config : 
            $this->config[$key];
    }
    protected function template($tpl=null, $params=[])
    {
        $tpl = $tpl.".twig";
        $twig = $this->twig;
        // $template = $twig->loadTemplate($tpl);
        return $twig->render($tpl, $params);
    }
    function set($key, $value){
        $this->$key = $value;
    }
    protected function send_mail($key='default',$args=[], $params=[])
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);
        $conf = $this->config('mail')[$key];
        if( $key !== 'default'){
            $conf = array_merge(
                $this->config('mail')['default'],
                $this->config('mail')[$key]
            );
        }
        $mail_prams = array_merge(
            $conf,
            $args
        );
        
        $body = '';
        if(empty($args['Body'])){
            $body = $this->template($key,$params);    
            if ( !preg_match('/^(.*?)\n\n(.*)$/s', $body, $mbody)) {
                return false;
            }
              
            $mail_prams['Subject'] = $mbody[1];
            $mail_prams['Body']    = $mbody[2];
        }
        else {
            $mail_prams['Body']    = $args['Body'];
        }

        if(!empty($args['Subject'])){
            $mail_prams['Subject'] = $args['Subject'];
        }

        $message = \Swift_Message::newInstance()
            ->setReturnPath($mail_prams['Return-path'])
            ->setSubject($mail_prams['Subject'])
            ->setTo($mail_prams['To'])
            ->setFrom($mail_prams['From'])
            ->setBody($mail_prams['Body']);

        if(!empty($mail_prams['Bcc'])){
            $message->setBcc($mail_prams['Bcc']);
        }

        $mailer->send($message);
    }

}
