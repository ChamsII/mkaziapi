<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

require '../vendor/autoload.php';
require "../src/config/databases.php";
require "../src/config/Crypte.php";

$app = new \Slim\App;

/***
 * User
*/
require "../src/controller/users/UserRepository.php";
require "../src/controller/users/LoginUser.php";
require "../src/controller/users/GetUsers.php";
require "../src/controller/users/AddUser.php";
require "../src/controller/users/GetUserById.php";
require "../src/controller/users/UpdateUser.php";


require "../src/controller/personnes/PersonneRepository.php";
require "../src/controller/personnes/GetPersonnes.php";
require "../src/controller/personnes/GetPersonById.php";
require "../src/controller/personnes/AddPersonne.php";
//




require "../src/routes/routes.php";


$app->run();