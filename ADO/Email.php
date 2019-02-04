<?php

    /**
     * Created by PhpStorm.
     * User: Vieiras
     * Date: 30/04/2015
     * Time: 13:17
     */
namespace Inovuerj\ADO;
    class Email extends PHPMailer
    {

        var $subject;
        var $message;
        var $destinatario;
        var $to;
        var $from;
        var $additional_headers;


        public function __construct()
        {

            # Additional headers
            # Para enviar enviar Email com HTML, O Content-type header precisa ser configurado
            $this->additional_headers = 'MIME-Version: 1.0' . "\r\n";
            $this->additional_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//            $headers = "Content-type: text/html; charset=iso-8859-1rn";

//            $this->additional_headers .= 'From: inovuerj@sr2.uerj.br' . "\r\n" .
//                'Reply-To: secretaria.tignp@ime.uerj.br' . "\r\n" .
//                'X-Mailer: PHP/' . phpversion();


            $this->additional_headers .= 'From: emiliojva@gmail.br' . "\r\n" .
                'Reply-To: emilio@sr2.uerj.br' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();


        }

        public function send()
        {
            # Envia o email
            if (mail($this->getTo() , $this->getSubject() , $this->getMessage() , $this->getAdditionalHeaders())) {
                return TRUE;
            }
            return FALSE;
        }


        /**
         * @return mixed
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param mixed $subject
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }

        /**
         * @return mixed
         */
        public function getMessage()
        {
            return $this->message;
        }

        /**
         * @param mixed $message
         */
        public function setMessage($message)
        {
            $this->message = $message;
        }

        /**
         * @return mixed
         */
        public function getDestinatario()
        {
            return $this->destinatario;
        }

        /**
         * @param mixed $destinatario
         */
        public function setDestinatario($destinatario)
        {
            $this->destinatario = $destinatario;
        }

        /**
         * @return mixed
         */
        public function getTo()
        {
            return $this->to;
        }

        /**
         * @param mixed $to
         */
        public function setTo($to)
        {
            $this->to = $to;
        }

        /**
         * @return mixed
         */
        public function getFrom()
        {
            return $this->from;
        }

        /**
         * @param mixed $from
         */
        public function setFrom($from)
        {
            $this->from = $from;
        }

        /**
         * @return mixed
         */
        public function getAdditionalHeaders()
        {
            return $this->additional_headers;
        }

        /**
         * @param mixed $additional_headers
         */
        public function setAdditionalHeaders($additional_headers)
        {
            $this->additional_headers = $additional_headers;
        }


    }