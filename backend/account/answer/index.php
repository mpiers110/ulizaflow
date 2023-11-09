<?php 
	require_once('../../conn/db.php');
	use UlizaFLOW\DbConnectAPI\DataSource;
	session_start();
	class Answer extends DataSource {
		function getAllAnswers($id) {
			$query = "SELECT answers.id,answers.questionid,answers.description,answers.created,answers.updated,answers.accepted,answers.votes,users.user_id,users.username FROM answers INNER JOIN users ON users.user_id = answers.userid WHERE answers.questionid = '$id'  ORDER BY answers.votes DESC";
			try{
				$result = $this->select($query, 'i');
		        if(is_array($result)){
			    	return json_encode($result);
		    	}else{
		    		return json_encode("No Answers");
		    	}

			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function getAnswer($id) {
			$query = "SELECT answers.id,answers.questionid,answers.description,answers.created,answers.updated,answers.accepted,answers.votes,users.user_id,users.username FROM answers INNER JOIN users ON users.user_id = answers.userid WHERE answers.id = '$id'";
			try{
				$result = $this->select($query, 'i');
		        if($result){
			    	return json_encode($result);
		    	}else{
		    		throw new Exception("An error occured while fetching answer...Try again");
		    	}

			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function acceptAnswer($id){
			$query = "UPDATE `answers` SET `accepted`=1 WHERE answers.id = '$id'";
			try{
				$this->execute($query, 'i');
			    return json_encode("OK");
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function rejectAnswer($id){
			$query = "UPDATE `answers` SET `accepted`=0 WHERE answers.id = '$id'";
			try{
				$this->execute($query, 'i');
			    return json_encode("OK");
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
		function insertAnswers($id, $description, $userid) {
			$query = "INSERT INTO `answers`(`questionid`, `description`, `userid`) VALUES (?, ?, ?)";
			try{
				$result = $this->insert($query, 'isi', [$id, $description, $userid]);
				if ($result) {
					return json_encode("OK");
		    	}else{
		    		throw new Exception("An error occured while posting answers...Try again");
		    	}
			}catch(Exception $err){
		        return $err->getMessage();
		    }
		}
	}

$Answer = new Answer;
header('Content-Type: application/json');

if (isset($_GET['question_id']) && !empty($_GET['question_id']) ) {
	$id = $_GET['question_id'];
	echo $Answer->getAllAnswers($id);

}else if (isset($_GET['insertAnswers']) && !empty($_POST)) {
	$quiz_id = $_POST['quiz_id'];
	$description = $_POST['my_answer'];
	$user_id = $_SESSION['id'];
	echo  $Answer->insertAnswers($quiz_id, $description, $user_id);

}else if (isset($_GET['getAnswer']) && !empty($_GET['getAnswer'])) {
	$id = $_GET['getAnswer'];
	echo $Answer->getAnswer($id);

}else if (isset($_GET['acceptAnswer']) && !empty($_POST)) {
	$id = $_POST['keyword'];
	echo $Answer->acceptAnswer($id);

}else if (isset($_GET['rejectAnswer']) && !empty($_POST)) {
	$id = $_POST['keyword'];
	echo $Answer->rejectAnswer($id);

}else{
    echo "Bad Request";
}
	

?>