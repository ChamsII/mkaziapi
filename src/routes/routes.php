<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//use \Psr\Http\Message\UploadedFileInterface as UploadedFile;



$app = new \Slim\App;



$app->group('/api/v1', function () use ($app) {
    

    // Version group
    $app->group('/personnes', function () use ($app) {
        
        // Liste toutes les personnes
        $app->get('/list', function (Request $request, Response $response) {
            $personnes = new GetPersonnes();
            return $personnes( $response );
        });

        //Personne by id  
        $app->get('/personne/{id}', function (Request $request, Response $response) {
            $personnes = new GetPersonById();
            return $personnes( $request, $response );
        });

        //Supprimer une personne by id 
        $app->delete('/personne/{id}', function (Request $request, Response $response) {
            $personne = new PersonneRepository();
            return $personne->deletePersonne( $request, $response );
        });

        //Ajout d'une nouvelle personne
        $app->post('/personne/add', function (Request $request, Response $response) {
            $personne = new AddPersonne();
            return $personne( $request, $response );
        });

    });


    // users
    $app->group('/users', function () use ($app) {

        // Liste all users
        $app->post('/login', function (Request $request, Response $response) {
            $users = new LoginUser();
            return $users( $request, $response );
        });

        // Liste all users
        $app->get('/users', function (Request $request, Response $response) {
            $users = new GetUsers();
            return $users( $response );
        });

        //Utilisateur by id 
        $app->get('/users/{id}', function (Request $request, Response $response) {
            $user = new GetUserById();
            return $user( $request, $response );
        });

        //Nouvel utilisateur
        $app->post('/user/add', function (Request $request, Response $response) {
            $user = new AddUser();
            return $user( $request, $response );
        });

        //Supprimer un utilisateur by id 
        $app->delete('/users/{id}', function (Request $request, Response $response) {
            $user = new UserRepository();
            return $user->deleteUser( $request, $response );
        });

        //Modifier un utilisateur
        $app->put('/user/update/{id}', function (Request $request, Response $response) {
            $user = new UpdateUser();
            return $user( $request, $response );
        });
        $app->post('/user/update/{id}', function (Request $request, Response $response) {
            $user = new UpdateUser();
            return $user( $request, $response );
        });
        

    });


    

});



?>