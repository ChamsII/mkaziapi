<?php


require "../src/config/Crypte.php";

//Exemple de l'appel aux fonctions Crypte et Decrypte :

$crypte = new Crypte();


$MonTexte = "Mon numéro de carte de crédit est le 445.32.443.12";

$TexteCrypte = $crypte->Crypte( $MonTexte );

$TexteClair = $crypte->Decrypte( $TexteCrypte );

echo "Texte original : $MonTexte <Br>";
echo "Texte crypté : $TexteCrypte <Br>";
echo "Texte décrypté : $TexteClair <Br>";

?>

