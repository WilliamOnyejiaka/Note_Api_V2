<?php
declare(strict_types=1);
ini_set("display_errors",1);
require __DIR__."/../../../../vendor/autoload.php";
require __DIR__."/../../../../config/config.php";

use Lib\Router;
use Lib\Controller;
use Lib\Database;
use Model\User;
use Lib\Response;
use Lib\Validator;
use Lib\Serializer;

$auth = new Router("auth",config("allow_cors"));

$auth->post("/sign_up",fn() => (new Controller())->public_controller(function($body){
  $validator = new Validator();
  $validator->validate_body($body, ['name','email','password']);
  $validator->validate_email_with_response($body->email);
  $validator->validate_password_with_response($body->password, 5);
  $user = new User((new Database(config("host"),config("username"),config("password"),config("database_name")))->connect());
  $response = new Response();
  if(!(new Serializer(['email']))->tuple($user->get_user_with_email($body->email))){
    if($user->create_user($body->name,$body->email,$body->password)){
      $response->send_response(201,[['error',false],['message','user has been created successfully']]);
    }else {
      $response->send_response(500,[['error',true],['message','something went wrong']]);
    }
  }else {
    $response->send_response(400,[['error',true],['message','email exists']]);

  }
}));


$auth->run();
?>
