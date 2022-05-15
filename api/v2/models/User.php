<?php
declare(strict_types=1);
namespace Model;
ini_set("display_errors",1);
// require __DIR__."/../../../config/config.php";
require __DIR__."/../../../vendor/autoload.php";

use Lib\Database;

class User {

  private $connection;
  private string $tbl_name = "users";

  public function __construct($connection){
    $this->connection = $connection;
  }

  public function create_user(string $name,string $email,string $password):bool{
    $query = "INSERT INTO $this->tbl_name(name,email,password) VALUES(?,?,?)";
    $stmt = $this->connection->prepare($query);

    $name = htmlspecialchars(strip_tags(($name)));
    $email = htmlspecialchars(strip_tags($email));
    $password = htmlspecialchars(strip_tags(password_hash($password,PASSWORD_DEFAULT)));

    $stmt->bind_param("sss",$name,$email,$password);
    return $stmt->execute();
  }

  public function get_user_with_email(string $email){
    $query = "SELECT * FROM $this->tbl_name WHERE email = ?";
    $stmt = $this->connection->prepare($query);

    $email = htmlspecialchars(strip_tags($email));

    $stmt->bind_param("s",$email);
    $stmt->execute();
    return $stmt->get_result();
  }

  public function get_user_with_id(int $id){
    $query = "SELECT * FROM $this->tbl_name WHERE id = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    return $stmt->get_result();
  }

}

?>
