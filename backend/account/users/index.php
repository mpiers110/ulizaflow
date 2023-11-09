<?php 
	require_once('../../conn/db.php');
	use UlizaFLOW\DbConnectAPI\DataSource;

	session_start();

	class Users extends DataSource {

		function fetchall() {
			$query = "SELECT `user_id`,`username`,`user_email`,`profilepic` FROM `users`";
			try{
		        $count = $this->getRecordCount($query);
		        if ($count > 0) {
					$questions = $this->select($query);
			        if($questions){
			        	return json_encode($questions);
			        }else{
			        	throw new Exception("An error occured while fetching questions...Try again");
			        }
		        }else{
		        	return json_encode("None");
		        }
		    }catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function existing($id) {
			$query = "SELECT `user_id`,`username`,`user_email`,`profilepic` FROM `users` WHERE `user_id` = $id ";
			try{
				$result = $this->select($query, 'i');
				if($result){
					return json_encode($result);
				}else{
		    		throw new Exception("An error occured while fetching questions...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function updateUser($id,$query) {
			try{
				$result = $this->execute($query, 'i');
				if($result){
					return json_encode("OK");		    		
		    	}else{
		    		throw new Exception("An error occured while updating your details...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function userProfile($id) {
			$query = "SELECT `user_id`,`username`,`user_email`,`profilepic`,`status` FROM `users` WHERE `user_id` = $id";
			try{
				$result = $this->select($query, 'i');
				if($result){
					return json_encode($result);		    		
		    	}else{
		    		throw new Exception("An error occured while fetching questions...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function answeredQuestions($id){
			$query="SELECT * FROM `users` INNER JOIN `answers` ON `answers`.`userid`=`users`.`user_id` WHERE `users`.`user_id`=?";
			try{
				$result = $this->getRecordCount($query, 'i', [$id]);
				if ($result) {
					return json_encode($result);
		    	}else{
		    		throw new Exception("An error occured while posting answers...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function askedQuestions($id){
			$query="SELECT * FROM `users` INNER JOIN `threads` ON `threads`.`user_id`=`users`.`user_id` WHERE `users`.`user_id`=?";
			try{
				$result = $this->getRecordCount($query, 'i', [$id]);
				if ($result) {
					return json_encode($result);
		    	}else{
		    		throw new Exception("An error occured while posting answers...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function userRating($id){
			$query="SELECT * FROM `users` INNER JOIN `answers` ON `answers`.`userid`=`users`.`user_id` WHERE `users`.`user_id`=? AND `answers`.`accepted`=1;";
			try{
				$result = $this->getRecordCount($query, 'i', [$id]);
				if ($result) {
					return json_encode($result);
		    	}else{
		    		throw new Exception("An error occured while posting answers...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		
		
	}

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');

$Users = new Users;

if (isset($_GET['existing']) ) {
	if (!empty($_GET['existing'])) {
		$id = $_GET['existing'];
		echo $Users->existing($id);
	}else{
	    echo "Empty Request";
	}

}else if (isset($_GET['updateUser']) && isset($_POST)) {
	if (!empty($_POST['user_id']) ) {
		$id = $_POST['user_id'];
		if ( !empty($_POST['inputEmail']) ){
			$email = $_POST['inputEmail'];
		}			
		if (!isset($_FILES['profilePic'])) {
			die("There is no file to upload");
		}
		$filePath = $_FILES['profilePic']['tmp_name'];
		$fileSize = filesize($filePath);
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$fileType = finfo_file($fileInfo, $filePath);

		if ($fileSize == 0) {
			die("The file is empty");
		}

		if ($fileSize > 3145728) { //Check if file is greater than 3MB
			die("The file is too large");
		}

		$allowedTypes = [
			'image/png' => 'png',
			'image/jpeg' => 'jpeg',
			'image/jpg' => 'jpg',
			'image/gif' => 'gif'
		];

		if (!in_array($fileType, array_keys($allowedTypes))) {
			die("Files of this type are not allowed");
		}

		$fileName = htmlentities($_SESSION['username']);
		$extension = $allowedTypes[$fileType];
		$targetDirectory = BASE_DIR . "file-uploads";//Directory where files are to be saved

		$newFileName = $fileName . "." . $extension;
		$newFilePath = $targetDirectory . "/" . $newFileName;

		if (!copy($filePath, $newFilePath)) { //copy the file, returns false incase of an error/failed
			die("Could not move file");
		}

		unlink($filePath);//Delete the temp file

		if (!isset($email)) {
			$query = "UPDATE `users` SET `profilepic`='$newFileName' WHERE `user_id` = $id";
		}else{
			$query = "UPDATE `users` SET `user_email`='$email',`profilepic`='$newFileName' WHERE `user_id` = $id";
		}

		echo $Users->updateUser($id,$query);
	}else{
	    echo "Empty Request";
	}

}else if (isset($_GET['fetchAll']) ) {
	echo $Users->fetchall();
}else if (isset($_GET['userProfile']) ) {
	if (!empty($_GET['userProfile']) ) {
		$id = $_GET['userProfile'];
		echo $Users->userProfile($id);
	}else{
	    echo "Empty Request";
	}

}else if (isset($_GET['answeredQuestions']) && !empty($_GET['answeredQuestions'])) {
	$id = $_GET['answeredQuestions'];
	echo $Users->answeredQuestions($id);

}else if (isset($_GET['askedQuestions']) && !empty($_GET['askedQuestions'])) {
	$id = $_GET['askedQuestions'];
	echo $Users->askedQuestions($id);

}else if (isset($_GET['userRating']) && !empty($_GET['userRating'])) {
	$id = $_GET['userRating'];
	echo $Users->userRating($id);

}else {
	echo "Bad Request";
}

?>