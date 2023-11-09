<?php 
	session_start();
	class checkLogin {

		function select() {
			if ( !isset($_SESSION['userIsLoggedIn'])){
				return json_encode("You are not Logged In!");
			}else{
				return json_encode("Authenticated");
			}

		}

	}
$checkLogin = new checkLogin;
header('Content-Type: application/json');
if (isset($_GET['check'])){
	echo $checkLogin->select();
}

?>