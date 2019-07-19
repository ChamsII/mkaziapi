<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class UserRepository
{

    public function __construct(){}

    /**
     * Vérifier si un email existe
     * cette fonction est appélée à l'authentification , l'inscription et à la modification d'un utilisateur
    */
    public function  checkUserByEmail(string $email)
    {
        $db = new Db();

        try{
            $db = $db->connect();

            $query = 'SELECT * FROM `utilisateur` WHERE `email` = :email';
            $statement = $db->prepare($query);
            $statement->bindParam('email', $email);
            $statement->execute();
            $user = $statement->fetchAll( PDO::FETCH_OBJ );

            return $user;

        }catch(PDOException $e){
            return array();
        }
        $db = null;

    }


    /**
     * Retourner utilisateur par rapport à son id
    */
    public function  checkAndGetUser(string $id)
    {
        $db = new Db();

        try{
            $db = $db->connect();

            $query = 'SELECT * FROM `utilisateur` WHERE `id` = :id';
            $statement = $db->prepare($query);
            $statement->bindParam('id', $id);
            $statement->execute();
            $user = $statement->fetchAll( PDO::FETCH_OBJ );

            return $user;

        }catch(PDOException $e){
            return array();
        }
        $db = null;

    }


    /**
     * Supprimer un utilisateur
    */
    public function  deleteUser(Request $request, Response $response )
    {
        $db = new Db();
        $id = $request->getAttribute("id");

        try{

            $db = $db->connect();

            $query = 'DELETE FROM `utilisateur` WHERE `id` = :id';
            $statement = $db->prepare($query);
            $statement->bindParam('id', $id);
            $resultat = $statement->execute();
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text"  => "Utilisateur supprimé avec succès"
                ));

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

    }



}


