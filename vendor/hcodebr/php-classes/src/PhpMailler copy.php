<?php

namespace Hcode;

use Rain\Tpl;

class PhpMailler {

    const USERNAME = "suporte.personal18@gmail.com";
    const PASSWORD = "GrafiteLoko720";
    const NAME_FROM = "PHP Em Loja";

    private $mail;
    
    public function __construct($toEndereco, $toNomeDestinatario, $assunto, $nomeTemplate, $dados = array())
    {
        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/Views/Email/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/Views-cache/",
            "debug"         => false
        );
        
        Tpl::configure( $config );

        $tpl = new Tpl;
        
        foreach ($dados as $key => $value) {
            $tpl->assign($key, $value);
        }

        $html = $tpl->draw($nomeTemplate, true);

        $this->mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        $this->mail->isSMTP();

        //$this->mail->SMTPDebug = 2;
        $this->mail->Debugoutput = 'html';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = PhpMailler::USERNAME;
        $this->mail->Password = PhpMailler::PASSWORD;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        $this->mail->setFrom(PhpMailler::USERNAME, PhpMailler::NAME_FROM);
        
        $this->mail->addAddress($toEndereco, $toNomeDestinatario);

        $this->mail->msgHTML(utf8_decode($html));
        $this->mail->Subject = $assunto;


        $this->mail->AltBody = 'This is a plain-text message body';
        
        // Talvez Terá que trocar a Variavel dessa função em especifica..
    function save_mail($mail) {
        $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

        $imapStream = imap_open($path, $mail->Username, $mail->Password);

        $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
        imap_close($imapStream);

        return $result;
    }


    }

    public function send()
    {

        return $this->mail->send();

    }
}