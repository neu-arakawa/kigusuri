<?php

require_once __DIR__.'/../app.php';

class AppByShell extends App
{
    function __construct($config_file=null)
    {
        if(empty($config_file)){
            if(!empty($_SERVER['USER']) && $_SERVER['USER'] === 'neu'){
                $config_file = 'example';
            }
            else {
                $config_file = 'production';
            }
        }
        $config = include __DIR__."/../app/Config/".$config_file.".php";
        $this->config = $config;
        $this->db     =  new SlimMVC\DB($this->config['database']);
        $this->db->beginTransaction();
    }
    function __destruct() {
        $this->db->commit();
    }
    public function model($name){
        if(empty($this->models[$name] )){
            $class = '\\Model\\'.$name;
            $this->models[$name] = new $class;
            $this->models[$name]->config = $this->config;
            $this->models[$name]->db     = $this->db;
        }
        return $this->models[$name];
    }


    public function send_mail($key='default',$args=[], $params=[])
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);

        $conf = $this->config['mail'][$key];
        if( $key !== 'default'){
            $conf = array_merge(
                $this->config['mail']['default'],
                $this->config['mail'][$key]
            );
        }
        $mail_prams = array_merge(
            $conf,
            $args
        );
        
        $body = '';
        if(empty($args['Body'])){
            // $body = $this->template($key,$params);    
            $loader = new Twig_Loader_Filesystem(__DIR__.'/../app/View');
            $twig = new Twig_Environment($loader);
            $body =  $twig->render('Mail/'.$key.'.twig', $params);
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

