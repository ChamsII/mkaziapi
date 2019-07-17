<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require "../src/config/databases.php";

$app = new \Slim\App;

// Courses Routes...
require "../src/routes/users.php";
require "../src/routes/personnes.php";


$app->run();