<?php 
require_once('checklogin/index.php');
require_once('../conn/db.php');
use UlizaFLOW\DbConnectAPI\DataSource;    
    
class UserDashboard extends DataSource {
  function getAllCategories(){
    $query = "SELECT * FROM `categories`";
    try{
      $rows = $this->select($query);
      return json_encode($rows);
    }catch(Exception $err){
      return $err->getMessage();
    }
  }
  function getCategory($id){
    $query = "SELECT * FROM `categories` WHERE category_id = '$id'";
    try{
      $rows = $this->select($query, 'i');
      return json_encode($rows);
    }catch(Exception $err){
      return $err->getMessage();
    }
  }
  function getCategories(){
    $query = "SELECT `category_id`, `category_name` FROM `categories`";
    try{
      $result = $this->select($query);
      return json_encode($result);
    }catch(Exception $err){
      return $err->getMessage();
    }
  }
} 

header('Content-Type: application/json');
$UserDashboard = new UserDashboard;

if (isset($_GET['allCategories']) ) {
  echo $UserDashboard->getAllCategories();  
}else if (isset($_GET['category']) && !empty($_GET['category']) ) {
  $id = $_GET['category'];
  echo $UserDashboard->getCategory($id);
}else if (isset($_GET['getCategories']) ) {
  echo $UserDashboard->getCategories();  
}else{
  echo "Bad Request";
}

?>