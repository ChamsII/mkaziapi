<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class LoginUser
{

    public function __invoke( Request $request, Response $response )
    {
        
        $checkuser = new UserRepository();
        $crypte = new Crypte();
        $db = new Db();

        $email      = $request->getParam("email");
        $password   = $request->getParam("password") ;

        try{

            $user = $checkuser->checkUserByEmail( $email );

            if( count( $user ) == 0 ) {
                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Aucun utilisateur n'est enregistré avec cet email"
                        )
                    ));
            }else {

                if( $crypte->Decrypte($user[0]->password) == $password ) {
                    $user[0]->password = $password;

                    return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson($user);

                }else {
                    return $response
                    ->withStatus(400)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Mot de passe incorrect !"
                        )
                    ));
                }

            }

        }catch(PDOException $e){
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(
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