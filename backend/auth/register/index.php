<?php 
require_once('../../conn/db.php');
use UlizaFLOW\DbConnectAPI\DataSource;

    class Register extends DataSource {
        function checkUser($value){
            $query = "SELECT * FROM users WHERE username LIKE '{$value}'";
            $val = $this->getRecordCount($query, 's');

            if($val > 0){
                return json_encode("Error");
            }else{
                return json_encode("OK");
            }
        }
        function checkUserEmail($value){
            $query = "SELECT * FROM users WHERE user_email LIKE '{$value}'";
            $val = $this->getRecordCount($query, 's');

            if($val > 0){
                return json_encode("Error");
            }else{
                return json_encode("OK");
            }
        }
        private function securePass($password) {
            $salt = 'uliza2023.';
            $password1 = md5($password);
            return $salt.$password1;
        }
        function registerUser($username, $email, $dob, $password) {
            $date = date('Y-m-d', strtotime($dob));
            $securepass =  $this->securePass($password);
            //$this->checkUser($key, $value);
            try{
                $query = "INSERT INTO `users`(`username`, `user_email`, `dob`, `password`) VALUES ('$username','$email','$dob','$securepass')";
                $row = $this->insert($query, 'ssii', [$title, $desc, $tag, $userid]);
                if ($row){
                    return json_encode('Account registration successfull');
                }else{
                    throw new Exception('Account registration error..Please try again later');
                }        
            }catch(Exception $err){
                return $err->getMessage();
            }
        }
    }

$Register = new Register;
header('Content-Type: application/json');

if ( isset($_GET['checkuser']) ){
    if ( $_GET['checkuser'] == 'username' ){
        $value = $_POST['keyword'];
        echo $Register->checkUser($value);
    }else if ( $_GET['checkuser'] == 'email' ){
        $value = $_POST['keyword'];
        echo $Register->checkUserEmail($value);
    }
}else if (isset($_GET['registeruser']) && !empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];    
    echo $Register->registerUser($username, $email, $dob, $password);        
}else{
    echo "Bad Request";
}   
?>