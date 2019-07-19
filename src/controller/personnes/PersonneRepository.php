<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class PersonneRepository
{

    public function __construct(){}

    /**
     * Retourner utilisateur par rapport à son id
    */
    public function  checkAndGetPersonne(string $id)
    {
        $db = new Db();

        try{
            $db = $db->connect();

            $query = 'SELECT * FROM `personnes` WHERE `id` = :id';
            $statement = $db->prepare($query);
            $statement->bindParam('id', $id);
            $statement->execute();
            $personnes = $statement->fetchAll( PDO::FETCH_OBJ );

            return $personnes;

        }catch(PDOException $e){
            return array();
        }
        $db = null;

    }

    /**
     * Supprimer une personne
    */
    public function  deletePersonne(Request $request, Response $response )
    {
        $db = new Db();
        $id = $request->getAttribute("id");

        try{

            $db = $db->connect();

            $query = 'DELETE FROM `personnes` WHERE `id` = :id';
            $statement = $db->prepare($query);
            $statement->bindParam('id', $id);
            $resultat = $statement->execute();
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text"  => "Personne supprimée avec succès"
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