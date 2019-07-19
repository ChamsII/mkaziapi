<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

class AddUser
{

    public function __invoke( Request $request, Response $response )
    {
        $crypte = new Crypte();

        $nom        = $request->getParam("nom");
        $prenom     = $request->getParam("prenom");
        $email      = $request->getParam("email");
        $password   = $crypte->Crypte( $request->getParam("password") );
        $adresse    = $request->getParam("adresse");
        $telephone  = $request->getParam("telephone");
        $niveau     = $request->getParam("niveau");
        $photo      = "avatar.png";

        $checkuser = new UserRepository();
        $db = new Db();
        

        if( count( $checkuser->checkUserByEmail( $email ) ) > 0 ) {
            return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Un utilisateur est enregistré avec cet e-mail. "
                        )
                    ));
        }else {

            try{

                $uploadedFiles = $request->getUploadedFiles();
                $uploadedFile = $uploadedFiles['photo'];
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    
                    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                    $basename = bin2hex(random_bytes(8)); 
                    $filename = sprintf('%s.%0.8s', $basename, $extension);
                    $directory = __DIR__ . "/../../../uploads/users";
                    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
                    $photo = $filename;

                }

                $db = $db->connect();
                $statement = "INSERT INTO utilisateur (nom, prenom, email, password, adresse, telephone, photo, niveau) VALUES(:nom, :prenom, :email, :password, :adresse, :telephone, :photo, :niveau)";
                $prepare = $db->prepare($statement);
                $prepare->bindParam("nom", $nom);
                $prepare->bindParam("prenom", $prenom);
                $prepare->bindParam("email", $email);
                $prepare->bindParam("password", $password);
                $prepare->bindParam("adresse", $adresse);
                $prepare->bindParam("telephone", $telephone);
                $prepare->bindParam("photo", $photo);
                $prepare->bindParam("niveau", $niveau);

                $resultat = $prepare->execute();
                if($resultat){
                    return $response
                        ->withStatus(200)
                        ->withHeader("Content-Type", 'application/json')
                        ->withJson(array(
                            "text"  => "Utilisateur crée avec succès."
                        ));
                } else {
                    return $response
                        ->withStatus(500)
                        ->withHeader("Content-Type", 'application/json')
                        ->withJson(array(
                            "error" => array(
                                "text"  => "Erreur lors de la création de l'utilisateur."
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