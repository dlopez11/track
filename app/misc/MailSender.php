<?php
namespace Sigmamovil\Misc;

$path =  \Phalcon\DI\FactoryDefault::getDefault()->get('path');
require_once "{$path->path}app/library/swiftmailer/lib/swift_required.php";

class MailSender
{
    public $session;
    public $logger;
    public $data;
    public $html;
    public $plainText;
    public $mta;
    
    public function __construct() 
    {
        $this->session = \Phalcon\DI::getDefault()->get('session');
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
        $this->mta = \Phalcon\DI::getDefault()->get('mta');
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setHtml($html)
    {
        $this->html = $html;
    }
    
    public function setPlainText($plainText)
    {
        $this->plainText = $plainText;
    }
    
    public function sendBasicMail()
    {
        $headers = "From: {$this->data->fromName} <{$this->data->fromEmail}> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        foreach ($this->data->target as $to) {
            $sent = mail($to, $this->data->subject,  $this->html, $headers);
            if (!$sent) {
                $this->logger->log("El correo con destino a {$to} no pudo ser envíado");
                $this->logger->log("Subject: {$this->data->subject}");
                $this->logger->log("Headers: {$headers}");
            }
        }
    }
    
    public function sendMessage()
    {
        $transport = \Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
        $swift = \Swift_Mailer::newInstance($transport);

        $message = new \Swift_Message();
        
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-GreenArrow-MailClass', 'SIGMA_NEWEMKTG_DEVEL');

        $message->setSubject($this->data->subject);
        $message->setFrom(array($this->data->fromEmail => $this->data->fromName));
        $message->setBody($this->html, 'text/html');
        $message->addPart($this->plainText, 'text/plain');
        
        foreach ($this->data->target as $to) {
            $message->setTo($to);
            $this->logger->log("Preparandose para enviar mensaje a: {$to}");
            $recipients = $swift->send($message, $failures);

            if ($recipients){
                Phalcon\DI::getDefault()->get('logger')->log('Recover Password Message successfully sent!');
            }
            else {
                throw new Exception('Error while sending message: ' . $failures);
            }
        }
    }
}