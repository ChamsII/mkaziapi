<?php


class Crypte {

    private $email_de; // info@
    private $email_a;
    private $email_type; //Mot de passe oublié , création de compte , suppression de compte , bloquage de compte 
    private $email_object;
    private $email_contenu;

    private $mail_signature;
    private $email_info_user;

    public function __construct( string $email_a, string $email_type)
    {
        $this->email_de = "hacham04";
        $this->email_a = $email_a;
        $this->email_type = $email_type;
    }


    
    public function password(string $nom_user) {

    }

    public function creation(string $nom_user) {

    }

    public function suppression(string $nom_user) {

    }

    public function bloquage(string $nom_user) {

    }

    public function getSignature(string $nom_user) {

    }

    public function getInfoUser(string $nom_user) {

    }

    public function sendEmail(string $nom_user) {

    }



}