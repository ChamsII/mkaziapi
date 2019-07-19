<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class GetUserById
{

    public function __invoke( Request $request, Response $response )
    {
        
        $id = $request->getAttribute("id");

        try{

            $checkuser = new UserRepository();
            $crypte = new Crypte();

            $user = $checkuser->checkAndGetUser( $id );

            if( count( $user ) == 0 ) {
                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Aucun utilisateur trouvé avec cet id : " . $id
                        )
                    ));
            }else {

                $user[0]->password = $crypte->Decrypte($user[0]->password) ;
                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson($user);
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

    }
}