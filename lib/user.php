<?php
require_once("database.php");

class User{
	public $userid;
	public $username;
	public $balance;
	
	public function __construct($userid , $username, $balance){
		$this->userid = $userid;
		$this->username = $username;
		$this->balance = $balance;
	}
	
	public static function processName($name){
		$name_lower = strtolower($name);
		$name_lower = preg_replace( '/\s+/', '', $name_lower ); 
		return $name_lower;
	}
	
	public static function isUser($name){
		$name = User::processName($name);
		//echo "[]" . $name . "[]";
		$dbh = new Database();
		$command = "SELECT COUNT(*) FROM user WHERE username = \"$name\"";
		//echo $command;
		$statement = $dbh->prepare($command);
		$statement-> execute();
		
		$result = $statement->fetch();
		if($result[0] != 0)
			return true;
		else
			return false;		
	}
	

	public static function checkBalance($name){
		if(!User::isUser($name)){
			return null;
		}
		$name = User::processName($name);
		$dbh = new Database();
		$command = "SELECT balance FROM user WHERE username = \"$name\"";
		$statement = $dbh->prepare($command);
		$statement-> execute();
		$result = $statement->fetch();
		return $result[0];
	}
	
	public static function updateBalance($name, $balanceChange){
		$dbh = new Database();
		$oldBalance = User::checkBalance($name);
		$currentBalance = $oldBalance + $balanceChange;
		$command = "UPDATE user SET balance=$currentBalance WHERE username = \"$name\"";
		$statement = $dbh->prepare($command);
		$result = $statement-> execute();
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}else{
			echo "<div class=\"alert alert-success\"> User <b>$name</b>'s balance has been successfully updated to $currentBalance! </div>";
		}
		//$result = $statement->fetch();
		//return $result[0];
	}
	
	public static function addUser($name, $balance){
		if(strlen($name) == 0){
			echo '<div class="alert alert-danger"> username cannot have spaces, try again</div>';
			return;
		}
		$dbh = new Database();
		//check duplicates first
		$command = "INSERT INTO user (username, balance) VALUES(?,?)";
		$statement = $dbh->prepare($command);
		if(!$statement->execute(array($name, $balance))){
			echo '<div class="alert alert-danger">';
			print_r($dbh->errorInfo());
			echo '</div>';
		}else{
			echo "<div class=\"alert alert-success\"> User <b>$name</b> has been successfully registered! </div>";
		}
		
	}
	
	public static function updateORregister($name, $balance){
		$name = User::processName($name);
		if(User::isUser($name))
			User::updateBalance($name, $balance);
		else
			User::addUser($name, $balance);
	}
	
	public static function deleteByName($name){
		$dbh = new Database();
		$command = "DELETE FROM user WHERE username = \"$name\"";
		$result = $dbh->exec($command);
		if($result != 1){
			echo "<div class=\"alert alert-danger\">Error deleting: this user may not exist. (deleted $result rows) </div>";
		}else{
			echo "<div class=\"alert alert-success\"> User <b>$name</b> has been deleted! </div>";
		}
	}
	
	public static function getAllUsers(){
		$dbh = new Database();
		$command = "SELECT * FROM user ORDER BY username ASC ";
		$result = $dbh->query($command);
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}
		$users = array();
		foreach($result as $row){
			$users[] = array($row['id'],$row['username'], $row['balance']);
		}
		return $users;
	}

	
if(isset ($_POST["functionname"])){
	 $name = $_POST['arguments'];
	echo User::checkBalance($name);
}
