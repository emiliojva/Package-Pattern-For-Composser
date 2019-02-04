<?php

/**
 * Created by PhpStorm.
 * User: emilio
 * Date: 28/05/15
 * Time: 20:24
 */
namespace Inovuerj\ADO;
class EmailInovuerj extends PHPMailer
{

    public function __construct()
    {
        # Enviar email confirmando inscri??o
        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');

        //Create a new PHPMailer instance
//        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $this->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $this->Debugoutput = 'html';
        //Set the hostname of the mail server
        $this->Host = 'smtp.gmail.com';
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $this->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $this->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
//        $this->Username = "inovuerj@sr2.uerj.br";
        $this->Username = "legin@ime.uerj.br";
        //Password to use for SMTP authentication
        $this->Password = "legin@123";
        //Set who the message is to be sent from
        $this->setFrom('legin@ime.uerj.br', 'Labor?t?rio de Estudos em Gest?o da Inova??o');

        $this->isHTML(TRUE);


        //Attach an image file
        //$this->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        # Assunto
//        $emailInovuerj->Subject("INOVUERJ - CRIA??O DE PERFIL NO CURSOS E EVENTOS");
        # Set who the message is to be sent to
//        $this->addAddress('emilio@sr2.uerj.br', 'Emilio Vieira');
        # Set an alternative reply-to address
//        $this->addReplyTo('emiliojva@gmail.com', 'Em?lio Vieira');
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$this->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//        $this->msgHTML($msg);

        //Replace the plain text body with one created manually
        //$emailInovuerj->AltBody = 'Departamento e Inova??o da Uerj';
//        $emailInovuerj->setMessage($msg);
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors

//        if (!$mail->send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
//            $obj_inscricao->email_enviado = false;
//        } else {
//            $obj_inscricao->email_enviado = true;
//            echo "Mensagem de email encaminhada para {$array_aluno['email']}";
//
//        }


    }
}