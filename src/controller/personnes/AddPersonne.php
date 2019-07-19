<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

class AddPersonne
{

    public function __invoke( Request $request, Response $response )
    {

        $nom                        = $request->getParam("nom");
        $prenom                     = $request->getParam("prenom");
        $autre_appelation           = $request->getParam("autre_appelation");
        $responsabilite_famille     = $request->getParam("responsabilite_famille") ;
        $date_naissance             = $request->getParam("date_naissance");
        $libelle_famille            = $request->getParam("libelle_famille");
        $adresse                    = $request->getParam("adresse");
        $genre                      = intval( $request->getParam("genre") );

        $etat_activite              = $request->getParam("etat_activite");
        $situation_administrative   = intval( $request->getParam("situation_administrative") );
        $profession                 = $request->getParam("profession");
        $en_activite                = intval( $request->getParam("en_activite" ) );
        $numero_tel                 = $request->getParam("numero_tel");
        $adresse_mail               = $request->getParam("adresse_mail");
        $region                     = intval( $request->getParam("region") );
        $photo                      = "avatar.png";

       


        //$checpersonne = new PersonneRepository();
        $db = new Db();

        //On vérifie s'il faut créer une famille 
        /**
         * On vérifie s'il faut créer une nouvelle famille
         * si libelle_famille == 0 , on crée une nouvelle famille qu'on assignera à la personne 
         */
        if( intval( $libelle_famille ) == 0 ) {
            
        }else {
            /***
             * Ce n'est pas une nouvelle famille 
             * On ajute 
             * Et on incremente le nombre de personne dans la famille 
             */
            try{

                

                //On vérifie si il y a image , on fait l'upload 
                if( $request->getUploadedFiles() ) {

                    $uploadedFiles = $request->getUploadedFiles();
                    $uploadedFile = $uploadedFiles['photo'];
                    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                        
                        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                        $basename = bin2hex(random_bytes(8)); 
                        $filename = sprintf('%s.%0.8s', $basename, $extension);
                        $directory = __DIR__ . "/../../../uploads/personnes";
                        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
                        $photo = $filename;

                    }

                }

                $db = $db->connect();
                $statement = "INSERT INTO `personnes` (`nom`, `prenom`, `autre_appelation`, `responsabilite_famille`, `date_naissance`, `libelle_famille`, `adresse`, `genre`, `etat_activite`, `situation_administrative`, `profession`, `en_activite`, `numero_tel`, `adresse_mail`, `photo`, `region`) VALUES (:nom, :prenom, :autre_appelation, :responsabilite_famille, :date_naissance, :libelle_famille, :adresse, :genre, :etat_activite, :situation_administrative, :profession, :en_activite, :numero_tel, :adresse_mail, :photo, :region)";

                $prepare = $db->prepare($statement);
                $prepare->bindParam("nom", $nom);
                $prepare->bindParam("prenom", $prenom);
                $prepare->bindParam("autre_appelation", $autre_appelation);
                $prepare->bindParam("responsabilite_famille", $responsabilite_famille);
                $prepare->bindParam("date_naissance", $date_naissance);
                $prepare->bindParam("libelle_famille", $libelle_famille);
                $prepare->bindParam("adresse", $adresse);
                $prepare->bindParam("genre", $genre);

                $prepare->bindParam("etat_activite", $etat_activite);
                $prepare->bindParam("situation_administrative", $situation_administrative);
                $prepare->bindParam("profession", $profession);
                $prepare->bindParam("en_activite", $en_activite);
                $prepare->bindParam("numero_tel", $numero_tel);
                $prepare->bindParam("adresse_mail", $adresse_mail);
                $prepare->bindParam("photo", $photo);
                $prepare->bindParam("region", $region);

                $resultat = $prepare->execute();
                if($resultat){
                    return $response
                        ->withStatus(200)
                        ->withHeader("Content-Type", 'application/json')
                        ->withJson(array(
                            "text"  => "Personne ajoutée avec succès."
                        ));
                } else {
                    return $response
                        ->withStatus(500)
                        ->withHeader("Content-Type", 'application/json')
                        ->withJson(array(
                            "error" => array(
                                "text"  => "Erreur lors de la création de la personne."
                            )
                        ));
                }


            }catch(PDOException $e){
       
                return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                        "error" => array(
                            "text"  => $e->getMessage(),
                            "code"  => $e->getCode()
                        )
                    )
                );
            }
            $db = null;

        }






    }
}