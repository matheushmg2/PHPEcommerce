<?php

namespace Hcode;

use Rain\Tpl;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer  {
	
	const USERNAME = "suporte.personal18@gmail.com";
    const PASSWORD = "GrafiteLoko720";
    const NAME_FROM = "PHP Em Loja";

	private $mail;


	public function __construct($toEndereco, $toNomeDestinatario, $assunto, $tplName, $dados = array()) {

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/Views/Email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/Views-cache/",
			"debug"         => false 
		);

		Tpl::configure( $config );

		$tpl = new Tpl; // atributo

		foreach ($dados as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		$this->mail = new PHPMailer(true);

		$this->mail->isSMTP();

		$this->mail->SMTPDebug = 2;

		$this->mail->Host = 'smtp.gmail.com';

		$this->mail->Port = 587;

		$this->mail->SMTPSecure = 'tls';

		$this->mail->SMTPAuth = true;

		$this->mail->SMTPOptions = array( // Parte em que faz a verificação de envio de e-mail.
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
		));
		
		$this->mail->Username = Mailer::USERNAME;

		$this->mail->Password = Mailer::PASSWORD;

		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

		$this->mail->addAddress($toEndereco, $toNomeDestinatario);

		$this->mail->Subject = $assunto;

		$this->mail->msgHTML(utf8_decode($html));

		$this->mail->AltBody = 'This is a plain-text message body';

		/*if (!$mail->send()) {
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    echo "Message sent!";
		}*/

		// Talvez Terá que trocar a Variavel dessa função em especifica..
		function save_mail($mail) {
		    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

		    $imapStream = imap_open($path, $mail->Username, $mail->Password);

		    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		    imap_close($imapStream);

		    return $result;
		}
	
	}

	public function send(){
		return $this->mail->send();
	}
}

?>