<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;


$app->group('/personnes', function () use ($app) {
    // Version group
    $app->group('/v1', function () use ($app) {
        

        // Liste all users
        $app->get('/personnes', function (Request $request, Response $response) {

            $db = new Db();
            try{
                $db = $db->connect();
                $utilisateurs = $db->query("SELECT * FROM personnes")->fetchAll(PDO::FETCH_OBJ);
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