<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class GetPersonById
{

    public function __invoke( Request $request, Response $response )
    {
        
        $id = $request->getAttribute("id");

        try{

            $checpersonne = new PersonneRepository();

            $personnes = $checpersonne->checkAndGetPersonne( $id );

            if( count( $personnes ) == 0 ) {
                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Aucune personne trouvée avec cet id : " . $id
                        )
                    ));
            }else {
                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson($personnes);
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

