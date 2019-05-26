<?php
  session_start();

  require_once 'database.php';

  if (isset($_POST['email'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    //$password = password_hash($password, PASSWORD_DEFAULT);
    //Access the database
    $db = new Database();

    $query = $db->getDB()->prepare("SELECT * FROM employee where emp_email = :EMAIL");
    if ($query->execute([":EMAIL"=> $email])){
      $result = $query->fetch(PDO::FETCH_ASSOC);
      if(password_verify($password, $result['emp_hash'])) {
        $_SESSION['user'] = $result['emp_pk'];
        $_SESSION['displayName'] = $result['emp_fname'];
        $_SESSION['permissions'] = $result['emp_permissions'];
        echo "1";
      } else {
        echo "-1";
      }
    } else {
      echo "-2";
    }
   }
 ?>
