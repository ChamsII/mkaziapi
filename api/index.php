<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

require '../vendor/autoload.php';
require "../src/config/databases.php";

$app = new \Slim\App;

// Courses Routes...
include "../src/routes/personnes.php";
include "../src/routes/users.php";



$app->run();