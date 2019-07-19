<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

class UpdateUser
{

    public function __invoke( Request $request, Response $response )
    {
        $crypte = new Crypte();
        $checkuser = new UserRepository();

        $id = $request->getAttribute("id");


        if( $id ){

            $nom      = $request->getParam("nom");
            $prenom = $request->getParam("prenom");
            $email      = $request->getParam("email");
            $password      = $crypte->Crypte( $request->getParam("password") );
            $adresse      = $request->getParam("adresse");
            $telephone      = $request->getParam("telephone");
            $niveau      = $request->getParam("niveau");
            $photo      = "";
            
            //Vérification si le e-mail n'est pas enregistré avce un autre compte
            $user = $checkuser->checkUserByEmail( $email );

            if( count( $user ) > 0 && $user[0]->id != $id ) {
                //Erreur 
                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Un autre compte existe avec cet e-mail"
                        )
                    ));
            }else{

                try {

                    $db = new Db();
                    //On vérifie si il y a image , on fait l'upload 
                    if( $request->getUploadedFiles() ) {

                        $uploadedFiles = $request->getUploadedFiles();
                        $uploadedFile = $uploadedFiles['photo'];
                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            
                            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                            $basename = bin2hex(random_bytes(8)); 
                            $filename = sprintf('%s.%0.8s', $basename, $extension);
                            $directory = __DIR__ . "/../../../uploads/users";
                            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
                            $photo = $filename;

                            if( $uploadedFile ) {
                                unlink( $directory . DIRECTORY_SEPARATOR . $user[0]->photo );
                            }

                        }

                    }
                    

                    $db = $db->connect();

                    if( $photo == "" ){
                        $statement = "UPDATE `utilisateur` SET `nom` = :nom, `prenom` = :prenom, `email` = :email, `password` = :password, `adresse` = :adresse, `telephone` = :telephone, niveau = :niveau WHERE `id` = $id ";
                        $prepare = $db->prepare($statement);
                    }else{
                        $statement = "UPDATE `utilisateur` SET `nom` = :nom, `prenom` = :prenom, `email` = :email, `password` = :password, `adresse` = :adresse, `telephone` = :telephone, `photo` = :photo, niveau = :niveau WHERE `id` = $id ";
                        $prepare = $db->prepare($statement);
                        $prepare->bindParam("photo", $photo);
                    }
                    $prepare->bindParam("nom", $nom);
                    $prepare->bindParam("prenom", $prenom);
                    $prepare->bindParam("email", $email);
                    $prepare->bindParam("password", $password);
                    $prepare->bindParam("adresse", $adresse);
                    $prepare->bindParam("telephone", $telephone);
                    $prepare->bindParam("niveau", $niveau);

                    $resultat = $prepare->execute();
                    if($resultat){
                        return $response
                            ->withStatus(200)
                            ->withHeader("Content-Type", 'application/json')
                            ->withJson(array(
                                "text"  => "Utilisateur modifié avec succès."
                            ));
                    } else {
                        return $response
                            ->withStatus(500)
                            ->withHeader("Content-Type", 'application/json')
                            ->withJson(array(
                                "error" => array(
                                    "text"  => "Erreur lors de la modification de l'utilisateur."
                                )
                            ));
                    }


                }catch(PDOException $e){
                    return $response
                        ->withStatus(500)
                        ->withHeader("Content-Type", 'application/json')
                        ->withJson( array(
                                "error" => array(
                                    "text"  => $e->getMessage(),
                                    "code"  => $e->getCode()
                                )
                            )
                        );
                }
                $db = null;

            }
            

        }else{
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "error" => array(
                        "text"  => "id utilisateur obligatoire"
                    )
                ));
        } //End if id


    } //End function 

}

