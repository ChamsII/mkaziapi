<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//use \Psr\Http\Message\UploadedFileInterface as UploadedFile;


$app = new \Slim\App;


$app->group('/users', function () use ($app) {
    // Version group
    $app->group('/v1', function () use ($app) {
        

        // Liste all users
        $app->get('/users', function (Request $request, Response $response) {

            $db = new Db();
            try{
                $db = $db->connect();
                $utilisateurs = $db->query("SELECT * FROM utilisateur")->fetchAll(PDO::FETCH_OBJ);
                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson($utilisateurs);
            }catch(PDOException $e){
                return $response->withJson(
                    array(
                        "error" => array(
                            "text"  => $e->getMessage(),
                            "code"  => $e->getCode()
                        )
                    )
                );
            }
            $db = null;
        });

        // Liste users by id 
        $app->get('/users/{id}', function (Request $request, Response $response) {

            $db = new Db();
            $id = $request->getAttribute("id");

            try{
                $db = $db->connect();
                $utilisateurs = $db->query("SELECT * FROM utilisateur WHERE id = $id  ")->fetchAll(PDO::FETCH_OBJ);
                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson($utilisateurs);
            }catch(PDOException $e){
                return $response->withJson(
                    array(
                        "error" => array(
                            "text"  => $e->getMessage(),
                            "code"  => $e->getCode()
                        )
                    )
                );
            }
            $db = null;
        });


        // Ajout nouvel utilisateur
        $app->post('/users/add', function (Request $request, Response $response) {
            $nom      = $request->getParam("nom");
            $prenom = $request->getParam("prenom");
            $email      = $request->getParam("email");

            $password      = $request->getParam("password");
            $adresse      = $request->getParam("adresse");
            $telephone      = $request->getParam("telephone");
            $photo      = ""; //$request->getParam("photo");

            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['photo'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                
                $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                // ubah nama file dengan id buku
                //$filename = sprintf('%s.%0.8s', $args["id"], $extension);
                $basename = bin2hex(random_bytes(8)); 
                $filename = sprintf('%s.%0.8s', $basename, $extension);
                $directory = $this->get('upload_directory');
                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
                $photo = $filename;

            }

            $db = new Db();

            try{
                $db = $db->connect();
                $statement = "INSERT INTO courses (nom, prenom, email, password, adresse, telephone, photo) VALUES(:nom, :prenom, :email, :password, :adresse, :telephone, :photo)";
                $prepare = $db->prepare($statement);
                $prepare->bindParam("nom", $nom);
                $prepare->bindParam("prenom", $prenom);
                $prepare->bindParam("email", $email);

                $prepare->bindParam("password", $password);
                $prepare->bindParam("adresse", $adresse);
                $prepare->bindParam("telephone", $telephone);
                $prepare->bindParam("photo", $photo);

                $course = $prepare->execute();
                if($course){
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
                return $response->withJson(
                    array(
                        "error" => array(
                            "text"  => $e->getMessage(),
                            "code"  => $e->getCode()
                        )
                    )
                );
            }
            $db = null;
        });


	});
});



?>