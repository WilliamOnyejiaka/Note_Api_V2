<?php
declare(strict_types=1);
ini_set("display_errors",1);
require __DIR__."/../../../../vendor/autoload.php";
require __DIR__."/../../../../config/config.php";

use Lib\Router;
use Lib\Controller;

$auth = new Router("auth",config("allow_cors"));

$auth->post("/",fn() => (new Controller())->public_controller(function($body){
  echo "string";
}));

$auth->run();
?>
