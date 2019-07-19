<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class GetPersonnes
{

    public function __invoke( Response $response )
    {
        
        $db = new Db();
        try{
            $db = $db->connect();
            $personnes = $db->query("SELECT * FROM personnes ORDER BY nom DESC ")->fetchAll(PDO::FETCH_OBJ);
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson($personnes);
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

