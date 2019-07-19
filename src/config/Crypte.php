<?php


class Crypte {

    private $secretPass;

    public function __construct()
    {
        $this->secretPass = "JD!5?6j_jL@JL5!.65";
    }

    /**
     * Algor de cryptage
     * Il permet de générer des résultat différent de cryptage du même texte
    */
    public function GenerationCle( $Texte )
    {
        $CleDEncryptage = md5( $this->secretPass );
        $Compteur = 0;
        $VariableTemp = "";
        for ( $Ctr = 0; $Ctr < strlen($Texte); $Ctr++ )
        {
            if ( $Compteur == strlen( $CleDEncryptage ) )
                $Compteur = 0;

            $VariableTemp .= substr( $Texte, $Ctr, 1 ) ^ substr( $CleDEncryptage, $Compteur, 1 );
            $Compteur++;
        }

        return $VariableTemp;
    }


    /**
     * Fonction Crypte
     * Prend en paramètre une chaine et retourne une chaine cryptée
    */
    public function Crypte( $Texte )
    {
        srand( (double)microtime()*1000000 );
        $CleDEncryptage = md5(rand(0,32000) );
        $Compteur = 0;
        $VariableTemp = "";

        for ( $Ctr = 0; $Ctr < strlen( $Texte ); $Ctr++ )
        {
            if ( $Compteur == strlen( $CleDEncryptage ) )
                $Compteur = 0;

            $VariableTemp .= substr( $CleDEncryptage, $Compteur, 1 ) . ( substr( $Texte, $Ctr, 1 ) ^ substr( $CleDEncryptage, $Compteur, 1 ) );
            $Compteur++;
        }

        return base64_encode( $this->GenerationCle( $VariableTemp ) );
    }

    /**
     * Fonction Decrypt
     * prend en paramètre la chaine cryptée et retourne la chaine décryptée
    */
    public function Decrypte( $Texte )
    {
        $Texte = $this->GenerationCle( base64_decode( $Texte ) );
        $VariableTemp = "";

        for ($Ctr = 0; $Ctr < strlen( $Texte ); $Ctr++ )
        {
            $md5 = substr( $Texte, $Ctr, 1 );
            $Ctr++;
            $VariableTemp .= ( substr( $Texte, $Ctr, 1) ^ $md5 );
        }

        return $VariableTemp;
    }




}


?>