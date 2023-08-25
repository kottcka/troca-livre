<?php 

namespace App\Libraries;

use Config\Email;
use Config\Services;

class Mail {

    protected $email;
    protected $template = '';
     
    public function __construct()
    {
        $this->initialize();
    }

    private function initialize()
    {
        $this->email = \Config\Services::email();
        $this->email->initialize([
         'protocol' => 'smtp',
         'SMTPHost' => env('EMAIL_HOST'),
         'SMTPPort' => env('EMAIL_PORT'),
         'SMTPUser' => env('EMAIL_USER'),
         'SMTPPass' => env('EMAIL_PASS'),
         'SMTPCrypto' => '',  // Se você não está usando criptografia, defina isso como uma string vazia
         'SMTPAuth' => true,  // Ative a autenticação SMTP
         'wordwrap' => true,
         'mailType' => 'html',
         'charset' => 'utf-8',
         'SMTPDebug' => true,
     ]);
     
    }

    public function setFrom(array $from)
    {
        $this->email->setFrom($from['email'], $from['name']);
        return $this;
    }

    public function setTo(string $to)
    {
        $this->email->setTo($to);
        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->email->setSubject($subject);
        return $this;
    }

    public function setTemplate(string $template, array $data = [])
    {
        $this->template = view($template, $data);
        $this->email->setMessage($this->template);
        return $this;
    }

    public function send()
    {
        return $this->email->send();
    }
}
