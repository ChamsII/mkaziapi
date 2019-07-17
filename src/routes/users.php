<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


$app->group('/api', function () use ($app) {
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




	});
});



?>